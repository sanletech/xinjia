<?php
/*
 *  财务中心
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class Financial extends Base
{
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    //账单列表
    public function financial_list() 
    {
      return $this->view->fetch('Financial\financial_list'); 
    }
    //修改账单列表
    public function financial_edit() 
    {
      return $this->view->fetch('Financial\financial_edit'); 
    }

    //客户订单
    public function customer_list() 
    {
        return $this->view->fetch('Financial\customer_list'); 
    }

    //公司报表
    public function company_form() 
    {
        return $this->view->fetch('Financial\company_form'); 
    }
    //修改公司报表
    public function company_edit() 
    {
      return $this->view->fetch('Financial\company_edit'); 
    }
} 