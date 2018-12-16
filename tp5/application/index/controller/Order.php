<?php

namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Order as OrderM;
use think\Db;
use think\Session;
use app\index\controller\IDCode ;
use app\index\controller\Bill;
class Order extends Base 
{    
    //海运运价
    public function order_list()
    {   
        // var_dump($this->request->param());exit;
        $member_code =Session::get('member_code','think');
        $start_add =$this->request->param('start_id');
        if($start_add){ $this->view->assign('start_add',$start_add);   }
        
        $end_add =$this->request->param('end_id');
        if($end_add){ $this->view->assign('end_add',$end_add);  }
        
        $load_time =$this->request->param('load_time');
        if($load_time){ $this->view->assign('load_time',$load_time);  } 
        
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $sea_pirce =new OrderM;
        $data = $sea_pirce ->price_sum($member_code,$start_add,$end_add,$load_time,$page,$limit);
        //获取总页数
        $count =  $data['count']; 
        $list = $data['list'] ;
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('list',$list);
//      $this->_p($list);exit;
       return $this->view->fetch('order/order_list');
    }
    
    //确认下单页面提交选择航线信息
    public function orderBook()
    {
        $data =$this->request->param();
        $sea_id = $data['sea_id'];//海运费id
        $container_size = $data['container_size'];
        if(!($container_size=='40HQ' || $container_size=='20GP')){
            return false;
        }
        $member_code =Session::get('member_code','think');
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->orderBook($sea_id ,$container_size,$member_code);
         //创建订单令牌
        action('OrderToken/createToken','', 'controller');
//        $this->_p($list);exit;
        $this->view->assign('list',$list);
        return $this->view->fetch('order/order_book');
    }

    
    //添加收/发货人的信息
    public function linkmanAdd()
    {
        $data =$this->request->param();
        $sea_pirce =new OrderM;
        $response = $sea_pirce ->linkmanAdd($data);
       if($response){
            $response=['status'=>1,'message'=>'添加联系人成功'];
        }else {
            $response=['status'=>0,'message'=>'添加联系人失败'];
        }
        
        return $response ;
    }
    
    //收/发货人的信息的删除
    public function linkmanDel() {
        $id=$this->request->param('id');
        $member_code =Session::get('member_code');
        $res =Db::name('linkman')
                ->where('member_code',$member_code)
                ->where('id','in',$id)->update(['status'=>0]);
        $res ? $response=['status'=>1,'message'=>'删除联系人成功']: $response=['status'=>0,'message'=>'删除联系人失败'];
        return $response;
    }
    //收/发货人的信息的修改
    public function linkmanUpdate() {
        $data=$this->request->param();
        // var_dump($data);exit;
        $id= $data['id'];
        $tem['name'] = $data['link_name'];
        $tem['phone'] = $data['phone'];
        $tem['company'] = $data['company'];
        $tem['address'] = $data['add'];
        $tem['mtime']= date('Y-m-d H:i:s');
        $member_code =Session::get('member_code');
        $res =Db::name('linkman')->where(['member_code'=>$member_code,'id'=>$id])->update($tem);
        $res ? $response=['status'=>1,'message'=>'修改联系人成功']: $response=['status'=>0,'message'=>'修改联系人失败'];
        return $response;
    }    
    
    //传给前台客户对应的发票信息
    public function selectInvoice() {
        $member_code =Session::get('member_code');
        $res = Db::name('invoice')->where('member_code',$member_code)->select();
        return json_encode($res);
    }
    
    //传给前台页面客户所有的联系人
      public function selectlinkman()
    {
        $member_code = Session::get('member_code');
        $res = Db::name('linkman')->where(['member_code'=>$member_code,'status'=>1])->order('mtime desc')->select();
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
//        $data = $data['data'];
        $post_token = $this->request->param('TOKEN');
        $is_wechat = $this->request->param('type');
        $order_data =new OrderM;
        $response = $order_data ->order_data($data ,$post_token,$is_wechat);
        return json($response) ;
    }
    
    //中间航线详情
     public function routeDetail()
    {  
        
        $data =$this->request->param('seaprice_id');
        echo $data;exit;
         $sea_pirce =new OrderM;
        $route_line= $sea_pirce ->route_detail($data);
        return json($route_line);
    }


}
