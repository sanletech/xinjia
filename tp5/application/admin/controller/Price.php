<?php
/*
 *  运价设置
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\Price as PriceM;
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
    public function route_add(){
          //传递所有的港口给前台页面
        $sql3="select *  from  hl_port ";
        $port_data =Db::query($sql3);
       //转成json格式传给js
        $js_port=json_encode($port_data);
        
        //传递所有的船公司给前台页面选择
        $sql4="select id , ship_short_name  from  hl_shipcompany ";
        $ship_data =Db::query($sql4);
       //转成json格式传给js
        $js_ship=json_encode($ship_data);
        
        $this->view->assign('js_port',$js_port);
        $this->view->assign('js_ship',$js_ship);
        
        return $this->view->fetch('Price/route_add');
    }
    //航线添加
    public function route_toadd(){
        $data = $this->request->param();
       // $this->_p($data);  exit;
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
        $sl_id = input('get.sl_id');
        $sm_id = input('get.sm_id');
        $seaprice = new PriceM;
        $res = $seaprice->price_route_edit($seaprice_id,$sl_id,$sm_id); 
       // $this->_p($res);exit;
       //传递原始数据给页面
        $data = $res[0];
        $middle_data =$res[1]; 
        $this->view->assign('data',$data);
        $this->view->assign('middle_data',$middle_data);
        //传递所有的港口给前台页面
        $sql3="select *  from  hl_port ";
        $port_data =Db::query($sql3);
        //转成json格式传给js
        $js_port=json_encode($port_data);
        
        //传递所有的船公司给前台页面选择
        $sql4="select id , ship_short_name  from  hl_shipcompany ";
        $ship_data =Db::query($sql4);
       //转成json格式传给js
        $js_ship=json_encode($ship_data);
        
        $this->view->assign('js_port',$js_port);
        $this->view->assign('js_ship',$js_ship);
        return $this->view->fetch("Price/route_edit");
    }
    //航线执行修改
    public function route_toedit(){
        $data = $this->request->param();
       // $this->_p($data);exit;
        $seaprice = new PriceM;
        $res = $seaprice->price_route_toedit($data);          
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);  
         return $status; 
    }
    //航线删除
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
    
    
        //拖车运价
    public function price_trailer() 
    {               
        return $this->view->fetch('Price/price_trailer'); 
    }
   //航线添加页面
    
    //拖车添加展示页面
    public function trailer_add(){
        //传递所有的港口给前台页面
        $sql="select *  from  hl_port ";
        $port_data =Db::query($sql);
       //转成json格式传给js
        $js_port = json_encode($port_data);
        
        //传递所有的车队给前台页面
        $sql2="select id ,car_name from  hl_cardata ";
        $car_data =Db::query($sql2);
       //转成json格式传给js
        $js_car = json_encode($car_data);
        
        $this->view->assign('js_port',$js_port);
        $this->view->assign('js_car',$js_car);
        
        return $this->view->fetch("Price/trailer_add");
    }
    //拖车添加执行页面
    public function trailer_toadd(){
        $data = $this->request->param();
        //  $this->_v($data);exit;
        //根据港口和地址 贮存车队送货/装货线路
        $port_id = strstr($data['port'],'_',true); 
        $address_id =  $data['town'] ? $data['town'] :$data['area']; 
        $load = $data['load'];
        $load['car']= strstr($data['car_load'],'_',true); 
        $send = $data['send'];
        $send['car']= strstr($data['car_send'],'_',true); 
        $carprice = new PriceM;
        $res = $carprice-> price_trailer_toadd($port_id, $address_id, $load, $send);
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
      
    }
    
    //拖车修改
    public function trailer_edit(){
      
        return $this->view->fetch("price/trailer_edit");
    }
}