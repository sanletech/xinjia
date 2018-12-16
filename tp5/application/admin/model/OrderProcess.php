<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Session;

class OrderProcess  extends Model{
    
    private $order_status;
    private $page=5;

    protected function initialize()
    {
        parent::initialize();
        $this->order_status = config('config.order_status');
  
    }   

    //审核客户提交的订单
    public function order_audit($pages,$state,$type){
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
            ->where('OP.type',$type)
            ->paginate($pages);
        return $list;
    } 
    
    //记录订单的更新状态和时间
        public function orderUpdate($order_num,$status,$title,$comment='',$submitter='') {
        if(empty($submitter)){
            $submitter= Session::get('user_info','think');
        }
        $mtime =  date('Y-m-d H:i:s');
        $data=array('order_num'=>$order_num,'status'=>$status,'title'=>$title,'comment'=>$comment,'submitter'=>$submitter,'mtime'=>$mtime);

        $res =Db ::name('order_port_status')->insert($data); //记录操作
        
        //排除 确认收款,申请放柜,同意放柜,继续扣柜,
        $map = array('container_appley'=>$this->order_status['container_appley'],
            'container_lock'=>$this->order_status['container_lock'],
            'container_unlock'=>$this->order_status['container_unlock'],
            'payment_status'=>$this->order_status['payment_status'],);
        
        $order_status= array_diff_assoc($this->order_status,$map);
        
        if(in_array($status,$order_status)){
                Db::startTrans();
                try{
                    Db::name('order_port')->where('order_num',$order_num)
                        ->update(['status'=>$status,'action'=>$title,'mtime'=>$mtime]);
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return array('status'=>0,'message'=>'操作失败'.$e->getMessage());
                }      
                    return array('status'=>1,'message'=>'操作成功');
        }
       
        return  $res ? array('status'=>1,'message'=>'操作成功'): array('status'=>0,'message'=>'操作失败');
        
    }
    
       //订单的详细信息
    public function order_details($order_num,$order_type='') {
        
        $list =Db::name('order_port')->alias('OP')
                ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
                ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id                                                    //起始港终点港
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
                ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶
                ->field('OP.*,HM.company,SC.ship_short_name,P1.port_name s_port_name,P2.port_name e_port_name')
                ->where('OP.order_num',$order_num)
                ->group('OP.id')
                ->find();
                // $this->_p($list);exit;  
        //根据订单号的信息判断是港到港还是门到门  
        if(empty($order_type) ){
            $order_type = substr($order_num,0,1);
        }
        if($order_type=='P'){
        //根据订单号 查询对应柜子的 柜号和封条号码
        $containerData =Db::name('order_truckage')
            ->where('order_num',$order_num)->where('type','s')
            ->field('container_code,seal')->select();
        
        //根据订单查询出拖车信息
        $carData['r'] =Db::name('order_truckage')
                ->where('order_num',$order_num)
                ->where('state','charge') //收费柜子
                ->where('type','r')->group('id')
                ->field('order_num,car_price,container_code,count(id) num ,`add`,mtime,link_man,shipper,load_time,link_phone,car,`comment`,seal')
                ->select();
       
        $carData['s'] =Db::name('order_truckage')
                ->where('order_num',$order_num)
                ->where('state','charge') //收费柜子
                ->where('type','s')->group('id')
                ->field('order_num,car_price,container_code,count(id) num ,`add`,mtime ,car,`comment`,seal')
                ->select();
        }elseif ($order_type == 'D') {
            //封条号
            $containerData = Db::name('order_car')
                ->where('order_num',$order_num)
                ->where('type','load')
                ->field('container_code,seal')->select();
            //派车信息
            $carData['r']=Db::name('order_car')
                ->where('order_num',$order_num)
                ->where('type','load')
                ->field('id,order_num,car_id,driver_id,mtime,type',TRUE)
                ->select();
            //船期动态
            $shipData = Db::name('order_ship')->where('order_num',$order_num)
                    ->field('id,order_num,mtime',TRUE);
        }  
//      var_dump($containerData);exit;
        $shipperArr= explode(',',$list['shipper']); 
        $consignerArr= explode(',',$list['consigner']); 
       switch ($list['payment_method'])
       {
            case 'month':
                $list['payment_method']='月结付款';
                break; 
            case 'cash':
                $list['payment_method']='在线支付';
                break; 
            case 'installment':
                $list['payment_method']='到港付款';
                break; 
            case 'pledge':
                $list['payment_method']='压柜付款';
                break; 
        }
        switch ($list['money_status'])
       {
            case 'nodo':
                $list['money_status']='未付款';
                break; 
            case 'do':
                $list['money_status']='已付款';
                break; 
        }
        $list['extra_info'] = ltrim($list['extra_info'],','); 
        $list['completion']= ($list['status']== $this->order_status['completion']) ?true:false;
        if($order_type =='P'){
            return array('list'=>$list ,'containerData'=>$containerData,'carData'=>$carData,'shipperArr'=>$shipperArr,'consignerArr'=>$consignerArr);
        }  else {
            return array('list'=>$list ,'containerData'=>$containerData,'carData'=>$carData,'shipperArr'=>$shipperArr,'consignerArr'=>$consignerArr,'shipData'=>$shipData); 
        }
    }
    
}
