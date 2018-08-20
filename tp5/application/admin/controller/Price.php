<?php
/*
 *  运价设置
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\Price as PriceM;
use app\admin\model\Port as PortM;
use think\Db;

class Price extends Base
{   
//    public function __construct(Request $request = null) {
//        parent::__construct($request);
//        $this->request= $request;
//    }
     //航线运价
    public function price_route() 
    {   
        $port_start = input('get.port_start');
        $port_over = input('get.port_over');
        if($port_start){
            $this->assign('port_start',$port_start); 
        }
        if($port_over){
            $this->assign('port_over',$port_over); 
        }
        $route = new PriceM;
        $list = $route->price_route_list($port_start,$port_over ,5);
        //$this->_p($list);exit;
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->view->fetch('Price/price_route'); 
    }
    //航线详情添加展示页面
    public function route_add(){
//        //传递船公司下面的船只
//        $sql ="select * from hl_boat ";
//        $boat_data = Db::query($sql);
//        $js_boat=json_encode($boat_data);
//        $this->view->assign('js_boat',$js_boat);
        
        return $this->view->fetch('Price/route_add');
    }
    //传递根据前面选择的起点和终点的中间线路行情
    public function route_select(){
        $data = $this->request->param();
     //   $this->_v($data);exit;
        $sl_start =$data['sl_start'];
        $sl_end =$data['sl_end'];
        //使用PortM 里的行情list方法查询对应的中间港口
        $ship_route = new PortM;
        $res =$ship_route->shiproute_list('','',100,$sl_start,$sl_end);
        $middle_route =$res->column('port_name','m_id');
        return json($middle_route);    
    }
    //航线添加
    public function route_toadd(){
        $data = $this->request->param();
//       $this->_p($data);  exit;
        $seaprice = new PriceM;
        $res = $seaprice->price_route_add($data); 
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status; 
    }
    
    //航线修改页面
    public function route_edit(){
        $seaprice_id = input('get.seaprice_id');
        $route_id= input('get.route_id');
        $seaprice = new PriceM;
        $res = $seaprice-> price_route_list('','',1,$seaprice_id);
//        $this->_p($res['0']);exit;
        $this->assign('data',$res['0']);
        return $this->view->fetch("Price/route_edit");
    }
    //航线执行修改
    public function route_toedit(){
        $data = $this->request->param();
//      $this->_p($data);exit;
        $seaprice = new PriceM;
        $res = $seaprice->price_route_toedit($data);          
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);  
         return $status; 
    }
    //航线运价删除
    public function route_del(){
        //接受price_route_del 的id 数组
        $data = $this->request->param();
        $seaprice_id = $data['id'];
        $seaprice = new PriceM;
        $res = $seaprice->price_route_del($seaprice_id);
         if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    
    
    //拖车运价列表页面
    public function price_trailer() 
    {      
        $port_name = input('get.port_name');
      
        if($port_name){
            $this->assign('port_name',$port_name); 
        }
    
        $route = new PriceM;
        $list = $route->price_trailer_list($port_name ,15);
        //$this->_p($list);exit;
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('url','admin/Price/price_trailer');
        $this->assign('addUrl','admin/Price/trailer_add');
        $this->assign('editUrl','admin/Price/trailer_edit');
        $this->assign('delUrl','admin/Price/traile_del');
        return $this->view->fetch('Price/price_trailer'); 
    }
    //拖车运价添加展示页面
    public function trailer_add(){
        //传递所有的车队给前台页面
        $sql2="select id ,car_name from  hl_cardata ";
        $car_data =Db::query($sql2);
       //转成json格式传给js
        $js_car = json_encode($car_data);
      //  $this->view->assign('js_port',$js_port);
        $this->view->assign('js_car',$js_car);
        $this->view->assign('url','admin/price/trailer_toadd');
        return $this->view->fetch("Price/trailer_add");
    }
    //拖车运价添加执行页面
    public function trailer_toadd(){
        $data = $this->request->param();
    //   $this->_v($data);exit;
        //根据港口和地址 贮存车队送货/装货线路
        $port_id = strstr($data['port'],'_',true); 
        $address_data =  $data['town'] ? $data['town'] :$data['area']; 
        $load = $data['load'];
        $load['car']= strstr($data['car_load'],'_',true); 
        $send = $data['send'];
        $send['car']= strstr($data['car_send'],'_',true); 
        $carprice = new PriceM;
        $res = $carprice->price_trailer_toadd($port_id, $address_data, $load, $send);
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
      
    }
    //拖车运价修改页面
    public function trailer_edit(){
        $data = $this->request->param();
        $carprice = new PriceM;
        $res =$carprice->price_trailer_edit($data);   
//      $this->_p($res);exit;
        //传递所有的车队给前台页面
        $sql2="select id ,car_name from  hl_cardata ";
        $car_data =Db::query($sql2);
       //转成json格式传给js
        $js_car = json_encode($car_data);
        
        $this->view->assign('js_car',$js_car);
        $this->view->assign('data',$res);
        $this->view->assign('url','admin/Price/route_toedit');
        return $this->view->fetch("price/trailer_edit");
    }
    //拖车运价执行修改
    public function trailer_toedit(){
        $data = $this->request->param();
       $this->_p($data);exit;
        $carprice = new PriceM;
        $res =$carprice->price_trailer_toedit($data);          
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);  
         return $status; 
    }
    //拖车运价执行删除
    public function traile_del(){
        //接受price_route_del 的id 数组
        $data = $this->request->param();
        $carprice_id = $data['id'];
        $carprice = new PriceM;
        $res = $carprice->price_trailer_del($carprice_id);
         if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    
    
    //拖车定价列表页面
    public function price_transport() 
    {       
        $port_name = input('get.port_name');
      
        if($port_name){
            $this->assign('port_name',$port_name); 
        }
    
        $route = new PriceM;
        $list = $route->price_trailer_list($port_name ,15);
        //$this->_p($list);exit;
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('url','admin/Price/price_transport');
        $this->assign('addUrl','admin/Price/transport_add');
        $this->assign('editUrl','admin/Price/transport_edit');
        $this->assign('delUrl','admin/Price/transport_del');
        return $this->view->fetch('Price/price_trailer'); 
    }
    //拖车定价添加展示页面
    public function transport_add(){
        //传递所有的车队给前台页面
        $sql2="select id ,car_name from  hl_cardata ";
        $car_data =Db::query($sql2);
       //转成json格式传给js
        $js_car = json_encode($car_data);
      //  $this->view->assign('js_port',$js_port);
        $this->view->assign('js_car',$js_car);
         $this->view->assign('url','admin/price/transport_toadd');
        return $this->view->fetch("Price/trailer_add");
    }
    //拖车定价添加执行页面
    public function transport_toadd(){
        $data = $this->request->param();
    //  $this->_v($data);exit;
        //根据港口和地址 贮存车队送货/装货线路
        $port_id = strstr($data['port'],'_',true); 
        $address_data =  $data['town'] ? $data['town'] :$data['area']; 
        $load = $data['load'];
        $send = $data['send'];
        $carprice = new PriceM;
        $res = $carprice->price_trailer_toadd($port_id, $address_data, $load, $send);
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
      
    }
    //拖车定价修改页面
    public function transport_edit(){
        $data = $this->request->param();
    ;
        $carprice = new PriceM;
        $res =$carprice->price_transport_edit($data);   
   $this->_p($res);exit;
        $this->view->assign('data',$res);
        $this->view->assign('carView','不展示车队');
        $this->view->assign('url','admin/Price/route_toedit');
        return $this->view->fetch("price/transport_edit");
    }
    //拖车定价执行修改
    public function transport_toedit(){
        $data = $this->request->param();
       $this->_p($data);exit;
        $carprice = new PriceM;
        $res =$carprice->price_transport_toedit($data);          
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);  
         return $status; 
    }
    //拖车定价执行删除
    public function transport_del(){
        //接受price_route_del 的id 数组
        $data = $this->request->param();
        $carprice_id = $data['id'];
        $carprice = new PriceM;
        $res = $carprice->price_transport_del($carprice_id);
         if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    
    
}