<?php
namespace app\admin\model;
use think\Model;
use think\Db;
//通讯录模块
class CarShipMan extends Model
{
   
   
    //展示船队人员资料的信息
    public function ship_list($searchdata = array(),$page = 5) { 
         $list = Db::name('shipman')->alias('SM')
                 ->join('hl_port P','P.port_code = SM.port_id ')
                 ->join('hl_shipcompany SC','SC.id = SM.ship_id')
                 ->field('SM.id ,P.port_name ,SC.ship_short_name,SM.* ');
      
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if( array_key_exists('ship_name', $searchdata)){
            $ship_name = $searchdata['ship_name'];
            $pageParam['query']['ship_name'] = $ship_name;
            $list = $list ->where('ship_short_name','like',"%{$ship_name}%");
        } 
        
        if( array_key_exists('port_name', $searchdata)){
            $port_name = $searchdata['port_name'];
            $pageParam['query']['port_name'] = $port_name; 
            $list =  $list ->where('port_name','like',"%{$port_name}%");
        } 
           
         $list= $list->paginate($page, false, $pageParam);
        
         return $list;

}

    //执行删除船队人员资料的信息
   public function ship_del($id ='') { 
       
        $sql = "delete  from hl_shipman where id in ($id) ";
       // var_dump($sql);exit;
        $res = Db::execute($sql);
        if($res){
            $response['success'][] = '删除shipman表';
        }else {
            $response['fail'][] = '删除shipman表';    
        }
        return $response;
   }
   
   
       //展示车队人员资料的信息
    public function car_list($searchdata = array(),$page = 5) { 
         $list = Db::name('carinfo')->alias('CI')
                 ->join('hl_cardata CD','CD.id = CI.car_data_id','left' )
                 ->field('CI.*,CD.car_name')
                 ->order('id,position_level');
      
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if( array_key_exists('car_name', $searchdata)){
            $car_name = $searchdata['car_name'];
            $pageParam['query']['car_name'] = $car_name;
            $list = $list ->where('car_name','like',"%{$car_name}%");
        } 
           
         $list= $list->paginate($page, false, $pageParam);
        
         return $list;

    }

    //执行删除车队人员资料的信息
   public function car_del($id ='') { 
       
        $sql = "delete  from hl_carinfo where id in ($id) ";
       // var_dump($sql);exit;
        $res = Db::execute($sql);
        if($res){
            $response['success'][] = '删除carinfo表';
        }else {
            $response['fail'][] = '删除carinfo表';    
        }
        return $response;
   }


}