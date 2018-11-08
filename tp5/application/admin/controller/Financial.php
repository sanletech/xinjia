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
    private $order_status;
    private $page=5;

    public function _initialize()
    {  
        $this->order_status=config('config.order_status');
  
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
    
     //放货对账付款状态更改页面
    public function OrderPortCenter(){
        return $this->view->fetch('financial/OrderPortCenter'); 
    }
    //收款状态修改
    public function orderport_money(){
        $order_num = $this->request->post('order_num');
        //同时修改订单和账单的扣柜状态
        // 启动事务
        Db::startTrans();
        try{
            $res =Db::name('order_port')->where('order_num',$order_num)->update(['money_status'=>1]);
            $res1 =Db::name('order_bill')->where('order_num',$order_num)->update(['money_status'=>1]);
            Db::commit();    
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return array('status'=>0,'message'=>'操作失败');
        }
        $data = new \app\admin\model\orderPort();
        $status= $this->order_status['payment_status'];
        $action='确认收款';
        $data->orderUpdate($order_num,$status,$action);
        return array('status'=>1,'message'=>'操作成功');
    }
    
      //订单的扣柜状态更改 通过和 驳回,需要填写说明
    public function orderport_judgment() 
    { 
       if (request()->isAjax()){
            $data =$this->request->param();
            $type =$data['type'];
            $order_num =$data['order_num'];
            $comment =$data['comment'];
            //$type 的值是pass就放柜，fail 就不放柜
            if($type=='fail'){
                $status = $this->order_status['container_lock'];
                $title='申请放柜>驳回';
            }elseif($type=='pass') {
                $status = $this->order_status['container_unlock'];
                $title='申请放柜>通过';
            }
            $data = new \app\admin\model\orderPort();
            $response= $data->orderUpdate($order_num,$status,$title,$comment);
            return $response;
       }
    }
    
    //放货对账付款状态更改
    public function OrderPortdata(){
        //用户名，订单号，起始日期，结束日期，审核中apply，扣货lock，已放货unlock， 所有订单2，已付款1，未付款0，
        $member = $this->request->param('member','not null','strval');
        $member = $member? $member:'not null';
        $order_num = $this->request->param('order_num','not null','strval');
        $order_num =$order_num ?$order_num:'not null';
        $date_start = $this->request->param('date_start',date('y-m-d h:i:s',strtotime('-3month')));
        $date_start=$date_start ?$date_start :date('y-m-d H:i:s',strtotime('-3month'));
        $date_end = $this->request->param('date_end',date('y-m-d H:i:s'));
        $date_end=$date_end ?$date_end :date('y-m-d H:i:s');
        $container_buckle = $this->request->param('container_buckle','apply','strval');
        // var_dump($container_buckle);exit;
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
        $count =  Db::table($list.' a')->count();
        $list = Db::table($list.' a')->limit($tol,$limit)->select();
        foreach($list as $key=>$value){

            switch($value['container_buckle'])
            {
                case 'lock':
                $list[$key]['container_buckle'] ='扣货';
                break;   
                case 'unlock':
                $list[$key]['container_buckle'] ='放货';
                break;  
                case 'apply':
                $list[$key]['container_buckle'] ='申请放货';
                break;  
           }
           
           switch($value['money_status'])
           {
                case '0':
                $list[$key]['money_status'] ='未付款';
                break; 
                case '1':
                $list[$key]['money_status'] ='已付款';
                break; 
            }
            switch($value['status'])
           {
                case $this->order_status['cancel']:
                $list[$key]['status'] ='订单取消';
                break; 
                case $this->order_status['order_audit']:
                $list[$key]['status'] ='订单审核中';
                break;
                case $this->order_status['completion']:
                $list[$key]['status'] ='订单审已经完成';
                break;  
                default:
                $list[$key]['status'] ='订单进行中';  
            }


        }
        
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$list,
            'member'=>$member,'order_num'=>$order_num,'date_start'=>$date_start,
            'date_end'=>$date_end,'container_buckle'=>$container_buckle,
            'money_status'=>$money_status); 
    }       

 
} 

