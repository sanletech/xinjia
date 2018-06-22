<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
class Personal extends Controller 
{
    //个人中心
    public function steward()
    {
       return $this->view->fetch('personal/steward');
    }
    //所有订单
    public function all_order()
    {
       return $this->view->fetch('personal/all_order');
    }
    //作废订单
    public function invalid()
    {
       return $this->view->fetch('personal/invalid');
    }
    //生产账单
    public function a_order()
    {
       return $this->view->fetch('personal/a_order');
    }
    //账单表报
    public function form_order()
    {
       return $this->view->fetch('personal/form_order');
    }
    //个人信息
    public function info()
    {
       return $this->view->fetch('personal/info');
    }
    //公司信息
    public function company()
    {
       return $this->view->fetch('personal/company');
    }
    //常用信息
    public function common_info()
    {
       return $this->view->fetch('personal/common_info');
    }
}
