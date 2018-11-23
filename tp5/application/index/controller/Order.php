<?php

namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Order as OrderM;
use think\Db;
use think\Session;
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
        if($load_time){ $this->view->assign('load_time',$load_time);  
        $load_time =strtotime($load_time); }
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
        // var_dump($data); exit;
       if(!array_key_exists('fail', $response)){
            $response=['status'=>1,'message'=>'添加联系人成功'];
        }else {
            $response=['status'=>0,'message'=>'添加联系人失败'];
        }
        
        return $response ;
    }
      //收/发货人的信息的删除
    public function linkmanDel() {
        $id=$this->request->param('id');
        $res =Db::name('linkman')->where('id',$id)->delete();
        $res ? $response=['status'=>1,'message'=>'删除联系人成功']: $response=['status'=>0,'message'=>'删除联系人失败'];
        return $response;
    }
    //收/发货人的信息的修改
    public function linkmanUpdate() {
        $data=$this->request->param();
        $id= $data['id'];
        $tem['name'] = $data['link_name'];
        $tem['phone'] = $data['phone'];
        $tem['company'] = $data['company'];
        $tem['address'] = $data['add'];
        $res =Db::name('linkman')->where('id',$id)->update($tem);
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
//        $this->_p($data);exit;
        $post_token = $this->request->post('TOKEN');
        //检查订单令牌是否重复
        if(!(action('OrderToken/checkToken',['token'=>$post_token], 'controller'))){
            return array('status'=>0,'mssage'=>'不要重复提交订单');
        }
        $member_code =Session::get('member_code');
       //线路价格 海运sea_id 车装货价格r_id 车送货价格s_id
        $sea_id = $data['sea_id']; //海运路线ID
        $carprice_rid = $data['rid']; //拖车装货费_id
        $carprice_sid = $data['sid'];//拖车送货费_id
        $pir_id = $data['pir_id'];    //起运港口港杂费_id
        $pis_id = $data['pis_id'];   //目的港口杂费_id
        $premium = $data['premium']; //总共的保险费
        $cargo_cost = $data['cargo_value'];
       
        //计算出车装货价格 送货价格 船运价格 ,利润 ,港口杂费 是否一致
        $member_code =Session::get('member_code');
        $container_size = $data['container_size'];
        $container_sum = $data['container_sum'];
        $sea_pirce =new OrderM;
        $data_price  = $sea_pirce->orderBook($sea_id ,$container_size,$member_code); //一个柜的总价格
        $carriage = $data_price['price_sum_'.$container_size];
        //计算门到门的海运费是否一致
        if(intval($data['carriage']) !== intval($carriage)){
            return array('status'=>0,'mssage'=>'海运费错误');
        }
        //门到门的总装货费,送货费,客户的利润
        $carprice_r = $data_price['r_'.$container_size];
        $carprice_s = $data_price['s_'.$container_size]; 
        $discount  = $data_price['discount_'.$container_size]; 
        //计算保险费 = 单个柜货值(万元为单位)*4*柜量
        if(intval($data['premium'])!==intval($data['cargo_value']*4)*$container_sum){
            return array('status'=>0,'mssage'=>'保险费错误');
        }
        //计算下单总共柜子的报价
//        var_dump(intval($data['price_sum']), intval($carriage*$container_sum));exit;
        if( intval($data['price_sum'])!==  intval($carriage*$container_sum+$data['premium']) ){
            return array('status'=>0,'mssage'=>'总费用错误');
        }
        $order_num = action('IDCode/order_num',['type'=>'door'], 'controller');   
        
        $shipper = implode(',',array($data['r_name'],$data['r_company'],$data['r_phone']));//装货信息
        $consigner = implode(',',array($data['s_name'],$data['s_company'],$data['s_phone']));//送货信息
       
        $fatherData =array('order_num'=>$order_num,'cargo'=>$data['cargo'],'container_size'=>$container_size,
            'container_sum'=>$container_sum,'weight'=>$data['weight'],'cargo_cost'=>$data['cargo_cost'],
            'container_type'=>$data['container_type'],'comment'=>$data['comment'],'member_code'=>$member_code,
            'ctime'=>date('Y-m-d H:i:s'),'payment_method'=>$data['payment_method'],'shipper_id'=>$data['r_id'],
            'shipper'=>$shipper,'consigner_id'=>$data['s_id'],'consigner'=>$consigner,'seaprice_id'=>$sea_id,
            'price_description'=>$data['price_description'],'premium'=>$data['premium'],'quoted_price'=>$data['price_sum'],
            'carprice_r'=>$carprice_r,'carprice_s'=>$carprice_s,'discount'=>$discount,
            'type'=>'door','status'=>2);
        $res =Db::name('order_port')->insert($fatherData);
        action('Bill/billCreate',['order_num'=>$order_num], 'controller'); //同时创建账单
        return json($res ? array('status'=>1,'message'=>'下单成功'):array('status'=>0,'message'=>'下单失败') );
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
