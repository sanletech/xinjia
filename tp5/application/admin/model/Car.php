<?php
namespace app\admin\model;
use think\Model;
use think\Db;
//通讯录模块
class Car extends Model
{
   
   
    //展示车队的对应信息
    public function carlist($page = 5) { 
        
           $sql="select CP.id cs_id, CP.port_id, P.port_name, CP.car_id ,CD.car_name, CD.address, CD.symbiosis, CD.status  ,"
                . 'group_concat(distinct CC.id order by CC.id) city_id ,'
                . 'group_concat(distinct CC.city_name order by CC.id) city_name , '
                . 'group_concat(distinct CS.ship_id order by CS.ship_id) ship_id  , '
                . 'group_concat(distinct SC.ship_short_name order by CS.ship_id )ship_short_name' 
                . "from hl_car_port CP "
                . "left join  hl_car_city CC on CC.car_id = CP.car_id "
                . "left join  hl_car_ship CS on CS.car_id = CP.car_id "
                . "left join  hl_shipcompany SC on SC.id =  CS.ship_id "     //查询船公司名字
                . "left join  hl_port P on P.id = CP.port_id "          //查询对应的港口名字
                . "left join  hl_cardata CD on CD.id = CP.car_id "
                . "order by CP.id  group by CP.id "  ;
       // var_dump($sql);exit;
          $list=Db::name('car_port')->alias('CP')
                  ->join('hl_car_city CC' , 'CC.car_id = CP.car_id' , 'left')
                  ->join('hl_car_ship CS' , 'CS.car_id = CP.car_id' , 'left')
                  ->join('hl_shipcompany SC' , 'SC.id =  CS.ship_id' , 'left')
                  ->join('hl_port P' , 'P.id = CP.port_id' , 'left')
                  ->join('hl_cardata CD' , 'CD.id = CP.car_id','left')
                  ->field('CP.id , CP.port_id, P.port_name,CP.car_id ,  CD.car_name, CD.address, CD.symbiosis, CD.status  ,'
                          . 'group_concat(distinct CC.id order by CC.id) city_id ,'
                          . 'group_concat(distinct CC.city_name order by CC.id) city_name , '
                          . 'group_concat(distinct CS.ship_id order by CS.ship_id) ship_id  , '
                          . 'group_concat(distinct SC.ship_short_name order by CS.ship_id )ship_short_name' )
                  ->order('CP.id')->group('CP.id')
                  ->paginate($page);
          
          return $list;
       
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
    
    //执行修改
    public function toedit($car_data,$port_data,$ship_data,$city_data) {
        //修改car_data表
        $car_data['id']=$car_data['car_id'];
        $car_data['mtime']=  time();
        $res1=Db::name('car_data')->update($car_data);
        //car_ship表
        //先获取对应car_id 的主键id
        $cs_id = Db::name('car_ship')->where('car_id',$car_data['car_id'])->select();
        //执行添加修改ship_id的数据 
        function addlist($value,$key,$p){
        }

        echo '再次修改';
        $res2 = Db::name('car_ship');
    }
 

    
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