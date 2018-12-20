<?php

namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;

class OrderPort extends Model
{
    
    public function price_port($page,$limit,$start_add='',$end_add='',$ship_id='',$seaprice_id='') { 
        //门到门 设置客户利润，港到港没有利润只有在线优惠
        $nowtime= date('y-m-d h:i:s');//要设置船期
        $price_list = Db::name('seaprice')->alias('SP')
                ->join('hl_ship_route SR','SR.id =SP.route_id','left')//海运路线表
                ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')//起始,目的港口
                ->join('hl_shipcompany SC','SC.id = SP.ship_id','left')//船公司表
                ->join('hl_boat BA','BA.id =SP.boat_id','left')//船舶表
                ->join('hl_port PR','PR.port_code = SB.sl_start','left')//起始港口
                ->join('hl_port PS','PS.port_code = SB.sl_end','left')//目的港口
                ->field('SP.*,SC.ship_short_name,BA.boat_name,BA.boat_code,'
                        . 'PR.port_name r_port_name,PS.port_name s_port_name,'
                        . 'PR.port_code r_port_code,PS.port_code s_port_code,'
                        . 'SR.middle_id')
                ->group('SP.id,SR.id')
                ->buildSql();
        $map =[];
        $ship_id ? $map['A.ship_id'] = $ship_id : '';
        $start_add ? $map['A.r_port_code'] = $start_add:'';
        $end_add?$map['A.s_port_code'] = $end_add:'';
        $seaprice_id?$map['A.id'] = $seaprice_id:'';
        
        $count = Db::table($price_list.' A')->where($map)->count();
        $list =Db::table($price_list.' A')->where($map)->order('A.mtime ASC')->page($page,$limit)->select();

        return array('list'=>$list,'count'=>$count);
    }
    
    public function portBook($member_code,$seaprice_id,$container_size){
        if(!($container_size =='20GP'||$container_size =='40HQ')){
            return '参数错误';
        } 
        //航线信息   
        $res = $this->price_port(1,100,0,0,0,$seaprice_id);
        if(empty($res)){
            return '参数错误';
        }
        //根据航线信息的船公司 来查询在线支付的优惠
        $res=$res['list'][0];
//        $this->_p($res);exit;
        $ship_id =$res['ship_id'];
        // 将集装箱字的尺寸添加到数组中 删除不符合的尺寸价格
        $res['container_size']=$container_size;
        if($container_size =='20GP'){
           $res['price'] = $res['price_20GP'];
           unset($res['price_20GP'],$res['price_40HQ']);
        }  else {
            $res['price'] = $res['price_40HQ'];
            unset($res['price_20GP'],$res['price_40HQ']);
        } 
        //查询有没有活动优惠
        $nowtime = date('y-m-d h:i:s');
        $discount = Db::name('discount')->where([
                        'discount_start'=>['<= time',$nowtime],
                        'discount_end'=>['>= time',$nowtime],
                        'status'=>1,  'ship_id'=>$ship_id,])
                      ->field("id,title,type,".$container_size. ' money')->select();
      
        //查询有没有设置默认地址
        $default_add= Db::name('linkman')
                ->where(['member_code'=>$member_code,'status'=>1])
                ->where('default',['=','r'],['=','s'],'or')
                ->field('member_code,town_code,mtime',true)->select();
        $default_addArr=[];
        if($default_add){
            foreach ($default_add as $add){
                $default_type= $add['default'];
                $default_addArr[$default_type]=$add;
            }
            if(!array_key_exists('s', $default_addArr)){$default_addArr['s']='';}
            if(!array_key_exists('r', $default_addArr)){$default_addArr['r']='';}
        }  else {
            $default_addArr=['r'=>'','s'=>''];
        }
           
        return array('seapriceData'=>$res,'discount'=>$discount,'default_addArr'=>$default_addArr);            
        
    }
    

    
    //处理装货费用和送货费用  订单号码和 装货 送货的信息
    public function truckage($order_num,$container_sum,$truckageData){
        
        $resultR = $this->insertdata($truckageData['r'], $order_num, $container_sum, 'r');
        $resultS = $this->insertdata($truckageData['s'], $order_num, $container_sum, 's');
        
        if(!($resultR&&$resultS)){
            return FALSE;
        }else{
            $priceR =Db::name('order_truckage')->where(['order_num'=>$order_num,'type'=>'r'])->sum('car_price');
            $priceS =Db::name('order_truckage')->where(['order_num'=>$order_num,'type'=>'s'])->sum('car_price');
            return array('carprice_r'=>$priceR,'carprice_s'=>$priceS);
        }
    }
    //根据送货装货信息生成对应的记录
    public function  insertdata($insertR,$order_num,$container_sum,$type)
    {   
        $mtime = date('Y-m-d H:i:s');
        if(!empty($insertR))
            {            
                $kr = array_keys($insertR);
                foreach ($insertR['num'] as $i => $v) {
                    $tmp[$i] = array_combine($kr,array_column($insertR,$i));
                    $tmp[$i]['order_num'] = $order_num;  
                    $tmp[$i]['type']=$type;
                    $tmp[$i]['state']='charge';
                    $tmp[$i]['mtime']=$mtime;
                }
                //如果装货的柜子数量少于订单的总柜子数量 就存在有客户自己装货的
                $receiveContainerSum= array_sum($insertR['num']);
                $freeContainerR = intval($container_sum - $receiveContainerSum);//客户自己处理的柜子数量
                if($freeContainerR >0){
                    $nextI= $i+1;
                    $tmp[$nextI]= array_fill_keys(array_keys($tmp[$i]),'');
                    $tmp[$nextI]['order_num'] = $order_num;  
                    $tmp[$nextI]['state']='free';
                    $tmp[$nextI]['type']=$type;
                    $tmp[$nextI]['num']=$freeContainerR;
                    $tmp[$nextI]['mtime']=$mtime;
                }
            }  else {
                $tmp[] =array('order_num'=>$order_num,'state'=>'free','type'=>$type,'num'=>$container_sum,'mtime'=>$mtime);
            }  
     
            $tmp = array_values($tmp);//将键重新从零开始
            $inser_arr=[];
            $k=0;//计数
            for($i=0;$i<count($tmp);$i++){
                $cycle = $tmp[$i]['num'];
                
                for($j=0;$j<$cycle;$j++){
                    $tmp[$i]['sequence']=$k;
                    $inser_arr[] = $tmp[$i];
                    ++$k;
                }
            }
            $res =Db::name('order_truckage')->insertAll($inser_arr);
//            $this->_p($res);
            return $res ? TRUE:FALSE; 
    }
    
    public function route_detail($seaprice_id)
    { 
        $str =Db::name('seaprice')->alias('SP')
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')
                ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
                ->join('hl_sea_middle SM','SR.middle_id=SM.sealine_id','left')
                ->join('hl_port P1','P1.port_code= SB.sl_start','left')
                ->join('hl_port P2','P2.port_code= SB.sl_end','left')
                ->join('hl_port P3','P3.port_code= SM.sl_middle','left')
                ->field("SP.id,P1.port_name s_port,P2.port_name e_port,"
                . " group_concat(distinct P3.port_name order by SM.sequence separator ',') m_port,"
                . "SP.shipping_date,SP.ETA")
                ->where('SP.id',$seaprice_id) ->group('SP.id')->find();

        return $str;
    }
    
    public function port_data($data,$post_token) {
       //检查订单令牌是否重复
        if(!(action('OrderToken/checkToken',['token'=>$post_token], 'controller'))){
            return array('status'=>0,'mssage'=>'不要重复提交订单');
        }
        $order_num = action('IDCode/order_num',['type'=>'port'], 'controller');
        $mtime= date('Y-m-d H:i:s'); //订单时间
        $member_code =Session::get('member_code');//提交账户
        $seaprice_id = $data['seaprice_id']; //海运价格表id
        $container_size=$data['container_size'];//柜型
        $container_sum =$data['container_sum'];//柜量
        //对支付方式做判断
        $payment_method= $data['payment_method'];
         //如果是数字就说明是在线支付了
        if(intval($payment_method)){
            $cash_id = $payment_method;
            $payment_method='cash';
            //计算单个柜优惠的现金优惠金额
            $discount = Db::name('discount')->where('id',$cash_id)->value($container_size);
            //在线支付付款状态就改为已付款
            $money_status ='do';
        }  else {
            $cash_id=0;
            $discount=0;
            $money_status ='nodo';
        }
        
        //计算装货费用和送货费用
        $truckageData = array(
            'r'=> ['car_price'=>$data['r_car_price'],'num'=>$data['r_num'],
                    'add'=>$data['r_add'],'link_man'=>$data['r_link_man'],
                    'shipper'=>$data['shipper'], 'load_time'=>$data['r_load_time'],
                    'link_phone'=>$data['r_link_phone'],'car'=>$data['r_car'],
                    'comment'=>$data['r_comment']   ], 
            's'=> ['car_price'=>$data['s_car_price'],'num'=>$data['s_num'],
                    'add'=>$data['s_add'],'car'=>$data['s_car'], 'comment'=>$data['s_comment'] 
                ]
            );
               
        // 根据订单号, 下单的柜子总数, 和实际的装货送货数据 来生成order_trackage的信息
        $truckagePrice = $this->truckage($order_num,$data['container_sum'], $truckageData);
     
        //计算出对应的海运，柜型,的单个柜海运费
        $ship_carriage = Db::name('seaprice')->where('id',$data['seaprice_id'])->value('price_'.$container_size);
        if(intval($ship_carriage)!==intval($data['carriage'])){
            return array('status'=>0,'mssage'=>'海运费错误');
        }
        if(intval($discount*$container_sum)!==intval($data['discount'])){
            return array('status'=>0,'mssage'=>'优惠金额错误');
        }
        //计算保险费 = 单个柜货值(万元为单位)*4*柜量
        if(intval($data['premium'])!==intval($data['cargo_value']*4)*$container_sum){
            return array('status'=>0,'mssage'=>'保险费错误');
        }
 
        //计算总共的成本 (海运费 -优惠)*柜子数量 + 保险费用+ 装货费 +送货费;
        $quoted_price= ($ship_carriage-$discount)*$container_sum + $data['premium'] +$truckagePrice['carprice_r']+$truckagePrice['carprice_s'];

        if(!(abs($quoted_price- $data['price_sum'])<0.01)){
            return array('status'=>0,'mssage'=>'报价错误');
        } 
        //如果没有选择发票
        if(!array_key_exists('invoice_if',$data)){
            $data['invoice_if']=0;
        }
        $shipper = implode(',',array($data['r_name'],$data['r_company'],$data['r_phone']));//装货信息
        $consigner = implode(',',array($data['s_name'],$data['s_company'],$data['s_phone']));//送货信息
        //生成订单
        $fatherData= array(
            'order_num'=>$order_num,'cargo'=>$data['cargo'],'container_size'=>$container_size,
            'container_sum'=>$container_sum,'weight'=>$data['weight'],'cargo_cost'=>$data['cargo_cost'],
            'container_type'=>$data['container_type'],'comment'=>$data['comment'],'ctime'=>$mtime,'member_code'=>$member_code,
            'payment_method'=>$payment_method,'cash_id'=>$cash_id,'invoice_id'=>$data['invoice_if'],'seaprice_id'=>$data['seaprice_id'],
            'shipper_id'=>$data['s_id'],'consigner_id'=>$data['r_id'],'price_description'=>$data['price_description'],'money_status'=>$money_status,
            'shipper'=>$shipper,'consigner'=>$consigner,'seaprice'=>$ship_carriage,'premium'=>$data['premium'],'discount'=>-$discount,
            'carprice_r'=>$truckagePrice['carprice_r'],'carprice_s'=>$truckagePrice['carprice_s'],'quoted_price'=>$quoted_price,
            'type'=>'port', 'status'=>2);
        $res1 = Db::name('order_port')->insert($fatherData); 
        //同时生成账单
        $Bill = controller('Bill');
        $billCreate_res =$Bill->billCreate($order_num);
        
        return $res1 ? array('status'=>1,'mssage'=>'提交成功'):array('status'=>0,'mssage'=>'提交失败');
 
        
    }
    


    
}
