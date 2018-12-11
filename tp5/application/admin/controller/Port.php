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
        $city_name = input('get.city_name');
        if($port_name){
            $port_name =  trim($port_name);
            $this->assign('port_name',$port_name); 
        }
        if($city_name){
            $city_name =  trim($city_name);
            $this->assign('city_name',$city_name); 
        }
        $port_list = new PortM;
        $list = $port_list->port_list($city_name,$port_name ,5);
   //  $this->_p($list);exit;
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->view->fetch('port/port_list'); 
    }
    
    //添加港口页面
    public function port_add() 
    {
      return $this->view->fetch('port/port_add'); 
    }
      //添加港口执行
    public function port_toadd() 
    { 
        $data = $this->request->param();
        $city_id = strstr($data['city'], '_',true);
        //使用正则分割页面提交过的字符串分隔符号为,，空格 换行
        $port_str = $data['port_name'];
        $port_str = str_replace(array(',','，',), ' ', $port_str);  //中英文逗号逗号
   
        $port_array = preg_split("/[\s,]+/",$port_str,20,PREG_SPLIT_NO_EMPTY);
        $portadd = new PortM;
        $res = $portadd->port_add($city_id ,$port_array);
        if(!array_key_exists('fail', $res)){
            $response = array('status'=>1,'message'=>'添加港口成功');
        }else {
            $response = array('status'=>0,'message'=>$res['fail']);
        } 
        // var_dump($response);exit;
        return $response;   
    }
    
    
    //删除港口
    public function port_del() 
    {
       //接受port_del 的id 数组
        $data = $this->request->param();
//        var_dump($data);exit;
        $seaprice_id = $data['id'];
        $portdel = new PortM;
        $res = $portdel ->shiproute_boat_port_del($seaprice_id,'port');
        if(!array_key_exists('fail', $res)){
            $response =['status'=>'1','message'=>$res['success']];
        }else {
            $response =['status'=>'0','message'=>$res['fail']];
        } 
        return $response;  
    }
    
    //修改港口页面
    public function port_edit() 
    {
        $id = input('get.id');
        $editPort =new PortM;
        $data = $editPort->port_edit($id);
        $this->assign('data',$data );
       // $this->_v($data);exit;
        return $this->view->fetch('port/port_edit'); 
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
        $ship_id =  $data['ship_id'];
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
        $res = $boatdel ->shiproute_boat_port_del($boat_id,'boat');
        if(!array_key_exists('fail', $res)){
            $response =['status'=>'1','message'=>$res['success']];
        }else {
            $response =['status'=>'0','message'=>$res['fail']];
        } 
        return $response;    
    }

    
    //航线详情列表
    public function shiproute_list(){
        $sl_start = input('get.sl_start');
        $sl_end = input('get.sl_end');
        if($sl_start){
            $sl_start =  trim($sl_start);
            $this->assign('sl_start',$sl_start); 
        }
        if($sl_end){
            $sl_end =  trim($sl_end);
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
            $response =['status'=>'1','message'=>$res['success']];
        }else {
            $response =['status'=>'0','message'=>$res['fail']];
        } 
        return $response;   
    }
    //删除航线详情
    public function shiproute_del(){
           //接受shiproute_del 的id 数组
        $data = $this->request->param();
        $shiproute = $data['id'];
//        var_dump($boat_id);exit;
        $shiproutedel = new PortM;
        $res = $shiproutedel->shiproute_boat_port_del($shiproute,'shiproute');
        if(!array_key_exists('fail', $res)){
            $response =['status'=>'1','message'=>$res['success']];
        }else {
            $response =['status'=>'0','message'=>$res['fail']];
        } 
        return $response;   
    }
    
    
        //分页展示船公司的列表信息
    public function ship_list() 
    {   
        $data    = input('get.'); 
        $ship_name   = input('get.ship_name');
        if($ship_name){
            $this->assign('searchship', $ship_name);
        }
        $shiplist = new PortM;
        $list  = $shiplist->shiplist($ship_name); 
       // $this->_v($list); exit;
        $count = count($list); 
        $page = $list->render();
        
        $this->assign('shiplist', $list);
        $this->assign('page' , $page);
        $this->assign('count',$count);
        return $this->view->fetch('ship/ship_list');
    }
    
    //针对船公司依照不同港口展示联系人的资料
    public function  ship_info(){
        $ship_id   = input('get.ship_id');
        $port_id   = input('get.port_id');
        $datainfo = new ShipM;
        $res = $datainfo->ship_info($ship_id ,$port_id);
         //$this->_p($res);exit;
        if(!$res){
        $res=array( 0=> Array('name' =>'','position' =>'','duty_line' =>'',
            'sn_tel' =>'', 'sn_mobile' =>'','sn_qq' => '','sn_fax' =>'',
            'port_name' =>'暂无','ship_short_name' =>'暂无',));
        }
         $this->assign('list', $res);
        return $this->view->fetch('ship/ship_info');
    }
    
   
   //展示添加页面
    public function  ship_add(){
        return $this->view->fetch('ship/ship_add'); 
   }
   
   public function ship_toadd() {
        $data = $this->request->param();
//        $this->_p($data);exit;
        $ship_short_name = trim($data['ship_short_name']);
        $ship_name =  trim($data['ship_name']);
        $ship =new PortM;
        $res = $ship->ship_toadd($ship_short_name,$ship_name);
        if(!array_key_exists('fail', $res)){
            $response =['status'=>'1','message'=>$res['success']];
        }else {
            $response =['status'=>'0','message'=>$res['fail']];
        } 
        return $response ;
       
   }
   
   //船公司编辑展示页面
    public function ship_edit() {
        $ship_id= $this->request->get('ship_id');
        $data = Db::name('shipcompany')->where('id',$ship_id)->find();
        $this->assign('ship', $data);
        return $this->view->fetch('ship/ship_edit'); 
    }
    
     //执行船公司编辑
    public function ship_toedit() {
        $data = $this->request->only('ship_id,ship_name,ship_short_name');
        $id =$data['ship_id']; unset($data['ship_id']);
        $ship =new PortM;
        $res = $ship->ship_toedit($id,$data);
        if(!array_key_exists('fail', $res)){
            $response =['status'=>'1','message'=>$res['success']];
        }else {
            $response =['status'=>'0','message'=>$res['fail']];
        } 
        return $response ;
     
    }
    
    //执行船公司删除
    public function ship_todel() {
        $data = $this->request->param();
        $ship= new PortM;
        $res = $ship->shiproute_boat_port_del($data['id'],'ship');
        if(!array_key_exists('fail', $res)){
             $response =['status'=>'1','message'=>$res['success']];
        }else {
            $response =['status'=>'0','message'=>$res['fail']];
        } 
        return $response ;
    }

    
} 
