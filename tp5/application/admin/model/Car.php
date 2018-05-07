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
        //获取相应的车队id
        $car_id =  Db::name('car_port')->where('id','in',$data)->select('car_id');
        $car_id_arr;
        //删除car_port的id
        $res = Db::name('car_port')->where('id','in',$data)->delete();
        //根据car_id 删除相应的car_ship car_city 的数据
        $res1 = Db::name('car_ship')->where(id)
        if($res >0){
            $status=1;
        }else{$status=0;}
        return $status;
    }
    
    //执行修改
    public function toedit($car_data,$port_data,$ship_data,$city_data) {
        $response = array();
        //修改car_data表
        $car_data['id']=$car_data['car_id'];
        unset($car_data['car_id']);
        $car_data['mtime']=  time();
        $res1=Db::name('cardata')->update($car_data);
        if($res1>0){$response['success'][]='修改car_data表';}else{$response['fail'][]='修改car_data表';}
        
        //修改car_port表
        $port_data = array_values($port_data);
        $res2 = Db::name('car_port')->update(['port_id'=>$port_data[0],'id'=>$port_data[1]]);
        //if($res2>0){$response['success'][]='修改car_port表';}else{$response['fail'][]='修改car_port表';}
         
        //修改car_ship表
        //先获取对应car_id 的主键id
        $cs_id = Db::name('car_ship')->where('car_id',$car_data['id'])->column('id');
        //执行添加修改ship_id的数据 
        $ship_id=array_values($ship_data);
        for($i=0;$i<count($ship_id);$i++){
            $ShipAddList[$i]=array('ship_id'=>$ship_id[$i],'car_id'=>$car_data['id']);
        }
        $res3 = Db::name('car_ship')->insertAll($ShipAddList);
        if($res3>0){
            $response['success'][]='修改car_ship表'; 
            $res4=Db::name('car_ship')->delete($cs_id); 
        }  else { $response['fail'][]='修改car_ship表';}  
     
        //修改car_city表
        //先获取对应car_id 的主键id
        $cc_id=Db::name('car_city')->where('car_id',$car_data['id'])->column('id');
         //执行添加修改city_id的数据 
        $city_id = array_values($city_data);
        $city_name = array_keys($city_data);
        for($i=0;$i<count($city_data);$i++){
            $CityAddList[$i]=array('city_id'=>$city_id[$i],'city_name'=>$city_name[$i],'car_id'=>$car_data['id']);
        }
        $res5 = Db::name('car_city')->insertAll($CityAddList);
        if($res5>0){
            $response['success'][]='修改car_city表'; 
            $res6 =Db::name('car_city')->delete($cc_id);  
        } else { $response['fail'][]='修改car_city表';}  
	
        return $response ;
    }
  

    
    /*
     * 对车队的人员信息展示
     */
    public function carinfo ($id) {
        $sql = "select * from hl_carinfo where car_data_id = '$id' " ;
        $res = Db::query($sql);
        return $res;
    }
    
    


}

?>