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
        //根据航线信息的船公司 来查询在线支付的优惠
        $res=$res[0][0];
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
        $default_add= Db::name('linkman')->where('member_code',$member_code)
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
    
//    //根据用户code 和sea_id海运价格id，柜子大小,付款方式 返回相应的单个柜子优惠价格
//    public function dicountPrice($member_code,$cash_id,$container_size,$payment_method,$special='') {
//        //根据sea_id 查询出对应的船公司的Id
//        $ship_id =Db::name('seaprice')->where('id',$cash_id)->value('ship_id');
//        if($special){
//            $mtime= date('y-m-d h:i:s');
//            $price =Db::name('discount_special')->where([
//                        'discount_start'=>['ELT',$mtime],
//                        'discount_end'=>['EGT',$mtime],
//                        'ship_id'=>$ship_id,
//                        'id'=>$special
//                    ])->value($container_size.'_promotion');
//        }else{
//            $price =Db::name('discount_normal')->where([
//                'member_code'=>$member_code,
//                'ship_id'=>$ship_id,
//            ])->value($container_size.'_'.$payment_method);
//        }
//        
//        return $price;
//        
  //  }
    
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
//var_dump($str);
//var_dump(Db::getLastSql());exit;
        return $str;
    }
    


    
}
