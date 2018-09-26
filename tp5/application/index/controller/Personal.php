<?php
namespace app\index\controller;

use app\index\common\Base;
use think\db;
class Personal extends Base
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
    //港到港订单
    public function place_order()
    {
        $member_code =Session::get('member_code','think');
        $data =Db::name('order_port')->alias('OP')
                ->join('hl_order_port_status OPS','OP.order_num = OPS.order_num','left')
                ->where('OP.member_code',$member_code)->field('OP.*,OPS.title,max(OPS.status),OPS.mtime')
                ->group('OPS.id,OP.id')->find();
       return $this->view->fetch('personal/place_order_details');
    }

    //提交柜号资料
    public function cabinet_number()
    {
       return $this->view->fetch('personal/cabinet_number');
    }
}
