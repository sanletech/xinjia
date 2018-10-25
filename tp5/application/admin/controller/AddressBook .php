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
        $data= array_filter($this->request->param());
        $car = new CarM;
        //接受搜索提供的搜索条件
        $data= $this->request->param();
        $search =  array_filter($data);//过滤下空的参数;
        //var_dump($search);
        //给页面展示保留搜索内容
        $bz=0; //设置一个判断标记
        if(array_key_exists('port', $data)){
        $bz=1;
        $this->view->assign('searchport',$data['port']); 
        }
        if(array_key_exists('city', $data)){
        $bz=1;    
        $this->view->assign('searchcity',$data['city']); 
        }
        //展示分页内容
        //  传参数展示分页carlist第一个参数是搜索条件 第二个参数是分页数量
        if($bz){
             $carlist= $car->carlist($search);
        }else {
             $carlist= $car->carlist();
        }
        //每页数量
        $count = count($carlist);
        // 获取分 页显示
        $page = $carlist->render();
       // $this->_p($carlist); 
        $this->view->assign('count',$count);
        $this->view->assign('carlist',$carlist);
        $this->assign('page', $page);
        return $this->view->fetch('car/car_list'); 
    }
    
    //展示修改车队信息
    public function car_edit(){
        
          //获取需要修改的港口车队car_port表的id
        $id= $this->request->get('id'); 
       
         //根据修改的港口_车队car_port ID获取对应的 港口 船公司信息
         
        $sql="select CP.id , CP.port_id, P.port_name, CP.car_id ,CD.car_name, CD.address, CD.symbiosis, CD.status  ,"
               . 'group_concat(distinct CC.city_id order by CC.id) city_code ,'
               . 'group_concat(distinct CC.city_name order by CC.id) city_name , '
               . 'group_concat(distinct CS.ship_id order by CS.ship_id) ship_id  , '
               . 'group_concat(distinct SC.ship_short_name order by CS.ship_id )ship_short_name   ' 
               ."  from hl_car_port CP  "
               ."  left join  hl_car_city CC on CC.car_id = CP.car_id   "
               ."  left join  hl_car_ship CS on CS.car_id = CP.car_id   "
               ."  left join  hl_shipcompany SC on SC.id =  CS.ship_id and SC.status='1'  "     //查询船公司名字
               ."  left join  hl_port P on P.id = CP.port_id            "    //查询对应的港口名字
               ."  left join  hl_cardata CD on CD.id = CP.car_id        "
               ."  where CP.id = '$id'  group by CP.id  "  ;
        $data =Db::query($sql);
  
        $data=$data['0'];   //将二维数组转换成一维
        //将字符串转成数组
        $data['city_code']= explode(',', $data['city_code']);
        $data['city_name']= explode(',', $data['city_name']);
        $data['ship_id']= explode(',', $data['ship_id']);
        $data['ship_short_name']= explode(',', $data['ship_short_name']);
        //$this->_p($data);
        
        //根据车队$car_id  获取对应hl_cardata信息
        $car_id = $data['car_id'];
        $sql2="select *  from hl_cardata where id='$car_id'";
        $car_data =Db::query($sql2);
         
        //传递所有的港口给前台页面
        $sql3="select *  from  hl_port ";
        $port_data =Db::query($sql3);
       //转成json格式传给js
        $js_port=json_encode($port_data);
        
        //传递所有的船公司给前台页面选择
        $sql4="select id , ship_short_name  from  hl_shipcompany  ";
        $ship_data =Db::query($sql4);
       //转成json格式传给js
        $js_ship=json_encode($ship_data);       
        
        $this->view->assign('data',$data);
        $this->view->assign('car_data',$car_data);
        $this->view->assign('js_port',$js_port);
        $this->view->assign('js_ship',$js_ship);
         
        return $this->view->fetch('car/car_edit'); 
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