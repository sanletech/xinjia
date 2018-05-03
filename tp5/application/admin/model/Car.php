<?php
namespace app\admin\model;
use think\Model;
use think\Db;
//通讯录模块
class Car extends Model
{
   
   
    //展示车队的对应信息
    public function carlist($page = 5) { 
        
           $sql="select CP.id, CP.port_id, P.port_name ,CP.car_id,group_concat(distinct  CC.id) city_id,"
                   . "group_concat(distinct CC.city_name) city_name, "
                   . "group_concat(distinct CS.ship_id) ship_id ,"
                   . "group_concat(distinct SC.ship_short_name)ship_short_name from hl_car_port CP "
                . "left join  hl_car_city CC on CC.car_id = CP.car_id "
                . "left join  hl_car_ship CS on CS.car_id = CP.car_id "
                . "left join  hl_shipcompany SC on SC.id =  CS.ship_id "     //查询船公司名字
                . "left join  hl_port P on P.id = CP.port_id "          //查询对应的港口名字
                . "left join  hl_cardata CD on CD.id = CP.car_id "
                . "where CP .id ='1'"  ;
       // var_dump($sql);exit;
          $list=Db::name('car_port')->alias('CP')
                  ->join('hl_car_city CC' , 'CC.car_id = CP.car_id' , 'left')
                  ->join('hl_car_ship CS' , 'CS.car_id = CP.car_id' , 'left')
                  ->join('hl_shipcompany SC' , 'SC.id =  CS.ship_id' , 'left')
                  ->join('hl_port P' , 'P.id = CP.port_id' , 'left')
                  ->join('hl_cardata CD' , 'CD.id = CP.car_id','left')
                  ->field('CP.id cs_id, CP.port_id, P.port_name, CD.car_name, CD.address, CD.symbiosis, CD.status  ,'
                          . 'CP.car_id,group_concat(distinct CC.id order by CC.id) city_id ,'
                          . 'group_concat(distinct CC.city_name order by CC.id) city_name , '
                          . 'group_concat(distinct CS.ship_id order by CS.ship_id) ship_id  , '
                          . 'group_concat(distinct SC.ship_short_name order by CS.ship_id )ship_short_name' )
                  ->order('CP.id')->group('CP.id')
                  ->paginate($page);
          
          return $list;
//          var_dump($list);    
//           
//          $aalist =Db::name('shipcompany')->alias('S')
//                ->join('hl_ship_port SP','S.id = SP.ship_id','left')
//                ->join('hl_city C','S.ship_city_code = C.city_id','left')
//                ->join('hl_port P','SP.port_id = P.id','left')
//                ->field('S.id , S.ship_short_name , S.ship_address ,C.city,GROUP_CONCAT(P.port_name order by P.id ) port_name')
//                ->group('S.ship_short_name')
//                ->order('S.id ')
//                ->paginate($pages);
//        
     
       
    }
    //执行删除
    public function todel($data) {
        $data = implode(',', $data);
        $sql="delete A,B from hl_cardata A left join hl_car_ship_port B  on A.id = B.car_data_id  where A.id in ($data) ";
        $res = Db::execute($sql);
        if($res >0){
            $status=1;
        }else{$status=0;}
        return $status;
    }
    
    //根据控制器传过来的值使用获取器 将港口id改成汉字数组传回去
    public function getPortIdAttr($value)
    {     
        $sql="select port_name,id from hl_port where id in ($value) order by id  ";
        $port_name=Db::query($sql);
        $arr_port= array_column($port_name, 'port_name','id');
        
        return $arr_port;
    }
    
   //根据控制器传过来的值使用获取器 将船公司id改成汉字数组传回去
    public function getShipcyIdAttr($value)
    {     
        $sql2="select name,id from hl_shipcompany where id in ($value) ";
        $shipcy_name=Db::query($sql2);
        $arr_shipcy= array_column($shipcy_name, 'name','id');
       
        return $arr_shipcy;
    }

//   public function getStatusAttr($value)
//    {
//    $status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
//     return $status[$value];
//
//    }

//     public function getSymbiosisAttr($value)
//    {
//    $status = [1=>'长期合作',2=>'临时合作',3=>'暂无合作',4=>'中止合作'];
//     return $status[$value];
//
//    }
    
    /*
     * 对车队的人员信息展示
     */
    public function carinfo ($id) {
        $sql = "select * from hl_carinfo where car_data_id = '$id' " ;
        $res = Db::query($sql);
        return $res;
    }
    
    
    
   /*
    * 对港口的关联查询
    */
      //belongsToMany(‘关联模型名’,’中间表名’,’外键名’,’当前模型关联键名’,[‘模型别名定义’]);
      public function port ()
    {
      return $this->belongsToMany('Port', 'car_ship_port','port_id','car_data_id');
    }
    
    /*
    * 对港口的关联查询
    */
      //belongsToMany(‘关联模型名’,’中间表名’,’外键名’,’当前模型关联键名’,[‘模型别名定义’]);
      public function ship ()
    {
      return $this->belongsToMany('Ship', 'car_ship_port','ship_id','car_data_id');
    }
    
    
  

}



?>