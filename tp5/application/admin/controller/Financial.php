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
        $member = $member? $member:'not null';
        $order_num = $this->request->param('order_num','not null','strval');
        $order_num =$order_num ?$order_num:'not null';
        $date_start = $this->request->param('date_start',date('y-m-d h:i:s',strtotime('-3month')));
        $date_start=$date_start ?$date_start :date('y-m-d h:i:s',strtotime('-3month'));
        $date_end = $this->request->param('date_end',date('y-m-d h:i:s'));
        $date_end=$date_end ?$date_end :date('y-m-d h:i:s');
        $container_buckle = $this->request->param('container_buckle','apply','strval');
        $money_status = $this->request->param('money_status',2,'intval');
        if($money_status == 2){
            $money_status = 'not null';
        }
       $page =$this->request->param('page',1,'intval');
       $limit =$this->request->param('limit',10,'intval');
       $tol = ($page-1)*$limit;

        $list =Db::name('order_bill')->alias('OB')
                ->join('hl_member M','M.member_code=OB.member_code','left')
                ->field('OB.*,M.name')
                ->where('OB.member_code|M.name',$member)
                ->where('OB.order_num',$order_num)
                ->whereTime('OB.ctime','between', [$date_start, $date_end])
                ->where('OB.container_buckle',$container_buckle)
                ->where('OB.money_status',$money_status)
                ->order('ctime desc,mtime desc')->buildSql();
                // var_dump($list);exit;
        $lista = Db::table($list.' a')->select();
  
        $count = count($lista);
        $list = Db::table($list.' a')->limit($tol,$limit)->select();
        foreach($list as $key=>$value){

            switch($value['container_buckle'])
            {
                case 'lock':
                $list[$key]['container_buckle'] ='扣货';
                case 'unlock':
                $list[$key]['container_buckle'] ='放货';
                case 'apply':
                $list[$key]['container_buckle'] ='申请放货';
           }
           
           switch($value['money_status'])
           {
               case '0':
               $list[$key]['money_status'] ='未付款';
               case '1':
               $list[$key]['money_status'] ='已付款';
         
          }

        }
        
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$list,
            'member'=>$member,'order_num'=>$order_num,'date_start'=>$date_start,
            'date_end'=>$date_end,'container_buckle'=>$container_buckle,
            'money_status'=>$money_status); 
    }       

    public function OrderPortCenter(){
        return $this->view->fetch('financial/OrderPortCenter'); 
    }
} 

