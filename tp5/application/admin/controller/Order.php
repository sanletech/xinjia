<?php
/*
 *  订单管理控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\Order as OrderM;
class Order extends Base
{   
    
    
        //审核订单
    public function order_audit() 
    {
        $data = new OrderM;
        $list = $data->order_audit();
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('Order/order_audit'); 
    }
    //审核订单 的通过
    public function order_audit_pass() 
    { 
       if (request()->isAjax()){
           $data =$this->request->param();
           $id=  implode(',', $data['id']);
           $sql = 'update hl_order_father set state = "2" where id  in  ('.$id.')';
           //var_dump($sql);exit;
           $res =Db::execute($sql);
           return json($res ? 1 : 0) ;
       }
    }
    
      //审核订单 的删除
    public function order_audit_del() 
    {
         if (request()->isAjax()){
           $data =$this->request->param();
           $id=  implode(',', $data['id']);
           $sql = 'update hl_order_fahter set state = "1" where id  in'. $id;
           $res =Db::execute($sql);
           return json($res ? 1 : 0) ;
       }
        
    }
    
  
    
    //处理订单
    public function order_list() 
    {
        $data = new OrderM;
        $list = $data->order_list();
        $page =$list->render();
        $count =  count($list);
//      $this->_p($list);exit;
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
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
    

    
    //处理订单订舱
    public function list_booking() 
    {  
        $container_num  = $this->request->param('container_num');
        $order_num = $this->request->param('order_num');
        $this->view->assign('order_num',$order_num);
        $this->view->assign('container_num',$container_num);
        return $this->view->fetch('Order/list_booking');
    }
    //录入运单号码, 如果只有一个运单号码 就是所有的柜子为一个运单号, 反之 有多少个柜子就录入多少个运单号码
     public function waybillNum () 
    {  
        $data = $this->request->param();
       // var_dump($data['waybillNum']);
        $track_num =  preg_split('/[,|，| ]+/', $data['waybillNum'], -1, PREG_SPLIT_NO_EMPTY);
        $container_num =$data['container_num'];
        $order_num = $data['order_num'];
        $track_sum =count($track_num);///输入的运单号码数量
        if(!($track_sum ==$data['container_num']&& $track_sum==1)){
            return '输入的运单号数量不对';
        }
        $trackM = new OrderM;
        $response = $trackM ->waybillNum ($order_num,$container_num,$track_num, $track_sum);
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