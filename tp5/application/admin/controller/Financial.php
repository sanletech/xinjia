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
      return $this->view->fetch('financial/financial_list'); 
    }
    //修改账单列表
    public function financial_edit() 
    {
      return $this->view->fetch('financial/financial_edit'); 
    }
    //详情账单列表
    public function financial_select() 
    {
        return $this->view->fetch('financial/financial_select'); 
    }
    //客户订单
    public function customer_list() 
    {
        return $this->view->fetch('financial/customer_list'); 
    }
    //客户报表截取时间
    public function customer_data() 
    {
        return $this->view->fetch('financial/customer_data'); 
    }
    //公司报表
    public function company_form() 
    {
        return $this->view->fetch('financial/company_form'); 
    }
    //修改公司报表
    public function company_edit() 
    {
      return $this->view->fetch('financial/company_edit'); 
    }
} 