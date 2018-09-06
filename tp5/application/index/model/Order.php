<?php

namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
class Order extends Model
{
    
   
    //前台页面展示门到门的价格表
    public function  price_sum($start_add='',$end_add='',$load_time=''){
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        $nowtime= date('y-m-d h:i:s');//要设置船期
        
        $price_sea = Db::name('seaprice')->alias('SP')
            ->join('hl_ship_route SR','SR.id =SP.route_id','left')
            ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
            ->join('hl_port P1','P1.port_code=SB.sl_start','left')
            ->join('hl_port P2','P2.port_code=SB.sl_end','left')
            ->field('SP.*,SB.sl_start,P1.port_name s_port_name,SB.sl_end,SR.middle_id,P2.port_name e_port_name')
            //->where('SP.cutoff_date','>',$nowtime)
            ->group('SP.id')->order('SP.route_id')->buildSql();
        if($load_time){
            $price_sea = Db::table($price_sea.' E')->where('E.cutoff_date','>',$load_time)->buildSql();
        }
        $price_car = Db::name('carprice')->alias('CP')
            ->join('hl_car_line CL','CL.id =CP.cl_id','left')
            ->field('CP.*,CL.port_id,CL.address_name')
            ->order('CP.cl_id')->buildSql();
        $price_sql = Db::table($price_sea.' A')
                ->join($price_car.' B','A.sl_start =B.port_id','left')
                ->join($price_car.' C','A.sl_end =C.port_id','left')
                ->join('hl_shipcompany SC','SC.id = A.ship_id')
                ->join('hl_boat BA','BA.boat_code =A.boat_code')
//                ->field('A.id,B.id rid,C.id sid,A.route_id,A.middle_id,A.ship_id,SC.ship_short_name,A.shipping_date'
//                     )
                ->field('A.id sea_id,B.id rid,C.id sid,A.route_id,A.middle_id,A.ship_id,SC.ship_short_name,A.shipping_date,'
                        . 'A.cutoff_date,A.boat_code,BA.boat_name,A.sea_limitation,'
                        . 'A.ETA,A.EDD,A.generalize,A.mtime,'
                        . 'A.sl_start,A.s_port_name,B.address_name r_add ,A.sl_end,A.e_port_name,C.address_name s_add ,'
                        . '(select A.price_20GP + B.price_20GP + C.price_20GP  ) as price_20GP,'
                        . '(select A.price_40HQ + B.price_40HQ + C.price_40HQ  ) as price_40HQ')
                ->where('B.variable', '=',"r")->where('C.variable', '=',"S")
                ->buildSql();

   // var_dump($price_sql);exit;
        if($start_add){
            $price_sql = Db::table($price_sql.' F')->where('F.r_add','like',"%$start_add%")->buildSql();
        }
        if($end_add){
            $price_sql = Db::table($price_sql.' G')->where('G.s_add','like',"%$end_add%")->buildSql();
        }
//       var_dump($price_sea); echo"</br>";
//       var_dump($price_car); echo"</br>";
//       var_dump($price_sql);exit;
       // $list = Db::table($price_sql.' D')->paginate($pages,false,$pageParam);   
        return $price_sql;
       
    }
    //展示已经选择好的价格信息
    public function orderBook($sea_id,$r_car_id,$s_car_id,$container_size) {
        if(!($container_size =='20GP'||$container_size =='40HQ')){
            return '参数错误';
        } 
        $list= $this->price_sum();
        //航线信息
        $res = Db::table($list.' A')
            ->where('sea_id',$sea_id)
            ->where('rid',$r_car_id)
            ->where('sid',$s_car_id)
            ->field('A.sea_id, A.rid, A.sid,A.ship_id, A.ship_short_name, A.shipping_date,'
                . 'A.boat_code, A.boat_name, A.sea_limitation,A.ETA,'
                . 'A.sl_start,A.s_port_name,A.r_add ,A.sl_end,A.e_port_name,A.s_add ,'
                . 'price_'.$container_size.' as price' )->find();
        // 将集装箱字的尺寸添加到数组中
        $res['container_size']=$container_size;
        if(!empty($res)){
        //对客户的提成也加进去
        $member_code =  $member_code =Session::get('member_code');
        $ship_id =$res['ship_id'];
        //查询members_porfit表里客户对应船公司的价格
      
        $member_porfit =Db::name('member_profit')
                ->where('member_code',$member_code)
                ->where('ship_id',$ship_id)
                ->value('money');
        $res['member_porfit']=$member_porfit; ;
        }    
      
       // $this->_p($res);exit;
        return $res;            
    }
    
     //添加收/发货人的信息
    public function linkman($data)
    {
        $link_name = $data['link_name'];
        $phone = $data['phone'];
        $company = $data['company'];
        $add = $data['add'];
        $member_code =Session::get('member_code');
        $time = date("Y-m-d H:i:s"); 
        $sql= "insert into hl_linkman(name ,phone ,company ,address,mtime,member_code) "
         . " values('$link_name','$phone','$company','$add','$time','$member_code')";
        $res =  Db::execute($sql);
        $res ?  $response['success'][]='添加linkman表': $response['fail'][]='添加linkman表';
        return  $response;    
        
    }
    
    //处理客户提交的订单信息
    public function order_data($data,$shipper,$consigner,$book_line_id) {
        //添加数据到hl_order_fahter表里
        $mtime =  date("Y-m-d H:i:s"); 
        $member_code =Session::get('member_code');
        $add_id = 1; //前台页面 将收货人 发货人 的联系地址 用ajax处理
        //生成订单编号
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $order_num =  $yCode[intval(date('Y')) - 2018].strtoupper(dechex(date('m'))).date('d').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
       // var_dump($order_num);exit;
       //转换税率 
        switch ($data['tax_rate']){
            case 1:
            $tax_rate =0;
            break;
            case 2:
            $tax_rate =0.04; 
            break;
            case 3:
            $tax_rate =0.07; 
            break;
        }
        //如果是第三付款则合拼第三方人的姓名电话
        if($data['payer'] =='thirdPayment'){
            $payer = $data['payer_name'].'_'.$data['payer_phone'];
        }  else {
            $payer =$data['payer'];
        }
        
        $sqldata =['order_num'=>$order_num,'cargo'=>$data['cargo'],'container_size'=>$data['container'],
                'container_sum'=>$data['container_sum'],'weight'=>$data['weight'],
                 'cargo_cost'=>$data['cargo_cost'], 'container_type_id'=>$data['container_type'],
                  'comment'=>$data['comment'],
                    'mtime'=>$mtime,'book_line_id'=>$book_line_id,'member_code'=>$member_code,'belong_order'=>0,'state'=>0,'action'=>'下单=>待审核',
                    'ctime'=>$mtime,'payer'=>$payer,'payment_method'=>$data['payment_method'],'message_send'=>$data['message_send'],
                  'sign_receipt'=>$data['sign_receipt'],'tax_rate'=>$tax_rate,'invoice_id'=>$data['invoice_id'],'add_id'=>1];
        $res =Db::name('order_father')->insert($sqldata);
        $res ? $response['success'][]='添加order_father表成功':$response['fail'][]='添加order_father表失败';
        return $respones;
    }
    
    //保存订单的航线价格信息
    public function book_line($sea_id,$rid,$sid) {
      
        $sql ="select id from hl_book_line where seaprice_id ='$sea_id' and "
                . "s_pricecar_id ='$sid' and r_pricecar_id ='$rid'";
        $sql2 = "insert into hl_book_line(seaprice_id,s_pricecar_id,"
                . "r_pricecar_id) values ('$sea_id','$sid','$rid')";
        $res = Db::query($sql);
        if(!$res){
            $res2 = Db::execute($sql2); 
            $res = Db::query($sql);
        }
        return $res['0']['id'];
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
      public function route_detail($sealine_id)
    { 
        $sql = "select P.port_name from hl_sea_middle SM "
            . "left join hl_port P on SM.sl_middle = P.port_code"
            . " where SM.sealine_id = '$sealine_id' order by SM.sequence";    
        $res =Db::query($sql);
        $data = array_column($res, 'port_name');
        return $data;
    }
    
     public function confirm_order($price)
    { 
         
    }
}
