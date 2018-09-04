<?php

namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Order as OrderM;
use think\Db;
use think\Session;
class Order extends Base 
{
    //路线详情
    public function order_xq()
    {
       return $this->view->fetch('order/order_xq');
    }
    
    //海运运价
    public function order_list()
    {
        $start_add =$this->request->param('start_id');
        if($start_add){ $this->view->assign('start_add',$start_add);   }
        $end_add =$this->request->param('end_id');
        if($end_add){ $this->view->assign('end_add',$end_add);  }
        $load_time =$this->request->param('load_time');
        if($load_time){ $this->view->assign('load_time',$load_time);  
        $load_time =strtotime($load_time); }
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->price_sum($start_add,$end_add,$load_time);
        //获取总页数
        $count =  Db::table($list.' A')->count(); 
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        // $tol=($page-1)*$limit+1;
        // 查询出当前页数显示的数据
        $list = Db::table($list.' A')->limit(($page-1)*$limit,$limit)->select();
      
       // $page= $list->render();
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('list',$list);
//      $this->_p($list);exit;
       return $this->view->fetch('Order/order_list');
    }
    

     //海运运价 分页数据
    public function pagedata()
    {
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->price_sum();
        $count =  Db::table($list.' A')->count(); //获取总页数
        //获取每页显示的条数
        $limit= $this->Request->param('limit');
        //获取当前页数
        $page= $this->Request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit+1;
        // 查询出当前页数显示的数据
        $list = Db::table($list.' A')->where("id",">=","$tol")->limit("$limit")->select()->toArray();
        
        return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
    }
    
    
    //确认下单页面提交选择航线信息
    public function orderBook()
    {
        $data =$this->request->param();
        $sea_id = $data['sea_id'];
        $r_car_id = $data['r_car_id'];
        $s_car_id = $data['s_car_id'];
        $container_size = $data['container_size'];
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->orderBook($sea_id,$r_car_id,$s_car_id,$container_size);
        $this->view->assign('list',$list);
      
        return $this->view->fetch('Order/place_order');
    }
    
    //添加收/发货人的信息
    public function linkman()
    {
        $data =$this->request->param();
        $sea_pirce =new OrderM;
        $response = $sea_pirce ->linkman($data);
       if(!array_key_exists('fail', $response)){
            $status =1; 
        }else {
            $status =0;  
              }
        json_encode($status);   
        return $status ;
    }
    //传给前台页面客户所有的联系人
      public function selectlinkman()
    {
        $data =$this->request->param();
        $member_code =Session::get('member_code');
        $res = Db::name('linkman')->where('member_code',$member_code)->order('mtime desc')->select();
        // $this->_v($res);exit;
        return json_encode($res);
    }
        //添加客户发票的所有信息
      public function invoice()
    {
        $data =$this->request->param();
         //var_dump($data);exit;
        $sea_pirce =new OrderM;
        $response = $sea_pirce ->invoice($data);
        if(!array_key_exists('fail', $response)){
            $status =1; 
        }else {
            $status =0;  
              }
        json_encode($status);   
        return $status ;

    }
    
          //添加客户订单所有信息
      public function order_data()
    {
        $data =$this->request->param();
//        $this->_P($data);exit;
       //线路价格sea_id r_id s_id  存进book_line表里
        $sea_id =$data['sea_id'];
        $rid =$data['rid'];
        $sid =$data['sid'];
        $sea_pirce =new OrderM;
        $book_line_id = $sea_pirce ->book_line($sea_id,$rid,$sid);
        unset($data['sea_id'],$data['rid'],$data['sid']);
        $response = $sea_pirce ->order_data($data,$book_line_id);
        if(!array_key_exists('fail', $response)){
            $status =1; 
        }else {
            $status =0;  
              }
        json_encode($status);   
        return $status ;
    }
    
    //中间航线详情
     public function route_detail()
    { 
        $data =$this->request->param();
        $this->_P($data);exit;
         $sea_pirce =new OrderM;
        $route_line= $sea_pirce ->route_detail($data);
        return json_encode($route_line);
     }
}
