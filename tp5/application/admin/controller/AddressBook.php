<?php
/*
 *  通讯录控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\AddressBook as AddressBookM ;
use think\Request;
use think\Db;

class AddressBook  extends Base
{   
    //车队列表
    public function car_list()
    {   
        $map =[]; //设置查询条件
        $port= $this->request->param('port');
        if($port){
            $this->view->assign('searchport',$port); 
            $map['A.port_name']=['like',"%$port%"];
        }  
        $city = $this->request->param('city');
        if($city){
            $this->view->assign('searchcity',$city);
            $map['A.city_name']=['like',"%$city%"];
        }
        
        $car = new AddressBookM;
        $carlist= $car->carlist($map);
        $page = $carlist->render();  // 获取分 页显示
       // $this->_p($carlist); 
        $this->view->assign('carlist',$carlist);
        $this->assign('page', $page);
        return $this->view->fetch('AddressBook/car/car_list'); 
    }
    
    //
    
    //展示修改车队信息
    public function car_edit(){
        //获取需要修改的港口车队car_data表的id
        $id= $this->request->get('id'); 
        //获取原有的信息
        $data = Db::name('car_data')->alias('CD')
                ->join('hl_port P',"FIND_IN_SET(P.port_code,CD.port_arr)",'left')
                ->join('hl_city C',"FIND_IN_SET(C.city_id,CD.city_arr)",'left')
                ->join('hl_shipcompany SP',"FIND_IN_SET(SP.id,CD.ship_arr)",'left')
                ->field('CD.*,group_concat(distinct P.port_name order by P.id) as port_name, '
                        . 'group_concat(distinct C.city order by C.id) as city_name,'
                        . 'group_concat(distinct SP.ship_short_name order by SP.id) as ship_name')
                ->group('CD.id')->where('CD.id',$id)->find();
        $this->assign('data', $data);
        return $this->view->fetch('AddressBook/car/car_edit'); 
    }
    
    //执行车队的修改
    public function  car_toedit(){
        $data= array_filter($this->request->param());
        //修改car_data信息表
        $car_data=$data['0'];
         //修改对应id的car_ship/port/city表
        $port_data=$data['port'];
        $ship_data=$data['ship'];
        $city_data=$data['city']; 
        
        $editM=new CarM;
        $response =$editM->toedit($car_data,$ship_data,$port_data,$city_data);
        if(!array_key_exists('fail', $response)){
            $status =1; 
        }else {
            $status =0;  
              }
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
        return $this->view->fetch('car/car_add');
    }
    
    //执行车队的添加
    public function car_toAdd() {
        $data= array_filter($this->request->param());
        //$this->_p($data);
        //需要添加的car_data信息表
        $car_data=$data['0'];
         //需要添加的car_ship/port/city表
        $port_data=$data['port'];
        $ship_data=$data['ship'];
        $city_data=$data['city'];
        $editM=new CarM;
        $response =$editM->toadd($car_data,$ship_data,$port_data,$city_data);
        if(!array_key_exists('fail', $response)){
            $status =1; 
        }else {
            $status =0;  
              }
        json_encode($status);   
        return $status;  
    }
    
    //执行车队信息的删除
    public function car_Del(){
        $data= array_filter($this->request->param());
        $del=new CarM;
        $response  = $del->todel($data['id']);
         if(!array_key_exists('fail', $response)){
            $status =1; 
        }else {
            $status =0;  
              }
        json_encode($status);
        return $status;
    }


    
} 