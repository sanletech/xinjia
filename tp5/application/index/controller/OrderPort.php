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
    protected $order_status;
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
        
        $sea_pirce =new OrderM;
        // 查询出当前页数显示的数据
        $listArr = $sea_pirce ->price_port($page,$limit,$start_add,$end_add,$ship_id);
        
        $list = $listArr['list'];  //获取总页数
        $count = $listArr['count'];  //分页数据
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
        $data =$this->request->param();
//        $this->_p($data);exit;
//        $data= $data['data'];
        $post_token = $data['TOKEN'];
        $OrderPortM =new OrderM();
        $response =  $OrderPortM->port_data($data,$post_token);
        return $response;   
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
            $data['status']=  $this->order_status['container_appley'];
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
        $oder_type = substr($order_num, 0,1);
        if($oder_type =='P'){
            $this->assign([
               'list'  =>$dataArr['list'],
               'containerData' => $dataArr['containerData'],
               'carData'=> $dataArr['carData'],
               'shipperArr'=>$dataArr['shipperArr'],
               'consignerArr'=>$dataArr['consignerArr'],
            ]);
            return $this->view->fetch('orderPort/order_port_detail');
            
        }  else {
            
            $this->assign([
                'list'  =>$dataArr['list'],
                'containerData' => $dataArr['containerData'],
                'carData'=> $dataArr['carData'],
                'shipperArr'=>$dataArr['shipperArr'],
                'consignerArr'=>$dataArr['consignerArr'],
                'shipData'=> $dataArr['shipData'],
            ]);
            return $this->view->fetch('order/order_detail');
        }
        //渲染后台的详情页面
        return $this->view->fetch('order/order_details');
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
