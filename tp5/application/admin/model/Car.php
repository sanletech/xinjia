<?php
namespace app\admin\model;
use think\Model;
use think\Db;
//通讯录模块
class Car extends Model
{
   
   
    //展示车队的对应信息
    public function carlist($searchdata = array(),$page = 5) { 
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        // var_dump($searchdata);
        //接受search条件，并做判断处理三种情况或 和且的条件处理
        if( array_key_exists('port', $searchdata)){
            $port_name ='  P.port_name like \'%'.$searchdata['port'].'%\'';
            $sql = "select C.id from hl_car_port C left join hl_port P on C.port_id = P.id where".$port_name ;
            $cp_id=Db::query($sql);
            //取以car_data_id 为键名的二维数组转成一维 
            $cp_id=array_column($cp_id, 'id');
            $bz=1; //做标记
            // 设置分页的额外参数 可以看http://www.thinkphp.cn/topic/44624.html 解释
            $pageParam['query']['port'] = $searchdata['port'];
         // var_dump($carid);
        } else{ $bz = -1;}
            
        if(array_key_exists('city', $searchdata)){
            $city_name ='  CC.city_name like \'%'.$searchdata['city'].'%\'';
            $sql2 = "select C.id from hl_car_port C left join hl_car_city CC on CC.car_id = C.car_id  where".$city_name ;
            $cp_id2=Db::query($sql2);
            $cp_id2=array_column($cp_id2, 'id');
            $bz2=1;
            $pageParam['query']['city'] = $searchdata['city'];
          //var_dump($carid2);
        } else{ $bz2 = -1;}
        if($bz>0 && $bz2>0 ){
          $id=  array_intersect($cp_id,$cp_id2);
        }elseif($bz>0 && $bz2<0){
          $id= $cp_id;
        }elseif($bz<0 && $bz2>0) {
          $id= $cp_id2; 
        }
       
        $sqlstr=Db::name('car_port')->alias('CP')
                ->join('hl_car_city CC' , 'CC.car_id = CP.car_id' , 'left')
                ->join('hl_car_ship CS' , 'CS.car_id = CP.car_id' , 'left')
                ->join('hl_shipcompany SC' , 'SC.id =  CS.ship_id' , 'left')
                ->join('hl_port P' , 'P.id = CP.port_id' , 'left')
                ->join('hl_cardata CD' , 'CD.id = CP.car_id','left')
                ->field('CP.id , CP.port_id, P.port_name,CP.car_id ,  CD.car_name, CD.address, CD.symbiosis, CD.status  ,'
                        . 'group_concat(distinct CC.id order by CC.id) city_id ,'
                        . 'group_concat(distinct CC.city_name order by CC.id) city_name , '
                        . 'group_concat(distinct CS.ship_id order by CS.ship_id) ship_id  , '
                        . 'group_concat(distinct SC.ship_short_name order by CS.ship_id )ship_short_name' );
                
        if(isset($id)){
            $pageParam['query']['id'] = $id;
            $list = $sqlstr->where('CP.id','in',$id)->order('CP.id')->group('CP.id') ->paginate($page,false,$pageParam);
        }else{
            $list=$sqlstr->order('CP.id')->group('CP.id') ->paginate($page);
        }
          
//          $list=Db::name('car_port')->alias('CP')
//                  ->join('hl_car_city CC' , 'CC.car_id = CP.car_id' , 'left')
//                  ->join('hl_car_ship CS' , 'CS.car_id = CP.car_id' , 'left')
//                  ->join('hl_shipcompany SC' , 'SC.id =  CS.ship_id' , 'left')
//                  ->join('hl_port P' , 'P.id = CP.port_id' , 'left')
//                  ->join('hl_cardata CD' , 'CD.id = CP.car_id','left')
//                  ->field('CP.id , CP.port_id, P.port_name,CP.car_id ,  CD.car_name, CD.address, CD.symbiosis, CD.status  ,'
//                          . 'group_concat(distinct CC.id order by CC.id) city_id ,'
//                          . 'group_concat(distinct CC.city_name order by CC.id) city_name , '
//                          . 'group_concat(distinct CS.ship_id order by CS.ship_id) ship_id  , '
//                          . 'group_concat(distinct SC.ship_short_name order by CS.ship_id )ship_short_name' )
//                  ->order('CP.id')->group('CP.id')
//                  ->paginate($page);
          
          return $list;
       
    }
    
    //执行删除
    public function todel($data) {
        $data = implode(',', $data);
        //获取相应的车队id
        $car_id =  Db::name('car_port')->where('id','in',$data)->select('car_id');
        $car_id_arr = implode(',', $car_id);
        //删除car_port的id
        $res = Db::name('car_port')->where('id','in',$data)->delete();
        if($res>0){
            $response['success'] = '删除car_port表';  
             //根据car_id 删除相应的car_ship car_city 的数据
            $res1 = Db::name('car_ship')->where('id','in',$car_id_arr)->delete();
            if($res1){$response['success'] = '删除car_ship表';}else{$response['fail'] = '删除car_ship表'; }
            $res2 = Db::name('car_city')->where('id','in',$car_id_arr)->delete(); 
            if($res1){$response['success'] = '删除car_city表';}else{$response['fail'] = '删除car_city表'; }
        }  else {
            $response['fail'][]='删除car_port表';   
        }
        
        return $response;
    }
    
    //执行修改
    public function toedit($car_data,$ship_data,$port_data,$city_data){
        $response = array();
        //修改car_data表
        $car_data['id']=$car_data['car_id'];
        unset($car_data['car_id']);
        $car_data['mtime']=  time();
        $res1=Db::name('cardata')->update($car_data);
        if($res1>0){$response['success'][]='修改car_data表';}else{$response['fail'][]='修改car_data表';}
        
        //修改car_port表 根据主键$port_data['cp_id']修改
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
  
    //执行增加
    public function toAdd($car_data,$ship_data,$port_data,$city_data) {
        $response = array();
        //添加car_data表
        unset($car_data['car_id']);
        $car_data['mtime']=  time();
        $res1=Db::name('cardata')->insert($car_data);
        $car_data['id'] = Db::name('cardata')->getLastInsID();
        if($res1>0){
            $response['success'][]='添加car_data表';
            
                     //添加car_port表
                    $port_data = array_values($port_data);
                    $res2 = Db::name('car_port')->insert(['port_id'=>$port_data[0],'car_id'=>$car_data['id']]);
                    if($res2>0){$response['success'][]='添加car_port表';}else{$response['fail'][]='添加car_port表';}

                    //添加car_ship表
                    $ship_id=array_values($ship_data);
                    for($i=0;$i<count($ship_id);$i++){
                        $ShipAddList[$i]=array('ship_id'=>$ship_id[$i],'car_id'=>$car_data['id']);
                    }
                    $res3 = Db::name('car_ship')->insertAll($ShipAddList);
                    if($res3>0){
                        $response['success'][]='添加car_ship表'; 
                    }  else { $response['fail'][]='添加car_ship表';}  

                    //添加car_city表
                    $city_id = array_values($city_data);
                    $city_name = array_keys($city_data);
                    for($i=0;$i<count($city_data);$i++){
                        $CityAddList[$i]=array('city_id'=>$city_id[$i],'city_name'=>$city_name[$i],'car_id'=>$car_data['id']);
                    }
                    $res5 = Db::name('car_city')->insertAll($CityAddList);
                    if($res5>0){
                        $response['success'][]='添加car_city表'; 
                    } else { $response['fail'][]='添加car_city表';}  

            
        }else{$response['fail'][]='添加car_data表';}
        
 
	
        return $response ;  
    }

    //对车队的人员信息展示
    public function carinfo ($id) {
        $sql = "select * from hl_carinfo where car_data_id = '$id' " ;
        $res = Db::query($sql);
        return $res;
    }
}

?>