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
    public function carList()
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
        $carlist= $car->carlist($map,$this->page);
        $page = $carlist->render();  // 获取分 页显示
       // $this->_p($carlist); 
        $this->view->assign('carlist',$carlist);
        $this->assign('page', $page);
        return $this->view->fetch('AddressBook/car/carList'); 
    }
    
    //执行车队的添加
    public function carToAdd() {
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
    

    //车队人员资料list
    public function carAddressBook(){
        $car_man = new AddressBookM;
        $list= $car_man->carAddressBook($type='car',$this->page);
        $page = $list->render();  // 获取分 页显示
       // $this->_p($carlist); 
        $this->view->assign('list',$list);
        $this->assign('page', $page);
        return $this->view->fetch('AddressBook/carman/carman_list'); 
    }
    //车队人员资料添加
    public function carman_add(){
        return $this->view->fetch('AddressBook/carman/carman_add'); 
    }
    
    //车队人员资料删除
    public function carman_del(){
        $id = $this->request->param('id');
        $res =Db::name('staff_list')->where('id','in',$id)->delete();
        return $res ? array('status'=>1,'message'=>'删除成功') :array('status'=>0,'message'=>'删除失败');
    }

    
} 