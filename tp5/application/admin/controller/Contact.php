<?php
/*
 *  车队通讯录控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Contact as ContactM ;
use think\Request;
use think\Db;
class Contact extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    
    public function car_list() 
    {               
        $car= new ContactM;
         //接受搜索提供的搜索条件
        $data= $this->request->param();
        $search =  array_filter($data);//过滤下空的参数;
        //var_dump($search);
        //给页面展示保留搜索内容
        if(array_key_exists('port', $data)){
        $this->view->assign('searchport',$data['port']); 
        }
        if(array_key_exists('car_name', $data)){
        $this->view->assign('searchcar',$data['car_name']); 
        }
       
        //展示分页内容
      //  如果有搜索条件就给按照参数来展示分页
     if(count($search)>0){
            $carlist= $car->carlist($search);
        }else {
             $carlist= $car->carlist();
        }
        
        $count = count($carlist);
        foreach ($carlist as $key=> $value) {
        //取出分页展示车队的Id
        $id = $value['id'];
       
        $sql="select GROUP_CONCAT(CC.city_name) as city_name  , c.car_data_id, GROUP_CONCAT(s.ship_short_name) as ship_name,group_concat(p.port_name)as port_name from "
                . "hl_car_ship_port c left join  hl_shipcompany s on s.id=c.ship_id "
                . "left join hl_port p on p.id=c.port_id left join hl_car_city CC on c.car_data_id = CC.car_data_id "
                . " where c.car_data_id='$id'  ";
        
        $ship_port[$id] =Db::query($sql);
        $ship_port[$id]=$ship_port[$id][0];
           
        }
        //分页
        $page=$carlist->render();
        $this->view->assign('page',$page);
    
      
       // $this->_p($ship_port);exit;
        //将车队信息赋值给模板
        $this->view->assign('ship_port',$ship_port);
        $this->view->assign('count',$count);
        $this->view->assign('carlist',$carlist);
    
      return $this->view->fetch('contact/car_list'); 
    }

    //修改车队信息
    public function  car_edit(){
        
          //获取需要修改的车队id
        $id= $this->request->get('id'); 
       
         //根据修改的车队ID获取对应的 港口 船公司信息
         $sql="select c.id as car_id , c.car_name , p.id as port_id , p.port_name , p.city_id ,s.id as ship_id , s.ship_short_name "
                 . "from hl_cardata c cross join hl_car_ship_port csp on c.id =csp.car_data_id "
                 . "left join hl_port p on csp.port_id=p.id left join hl_shipcompany s  on csp.ship_id=s.id  where(c.id='$id') order by port_id ";
       
       $data =Db::query($sql);
       
          //根据车队id 获取对应hl_cardata信息
        $sql2="select *  from hl_cardata where id='$id'";
        $car_data =Db::query($sql2);
         
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
        
        $this->view->assign('data',$data);
        $this->view->assign('car_data',$car_data);
        $this->view->assign('js_port',$js_port);
        $this->view->assign('js_ship',$js_ship);
         
        return $this->view->fetch('contact/car_edit'); 
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