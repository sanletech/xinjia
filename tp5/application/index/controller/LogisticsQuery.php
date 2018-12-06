<?php
namespace app\index\controller;
use think\Db;
use app\index\common\Base;
use think\Session;
class LogisticsQuery extends Base{
    
    //订单查询物流动态
    public function orderQuery() {
        $order_num =  $this->request->param('order_num');
        $track_num =   $this->request->param('track_num');
        $container_code = $this->request->param('container_code');
        //根据运单号 或者柜号 查询 订单
        if($track_num){
            $order_num = Db::name('order_port')->where('track_num',$track_num)->value('order_num');
        }
        if($container_code){
            $order_num = Db::name('order_car')->where('container_code',$container_code)->value('order_num');
            if(empty($order_num)){
                $order_num = Db::name('order_truckage')->where('container_code',$container_code)->value('order_num');
            }
        }
        if($order_num){
            $order_num = Db::name('order_port')->where('order_num',$order_num)->value('order_num');
        }
        if(empty($order_num)){
            return false;
        }
        
        $list=Db::name('order_status')->where($map)->field('submit_man_code',true)->order('change_time ASC')->select();
        return json($list);
    }
    
}