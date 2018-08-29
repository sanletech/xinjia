<?php
/*
 *  订单拆分查看完整订单控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\Order as OrderM;
use think\Validate;
use think\session;
class OrderProcess extends Base
{  
   
     //查看完成的详细订单
    public function OrderDetail() {
        
        
    } 
    //拆订单
    public function orderSplit() {
        
    }
    
    //修改订单
    public function orderModify()  {
    
        
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