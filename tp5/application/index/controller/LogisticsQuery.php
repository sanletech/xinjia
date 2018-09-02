<?php
namespace app\index\controller;
use think\Db;
use app\index\common\Base;
use think\Session;
class LogisticsQuery extends Base{
    
    //订单查询物流动态
    public function orderQuery() {
        $order_num =  $this->request->param();
        $map= 
        $list=Db::name('order_status')->where('order_num',$order_num)->field('submit_man_code',true)->order('change_time ASC')->select();
        return json($list);
    }
    
}