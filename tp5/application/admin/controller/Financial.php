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
    //放货对账付款状态更改
    public function OrderPortCenter(){
        //    //所用的账单 done 未完成 undone
        $type = $this->request->param('type');
        // var_dump($type);exit;
        if($type=='done'){
            $money_status ='not null';
        }elseif($type=='undone'){
            $money_status = 0;
        }
      
        $page =$this->request->param('page',1,'intval');
        $limit =$this->request->param('limit',10,'intval');
        $tol = ($page-1)*$limit;
      
        $list =Db::name('order_bill')
                ->field('member_code',TRUE)->where('money_status',$money_status)
                ->order('ctime desc,mtime desc')->buildSql();
        $lista = Db::table($list.' a')->select();
  
        $count = count($lista);
        $list = Db::table($list.' a')->limit($tol,$limit)->select();
        
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$list);
        

    }

    public function aaa(){
        return $this->view->fetch('financial/OrderPortCenter'); 
    }
} 

