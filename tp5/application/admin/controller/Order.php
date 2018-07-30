<?php
/*
 *  订单管理控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\Order as OrderM;
use think\Validate;
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
           $sql = 'update hl_order_father set state = "1" where id  in  ('.$id.')';
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
            $data = new OrderM;
            $sql = 'update hl_order_fahter set state = "10" where id  in'. $id;
            $res =Db::execute($sql);
            return json($res ? 1 : 0) ;
       }
        
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
    
    //处理订单的公共头部
    public function orderCenter() 
    {
        return $this->view->fetch('Order/order_center'); 
    }
    
    //待订舱页面list
    public function listBook() 
    {   
        $data = new OrderM;
        $list = $data->listBook();
        $page =$list->render();
        $count =  count($list);
//      $this->_p($list);exit;
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        return $this->view->fetch('listOrder/list_book'); 
    }
    
    
    //展示录入运单号的页面信息
    public function list_booking() 
    {  
        $container_sum  = $this->request->param('container_sum');
        $order_num = $this->request->param('order_num');
        $container_code  = $this->request->param('container_code');
        $this->view->assign('order_num',$order_num);
        $this->view->assign('container_sum',$container_sum);
        $this->view->assign('container_code',$container_code);
        return $this->view->fetch('Order/list_booking');
    }
    
    //录入运单号码, 如果只有一个运单号码 就是所有的柜子为一个运单号, 反之 有多少个柜子就录入多少个运单号码
     public function waybillNum () 
    {  
        $data = $this->request->param();
       // var_dump($data['waybillNum']);
        $track_num =  preg_split('/[,|，| ]+/', $data['waybillNum'], -1, PREG_SPLIT_NO_EMPTY);
        $container_sum = $data['container_sum'];
        settype($container_sum,'integer'); //转换成int类型
        $order_num = $data['order_num'];
        $track_sum =count($track_num);///输入的运单号码数量
        //运单号,订单号,集装箱数量,输入的运单号数量
        $data=array('track_num'=>$track_num ,'order_num'=>$order_num,'container_sum'=>$container_sum,'track_sum'=>$track_sum);
        $result = $this->validate($data ,'Order');
        if(true !== $result){
            // 验证失败 输出错误信息
            return json(['msg'=>$result,'status'=>0]);
        }

        $trackM = new OrderM;
        $response = $trackM ->waybillNum ($order_num,$container_sum,$track_num, $track_sum);
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'录入运单号成功','status'=>1];
        }else   {
            $status =['msg'=>'录入运单号失败','status'=>0]; 
        }
        return json($status);  
    }
    
    
    //待派车list页面
    public function listSendCar() 
    {      
        $dataM = new OrderM;
        $list = $dataM->listSendCar($pages=5,$state='2');
        $page =$list->render();
        $count =  count($list);
       // $this->_p($list);exit;
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        return $this->view->fetch('listOrder/list_sendCar');
    }
    
    //展示录入派车信息页面
    public function sendCarInfo() 
    {  
        $order_num =$this->request->get('order_num');
        $container_sum =$this->request->get('container_sum');
        $container_code =$this->request->get('container_code');
        $track_num = Db::name('order_son')->where('order_num',$order_num)->column('track_num');
      // var_dump($track_num);exit;
        $this->assign([
        'order_num'  => $order_num,
        'container_sum' => $container_sum,
        'container_code' => $container_code,
        'track_num'=>$track_num
        ]);
        return $this->view->fetch('Order/sendCarInfo');
    }
    
    //录入派车信息
    public function tosendCar() 
    {  
        $data =$this->request->param();
      //$this->_v($data);exit;
        $Order= new OrderM;
        $response = $Order->tosendCar($data);
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'录入运单号成功','status'=>1];
        }else {
            $status =['msg'=>'录入运单号失败','status'=>0]; 
        } 
        return json($status);
    }
    
    //待装货展示页面
    public function listLoad() 
    {   
        $dataM = new OrderM;
        $list = $dataM->listSendCar($pages=5,$state='3');
        $page =$list->render();
        $count =  count($list);
     //  $this->_p($list);exit;
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        return $this->view->fetch('listOrder/list_load');
    }
    
    //待装货展示页面添加装货时间
    public function addLoadTime() 
    {   
        $order_num =$this->request->get('order_num');
        //根据定单号展示柜号
        $data = Db::name('order_son')->where("order_num = '$order_num'")->column('track_num','container_code');
      // $this->_v($data);exit;
        $this->assign([
        'order_num'  => $order_num,
        'data' => $data
        ]);
        return $this->view->fetch('Order/load_time');
    }
        
    //添加实际装货时间
    public function toLoadTime() 
    {   
        $order_num = $this->request->post('order_num');
        $data = $this->request->except('order_num');
        //var_dump($data);exit;
        //根据运单号和 柜号 添加对应的实际装货时间
        $dataM = new OrderM;
        $response= $dataM->toLoadTime($order_num,$data);
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'录入运单号成功','status'=>1];
        }else {
            $status =['msg'=>'录入运单号失败','status'=>0]; 
        } 
        return json($status);
    }
    
    //展示需要向船公司提交柜号的list页面
    public function listBaogui() 
    {   
        $dataM = new OrderM;
        $list = $dataM->listSendCar($pages=5,$state='4');
//        $this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        return $this->view->fetch('listOrder/list_baogui');
    }
    
    //处理报柜号
    public function toBaogui() {
        $data= $this->request->param();
        $dataM = new OrderM;
        $response  = $dataM->toBaogui($data);
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'录入运单号成功','status'=>1];
        }else {
            $status =['msg'=>'录入运单号失败','status'=>0]; 
        } 
        return json($status);
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