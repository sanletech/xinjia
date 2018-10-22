<?php

namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
class OrderPort extends Model
{
    
    public function price_port($tol,$limit,$start_add='',$end_add='',$ship_id='',$seaprice_id='') {
//        var_dump($tol,$limit,$start_add,$end_add,$ship_id,$seaprice_id);exit;
        $nowtime= date('y-m-d h:i:s');//要设置船期
        $price_list = Db::name('seaprice')->alias('SP')
                ->join('hl_ship_route SR','SR.id =SP.route_id')//海运路线表
                ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id')//起始,目的港口
                ->join('hl_shipcompany SC','SC.id = SP.ship_id')//船公司表
                ->join('hl_boat BA','BA.id =SP.boat_id')//船舶表
                ->join('hl_port PR','PR.port_code = SB.sl_start')//起始港口
                ->join('hl_port PS','PS.port_code = SB.sl_end')//目的港口
                ->field('SP.*,SC.ship_short_name,BA.boat_name,BA.boat_code,'
                        . 'PR.port_name r_port_name,PS.port_name s_port_name,'
                        . 'PR.port_code r_port_code,PS.port_code s_port_code,'
                        . 'SR.middle_id')
                ->group('SP.id,SR.id')
                ->buildSql();
        if($ship_id){
            $price_list = Db::table($price_list.' E')->where('E.ship_id',$ship_id)->buildSql();
        }
        if($start_add){
            $price_list = Db::table($price_list.' F')->where('F.r_port_code',$start_add)->buildSql();
        }
        if($end_add){
            $price_list = Db::table($price_list.' G')->where('G.s_port_code', $end_add)->buildSql();
        }
        if($seaprice_id){
            $price_list = Db::table($price_list.' H')->where('H.id', $seaprice_id)->buildSql();
        }
//        var_dump($price_list);exit;
        $count = Db::table($price_list.' K')->count();
        $list =Db::table($price_list.' J')->order('J.mtime ASC')->limit($tol,$limit)->select();
//        $this->_p($list);exit;
        return array($list,$count);
    }
    
    public function portBook($member_code,$seaprice_id,$container_size){
        if(!($container_size =='20GP'||$container_size =='40HQ')){
            return '参数错误';
        } 
        //航线信息
        $res = $this->price_port(0,100,0,0,0,$seaprice_id);
        if(empty($res)){
            return '参数错误';
        }
        $res=$res[0][0];
//        $this->_p($res);exit;
        // 将集装箱字的尺寸添加到数组中
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
        $discount_special = Db::name('discount_special')->where([
                        'discount_start'=>['ELT',$nowtime],
                        'discount_end'=>['EGT',$nowtime],
                        'status'=>['NEQ',0],
                        'ship_id'=>$res['ship_id'],
                       ])->field("{$container_size}_promotion promotion,"
                       . "promotion_title,id promotion_id")->find();
                       
        //查询出客户对应的月结,到港付,现款的优惠
        $discount =Db::name('discount_normal')->where([
                    'member_code'=>$member_code,
                    'ship_id'=>$res['ship_id']
                     ])
                ->field("{$container_size}_installment installment,"
                . "{$container_size}_month month,"
                . "{$container_size}_cash cash")
                ->find();
        $discount ? $discount :$discount=[];
        array_key_exists('installment', $discount)?$discount : $discount['installment']=0;
        array_key_exists('month', $discount)?$discount : $discount['month']=0;
        array_key_exists('cash', $discount)?$discount : $discount['cash']=0;
        $discount_special?$discount['special']=$discount_special:$discount['special']=0;
      
      //  $this->_p($res); $this->_p($discount);exit;
        return array($res,$discount);            
        
    }
    
    //根据用户code 和sea_id海运价格id，柜子大小,付款方式 返回相应的单个柜子优惠价格
    public function dicountPrice($member_code,$seaprice_id,$container_size,$payment_method,$special='') {
        //根据sea_id 查询出对应的船公司的Id
        $ship_id =Db::name('seaprice')->where('id',$seaprice_id)->value('ship_id');
        if($special){
            $mtime= date('y-m-d h:i:s');
            $price =Db::name('discount_special')->where([
                        'discount_start'=>['ELT',$mtime],
                        'discount_end'=>['EGT',$mtime],
                        'ship_id'=>$ship_id,
                        'id'=>$special
                    ])->value($container_size.'_promotion');
        }else{
            $price =Db::name('discount_normal')->where([
                'member_code'=>$member_code,
                'ship_id'=>$ship_id,
            ])->value($container_size.'_'.$payment_method);
        }
        
        return $price;
        
    }
    
    //处理装货费用和送货费用  订单号码和 装货 送货的信息
    public function truckage($order_num,$container_sum,$truckageData){
        
        function  insertdata($insertR,$order_num,$container_sum,$type){
            $container_code = time();   // 设置虚拟的柜号
            if(!empty($insertR))
            {   $kr = array_keys($insertR);
                foreach ($insertR['num'] as $i => $v) {
                    $tmp[$i] = array_combine($kr,array_column($insertR,$i));
                    $tmp[$i]['order_num'] = $order_num;  $tmp[$i]['type']=$type;
                }
                //如果装货的柜子数量少于订单的总柜子数量 就存在有客户自己装货的
                $receiveContainerSum= array_sum( $insertR['num']);
                $freeContainerR = $container_sum- $receiveContainerSum;//客户自己处理的柜子数量
                if($freeContainerR >0){
                    $tmp[]=array('order_num'=>$order_num,'state'=>1,'type'=>$type,'num'=>$freeContainerR);
                }
            }  else {
                $tmp[] =array('order_num'=>$order_num,'state'=>1,'type'=>$type,'num'=>$container_sum);
            }  
            $response =[];
            $tmp = array_values($tmp);//将键重新从零开始
            for($i=0;$i<count($tmp);$i++){
                $cycle = $tmp[$i]['num'];
                for($j=0;$j<$cycle;$j++){
                    $tmp[$i]['sequence']= $i;
                    //$tmp[$i]['container_code']=$container_code.$i.$j;
                    unset($tmp[$i]['num']);
                    $res =Db::name('order_truckage')->insert($tmp[$i]);
                    $res ? $response['success'][]= $i.$j.'添加成功':$response['fail'][]= $i.$j.'添加失败'; 
                }
            }
            return $response;
        }
        
        $insertR = $truckageData['r']; 
//        $this->_p($insertR);exit;
        $resultR = insertdata($insertR, $order_num, $container_sum, 'r');
        $insertS = $truckageData['s'];
        $resultS = insertdata($insertS, $order_num, $container_sum, 's');
    
        if((array_key_exists('fail', $resultR))&&(array_key_exists('fail', $resultS))){
            return FALSE;
        }else{
            $priceR =Db::name('order_truckage')->where(['order_num'=>$order_num,'type'=>'r'])->sum('car_price');
            $priceS =Db::name('order_truckage')->where(['order_num'=>$order_num,'type'=>'S'])->sum('car_price');
            return array('carprice_r'=>$priceR,'carprice_s'=>$priceS);
        }
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
//var_dump($str);
//var_dump(Db::getLastSql());exit;
        return $str;
    }
    
    public function orderData($order_num) {
        $list =Db::name('order_port')->alias('OP')
                ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
                ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id                                                    //起始港终点港
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
                ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶
                ->field('OP.*,HM.company,SC.ship_short_name')
                ->where('OP.order_num',$order_num)
                ->group('OP.id,SP.id,SR.id,SB.id,SC.id,B.id')
                ->find();
        
        //根据订单号 查询对应柜子的 柜号和封条号码
        $containerData =Db::name('order_truckage')->alias('OT')
                ->join('hl_order_port OP','OP.order_num=OT.order_num','left')
                ->where('OT.order_num',$order_num)
                ->field('OT.container_code,OT.seal')->select();
        
        //根据订单查询出拖车信息
        $carData['r'] =Db::name('order_truckage')
                ->where('order_num',$order_num)
                ->where('state',0) //收费柜子
                ->where('type','r')->group('sequence')
                ->field('order_num,car_price,container_code,count(id) num ,`add`,mtime,link_man,shipper,load_time,link_phone,car,`comment`,seal')
                ->select();
       
        $carData['s'] =Db::name('order_truckage')
                ->where('order_num',$order_num)
                ->where('state',0) //收费柜子
                ->where('type','s')->group('sequence')
                ->field('order_num,car_price,container_code,count(id) num ,`add`,mtime ,car,`comment`,seal')
                ->select();
        
        return array($list ,$containerData,$carData);
        
    }

    
}
