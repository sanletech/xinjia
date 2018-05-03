<?php
/*
 *  车队通讯录控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Car as CarM ;
use think\Request;
use think\Db;
class Car extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    
    public function car_list() 
    {               
        $car= new CarM;
         //接受搜索提供的搜索条件
        $list= $car->carlist();
        //每页数量
        $count = count($list);
        // 获取分页显示
        $page = $list->render();
      
        $this->_p($list); 
        
        $this->view->assign('count',$count);
        $this->view->assign('carlist',$list);
        return $this->view->fetch('Car/car_list'); 
    }
    
    //执行车队的修改
     public function  toEdit(){
        $data= array_filter($this->request->param());
 
        //修改car_data信息表
        $car_data=$data['0'];
         //修改对应id的car_ship_port表
        $port_data=$data['port'];
        $ship_data=$data['ship'];
         
        $editM=new ContactM;
        $status =$editM->toedit($car_data,$port_data,$ship_data);
        json_encode($status);   
        return $status;  
     }
     
    //展示添加车队页面
    public function car_add() {
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
        return $this->view->fetch('contact/car_add');
    }
    
    //执行车队的添加
    public function toAdd() {
         $data= array_filter($this->request->param());
          //需要添加的car_data信息表
        $car_data=$data['0'];
         //需要添加的car_ship_port表
        $port_data=$data['port'];
        $ship_data=$data['ship'];
         
        $editM=new ContactM;
        $status =$editM->toadd($car_data,$port_data,$ship_data);
        json_encode($status);
        return $status;  
    }
    
    // 查看车队各个人员的信息
    public function car_info() {
        //获取需要查看车队的ID   
        $id= $this->request->get('id');
        $carinfo = new ContactM;
        $data = $carinfo->carinfo($id);
        
        $this->view->assign('data',$data);
        return $this->view->fetch('contact/car_info'); 
    }
    
    //执行车队信息的删除
    public function toDel(){
        $data= array_filter($this->request->param());
        $del=new ContactM;
        $status = $del->todel($data['id']);
        json_encode($status);
        return $status;
    }


    public function search() {
       
        $data= array_filter($this->request->param());
        $search =new ContactM;
        $search->carlist($data);
    }
    
        // 废弃
    public function car_list_bak() 
    {
        $car= new ContactM;
        $carlist= $car->carlist();
        $count = count($carlist); 
         
        foreach ($carlist as $key=> $value) {
            //根据分页 循环 调用port_id和shipcy_id获取器处理成汉字数组 
            $port_array[] =  $value->port_id;
            $shipcy_array[] =  $value->shipcy_id; 
        
        }
        
//        $this->_p($port_array);
//        $this->_p($shipcy_array);
        //将车队信息赋值给模板
        $this->view->assign('port_array',$port_array);
        $this->view->assign('shipcy_array',$shipcy_array);
        $this->view->assign('count',$count);
        $this->view->assign('carlist',$carlist);
    
      return $this->view->fetch('contact/car_list'); 
    }
    
} 