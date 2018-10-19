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
        if($order_num){
            $map['order_num'] = $order_num; 
        }
        if($container_code && $track_num){
            $order_num =Db::name('order_son')->where('container_code',$container_code)
                    ->where('track_num',$track_num)->value('order_num');
            $map['order_num'] = $order_num; 
        }
        $list=Db::name('order_status')->where($map)->field('submit_man_code',true)->order('change_time ASC')->select();
        return json($list);
    }
    
}