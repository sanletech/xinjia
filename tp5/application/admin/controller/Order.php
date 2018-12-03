<?php
/*
 *  订单管理控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\Order as OrderM;
use app\admin\controller\OrderProcess as OrderProcessC;
use think\Validate;
class Order extends Base
{       
    private $order_status;
    private $page=5;

    public function _initialize()
    {  
        $this->order_status=config('config.order_status');
    }
    
        //审核订单
    public function order_audit() 
    {
        $data = new OrderM;
        $list = $data->order_audit(5,2);
//        $this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('order/order_audit'); 
    }
         //审核详情页
    public function audit_page()
    {   
        $order_num =  $this->request->get('order_num');
        $data = new OrderM;
        $dataArr = $data->orderData($order_num);
//        $this->_p($dataArr);exit;

        $this->assign([
            'list'  =>$dataArr['list'],
            'containerData' => '',
            'carData'=> '',
            'shipperArr'=>$dataArr['shipperArr'],
            'consignerArr'=>$dataArr['consignerArr'],
            'discount'=>''
        ]);;
        return $this->view->fetch('order/audit_page');
    }
    

        
    //查看订单
    public function order_edit() 
    {
        return $this->view->fetch('order/order_edit'); 
    }
    //废弃订单
    public function order_waste() 
    {  
        $data = new OrderM;
        $list = $data->order_audit($pages=5,$state=404040);
//        var_dump($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('order/order_waste'); 
    }
     //废弃订单的恢复
    public function order_waste_pass() 
    { 
       if (request()->isAjax()){
            $idArr =$this->request->param();
            $res =Db::name('order_father')->where('id','in',$idArr['id'])->update(['state'=>0,'action'=>'通过恢复>待审核']);
            $order_numArr = Db::name('order_father')->where('id','in',$idArr['id'])->column('order_num');
            foreach ($order_numArr as $order_num) {
               action('OrderProcess/orderRecord', ['order_num'=>$order_num,'status'=>0,'action'=>'通过恢复>待审核'], 'controller');
            }
           return json($res ? 1 : 0) ;
       }
    }
    
      //废弃订单 的删除
    public function order_waste_del() 
    {
         if (request()->isAjax()){
            $idArr =$this->request->param();
            $res =Db::name('order_father')->where('id','in',$idArr)->delete();
            $order_numArr = Db::name('order_father')->where('id','in',$idArr['id'])->column('order_num');
     
           return json($res ? 1 : 0) ;
       }
        
    }
        
    

    
    
    //展示录入运单号的页面信息
    public function list_booking() 
    {  
        $container_sum  = $this->request->param('container_sum');
        $order_num = $this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $container_code  = $this->request->param('container_code');
        $this->view->assign('order_num',$order_num);
        $this->view->assign('container_sum',$container_sum);
        $this->view->assign('track_num',$track_num);
        $this->view->assign('container_code',$container_code);
        return $this->view->fetch('order/order_booking');
    }
    
    //录入运单号码, 如果只有一个运单号码 就是所有的柜子为一个运单号, 反之 有多少个柜子就录入多少个运单号码
     public function waybillNum () 
    {  
        $data = $this->request->param();
       // var_dump($data['waybillNum']);
        //$track_num =  preg_split('/[,|，| ]+/', $data['waybillNum'], -1, PREG_SPLIT_NO_EMPTY);
        $track_num = $data['track_num'];
        $newTrack_num= trim($data['waybillNum']);
        $container_sum = $data['container_sum'];
        settype($container_sum,'integer'); //转换成int类型
        $order_num = $data['order_num'];
        $track_sum =count($track_num);///输入的运单号码数量
        //运单号,订单号,集装箱数量,输入的运单号数量
        $data=array('track_num'=>$newTrack_num ,'order_num'=>$order_num,'container_sum'=>$container_sum,'track_sum'=>$track_sum);
        $result = $this->validate($data ,'Order');
        if(true !== $result){
            // 验证失败 输出错误信息
            return json(['msg'=>$result,'status'=>0]);
        }

        $trackM = new OrderM;
        $response = $trackM ->waybillNum($order_num,$container_sum,$track_num,$newTrack_num,$track_sum);
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'录入运单号成功','status'=>1];
        }else   {
            $status =['msg'=>'录入运单号失败','status'=>0]; 
        }
        return json($status);  
    }
    
    

    
    //录入派车信息
    public function send_car() 
    {  
        $data =$this->request->param();
        $this->_p($data);exit ;
        $order_num = $this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $contact = $this->request->param('contact');
        $car_data = $this->request->param('car_data');
        $type =$this->request->param('type'); //load装货，send送货
        
        //查询提交的司机信息个数是否和柜子数量一致
        $container_sum = Db::name('order_port')->where('order_num',$order_num)->value('container_sum');
        if($container_sum!== count($car)){
            return json(array('status'=>0,'message'=>'司机信息与柜子数量不符'));
        }
        $Order= new OrderM;
        $response = $Order->send_car($order_num,$track_num,$container_sum,$contact,$car_data,$type);
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>implode(',', $response['success']) ,'status'=>1];
        }else {
            $status =['msg'=>implode(',', $response['fail']) ,'status'=>0]; 
        } 
        return json($status);
    }
    
    //待装货展示页面
    public function listLoad() 
    {    //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM = new OrderM;
        $listArr = $dataM->listOrder($tol,$limit,$state='300');
        //分页数据
        $list =$listArr[0];
//        $this->_p($listArr);exit;
       // 总页数
        $count = $listArr[1];
        $this->view->assign('list_book',$list);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('page_url',url('admin/order/listLoad')); 
        return $this->view->fetch('listOrder/list_load');
    }
    
    //待装货展示页面添加装货时间
    public function addLoadTime() 
    {   
        $order_num =$this->request->get('order_num');
        $track_num =$this->request->get('track_num');
        //根据定单号展示柜号
        $data = Db::name('order_son')->where(['order_num'=>$order_num,'track_num'=>$track_num])
                ->field('track_num,container_code')->select();
//        $this->_v($data);exit;
        $this->assign([
        'order_num'  => $order_num,
        'data' => $data
        ]);
        return $this->view->fetch('order/load_time');
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
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM = new OrderM;
        $listArr = $dataM->listOrder($tol,$limit,$state='400');
        //分页数据
        $list =$listArr[0];
     
        foreach ($list as $key => $value) {
            $list[$key]['container_code']= explode('_', $value['container_code']);
            $list[$key]['track_num']=explode('_', $value['track_num']);
        }
       // $this->_p($list);exit;
       // 总页数
        $count = $listArr[1];
     
        $this->view->assign('list_book',$list);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('page_url',url('admin/order/listBaogui')); 
        return $this->view->fetch('listOrder/list_baogui');
    }
    
    //处理报柜号
    public function toBaogui() {
        $data= $this->request->param();
       
        $order_num =  array_splice($data, -1);
        $order_id = $order_num[0]['order_num'];
        $container_codeArr = array_column($data,'container_code');
       // $this->_p($order_num);$this->_p($container_code);exit;
        $dataM = new OrderM;
        //直接更改状态
        $response  = $dataM->updateState($order_id,$container_codeArr,'505','录完实际装货时间>待配船');
       
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'报柜号成功','status'=>1];
        }else {
            $status =['msg'=>'报柜号失败','status'=>0]; 
        } 
        return json($status);
    }
    
    //待配船list
    public function  listCargo(){
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM = new OrderM;
        $listArr = $dataM->listOrder($tol,$limit,$state='505,515,525,535');
        //分页数据
        $list =$listArr[0];
//        $this->_p($listArr);exit;
       // 总页数
        $count = $listArr[1];
        $this->view->assign('list_book',$list);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('page_url',url('admin/order/listCargo'));
        $this->view->assign('ship_status','待配船'); 
        $this->view->assign('url','admin/order/cargoPlan'); 
        return $this->view->fetch('listOrder/list_cargo');
        
    }
    ///录入配船信息的页面
    public function cargoPlan() {
        $order_num = $this->request->get('order_num');
        $track_num = $this->request->get('track_num');
        $container_code = explode('_', $this->request->get('container_code'));
        //查询对应订单的运线详情
        $dataM = new OrderM;
        $data = $dataM->cargoPlan($order_num,$track_num);
       
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
        $res = $dataM->orderShip($order_num,$track_num,$portArr,$portCodeArr);
       //查询对应order_ship表 的航线信息 根据对应的信息 设置没到对应的字段为只读
        $inputData = $dataM->orderShipInput($order_num,$track_num);
//   $this->_p($inputData);exit;
        $this->view->assign([
            'order_num'=>$order_num,
            'container_code'=>$container_code,
            'track_num'=>$track_num,
            'inputData' =>$inputData,
            'url'=>'admin/order/toCargoPlan'
        ]);
        return $this->view->fetch('order/cargoPlan');
    }
    //处理待配船的信息
    public function toCargoPlan() {
        $data =  $this->request->param();
        //$this->_p($data);exit;
        $order_num =$data['order_num'];
        $container_code =$data['container_code'];
        $track_num = $data['track_num'];
        unset($data['container_code'],$data['order_num'],$data['track_num']);
        $dataM = new OrderM;
        $res = $dataM->toOrderShip($data,$order_num,$track_num);
        //var_dump($res);
        if(!array_key_exists('fail', $res)){
            $status =['msg'=>'录入配船成功','status'=>1];
            //修改订单状态
            $state =$res['orderStatus'];  $action= '录入配船完毕'.$res['sequence'];
            $res1 = $dataM->updateState($order_num,$container_code,$state,$action);
        
            $res1 ?$response['success'][]="修改状态成功" :$response['fail'][]="修改状态失败";   
        }else {
            $status =['msg'=>'录入配船失败','status'=>0]; 
        } 
        return json($status);
       
    }
    
    //展示待到港信息的页面
    public function  listArrival(){
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM = new OrderM;
        $listArr = $dataM->listOrder($tol,$limit,$state='506,516,526,536');
        //分页数据
        $list =$listArr[0];
//        $this->_p($listArr);exit;
       // 总页数
        $count = $listArr[1];
        $this->view->assign('list_book',$list);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('page_url',url('admin/order/listArrival'));
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
        return $this->view->fetch('order/cargoPlan');
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
            $state =$res['orderStatus']; $action= '录入到港完毕'.$res['sequence'];
            $res1 = $dataM->updateState($order_id,$container_code,$state,$action);
            
            $res1 ?$response['success'][]="修改状态成功" :$response['fail'][]="修改状态失败";
        }else {
            $status =['msg'=>'录入待到港失败','status'=>0]; 
        } 
        return json($status);
       
    }


    
    //展示待卸船信息的页面
    public function  listUnShip(){
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM = new OrderM;
        $listArr = $dataM->listOrder($tol,$limit,$state='507,517,527,537');
        //分页数据
        $list =$listArr[0];
//        $this->_p($listArr);exit;
       // 总页数
        $count = $listArr[1];
        $this->view->assign('list_book',$list);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('page_url',url('admin/order/listUnShip'));
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
        return $this->view->fetch('order/cargoPlan');
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
            $state =$res['orderStatus']; $action= '录入卸船完毕'.$res['sequence'];
            $res1 = $dataM->updateState($order_id,$container_code,$state,$action);
            $res1 ?$response['success'][]="修改状态成功" :$response['fail'][]="修改状态失败";
        }else {
            $status =['msg'=>'录入待卸船失败','status'=>0]; 
        } 
        return json($status);
       
    }
    
      //订单收款list页面
    public function listtoCollect() 
    {   
      //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM = new OrderM;
        $listArr = $dataM->listOrder($tol,$limit,$state='800');
        //分页数据
        $list =$listArr[0];
//        $this->_p($listArr);exit;
       // 总页数
        $count = $listArr[1];
        $this->view->assign('list_book',$list);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('status','待收款'); 
        $this->view->assign('page_url',url('admin/order/listtoCollect'));
        $this->view->assign('ajaxurl',url('admin/order/toCollect'));
        return $this->view->fetch('listOrder/list_collect');
    }
    //处理订单收款
    public function toCollect (){
        $order_num = $this->request->param('order_num');
        $payment = $this->request->param('payment');
        //将收款方式写进order_father里
        $res =Db::name('order_father')->where('order_num',$order_num)->update(['send_payment'=>$payment]);
         //直接更改状态
        $container_codeArr =Db::name('order_son')->where('order_num',$order_num)->column('container_code');
        $dataM = new OrderM;
        $response  = $dataM->updateState($order_num,$container_codeArr,'900','收款完毕>待送货');
        
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'收钱成功','status'=>1];
        }else {
            $status =['msg'=>'收钱失败','status'=>0]; 
        } 
        return json($status);
        
    }
    
    
    //展示订单送货list页面
    public function listDelivery () 
    {
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM = new OrderM;
        $listArr = $dataM->listOrder($tol,$limit,$state='900');
        //分页数据
        $list =$listArr[0];
//        $this->_p($listArr);exit;
       // 总页数
        $count = $listArr[1];
        $this->view->assign('list_book',$list);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('status','待送货'); 
        $this->view->assign('page_url',url('admin/order/listDelivery'));
        $this->view->assign('ajaxurl',url('admin/order/toDelivery'));
        return $this->view->fetch('listOrder/list_delivery');
        //return $this->view->fetch('listOrder/list_loadCar');
    }
       //展示处理订单送货页面
    public function deliveryInfo() {
        $order_num = $this->request->param('order_num');

        //直接更改状态
        $container_codeArr =Db::name('order_son')->where('order_num',$order_num)->column('container_code');
        $response  = $dataM->updateState($order_num,$container_codeArr,'999','送货完成>订单完成');
        if(!array_key_exists('fail', $response)){
            $status =['msg'=>'送货成功','status'=>1];
        }else {
            $status =['msg'=>'送货失败','status'=>0]; 
        } 
        return json($status);
    }
    //送货派车信息处理
    
    
    //订单处理页面
    public function order_public() {
        return $this->view->fetch('Order/order_public');
    }

    public function order_data() {
        
        $data= $this->request->except('limit,page');   
        $limit= $this->request->param('limit',10,'intval'); //获取每页显示的条数
        $page= $this->request->param('page',1,'intval');   //获取当前页数
        $search = array_key_exists('search', $data)? $data['search']:''; //搜索条件
        $status = array_key_exists('status', $data)? $data['status']:array(); //状态选择
        $status_arr = array_intersect_key($this->order_status, array_flip($status));
        $dataM = new OrderM;
        $data = $dataM->order_public($page,$limit,$status_arr);
        $list =$data['list']; //分页数据
    //    $this->_p($list);exit;
        $count = $data['count'];// 总页数
        
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$list);
       
    }
    //上传订舱单 和水运单
    public function waybill_upload(){
        
        $file = request()->file('file');
        $order_num = $this->request->param('order_num');
        $type = trim($this->request->param('type'));
        if(!($type=='book_note'||$type=='sea_waybill')){
            return FALSE;
        }
        $track_num= $this->request->param('track_num');
        $OrderProcessC =  new OrderProcessC();
        $res =$OrderProcessC->Upload($order_num,$type,$file);
        //上传成功就更改状态
        if($res['status']=='1'){
                $dataM = new OrderM;
                if($type=='book_note'){
                    $status =  $this->order_status['send_car'];
                    $title ='上传订舱单->待派车';
                    if(!empty(trim($track_num))){
                        $map =['track_num'=>$track_num,'container_status'=>1];
                        $res= Db::name('order_port')->where('order_num',$order_num)->update($map);
                    }
                }elseif ($type=='sea_waybill') {
                    $status =  $this->order_status['send_car'];
                    $title ='上传水运单->待申请放柜';
                }
                //更改状态
                $res1 = $dataM->orderUpdate($order_num,$status,$title);
                return $res1; 
        }  else {
            
            return $res;
        }
    }

    //查看订单
    public function order_aaa()
    {
        $data= $this->request->param();
        $this->_p($data);exit;
    }
 
} 
