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
    public function OrderPortdata(){
        //用户名，订单号，起始日期，结束日期，审核中apply，扣货lock，已放货unlock， 所有订单2，已付款1，未付款0，
        $member = $this->request->param('member','not null','strval');
        $order_num = $this->request->param('order_num','not null','strval');
        $date_start = $this->request->param('date_start',date('y-m-d h:i:s',strtotime('-3month')));
        $date_end = $this->request->param('date_end',date('y-m-d h:i:s'));
        $container_buckle = $this->request->param('container_buckle','apply','strval');
        $money_status = $this->request->param('money_status',2,'intval');
        if($money_status == 2){
            $money_status = 'not null';
        }
//        $page =$this->request->param('page',1,'intval');
//        $limit =$this->request->param('limit',10,'intval');
//        $tol = ($page-1)*$limit;->limit($tol,$limit)
        
        $list =Db::name('order_bill')->alias('OB')
                ->join('hl_member M','M.member_code=OB.member_code','left')
                ->field('OB.*,M.name')
                ->where('OB.member_code',$member)
                ->whereOr('M.name',$member)
                ->where('OB.order_num',$order_num)
                ->whereTime('OB.ctime','between', [$date_start, $date_end])
                ->where('OB.container_buckle',$container_buckle)
                ->where('OB.money_status',$money_status)
                ->order('ctime desc,mtime desc')->buildSql();
        $lista = Db::table($list.' a')->select();
  
        $count = count($lista);
        $list = Db::table($list.' a')->select();
        $this->_p(array('code'=>0,'msg'=>'','count'=>$count,'data'=>$list,
            'member'=>$member,'order_num'=>$order_num,'date_start'=>$date_start,
            'date_end'=>$date_end,'container_buckle'=>$container_buckle,
            'money_status'=>$money_status)
         );exit;
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$list,
            'member'=>$member,'order_num'=>$order_num,'date_start'=>$date_start,
            'date_end'=>$date_end,'container_buckle'=>$container_buckle,
            'money_status'=>$money_status); 
    }       

    public function OrderPortCenter(){
        return $this->view->fetch('financial/OrderPortCenter'); 
    }
} 

