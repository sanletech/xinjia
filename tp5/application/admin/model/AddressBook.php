<?php
namespace app\admin\model;
use think\Model;
use think\Db;
//通讯录模块
class AddressBook extends Model
{
   
   
    //展示车队的对应信息
    public function carlist($map,$page =10) { 
        $list = Db::name('car_data')->alias('CD')
                ->join('hl_port P',"FIND_IN_SET(P.port_code,CD.port_arr)",'left')
                ->join('hl_city C',"FIND_IN_SET(C.city_id,CD.city_arr)",'left')
                ->join('hl_shipcompany SP',"FIND_IN_SET(SP.id,CD.ship_arr)",'left')
                ->field('CD.*,group_concat(distinct P.port_name order by P.id) as port_name, '
                        . 'group_concat(distinct C.city order by C.id) as city_name,'
                        . 'group_concat(distinct SP.ship_short_name order by SP.id) as ship_name')
                ->group('CD.id')->order('CD.id')->buildSql();
        $list = Db::table($list.' A')->where($map)->fetchSql(FALSE)->paginate($page);
//        $this->_p($list);exit;
        return $list;//paginate($page)
       
    }
    
    //展示车队对应人员的资料
    
    //执行删除
    public function todel($data) {
        $data = implode(',', $data);
        //获取相应的车队id
        $car_id =  Db::name('car_port')->where('id','in',$data)->select('car_id');
        $car_id_arr = implode(',', $car_id);
        //删除car_port的id
        $res = Db::name('car_port')->where('id','in',$data)->delete();
        if($res>0){
            $response['success'][] = '删除car_port表';  
             //根据car_id 删除相应的car_ship car_city 的数据
            $res1 = Db::name('car_ship')->where('id','in',$car_id_arr)->delete();
            if($res1){$response['success'][] = '删除car_ship表';}else{$response['fail'][] = '删除car_ship表'; }
            $res2 = Db::name('car_city')->where('id','in',$car_id_arr)->delete(); 
            if($res1){$response['success'][] = '删除car_city表';}else{$response['fail'][] = '删除car_city表'; }
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
        $car_data['mtime']=  date('Y-m-d H:i:s');
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
        $car_data['mtime']=  date('Y-m-d H:i:s');
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

    //车队的人员信息展示
    public function car_address_book ($type='car',$pages='10',$id='') {
        //设置id查询
        $id ? $map =$id : $map = "not null";
        $lists =Db::name('staff_list')->alias('SL')
            ->join('hl_port P','P.port_code=SL.port_code','left')
             ->join('hl_city C','C.city_id=SL.city_code','left')
             ->field('SL.*,P.port_name,C.city')
            ->where('SL.id',$map)->where('SL.type',$type)->buildSql();
        if($type=='car'){
            $list = Db::table($lists)->alias('A')
                ->join('hl_car_data CD','CD.id=A.belong_id','left')
                ->field('A.*,CD.car_name company')->fetchSql(FALSE)->paginate($pages);
        }elseif ($type=='ship'){
            $list = Db::table($lists,' A')->alias('A')
                ->join('hl_shipcompany SC','SC.id=A.belong_id','left')
                ->field('A.*,SC.ship_short_name company')->paginate($pages);   
        }
        
        return $list;
    }
}

?>