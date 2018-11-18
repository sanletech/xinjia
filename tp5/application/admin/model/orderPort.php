<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Session;
class orderPort extends Model
{
    private $order_status;
    private $page=5;

    protected function initialize()
    {
        parent::initialize();
        $this->order_status = config('config.order_status');
  
    }            

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
            ->group('OP.id')
            ->where('OP.status','in',$state)
            ->paginate($pages); 
//    $this->_p($list);exit; 
            return $list;
    } 

    //订单的状态
    public function order_status($tol,$limit,$map){
        $list =Db::name('order_port')->alias('OP')
                ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
                ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id                                                    //起始港终点港
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
                ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶
                ->field('OP.*,HM.company,SP.ship_id,SC.ship_short_name,SB.sl_start,'
                        . 'P1.port_name s_port,SB.sl_end,P2.port_name e_port,'
                        . 'B.boat_code,B.boat_name,HM.company')
                ->group('OP.id,SP.id,SR.id,SB.id,SC.id,B.id')->buildSql();
// $this->_p($map); //exit;
        $count=Db::table($list.' A')->where($map)->count(); 
//        var_dump( $list =Db::table($list.' A')->where()->limit($tol,$limit)->buildSql());exit;
        $list =Db::table($list.' A')->where($map)->limit($tol,$limit)->fetchSql(FALSE)->select();       
//        $this->_p($list);exit;
        foreach ($list as $key => $value) {
            switch($value['container_buckle'])
            {
                case 'lock':
                $list[$key]['container_buckle'] ='扣货';
                break;   
                case 'unlock':
                $list[$key]['container_buckle'] ='放货';
                break;  
                case 'apply':
                $list[$key]['container_buckle'] ='申请放货';
                break;  
            }
           
            switch($value['money_status'])
            {
                case '0':
                $list[$key]['money_status'] ='未付款';
                break; 
                case '1':
                $list[$key]['money_status'] ='已付款';
                break; 
            }   
            switch($value['container_status'])
            {
                case '0':
                $list[$key]['container_status'] ='未提交柜号';
                break; 
                case '1':
                $list[$key]['container_status'] ='已提交柜号';
                break; 
            }   
        }
        
        
        
        return array('list'=>$list,'count'=>$count);
        
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
                ->join('hl_container_type CT','CT.id=OP.container_type_id','left')//集装箱子的型号
                ->field('OP.*,HM.company,SP.ship_id,SC.ship_short_name,CT.container_type')
                ->where('OP.order_num',$order_num)
                ->group('OP.id,SP.id,SR.id,SB.id,SC.id,B.id')
                ->find();
//        $this->_p($list);exit;
        //根据订单号 查询对应柜子的 柜号和封条号码
        $containerData =Db::name('order_truckage')->alias('OT')
                ->join('hl_order_port OP','OP.order_num=OT.order_num','left')
                ->where('OT.order_num',$order_num)->where('OT.type','s')
                ->field('OT.container_code,OT.seal')->select();
        
        //根据订单查询出拖车信息
        $carData['r'] =Db::name('order_truckage')
                ->where('order_num',$order_num)
                ->where('state',0) //收费柜子
                ->where('type','r')->group('id')
                ->field('order_num,car_price,container_code,count(id) num ,`add`,mtime,link_man,shipper,load_time,link_phone,car,`comment`,seal')
                ->select();
       
        $carData['s'] =Db::name('order_truckage')
                ->where('order_num',$order_num)
                ->where('state',0) //收费柜子
                ->where('type','s')->group('id')
                ->field('order_num,car_price,container_code,count(id) num ,`add`,mtime ,car,`comment`,seal')
                ->select();
        //查询对应现金优惠金额 和临时优惠
        $nowtime = date('y-m-d h:i:s');
        $discount = Db::name('discount')->where([
                        'discount_start'=>['<= time',$nowtime],
                        'discount_end'=>['>= time',$nowtime],
                        'status'=>1,
                        'ship_id'=>$list['ship_id'],
                       ])->field("id,title,type,".$list['container_size']. ' money')->select();
        
        $shipperArr= explode(',',$list['shipper']); 
        $consignerArr= explode(',',$list['consigner']);
        
        return array('list'=>$list ,'containerData'=>$containerData,'carData'=>$carData,'discount'=>$discount,'shipperArr'=>$shipperArr,'consignerArr'=>$consignerArr);
        
    }
    
    //记录订单的更新状态和时间
    public function orderUpdate($order_num,$status,$title,$comment='',$submitter='') {
        if(empty($submitter)){
            $submitter= Session::get('user_info','think');
        }
        $mtime =  date('Y-m-d H:i:s');
        $data=array('order_num'=>$order_num,'status'=>$status,'title'=>$title,'comment'=>$comment,'submitter'=>$submitter,'mtime'=>$mtime);
//        var_dump($data);exit;
        $res =Db ::name('order_port_status')->insert($data); //记录操作
//        var_dump($res);exit;
        //根据不同的记录是否更新order_port 和order_bill 的状态
        $order_status= $this->order_status;
       
        //只更改订单和账单的对应字段
        $map1 =array($order_status['payment_status'],$order_status['container_appley'],
            $order_status['container_lock'],$order_status['container_unlock'],$order_status['up_container_code']);
         //修改order_bill的状态 ,对应order_port的状态和其字段container_status的更改 
        $map2 = array_diff($order_status,$map1) ;  
        if(in_array($status,$order_status)){
            
            if(in_array($status,$map1)){
                switch ($status) {
                    case $order_status['payment_status']:
                    $param = ['money_status'=>1];
                    break;
                    case $order_status['container_appley']:
                    $param = ['container_buckle'=>'appley'];
                    break;
                    case $order_status['container_lock']:
                    $param = ['container_buckle'=>'lock'];
                    break;
                    case $order_status['container_unlock']:
                    $param = ['container_buckle'=>'unlock'];
                    break;
                    case $order_status['up_container_code']:
                    $param = ['container_status'=>'1','status'=>$order_status['up_container_code']];
                    break;
                }
            }elseif(in_array($status,$map2)) {
                $param =['status'=>$status];
            }
            $param['mtime']=$mtime;
//            var_dump($param);exit;
            Db::startTrans();
            try{
                $res =Db::name('order_port')->where('order_num',$order_num)->update($param);
                $res1 =Db::name('order_bill')->where('order_num',$order_num)->update($param);
            Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return array('status'=>0,'message'=>'操作失败');
            }      
                return array('status'=>1,'message'=>'操作成功');
        }else{
            return array('status'=>1,'message'=>'操作成功');
        }
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
