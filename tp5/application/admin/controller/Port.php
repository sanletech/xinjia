<?php
/*
 * 数据录入管理
 * 港口管理
 * 船名管理
 * 航线详情管理
 * 
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\Port as PortM;
use think\Db;
class Port extends Base
{   
  
    //港口列表
    public function port_list() 
    {
        $port_name = input('get.port_name');
        if($port_name){
            $this->assign('port_name',$port_name); 
        }
        $port_list = new PortM;
        $list = $port_list->port_list($port_name ,5);
   //  $this->_p($list);exit;
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->view->fetch('port\port_list'); 
    }
    
    //添加港口页面
    public function port_add() 
    {
      return $this->view->fetch('port\port_add'); 
    }
      //添加港口执行
    public function port_toadd() 
    { 
        $data = $this->request->param();
        $city_id = strstr($data['city'], '_',true);
        //使用正则分割页面提交过的字符串分隔符号为,，空格 换行
        $port_array = preg_split('/[;,，\r\n]+/s', $data['port_name']); 
//        $this->_v($port_array);exit;
        $portadd = new PortM;
        $res = $portadd->port_add($city_id ,$port_array);
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    
 
 
    
    
    
    
    
    
    
    
    
    
    
    
    //删除港口
    public function port_del() 
    {
       //接受port_del 的id 数组
        $data = $this->request->param();
        $seaprice_id = $data['id'];
        $portdel = new PortM;
        $res = $portdel ->port_del($seaprice_id);
         if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    
    //修改港口页面
    public function port_edit() 
    {
        $id = input('get.id');
        $editPort =new PortM;
        $data = $editPort->port_edit($id);
        $this->assign('data',$data );
       // $this->_v($data);exit;
        return $this->view->fetch('port\port_edit'); 
    }
        //修改港口执行
    public function port_toedit() 
    {
        $data = $this->request->param();
        //$this->_v($data);exit;
        $id = $data['id'];
        if(array_key_exists('city_id', $data)&&$data['city_id']>0){
            $city =$data['city_id'];
        }else{
            $city = strstr($data['city'],'_',true);
        }
        
        $port_name = $data['port_name'];
        $port_code = Db::name('port')->where('city_id',"'$city'")->max('port_code');
        if($port_code < $city*1000){
            $port_code = $city*1000+1;
        }else { 
            $port_code = $port_code + 1; 
        }
        $porttoedit = new PortM;
        $res = $porttoedit ->port_toedit($id,$city,$port_code,$port_name);
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;    
    }
    
        //船名列表
    public function boat_name(){
        $ship_name = input('get.ship_name');
        $boat_name = input('get.boat_name');
        if($ship_name){
            $this->assign('ship_name',$ship_name);
        }
        if($boat_name){
            $this->assign('boat_name',$boat_name);
        }
        
        $boat_list = new PortM;
        $list = $boat_list->boat_list($ship_name, $boat_name,5);
//        $this->_v($list);exit;
        $page = $list->render();
        $this->assign('boatlist',$list);
        $this->assign('page',$page);
        return $this->view->fetch('port/boat_name');
    }
    //添加船名
    public function boat_add(){
        
        return $this->view->fetch('port/boat_add');
    }
    //执行添加船名
    public function boat_toadd(){
        $data = $this->request->param();
        $boat_code = $data['boat_code'];
        $boat_name = $data['boat_name'];
        $ship_id =  substr($data['ship'],0,strpos($data['ship'], '_'));
        $boatadd = new PortM;
        $res =$boatadd->boat_add($ship_id, $boat_code,$boat_name);
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;    
        
    }
    //删除船名
    public function boat_del(){
        //接受port_del 的id 数组
        $data = $this->request->param();
        $boat_id = $data['id'];
//        var_dump($boat_id);exit;
        $boatdel = new PortM;
        $res = $boatdel ->boat_del($boat_id);
         if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }

    
    //航线详情列表
    public function shiproute_list(){
        $sl_start = input('get.sl_start');
        $sl_end = input('get.sl_end');
        if($sl_start){
            $this->assign('sl_start',$sl_start); 
        }
        if($sl_end){
            $this->assign('sl_end',$sl_end); 
        }
        $shiproute =new PortM;
        $list = $shiproute->shiproute_list($sl_start,$sl_end ,5);
//        $this->_p($list);exit;
        $page =$list->render();
        $this->view->assign('page',$page);
        $this->view->assign('list',$list);
        return $this->view->fetch('port/shiproute_list');
    }
    //添加航线详情
    public function shiproute_add(){
        return $this->view->fetch('port/shiproute_add');
    }
    //添加航线详情
    public function shiproute_toadd(){
        $data = $this->request->param();
//        $this->_v($data);exit;
        $port_arr= $data['port_code'];
        $route =new PortM;
        $res = $route->shiproute_add($port_arr);
        if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    //删除航线详情
    public function shiproute_del(){
           //接受shiproute_del 的id 数组
        $data = $this->request->param();
        $shiproute = $data['id'];
//        var_dump($boat_id);exit;
        $shiproutedel = new PortM;
        $res = $shiproutedel->shiproute_del($shiproute);
         if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    
    
    

    
} 