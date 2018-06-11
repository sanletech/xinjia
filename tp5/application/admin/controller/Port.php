<?php
/*
 * 港口添加修改
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
       // $this->_p($list);exit;
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
    public function ship_name(){
        return $this->view->fetch('carshipman/ship_name');
    }
    //添加船名
    public function ship_add(){
        return $this->view->fetch('carshipman/ship_add');
    }
    //修改船名
    public function ship_edit(){
        return $this->view->fetch('carshipman/ship_edit');
    }

    //航线详情列表
    public function sealine_list(){
        return $this->view->fetch('carshipman/sealine_list');
    }
    //添加航线详情
    public function sealine_add(){
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
        return $this->view->fetch('carshipman/sealine_add');
    }
    

    
} 