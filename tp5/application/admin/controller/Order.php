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
            $status =['msg'=>'报柜号成功','status'=>1];
        }else {
            $status =['msg'=>'报柜号失败','status'=>0]; 
        } 
        return json($status);
    }
    
    //待配船list
    public function  listCargo(){
        $dataM = new OrderM;
        $list = $dataM->listShip($pages=5,$state='505,515,525,535');
        
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        $this->view->assign('ship_status','待配船'); 
        $this->view->assign('url','admin/order/cargoPlan'); 
        return $this->view->fetch('listOrder/list_cargo');
        
    }
    ///录入配船信息的页面
    public function cargoPlan() {
        $order_num = $this->request->get('order_num');
        $container_code = explode('_', $this->request->get('container_code'));
        //查询对应订单的运线详情
        $dataM = new OrderM;
        $data = $dataM->cargoPlan($order_num);
       
        $portArr =[]; $portCodeArr=[];
        if(!empty($data['middle_id'])){
            $portArr = explode('_', $data['port_middle']);
            array_unshift($portArr,$data['port_s']);
            array_push($portArr,$data['port_e']);
            
            $portCodeArr = explode('_', $data['port_middle_code']);
            array_unshift($portCodeArr,$data['port_code_s']);
            array_push($portCodeArr,$data['port_code_e']);
           
        }  else {
            $portArr[]=$data['port_s'];
            $portArr[]=$data['port_e'];
            $portCodeArr[]=$data['port_code_s'];
            $portCodeArr[]=$data['port_code_e'];
        }
        $num =count($portArr)-1;
       
       //生成对应order_ship表 贮存对应的航线信息
        $res = $dataM->orderShip($order_num,$portArr,$portCodeArr);
       //查询对应order_ship表 的航线信息 根据对应的信息 设置没到对应的字段为只读
        $inputData = $dataM->orderShipInput($order_num);
//   $this->_p($inputData);exit;
        $this->view->assign([
            'order_num'=>$order_num,
            'container_code'=>$container_code,
            'inputData' =>$inputData,
            'url'=>'admin/order/toCargoPlan'
        ]);
        return $this->view->fetch('Order/cargoPlan');
    }
    //处理待配船的信息
    public function toCargoPlan() {
        $data =  $this->request->param();
        //$this->_p($data);exit;
        $order_id =$data['order_id'];
        $container_code =$data['container_code'];
        unset($data['container_code'],$data['order_id']);
        $dataM = new OrderM;
        $res = $dataM->toOrderShip($data,$order_id);
        var_dump($res);
        if(!array_key_exists('fail', $res)){
            $status =['msg'=>'录入配船成功','status'=>1];
            //修改订单状态
            $father =['order_num'=>[$order_id],'state'=>$res['orderStatus'],'action'=>'录入配船完毕'.$res['sequence']];
            $son =['order_num'=>[$order_id],'container_code'=>$container_code,'state'=>$res['orderStatus'],'action'=>'录入配船完毕'.$res['sequence']];
            $dataM->updateState($father, $son);
        }else {
            $status =['msg'=>'录入配船失败','status'=>0]; 
        } 
        return json($status);
       
    }
    
    //展示待到港信息的页面
    public function  listArrival(){
        $dataM = new OrderM;
        $list = $dataM->listShip($pages=5,$state='506,516,526,536');
        //$this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        $this->view->assign('ship_status','待到港'); 
        $this->view->assign('url','admin/order/arrivalPort'); 
        return $this->view->fetch('listOrder/list_cargo'); 
    }
    //录入待港口信息页面
    public function arrivalPort() {
        $order_num = $this->request->get('order_num');
        $container_code = explode('_', $this->request->get('container_code'));
        //查询对应order_ship表 的航线信息 根据对应的信息 设置没到对应的字段为只读
        $dataM = new OrderM;
        $inputData = $dataM->orderShipInput($order_num);
        $this->view->assign([
            'order_num'=>$order_num,
            'container_code'=>$container_code,
            'inputData' =>$inputData,
            'url'=>'admin/order/toArrivalPort'
        ]);
        return $this->view->fetch('Order/cargoPlan');
    }
    //处理待港口的信息
    public function toArrivalPort() {
        $data =  $this->request->param();
        //$this->_p($data);exit;
        $order_id =$data['order_id'];
        $container_code =$data['container_code'];
        unset($data['container_code'],$data['order_id']);
        $dataM = new OrderM;
        $res = $dataM->toOrderShip($data,$order_id);
       // var_dump($res);
        if(!array_key_exists('fail', $res)){
            $status =['msg'=>'录入待到港成功','status'=>1];
            //修改订单状态
            $father =['order_num'=>[$order_id],'state'=>$res['orderStatus'],'action'=>'录入待到港完毕'.$res['sequence']];
            $son =['order_num'=>[$order_id],'container_code'=>$container_code,'state'=>$res['orderStatus'],'action'=>'录入待到港完毕'.$res['sequence']];
            $dataM->updateState($father, $son);
        }else {
            $status =['msg'=>'录入待到港失败','status'=>0]; 
        } 
        return json($status);
       
    }
    
    //展示待卸船信息的页面
    public function  listUnShip(){
        $dataM = new OrderM;
        $list = $dataM->listShip($pages=5,$state='507,517,527,537');
        //$this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        $this->view->assign('ship_status','待卸船'); 
        $this->view->assign('url','admin/order/unShip'); 
        return $this->view->fetch('listOrder/list_cargo'); 
    }
    //录入待卸船信息页面
    public function unShip() {
        $order_num = $this->request->get('order_num');
        $container_code = explode('_', $this->request->get('container_code'));
        //查询对应order_ship表 的航线信息 根据对应的信息 设置没到对应的字段为只读
        $dataM = new OrderM;
        $inputData = $dataM->orderShipInput($order_num);
        $this->view->assign([
            'order_num'=>$order_num,
            'container_code'=>$container_code,
            'inputData' =>$inputData,
            'url'=>'admin/order/toUnShip'
        ]);
        return $this->view->fetch('Order/cargoPlan');
    }
    //处理待卸船的信息
    public function toUnShip() {
        $data =  $this->request->param();
        //$this->_p($data);exit;
        $order_id =$data['order_id'];
        $container_code =$data['container_code'];
        unset($data['container_code'],$data['order_id']);
        $dataM = new OrderM;
        $res = $dataM->toOrderShip($data,$order_id);
       // var_dump($res);
        if(!array_key_exists('fail', $res)){
            $status =['msg'=>'录入待卸船成功','status'=>1];
            //修改订单状态
            $father =['order_num'=>[$order_id],'state'=>$res['orderStatus'],'action'=>'录入待卸船完毕'.$res['sequence']];
            $son =['order_num'=>[$order_id],'container_code'=>$container_code,'state'=>$res['orderStatus'],'action'=>'录入待卸船完毕'.$res['sequence']];
            $dataM->updateState($father, $son);
        }else {
            $status =['msg'=>'录入待卸船失败','status'=>0]; 
        } 
        return json($status);
       
    }
    
      //处理订单收款
    public function listtoCollect () 
    {   
        $dataM = new OrderM;
        $list = $dataM->listSendCar($pages=5,$state='800');
       // $this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        $this->view->assign('url','admin/order/toCollect');
        return $this->view->fetch('listOrder/list_collect');
    }
         //处理订单收款
    public function toCollect (){
        $data= $this->request->param();
        $this->_p($data);exit;
        $father =['order_num'=>[$order_id],'state'=>$res['orderStatus'],'action'=>'录入收款完毕'];
        $son =['order_num'=>[$order_id],'container_code'=>$container_code,'state'=>$res['orderStatus'],'action'=>'录入收款完毕'];
        $dataM->updateState($father, $son);
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'收钱成功','status'=>1];
        }else {
            $status =['msg'=>'收钱失败','status'=>0]; 
        } 
        return json($status);
        
    }
    
    
    //展示订单送货
    public function listDelivery () 
    {
        $dataM = new OrderM;
        $list = $dataM->listSendCar($pages=5,$state='900');
       // $this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count_book',$count);
        $this->view->assign('list_book',$list);
        $this->view->assign('page_book',$page);
        $this->view->assign('page_book',$page);
        $this->view->assign('url','admin/order/toDelivery');
        return $this->view->fetch('listOrder/list_collect');
    }
       //处理订单送货
    public function toDelivery() {
        $data= $this->request->param();
        $this->_p($data);exit;
        $father =['order_num'=>[$order_id],'state'=>$res['orderStatus'],'action'=>'录入送货完毕'];
        $son =['order_num'=>[$order_id],'container_code'=>$container_code,'state'=>$res['orderStatus'],'action'=>'录入送货完毕'];
        $dataM->updateState($father, $son);
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'收钱成功','status'=>1];
        }else {
            $status =['msg'=>'收钱失败','status'=>0]; 
        } 
        return json($status);
        
  
        
    }
  
 


    
} 