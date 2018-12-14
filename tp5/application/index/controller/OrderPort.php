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
    protected $member_code;
    private $order_status;
    private $page=5;

    public function _initialize()
    {  
        $this->order_status=config('config.order_status');
    }
    
    //判断登录
    public function login(){
        $this->member_code =  Session::get('member_code');
//        var_dump( $this->member_code );exit;
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
        $member_code = $this->member_code;
        $sea_pirce =new OrderM;
        $data = $sea_pirce ->portBook($member_code,$seaprice_id,$container_size);
        $list =$data['seapriceData']; //下单信息
        $discount=$data['discount']; //优惠信息
        $default_addArr=$data['default_addArr']; //默认地址
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
        $data =$this->request->param('data'); 
//         $this->_p($data);exit;
        $post_token = $this->request->post('TOKEN');
        //检查订单令牌是否重复
        if(!(action('OrderToken/checkToken',['token'=>$post_token], 'controller'))){
            return array('status'=>0,'mssage'=>'不要重复提交订单');
        }
        
        $order_num = action('IDCode/order_num',['type'=>'port'], 'controller');
        
        $mtime= date('Y-m-d H:i:s'); //订单时间
        $member_code =$this->member_code;//提交账户
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
            $money_status ='do';
        }  else {
            $cash_id=0;
            $discount=0;
            $money_status ='nodo';
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
        'shipper'=>$shipper,'consigner'=>$consigner,'seaprice'=>$ship_carriage,'premium'=>$data['premium'],'discount'=>-$discount,
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
        //记录客户修改的时间
        if($res){
            $data=[];
            $data['submitter'] = $this->member_code;
            $data['mtime']=$mtime;
            $data['order_num']=$order_num;
            $data['status']=  $this->status['container_appley'];
            $res2 = Db::name('order_port_status')->insert($data);
        }
        return $res? array('status'=>1,'mssage'=>'提交成功'):array('status'=>0,'mssage'=>'提交失败');       
    }

    
    //港到港订单详情页面
    public function orderPortDetail() {
        
        $order_num =  $this->request->get('order_num');
        //访问后台的 model\orderPort\orderData 方法
        $data = new \app\admin\model\OrderProcess();
        $dataArr = $data->order_details($order_num);
        $this->assign([
                'list'  =>$dataArr['list'],
                'containerData' => $dataArr['containerData'],
                'carData'=> $dataArr['carData'],
                'shipperArr'=>$dataArr['shipperArr'],
                'consignerArr'=>$dataArr['consignerArr'],
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
    
    
    
     
}
