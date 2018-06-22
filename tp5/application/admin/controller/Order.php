<?php
/*
 *  订单管理控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class Order extends Base
{   
    
        //处理订单
    public function order_submit() 
    {
        return $this->view->fetch('Order/order_submit'); 
    }
    
    //处理订单
    public function order_list() 
    {
        return $this->view->fetch('Order/order_list'); 
    }
    //查看订单
    public function order_edit() 
    {
        return $this->view->fetch('Order/order_edit'); 
    }
    //废弃订单
    public function order_waste() 
    {
        return $this->view->fetch('Order/order_waste'); 
    }
    //审核订单
    public function order_audit() 
    {
        return $this->view->fetch('Order/order_audit'); 
    }

    //处理订单订舱
    public function list_booking() 
    {
        return $this->view->fetch('Order/list_booking');
    }
    //处理订单派车
    public function list_paiche() 
    {
        return $this->view->fetch('Order/list_paiche');
    }
    //处理订单送货
    public function list_songhuo() 
    {
        return $this->view->fetch('Order/list_songhuo');
    }
    //处理订单收款
    public function list_shouqian() 
    {
        return $this->view->fetch('Order/list_shouqian');
    }
    //处理订单待配船
    public function list_dship() 
    {
        return $this->view->fetch('Order/list_dship');
    }
    //处理订单卸船
    public function list_zship() 
    {
        return $this->view->fetch('Order/list_zship');
    }
    //处理订单待配船
    public function list_peiship() 
    {
        return $this->view->fetch('Order/list_peiship');
    }

    
} 