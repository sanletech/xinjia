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
        foreach ($res as $v){
            $this->_p($v);
            echo '</br></br></br>';
            
        }
        return $this->view->fetch('OrderProcess\orderprocess_list'); 
    } 
    //拆订单
    public function orderSplit() {
        return $this->view->fetch('OrderProcess\orderprocess_add'); 
    }
    
    //查看订单的进行状态
    public function OrderDynamic($order_num) {
        $res =Db::name('order_status')->where('order_num',$order_num)->select();
        return $res;
    } 
    
    
    //修改订单
    public function orderModify()  {
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