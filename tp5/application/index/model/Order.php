<?php

namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
use app\index\controller\IDCode ;
use app\index\controller\Bill;
class Order extends Model
{
    
   
    //前台页面展示门到门的价格表
    public function  price_sum($member_code,$start_add,$end_add,$ship_id,$start_time,$end_time,$page,$limit,$sea_id=''){
        $nowtime= date('y-m-d h:i:s');//要设置船期
        $price_list = Db::name('seaprice')->alias('SP')
                ->join('hl_ship_route SR','SR.id =SP.route_id and SR.status=1')//中间港口
                ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id')//起始,目的港口
                ->join('hl_carprice CPR',"CPR.port_id = SB.sl_start and CPR.status='1'",'left') //拖车装货费
                ->join('hl_carprice CPS',"CPS.port_id = SB.sl_end and CPS.status='1'",'left') //拖车送货货费
                ->join('hl_price_incidental PIR',"PIR.ship_id=SP.ship_id and PIR.port_code = SB.sl_start and PIR.status='1' ",'left') //起运港口杂费
                ->join('hl_price_incidental PIS',"PIS.ship_id=SP.ship_id and PIS.port_code = SB.sl_end   and PIS.status='1'",'left') //目的港口杂费
                ->join('hl_member_profit MP',"MP.ship_id=SP.ship_id" ,'left') //不同客户对应不同船公司的利润
                ->join('hl_shipcompany SC','SC.id = SP.ship_id','left') //船公司
                ->join('hl_boat BA','BA.id =SP.boat_id BA.status=1','left') //船舶
                ->join('hl_port PR','PR.port_code = SB.sl_start PR.status=1')//起始港口
                ->join('hl_port PS','PS.port_code = SB.sl_end PS.status=1')//目的港口
                ->field('SP.*, CPR.id rid,CPS.id sid,PIR.id pir_id,PIS.id pis_id,SC.ship_short_name,'
                        . ' BA.boat_code,BA.boat_name,SB.sl_start,SB.sl_end,'
                        . ' PR.port_name r_port_name,PS.port_name s_port_name,CPR.address_name r_add,CPS.address_name s_add,'
                        . '  CPR.r_20GP,CPR.r_40HQ,CPS.s_20GP,CPR.s_40HQ,MP.20GP discount_20GP,MP.40HQ discount_40HQ,'
                        . ' (select SP.price_20GP + IFNULL(PIR.r_20GP,0) + IFNULL(CPR.r_20GP,0) + IFNULL(PIS.s_20GP,0)  + IFNULL(CPS.s_20GP,0) + IFNULL(MP.20GP,0) ) as price_sum_20GP,'
                        . ' (select SP.price_40HQ + IFNULL(PIR.r_40HQ,0) + IFNULL(CPR.r_40HQ,0) + IFNULL(PIS.s_40HQ,0) + IFNULL(CPS.s_40HQ,0) + IFNULL(MP.40HQ,0) ) as price_sum_40HQ')
                ->where('MP.member_code',$member_code)
                ->where('SP.status',1)->where('SP.stale_date',1)
                ->group('SP.id')->buildSql();
        $map =[];
        $start_time ? $map[] = "A.shipping_date >= '$start_time' or A.cutoff_date >=  '$start_time'" :'';
        $end_time ? $map[] = "A.shipping_date  <=  '$end_time' and A.cutoff_date  <=  '$end_time' " :'';
        $start_add ? $map[]= "A.r_add like '%$start_add%' ":'';
        $end_add ? $map[]= "A.s_add like '%$end_add%' ":'';
        $sql = implode(' and ', $map);
        $sql = trim($sql,' and ');
        $count = Db::table($price_list.' A')->where($sql)->count();
        $list =  Db::table($price_list.' A')->where($sql)->fetchSql(false)
                ->page($page,$limit)->select();

        return array('list'=>$list, 'count'=>$count);
             
    }
    //展示已经选择好的价格信息
    public function orderBook($sea_id ,$container_size,$member_code) {
        //航线信息
        $price= $this-> price_sum($member_code,$start_add='',$end_add='',$load_time='',$page=1,$limit=100,$sea_id);
        $price =$price['list'][0]; 
        // 将集装箱字的尺寸添加到数组中
        $price['container_size']=$container_size;
        if($container_size=='40HQ'){
            unset($price['price_sum_20GP'],$price['price_20GP'],$price['r_20GP'],$price['s_20GP'],$price['discount_20GP']);
        } elseif($container_size=='20GP') {
            unset($price['price_sum_40HQ'],$price['price_40HQ'],$price['r_40HQ'],$price['s_40HQ'],$price['discount_40HQ']);
        }
//        $this->_P($price);exit;
        return $price;            
    }
    //添加收/发货人的信息
    public function linkmanAdd($data)
    {
        $link_name = $data['link_name'];
        $phone = $data['phone'];
        $company = $data['company'];
        $add= $data['add'];
        $member_code =Session::get('member_code');
        $time = date("Y-m-d H:i:s"); 
        $data = array('name'=>$link_name ,'phone'=>$phone ,'company'=>$company ,
            'mtime'=>$time,'member_code'=>$member_code,'address'=>$add);
        $res =  Db::name('linkman')->insert($data);
        return  $res ?TRUE : FALSE;    
    }
    
    //处理客户提交的订单信息
    public function order_data($data ,$post_token) {
        // $this->_p($data);$this->_p($post_token);exit;
       //检查订单令牌是否重复,小程序不用检查
    //    $this->_v($post_token);
    //    var_dump(Session::get('TOKEN')) ;exit;
        if(!(action('index/OrderToken/checkToken',['token'=>$post_token], 'controller'))){
            return array('status'=>0,'mssage'=>'不要重复提交订单');
        }
      
        
        $sea_id = $data['sea_id']; //海运路线ID
        $member_code =Session::get('member_code'); //用户帐号
        $container_size = $data['container_size']; //箱型
        $container_sum = $data['container_sum'];  //柜量
        $data_price  = $this->orderBook($sea_id ,$container_size,$member_code); //一个柜的价格信息
        $carriage = $data_price['price_sum_'.$container_size]; //一个柜的总运费
        //计算门到门的海运费是否一致
        if(intval($data['carriage']) !== intval($carriage)){
            return array('status'=>0,'mssage'=>'海运费错误');
        }
        
        $carprice_r = $data_price['r_'.$container_size];//门到门的总装货费
        $carprice_s = $data_price['s_'.$container_size]; //门到门的总送货费
        $discount  = $data_price['discount_'.$container_size]; //门到门的客户的利润
        //计算保险费 = 单个柜货值(万元为单位)*6*柜量
        if(intval($data['premium'])!==intval($data['cargo_value']*4)*$container_sum){
            return array('status'=>0,'mssage'=>'保险费错误');
        }
        //计算下单总共柜子的报价
        if( intval($data['price_sum'])!==  intval($carriage*$container_sum+$data['premium']) ){
            return array('status'=>0,'mssage'=>'总费用错误');
        }
        //生成订单编码
        $IDCODE = new IDCode();
        $order_num = $IDCODE->order_num($type='door');  
        //发货人 ,收货人信息
        $shipper = implode(',',array($data['r_name'],$data['r_company'],$data['r_phone'],$data['r_add']));//装货信息
        $consigner = implode(',',array($data['s_name'],$data['s_company'],$data['s_phone'],$data['s_add']));//送货信息
       
        $fatherData =array('order_num'=>$order_num,'cargo'=>$data['cargo'],'container_size'=>$container_size,
                'container_sum'=>$container_sum,'weight'=>$data['weight'],'cargo_cost'=>$data['cargo_cost'],
                'container_type'=>$data['container_type'],'comment'=>$data['comment'],'member_code'=>$member_code,
                'ctime'=>date('Y-m-d H:i:s'),'payment_method'=>$data['payment_method'],'shipper_id'=>$data['r_id'],
                'shipper'=>$shipper,'consigner_id'=>$data['s_id'],'consigner'=>$consigner,'seaprice_id'=>$sea_id,
                'price_description'=>$data['price_description'],'premium'=>$data['premium'],'quoted_price'=>$data['price_sum'],
                'carprice_r'=>$carprice_r,'carprice_s'=>$carprice_s,'discount'=>$discount,
                'type'=>'door','status'=>2);
        $res =Db::name('order_port')->insert($fatherData);
        //同时生成账单
        $Billc =new Bill();
        $bill_res = $Billc ->billCreate($order_num);
        
        return  $res ? array('status'=>1,'message'=>'下单成功'):array('status'=>0,'message'=>'下单失败');
    }
    

    
    //保存发票信息
    public function invoice($data) {
        $mtime = date('y-m-d h:i:s');
        $title = $data['invoice_title'];
        $taxpayer_id =$data['taxpayer_id'];
        $registered_address =$data['registered_address'];
        $registered_phone = $data['registered_phone'];
        $deposit_bank = $data['deposit_bank'];
        $bank_account = $data['bank_account'];
        $member_code = $data['member_code'];
        $sql ="insert into hl_invoice (invoice_title,taxpayer_id,registered_address ,"
                . "registered_phone,deposit_bank,bank_account,member_code,mtime)  "
                . " values ('$title','$taxpayer_id' ,'$registered_address' ,"
                . "'$registered_phone' ,'$deposit_bank' ,'$bank_account','$member_code','$mtime')";
        $res = Db::execute($sql);
        $res ? $response['success'][]='添加invoice表':$response['fail'][]='添加invoice表';
        return $response;
    }
    public function route_detail($seaprice_id)
    { 
        $str =Db::name('hl_seaprice')->alias('SP')
                ->join('hl_ship_route SR','SP.route_id=SR.id','left')
                ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
                ->join('hl_sea_middle SM','SM.sealine_id=SR.middle_id','left')
                ->join('hl_port P1','P1.port_code= SB.sl_start','left')
                ->join('hl_port P2','P2.port_code= SB.sl_end','left')
                ->join('hl_port P3','P3.port_code= SM.sl_middle','left')
                ->field('P1.port_name s_port,P2.port_name e_port group_concate(DISTINCT P3.port_name order by SM.sequence ) m_port')
               ->group('P1,P2,P3')->find();
        return $str;
    }
    


    
}
