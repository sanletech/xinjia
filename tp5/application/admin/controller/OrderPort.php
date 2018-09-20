<?php
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class OrderPort extends Base
{
    //港到港订单页
    public function portList()
    {
        return $this->view->fetch('orderPort/port_list');
    }
    //所有订单
    public function all_ordePport()
    {
        return $this->view->fetch('orderPort/all_ordePport');
    }
    //在线支付
    public function port_payment()
    {
        return $this->view->fetch('orderPort/port_payment');
    }
    //月结
    public function port_month()
    {
        return $this->view->fetch('orderPort/port_month');
    }
    //详情
    public function port_details()
    {
        return $this->view->fetch('orderPort/port_details');
    }

}
