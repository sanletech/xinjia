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
        $member_code =Session::get('member_code','think');
        $start_add =$this->request->param('start_id');
        if($start_add){ $this->view->assign('start_add',$start_add);   }
        $end_add =$this->request->param('end_id');
        if($end_add){ $this->view->assign('end_add',$end_add);  }
        $load_time =$this->request->param('load_time');
        if($load_time){ $this->view->assign('load_time',$load_time);  
        $load_time =strtotime($load_time); }
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->price_sum($member_code,$start_add,$end_add,$load_time);
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
       return $this->view->fetch('order/order_list');
    }
    

     //海运运价 分页数据
    public function pagedata()
    {   
        $member_code =Session::get('member_code','think');
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->price_sum($member_code);
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
        $sea_id = $data['sea_id'];//海运费id
        $r_car_id = $data['r_car_id']; //起运港装货拖车费
        $s_car_id = $data['s_car_id']; //目的港送货拖车费
        $pir_id =$data['pir_id'];//起运港港口杂费
        $pis_id =$data['pis_id'];//目的港港口杂费
        $container_size = $data['container_size'];
        $member_code =Session::get('member_code','think');
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->orderBook($sea_id,$r_car_id,$s_car_id,$container_size,$member_code,$pir_id,$pis_id);
        $this->view->assign('list',$list);
      
        return $this->view->fetch('order/place_order');
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
       $member_code =Session::get('member_code');
       //线路价格 海运sea_id 车装货价格r_id 车送货价格s_id
        $seaprice_id =$data['sea_id'];  $carprice_rid=$data['rid']; $carprice_sid =$data['sid'];
        $pir_id=$data['pir_id']; $pis_id =$data['pis_id'];
        //计算出车装货价格 送货价格 船运价格 保险费, 法税 ,利润 ,港口杂费
        $carprice_r= Db::name('carprice')->where('id',$carprice_rid)->value('price_'.$data['container']); //车装货费
        $carprice_s= Db::name('carprice')->where('id',$carprice_sid)->value('price_'.$data['container']); //车送货费
        $seaprice = Db::name('seaprice')->where('id',$seaprice_id)->value('price_'.$data['container']); //海运费
        $portprice_r =Db::name('price_incidental')->where('id',$pir_id)->value($data['container']);    //起运港杂费
        $portprice_s =Db::name('price_incidental')->where('id',$pis_id)->value($data['container']);    //目的港杂费
        $premium  = $data['cargo_cost']*6; //保险费
        $profit = Db::name('member_profit')->alias('MP')  //利润
                ->join('hl_seaprice SP','SP.ship_id=MP.ship_id','left')
                ->field('MP.money')->where('SP.id',$seaprice_id)->group('SP.id,SP.ship_id')->value('MP.money');
        $cost = ($carprice_r + $portprice_r + $seaprice + $portprice_s + $carprice_s + $profit); //单个柜子成本
          //转换税率 
        switch ($data['tax_rate']){
            case 0:
            $tax_rate =0;
            break;
            case 1:
            $tax_rate =0.04; 
            break;
            case 2:
            $tax_rate =0.07; 
            break;
        }
        $total_cost = $cost*$data['container_sum']; //总成本
        $quoted_price  = ($total_cost+$premium)*(1+$tax_rate) ; //总报价
        var_dump($data['money'],$cost);exit;
        if(bccomp($data['money'],$cost,2)!==0){
            return json(['status'=>1,'message'=>'报价错误']);
        }
        var_dump($data['price_sum'],$quoted_price);exit;
        if(bccomp($data['price_sum'],$quoted_price,2)!==0){
            return json(['status'=>1,'message'=>'报价错误']);
        }
        
        $sea_pirce =new OrderM;
        //储存发货人和 收货人的信息
        $shipper   = $data['r_name'].','.$data['r_phone'].','.$data['r_add'].','.$data['r_company'];
        $consigner = $data['s_name'].','.$data['s_phone'].','.$data['s_add'].','.$data['s_company'];
        //储存客户的地址薄 
        $shipper_linkmanID =  $data['r_id']; 
        $consigner_linkmanID= $data['s_id'];
        $res =Db::name('member_add')->where(['shipper_linkmanID'=>$shipper_linkmanID,
            'consigner_linkmanID'=>$consigner_linkmanID,
            'member_code'=> $member_code])->find();
        if(empty($res)){
        $res2 = Db::name('member_add')->insert(['shipper_linkmanID'=>$shipper_linkmanID,
            'consigner_linkmanID'=>$consigner_linkmanID,
            'member_code'=> $member_code,'is_default'=>1]);
        }
        unset($data['sea_id'],$data['rid'],$data['sid'],$data['r_name'],
                $data['r_phone'],$data['r_add'],$data['r_company'],
                $data['s_name'],$data['s_phone'],$data['s_add'],$data['s_company']);
        
        $response = $sea_pirce ->order_data($member_code,$data,$shipper,$consigner,$seaprice_id,$carprice_rid,$carprice_sid,
                 $carprice_r,$carprice_s,$seaprice,$premium,$profit,$cost,$quoted_price,$tax_rate);
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
    
    //港到港
    public function orderPort(){
       
        $start_add =$this->request->param('start_id');
        if($start_add){ $this->view->assign('start_add',$start_add);   }
        $end_add =$this->request->param('end_id');
        if($end_add){ $this->view->assign('end_add',$end_add);  }
        $ship_id =$this->request->param('ship_id');
        if($ship_id){ $this->view->assign('ship',$ship_id);  }
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->price_port($start_add,$end_add,$ship_id);
//        var_dump($list);exit;
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
//        $this->_p($list);exit;
       // $page= $list->render();
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('list',$list);
        return $this->view->fetch('order/order_port');
    }
    
    //港到港下单
    public function portBook(){
        $data =$this->request->param();
        $sea_id = $data['sea_id'];
        $container_size = $data['container_size'];
       // $member_code =Session::get('member_code','think');
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->portBook($sea_id,$container_size);
        $this->view->assign('list',$list);
        return $this->view->fetch('order/place_order_port');
    }
    //港到港下单详情
    public function place_details(){
        return $this->view->fetch('order/place_details');
    }
}
