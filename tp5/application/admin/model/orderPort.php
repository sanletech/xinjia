<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\session;
class orderPort extends Model
{
    public function order_audit($pages,$state){
        $list =Db::name('order_port')->alias('OP')
            ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
            ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
            ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
            ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
            ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id                                                    //起始港终点港
            ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
            ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
            ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶 
            ->field('OP.*,HM.company,HM.name,SC.ship_short_name,B.boat_code,B.boat_name,P1.port_name s_port_name ,P2.port_name e_port_name')
            ->group('HM.id,OP.id,SP.id,SR.id,SB.id,SC.id,B.id')
            ->where('OP.status','in',$state)
            ->paginate($pages); 
//    $this->_p($list);exit; 
            return $list;
    } 

    //订单的状态
    public function order_status($tol,$limit,$state,$order_num='',$payment_method= array('month','cash','special','installment')) {
        if(empty($order_num)){
            $order_num = 'not NUll';
        }else{
            $order_num = trim($order_num);
        }
        $list =Db::name('order_port')->alias('OP')
                ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
                ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id                                                    //起始港终点港
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
                ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶
                ->field('OP.*,HM.company,SC.ship_short_name,P1.port_name s_port,P2.port_name e_port,B.boat_code,B.boat_name')
                ->group('OP.id,SP.id,SR.id,SB.id,SC.id,B.id')
                ->where('OP.status','in',$state)
                ->where('OP.order_num',$order_num)
                ->where('OP.payment_method','in',$payment_method)
                ->limit($tol,$limit)->select();
//        $this->_p($list);exit;
//        var_dump(Db::getLastSql());exit;     
        return $list;
        
    }
    //订单的详细信息
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
    
    //记录订单的更新状态和时间
    public function orderUpdate($order_num,$status,$title) {
        $submitter= Session::get('user_info','think');
         $mtime =  date('Y-m-d H:i:s');
        $data=array('order_num'=>$order_num,'status'=>$status,'title'=>$title,'submitter'=>$submitter);
        $res =Db ::name('order_port_status')->insert($data);
        
    }
    
    //子订单的修改order_truckage 也是送货 装货服务的信息修改
    public function truckage($order_num,$container_sum, $truckageData){
        
        function  insertdata($insertR,$order_num,$container_sum,$type){
            $mtime= time();   // 设置虚拟的柜号  
            $response =[];
            if(!empty($insertR))
            {   $kr = array_keys($insertR);
                foreach ($insertR['num'] as $i => $v) {
                    $tmp[$i] = array_combine($kr,array_column($insertR,$i));
                    $tmp[$i]['order_num'] = $order_num;  $tmp[$i]['type']=$type;$tmp[$i]['mtime']=$mtime;
                    $res =Db::name('order_truckage')->where(['order_num'=>$order_num,'container_code'=>$tmp[$i]['container_code']])
                    ->update($tmp[$i]);
                    $res ? $response['success'][]= $tmp[$i]['container_code'].'修改成功':$response['fail'][]= $tmp[$i]['container_code'].'修改失败'; 
                }
            }
            return $response;
        }
        
        $insertR = $truckageData['r']; 
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
}