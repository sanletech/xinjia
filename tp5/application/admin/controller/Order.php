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
use app\admin\model\OrderProcess as  OrderProcessM;
use think\Validate;
class Order extends Base
{       
    private $order_status;
    private $page=5;

    public function _initialize()
    {  
        $this->order_status=config('config.order_status');
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
        
    
    
    //录入派车信息
    public function send_car() 
    {  
        $data =$this->request->param();
//        $this->_p($data);exit;
        $order_num = $this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $contact = $data['contact'];
        $car_data = $data['car_data'];
        $type =$this->request->param('type'); //load装货，send送货
        //查询提交的司机信息个数是否和柜子数量一致
        $container_sum = Db::name('order_port')->where('order_num',$order_num)->value('container_sum');
        if($container_sum!== count($car_data)){
            return json(array('status'=>0,'message'=>'司机信息与柜子数量不符'));
        }
        $OrderM= new OrderM;
        $res = $OrderM->send_car($order_num,$track_num,$container_sum,$contact,$car_data,$type);
        if(!array_key_exists('fail', $res)){
            //更新订单状态同时记录操作
            $OrderProcessM = new OrderProcessM();
            $response = $OrderProcessM->orderUpdate($order_num,$status=$this->order_status['loading'],$title='录入派车信息->待装货');
       
        }else {
            $response =['msg'=>implode(',', $response['fail']) ,'status'=>0]; 
        } 
        return json($response);
    }
    
   //带装货 的 柜号封条 司机 信息获取
    public function LoadData() 
    { 
        $order_num =$this->request->get('order_num');
        $data = Db::name('order_car')
                ->where('order_num',$order_num)
                ->field('id,container_code,seal_No,driver_name')
                ->find();
        return json($data);
    }
    
    //带装货 添加装货时间
    public function addLoadTime() 
    {   
//      $this->_p($this->request->param());exit;
        $order_num =$this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $modify = $this->request->param('modify');
        $lists = $this->request->only('list');
        //更新order_car的时间
        $arr=[];
        $mtime = date('Y-m-d H:i:s');
        foreach ($lists as $value){
            $value['mtime']=$mtime;
            $id = $value['id'];
            if($modify){
                $map= array('work_time'=>$value['work_time']);
            }  else {
                $map=$value;
            }
            $res = Db::name('order_car')->where('id',$id)->update($map);
            $res ? $arr['success'][] = $id :$arr['fail'][] = $id ;
        }
        if(array_key_exists('fail', $arr)){
            return json(array('status'=>0,'message'=> '更新失败'.implode(',', $arr['fail'])));
        }  else {
              //更新订单状态同时记录操作
            $OrderProcessM = new OrderProcessM();
            $response = $OrderProcessM->orderUpdate($order_num,$status=$this->order_status['up_container_code'],$title='录入装货时间->待报柜号');
            return json($response);
        }
    
    }
        

    
    //待报柜号
    public function report_container () {
        $order_num =$this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $modify = $this->request->param('modify');//是否修改
        $lists = $this->request->only('list');
        //更新order_car的时间
        $arr=[];
        $mtime = date('Y-m-d H:i:s');
        if($modify){
            foreach ($lists as $value){
                $value['mtime']=$mtime;
                $id = $value['id'];
                $res = Db::name('order_car')->where('id',$id)->update($value);
                $res ? $arr['success'][] = $id :$arr['fail'][] = $id ;
            }
            if(array_key_exists('fail', $arr)){
                return json(array('status'=>0,'message'=> '更新失败'.implode(',', $arr['fail'])));
            }
        }
       
        //更新订单状态同时记录操作
        $OrderProcessM = new OrderProcessM();
        $response = $OrderProcessM->orderUpdate($order_num,$status=$this->order_status['load_ship'],$title='报柜号完毕->待配船');
        return json($response);
       
    }
    
    //待配船 的港口信息
    public function  cargo_plan_data(){
        $order_num =$this->request->param('order_num');
        $data = Db::name('order_ship')
                ->where('order_num',$order_num)
                ->find();
        return json($data); 
    }
    
    //待配船 
    public function  cargo_plan(){
        $order_num = $this->request->param('order_num');
        $type = $this->request->param('type');
        $modify = $this->request->param('modify');//是否修改 true or false
        $shipment_time = $this->request->param('shipment_time');  //实际开船时间
        $dispatch_time = $this->request->param('dispatch_time');//离港时间
        $arrival_time  = $this->request->param('arrival_time'); //到港时间
        $discharge_time = $this->request->param('discharge_time');//卸货时间
        // 查找是否存在
        $id = Db::name('order_ship')->where('order_num',$order_num)->value('id');
        if($modify){
            
            
        }
        
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
//        $this->_p($this->request->param());exit;
        $OrderProcessC =  new OrderProcessC();
        $res =$OrderProcessC->Upload($order_num,$type,$file);
        //上传成功就更改状态
        if($res['status']=='1'){
                $OrderM = new OrderProcessM();
                if($type=='book_note'){
                    $status =  $this->order_status['send_car'];
                    $title ='上传订舱单->待派车';
                    if(!empty(trim($track_num))){
                        $map =['track_num'=>$track_num,'container_status'=>'do'];
                        $res= Db::name('order_port')->where('order_num',$order_num)->update($map);
                    }
                }elseif ($type=='sea_waybill') {
                    $status =  $this->order_status['send_car'];
                    $title ='上传水运单->待申请放柜';
                }
                //更改状态
                $res1 = $OrderM->orderUpdate($order_num,$status,$title);
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
