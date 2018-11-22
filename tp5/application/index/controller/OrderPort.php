<?php

namespace app\index\controller;
use think\Controller;
use app\index\model\OrderPort as OrderM;
use think\Db;
use think\Session;
class OrderPort extends Controller 
{    
    
    protected $beforeActionList = [
        'login'=> ['except'=>'orderport,routedetail'],
    ];
    protected $member_code='';
    
    //判断登录
    public function login(){
        $this->member_code=  Session::get('member_code');
        //如果登录常量为nll，表示没有登录
        if(is_null($this->member_code)){
            $this->error('未登录，无权访问','login/login');
//            $this->redirect('Login/login');
        } 
    }
    //港到港
    public function orderPort(){
        // var_dump($this->request->param());exit;
        $start_add =$this->request->param('start_add');
        $start_name =$this->request->param('start_name');
        if($start_add){ $this->view->assign(['start_add'=>$start_add,'start_name'=>$start_name]);   }
        
        $end_name =$this->request->param('end_name');
        $end_add =$this->request->param('end_add');
        if($end_add){ $this->view->assign(['end_add'=>$end_add,'end_name'=>$end_name]);  }
        $ship_id =$this->request->param('ship_id');
        if($ship_id){ $this->view->assign('ship_id',$ship_id);  }

        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        
        $sea_pirce =new OrderM;
        // 查询出当前页数显示的数据
        // var_dump($tol,$limit,$start_add,$end_add,$ship_id);exit; 
        $listArr = $sea_pirce ->price_port($tol,$limit,$start_add,$end_add,$ship_id);
        
        $count=$listArr[1];  //获取总页数
        $list = $listArr[0];  //分页数据
//        $this->_p($list);exit;
        $this->view->assign([
            'page'=>$page,
            'count'=>$count,
            'limit'=>$limit,
            'list'=>$list
        ]); 
//        $this->view->assign();
        return $this->view->fetch('orderPort/order_port_list');
    }
    
    //港到港下单
    public function portBook(){
        $data =$this->request->param();
        $seaprice_id= $data['seaprice_id'];//海运价格id
        $container_size = $data['container_size'];
        $member_code =Session::get('member_code','think');
        $sea_pirce =new OrderM;
        $data = $sea_pirce ->portBook($member_code,$seaprice_id,$container_size);
        $list =$data['seapriceData'];$discount=$data['discount'];$default_addArr=$data['default_addArr'];
        //创建订单令牌
        action('OrderToken/createToken','', 'controller');
        // $this->_p($data);exit;
        $this->view->assign('list',$list);
        $this->view->assign('discount',$discount);
        $this->view->assign('default_addArr',$default_addArr);
        return $this->view->fetch('orderPort/port_book');
    }
    //港到港订单的处理
    public function port_data() {
        $data =$this->request->param(); 
//         $this->_p($data);exit;
        $post_token = $this->request->post('TOKEN');
        //检查订单令牌是否重复
        if(!(action('OrderToken/checkToken',['token'=>$post_token], 'controller'))){
            return array('status'=>0,'mssage'=>'不要重复提交订单');
        }
        
        $order_num = action('IDCode/order_num',['type'=>'port'], 'controller');
        
        $mtime= date('Y-m-d H:i:s'); //订单时间
        $member_code =Session::get('member_code','think');//提交账户
        $seaprice_id = $data['seaprice_id']; //海运价格表id
        $container_size=$data['container_size'];//柜型
        $container_sum =$data['container_sum'];//柜量
        //对支付方式做判断
        $payment_method= $data['payment_method'];
         //如果是数字就说明是在线支付了
        if(intval($payment_method)){
            $cash_id = $payment_method;
            $payment_method='cash';
            //计算单个柜优惠的现金优惠金额
            $discount = Db::name('discount')->where('id',$cash_id)->value($container_size);
            //在线支付付款状态就改为已付款
            $money_status =1;
        }  else {
            $cash_id=0;
            $discount=0;
            $money_status =0;
        }
        
        $Pirce =new OrderM;
        //计算装货费用和送货费用
        $truckageData = array(
            'r'=> ['car_price'=>$data['r_car_price'],'num'=>$data['r_num'],'add'=>$data['r_add'],'link_man'=>$data['r_link_man'],'shipper'=>$data['shipper'],
                    'load_time'=>$data['r_load_time'],'link_phone'=>$data['r_link_phone'],'car'=>$data['r_car'],'comment'=>$data['r_comment']   ], 
            's'=> ['car_price'=>$data['s_car_price'],'num'=>$data['s_num'],'add'=>$data['s_add'],'car'=>$data['s_car'], 'comment'=>$data['s_comment'] ] );
               
        // 根据订单号, 下单的柜子总数, 和实际的装货送货数据 来生成order_trackage的信息
        $truckagePrice = $Pirce->truckage($order_num,$data['container_sum'], $truckageData);
     
        //计算出对应的海运，柜型,的单个柜海运费
        $ship_carriage = Db::name('seaprice')->where('id',$data['seaprice_id'])->value('price_'.$container_size);
        if(intval($ship_carriage)!==intval($data['carriage'])){
            return array('status'=>0,'mssage'=>'海运费错误');
        }
        if(intval($discount*$container_sum)!==intval($data['discount'])){
            return array('status'=>0,'mssage'=>'优惠金额错误');
        }
        //计算保险费 = 单个柜货值(万元为单位)*4*柜量
        if(intval($data['premium'])!==intval($data['cargo_value']*4)*$container_sum){
            return array('status'=>0,'mssage'=>'保险费错误');
        }
 
        //计算总共的成本 (海运费 -优惠)*柜子数量 + 保险费用+ 装货费 +送货费;
        $quoted_price= ($ship_carriage-$discount)*$container_sum + $data['premium'] +$truckagePrice['carprice_r']+$truckagePrice['carprice_s'];
//        var_dump($ship_carriage,$discount,$data['premium'],$truckagePrice['carprice_r'],$truckagePrice['carprice_s']);exit;

        if(!(abs($quoted_price- $data['price_sum'])<0.01)){
            return array('status'=>0,'mssage'=>'报价错误');
        } 
        //如果没有选择发票
        if(!array_key_exists('invoice_if',$data)){
            $data['invoice_if']=0;
        }
        $shipper = implode(',',array($data['r_name'],$data['r_company'],$data['r_phone']));//装货信息
        $consigner = implode(',',array($data['s_name'],$data['s_company'],$data['s_phone']));//送货信息
        //生成订单
        $fatherData= array(
        'order_num'=>$order_num,'cargo'=>$data['cargo'],'container_size'=>$container_size,
        'container_sum'=>$container_sum,'weight'=>$data['weight'],'cargo_cost'=>$data['cargo_cost'],
        'container_type'=>$data['container_type'],'comment'=>$data['comment'],'ctime'=>$mtime,'member_code'=>$member_code,
        'payment_method'=>$payment_method,'cash_id'=>$cash_id,'invoice_id'=>$data['invoice_if'],'seaprice_id'=>$data['seaprice_id'],
        'shipper_id'=>$data['s_id'],'consigner_id'=>$data['r_id'],'price_description'=>$data['price_description'],'money_status'=>$money_status,
        'shipper'=>$shipper,'consigner'=>$consigner,'seaprice'=>$ship_carriage,'premium'=>$data['premium'],'discount'=>$discount,
        'carprice_r'=>$truckagePrice['carprice_r'],'carprice_s'=>$truckagePrice['carprice_s'],'quoted_price'=>$quoted_price,
        'type'=>'port', 'status'=>2);
        $res1 = Db::name('order_port')->insert($fatherData); 
        //同时生成账单
        $Bill = controller('Bill');
        $billCreate_res =$Bill->billCreate($order_num);
        return json($res1 ? array('status'=>1,'mssage'=>'提交成功'):array('status'=>0,'mssage'=>'提交失败'));
 
                
    }
    public function apply_cargo() {
        $order_num =  $this->request->param('order_num');
        $mtime =  date('Y-m-d H:i:s');
        $res = Db::name('order_port')->where('order_num',$order_num)->update(['container_buckle'=>'apply','mtime'=>$mtime]);
        $res1 = Db::name('order_bill')->where('order_num',$order_num)->update(['container_buckle'=>'apply','mtime'=>$mtime]);
        return $res? array('status'=>1,'mssage'=>'提交成功'):array('status'=>0,'mssage'=>'提交失败');       
    }

    
    //港到港订单详情页面
    public function orderPortDetail() {
        
        $order_num =  $this->request->get('order_num');
        //访问后台的 model\orderPort\orderData 方法
        $data = new \app\admin\model\orderPort();
        $dataArr = $data->orderData($order_num);
        $this->assign([
                'list'  =>$dataArr['list'],
                'containerData' => $dataArr['containerData'],
                'carData'=> $dataArr['carData'],
                'shipperArr'=>$dataArr['shipperArr'],
                'consignerArr'=>$dataArr['consignerArr'],
                'discount'=>$dataArr['discount']
        ]);
        //渲染后台的详情页面
        return $this->view->fetch('orderPort/order_port_detail');
    }
    
    
    
    
    
        //中间航线详情
     public function routeDetail()
    {  
        $data =$this->request->param('seaprice_id');
        $sea_pirce =new OrderM;
        $route_line= $sea_pirce ->route_detail($data);
        return json($route_line);
    }
    
    
    
        //海运运价
//    public function order_list()
//    {   
//        $member_code =Session::get('member_code','think');
//        $start_add =$this->request->param('start_id');
//        if($start_add){ $this->view->assign('start_add',$start_add);   }
//        $end_add =$this->request->param('end_id');
//        if($end_add){ $this->view->assign('end_add',$end_add);  }
//        $load_time =$this->request->param('load_time');
//        if($load_time){ $this->view->assign('load_time',$load_time);  
//        $load_time =strtotime($load_time); }
//        $sea_pirce =new OrderM;
//        $list = $sea_pirce ->price_sum($member_code,$start_add,$end_add,$load_time);
//        //获取总页数
//        $count =  Db::table($list.' A')->count(); 
//        //获取每页显示的条数
//        $limit= $this->request->param('limit',10,'intval');
//        //获取当前页数
//        $page= $this->request->param('page',1,'intval');  
//        //计算出从那条开始查询
//        // $tol=($page-1)*$limit+1;
//        // 查询出当前页数显示的数据
//        $list = Db::table($list.' A')->limit(($page-1)*$limit,$limit)->select();
//      
//       // $page= $list->render();
//        $this->view->assign('page',$page); 
//        $this->view->assign('count',$count); 
//        $this->view->assign('limit',$limit); 
//        $this->view->assign('list',$list);
////      $this->_p($list);exit;
//       return $this->view->fetch('order/order_list');
//    }
//    
//    //确认下单页面提交选择航线信息
//    public function orderBook()
//    {
//        $data =$this->request->param();
//        $sea_id = $data['sea_id'];//海运费id
//        $r_car_id = $data['r_car_id']; //起运港装货拖车费
//        $s_car_id = $data['s_car_id']; //目的港送货拖车费
//        $pir_id =$data['pir_id'];//起运港港口杂费
//        $pis_id =$data['pis_id'];//目的港港口杂费
//        $container_size = $data['container_size'];
//        $member_code =Session::get('member_code','think');
//        $sea_pirce =new OrderM;
//        $list = $sea_pirce ->orderBook($sea_id,$r_car_id,$s_car_id,$container_size,$member_code,$pir_id,$pis_id);
//        $this->view->assign('list',$list);
//      
//        return $this->view->fetch('order/order_book');
//    }
//    
//    //添加收/发货人的信息
//    public function linkman()
//    {
//        $data =$this->request->param();
//        $sea_pirce =new OrderM;
//        $response = $sea_pirce ->linkman($data);
//       if(!array_key_exists('fail', $response)){
//            $status =1; 
//        }else {
//            $status =0;  
//              }
//        json_encode($status);   
//        return $status ;
//    }
//    
//    //传给前台客户对应的发票信息
//    public function selectInvoice() {
//        $member_code =Session::get('member_code');
//        $res = Db::name('invoice')->where('member_code',$member_code)->select();
//        return json_encode($res);
//    }
//    
//    //传给前台页面客户所有的联系人
//      public function selectlinkman()
//    {
//        $member_code =Session::get('member_code');
//        $res = Db::name('linkman')->where('member_code',$member_code)->order('mtime desc')->select();
//        // $this->_v($res);exit;
//        return json_encode($res);
//    }
//    
//        //添加客户发票的所有信息
//      public function invoice()
//    {
//        $data =$this->request->param();
//         //var_dump($data);exit;
//        $sea_pirce =new OrderM;
//        $response = $sea_pirce ->invoice($data);
//        if(!array_key_exists('fail', $response)){
//            $status =1; 
//        }else {
//            $status =0;  
//              }
//        json_encode($status);   
//        return $status ;
//
//    }
//    
//          //添加客户订单所有信息
//      public function order_data()
//    {
//        $data =$this->request->param();
//        $member_code =Session::get('member_code');
//       //线路价格 海运sea_id 车装货价格r_id 车送货价格s_id
//        $seaprice_id =$data['seaprice_id'];  $carprice_rid=$data['rid']; $carprice_sid =$data['sid'];
//        $pir_id=$data['pir_id']; $pis_id =$data['pis_id'];
//        //计算出车装货价格 送货价格 船运价格 保险费, 法税 ,利润 ,港口杂费
//        $carprice_r= Db::name('carprice')->where('id',$carprice_rid)->value('price_'.$data['container']); //车装货费
//        $carprice_s= Db::name('carprice')->where('id',$carprice_sid)->value('price_'.$data['container']); //车送货费
//        $seaprice = Db::name('seaprice')->where('id',$seaprice_id)->value('price_'.$data['container']); //海运费
//        $portprice_r =Db::name('price_incidental')->where('id',$pir_id)->value($data['container']);    //起运港杂费
//        $portprice_s =Db::name('price_incidental')->where('id',$pis_id)->value($data['container']);    //目的港杂费
//        $premium  = $data['cargo_cost']*6; //保险费
//        $profit = Db::name('member_profit')->alias('MP')  //利润
//                ->join('hl_seaprice SP','SP.ship_id=MP.ship_id','left')
//                ->field('MP.money')->where('SP.id',$seaprice_id)->group('SP.id,SP.ship_id')->value('MP.money');
//        $cost = ($carprice_r + $portprice_r + $seaprice + $portprice_s + $carprice_s + $profit); //单个柜子成本
//          //转换税率 
//        switch ($data['tax_rate']){
//            case 0:
//            $tax_rate =0;
//            break;
//            case 1:
//            $tax_rate =0.04; 
//            break;
//            case 2:
//            $tax_rate =0.07; 
//            break;
//        }
//        $total_cost = $cost*$data['container_sum']; //总成本
//        $quoted_price  = ($total_cost+$premium)*(1+$tax_rate) ; //总报价
//        var_dump($data['money'],$cost);exit;
//        if(bccomp($data['money'],$cost,2)!==0){
//            return json(['status'=>1,'message'=>'报价错误']);
//        }
//        var_dump($data['price_sum'],$quoted_price);exit;
//        if(bccomp($data['price_sum'],$quoted_price,2)!==0){
//            return json(['status'=>1,'message'=>'报价错误']);
//        }
//        
//        $sea_pirce =new OrderM;
//        //储存发货人和 收货人的信息
//        $shipper   = $data['r_name'].','.$data['r_phone'].','.$data['r_add'].','.$data['r_company'];
//        $consigner = $data['s_name'].','.$data['s_phone'].','.$data['s_add'].','.$data['s_company'];
//        //储存客户的地址薄 
//        $shipper_linkmanID =  $data['r_id']; 
//        $consigner_linkmanID= $data['s_id'];
//        $res =Db::name('member_add')->where(['shipper_linkmanID'=>$shipper_linkmanID,
//            'consigner_linkmanID'=>$consigner_linkmanID,
//            'member_code'=> $member_code])->find();
//        if(empty($res)){
//        $res2 = Db::name('member_add')->insert(['shipper_linkmanID'=>$shipper_linkmanID,
//            'consigner_linkmanID'=>$consigner_linkmanID,
//            'member_code'=> $member_code,'is_default'=>1]);
//        }
//        unset($data['sea_id'],$data['rid'],$data['sid'],$data['r_name'],
//                $data['r_phone'],$data['r_add'],$data['r_company'],
//                $data['s_name'],$data['s_phone'],$data['s_add'],$data['s_company']);
//        
//        $response = $sea_pirce ->order_data($member_code,$data,$shipper,$consigner,$seaprice_id,$carprice_rid,$carprice_sid,
//                 $carprice_r,$carprice_s,$seaprice,$premium,$profit,$cost,$quoted_price,$tax_rate);
//        if(!array_key_exists('fail', $response)){
//            $status =1; 
//        }else {
//            $status =0;  
//              }
//        json_encode($status);   
//        return $status ;
//    }
//    
//    //中间航线详情
//     public function route_detail()
//    { 
//        $data =$this->request->param();
//        $this->_P($data);exit;
//         $sea_pirce =new OrderM;
//        $route_line= $sea_pirce ->route_detail($data);
//        return json_encode($route_line);
//    }
//    

}
