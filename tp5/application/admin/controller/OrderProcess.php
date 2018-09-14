<?php
/*
 *  订单拆分查看完整订单控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\OrderProcess as OrderPM;
use think\Validate;
use think\session;
class OrderProcess extends Base
{     
     //查看完成的详细订单
    public function OrderDetail() {
        $M= new OrderPM;
       // $res =$M->OrderDetail($order_num);
        $res =$M->OrderDetail('A906353031424552');
        $father =$res[0];
        $car = $res[1];
        $ship =$res[2];
        $status = $res[3];
        $orderInvoice =$res[4];
        $container_code =$res[5];
        $track_num  =$res[6];
       $statusArr = array_column($status, 'change_time','status');
      
        $this->view->assign([
           'father'=>$father,
            'car'=>$car,
            'ship'=>$ship,
            'statusArr'=>$statusArr,
            'container_code'=>$container_code,
            'track_num'=>$track_num    
        ]);
        return $this->view->fetch('OrderProcess\orderprocess_list'); 
    } 
    //拆订单
    public function orderSplit() {
//        $order_num =$this->request->param('order_num');  
        $order_num ='A906371578264503';
        $data =Db::name('order_son')->where('order_num',$order_num)->column('container_code,track_num');
        $tmpArr =[];
        $container_code = array_keys($data);
        $track_num = array_unique(array_values($data))[0];
        $tmpArr =['order_num'=>$order_num ,'track_num'=>$track_num,'container_code'=>$container_code];
      //  var_dump($tmpArr);exit;
        $this->view->assign('list',$tmpArr);
        return $this->view->fetch('OrderProcess\orderprocess_split'); 
    }
    
    //查看订单的进行状态
    public function OrderDynamic($order_num) {
        $res =Db::name('order_status')->where('order_num',$order_num)->select();
        return $res;
    } 
    
    
    //修改订单
    public function orderModify(){
        $M= new OrderPM;
       // $res =$M->OrderDetail($order_num);
        $res =$M->OrderDetail('A906353031424552');
        $father =$res[0];
        $car = $res[1];
        $ship =$res[2];
        $status = $res[3];
        $orderInvoice =$res[4];
        $container_code =$res[5];
        $track_num  =$res[6];
        $statusArr = array_column($status, 'change_time','status');
      
        $this->view->assign([
           'father'=>$father,
            'car'=>$car,
            'ship'=>$ship,
            'statusArr'=>$statusArr,
            'container_code'=>$container_code,
            'track_num'=>$track_num    
        ]);
        
        return $this->view->fetch('OrderProcess\orderprocess_edit'); 
    }
    
    //记录订单修改的状态和时间操作人
    public function orderRecord($order_num,$status,$action) {
        $res =Db::name('order_status')->where('order_num',$order_num)->where('status',$status)->find();
        if(empty($res)){
            $submit_man_code =  Session::get('user_info','think');
            $data =['order_num'=>$order_num,'status'=>$status,'change_time'=>date('y-m-d h:i:s'),'action'=>$action,'submit_man_code'=>$submit_man_code ];
            $res =Db::name('order_status')->insert($data);
            return $res?['mesg'=>true]: ['mesg'=>FALSE];
        }
        return ['mesg'=>true];
    }
    
}