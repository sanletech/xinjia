<?php
/*
 *  船公司通讯录控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Ship as ShipM ;
use think\Request;
use think\Db;
class Ship extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    //分页展示船公司的列表信息
    public function ship_list() 
    {   
        $data    = input('get.'); 
        $ship_name   = input('get.ship_name');
        $port_name   = input('get.port_name');
        if($ship_name){
            $this->assign('searchship', $ship_name);
        }
        if($port_name){
            $this->assign('searchport', $port_name);
        } 
        $shiplist = new ShipM;
        $list  = $shiplist->shiplist($ship_name,$port_name); 
       // $this->_v($list); exit;
        $count = count($list); 
        $page = $list->render();
        
        $this->assign('shiplist', $list);
        $this->assign('page' , $page);
        $this->assign('count',$count);
        return $this->view->fetch('Ship/ship_list');
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
        return $this->view->fetch('Ship/ship_info');
    }
    
   //执行删除
    public function to_del() {
       //接受ship_port_city 的id 数组
        $data = $this->request->param();
        $id_arr = $data['id'];
        foreach ($id_arr as $k=>$v){
           $arr[] = explode('_', $v);
        }
        $result =   array();
        //依照ship_id 分组对应的port_id
        foreach($arr as $k=>$v){
            $result[$v[0]][]    =   $v[1];
        } 
        $shipdel = new ShipM;
        $res = $shipdel->to_del($result);
        if(!array_key_exists('fail', $res)){
                  $status =1; 
              }else {
                  $status =0;  
                    }
        json_encode($status);   
        
         return $status;   
   }
   
   //展示添加页面
    public function  ship_add(){
       
        //传递所有的港口给前台页面
        $sql3="select *  from  hl_port ";
        $port_data =Db::query($sql3);
         //转成json格式传给js
        $js_port=json_encode($port_data);
        
        $this->view->assign('js_port', $js_port);
        
        return $this->view->fetch('Ship/ship_add'); 
       
   }
   
   public function to_add() {
        $data = $this->request->param();
     //   $this->_p($data);exit;
        $port_arr =$data['port_code'];
        $ship_short_name = $data['ship_short_name'];
        $ship_name = $data['ship_name'];
        $ship= new ShipM;
        $res =$ship->to_add($port_arr,$ship_name ,$ship_short_name); 
        if(!array_key_exists('fail', $res)){
                  $status =1; 
              }else {
                  $status =0;  
                    }
        json_encode($status);   
        
         return $status;   
       
   }
   
   
   
   //船公司编辑展示页面
    public function ship_edit() {
        $ship_id= $this->request->get('ship_id');
        $port_id= $this->request->get('port_id');
        $ship= new ShipM;
        $res =$ship->ship_edit($ship_id,$port_id);
//        $this->_p($res); exit; 
        $ship_arr = $res[1];
        $port_arr = $res[0];
        $port_arr = json_encode($port_arr);
        $this->assign('ship', $ship_arr);
        $this->assign('port', $port_arr);
        return $this->view->fetch('Ship/ship_edit'); 
    }
    
     //执行船公司编辑
    public function to_edit() {
        $data = $this->request->param();
        // $this->_p($data); exit; 
        $port_code = $data['port_code'];
        $ship_id = $data['ship_id'];
        $ship_short_name =$data['ship_short_name'];
        $ship_name = $data['ship_name'];
        $ship= new ShipM;
        $res =$ship->to_edit($ship_id ,$ship_short_name ,$ship_name ,$port_code); 
        if(!array_key_exists('fail', $res)){
                  $status =1; 
              }else {
                  $status =0;  
                    }
        json_encode($status);   
        
        return $status;   
        }
   
}