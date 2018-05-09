<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Page;
class Ship extends Model
{
 
    protected $table = 'hl_shipcompany';
    
    public function shiplist($pages=5){
       //解析字符拆成分行展示
        function resultSQL($sub_id) {
            $sub_name = $sub_id; //需要拆分的字段的名字
            $sub_id = 'SPC.'.$sub_id; //hl_ship_port_city 需要拆分的字段的
            $sql = " SELECT SPC.id , SUBSTRING_INDEX(SUBSTRING_INDEX($sub_id, ',', S.seq), ',' ,- 1)$sub_name , S.seq "
               . " FROM hl_sequence S CROSS JOIN hl_ship_port_city SPC  WHERE  S.seq  BETWEEN 1 AND "
               . "(  SELECT 1 + LENGTH($sub_id) - LENGTH(REPLACE($sub_id, ',', ''))  ) order by SPC.ID ,$sub_name desc"; 
//            $result = Db::name('ship_port_city')->query($sql);
//          return $result;
//          // var_dump($sql);exit;   ORDER BY  SPC.id
       return $sql;
        }
//         $city_id   =  resultSQL('city_id');
//         $city_name =  resultSQL('city_name');
         
         $port_id   =  resultSQl('port_id');
         
      
         
        
//        $result =Db::name('ship_port_city')->alias('SPC')
//                    ->join('hl_port P','P.id = SPC.port_id' ,'left') 
//                    ->field('SPC.id , SPC.city_id ,SPC.city_name , SPC.port_id , group_concat(distinct P.port_name  order by SPC.port_id ) port_name ') 
//                    ->order('SPC.id')->group('SPC.id') ->paginate($page ,false,['query'=>request()->param()])
//                    ->each(function($item, $key){
//                            $id = $item["id"]; //获取数据集中的id
//                            $sql2 = $port_id ."where SPC.id =$id ";
//                            $num =  Db::name('ship_port_city')->query($port_id); ; //根据ID查询相关其他信息
//                            $item['num'] = $num; //给数据集追加字段num并赋值
//                            return $item;
//                            });     
    
         
         $sql="select SPC.id , SPC.city_id ,SPC.city_name , SPC.port_id , group_concat(distinct P.port_name  order by RES.port_id ) port_name "
                 . "from hl_ship_port_city SPC left join ($port_id) AS RES on RES.id =SPC.id left join hl_port P on P.id = RES.port_id "
                 . "group by SPC.id order by SPC.id";

            
            $count = Db::name('ship_port_city')->where('id','>','0')->count();// 查询满足要求的总记录数         
            $Page  = new \think\Page($count,5);
            $list = $sql."limit $Page->firstRow,$Page->listRows";
            $result = Db::query($list);
//         $result =Db::name('ship_port_city')->alias('SPC')
//                    ->join('hl_port P','P.id = SPC.port_id' ,'left') 
//                    ->field('SPC.id , SPC.city_id ,SPC.city_name , SPC.port_id , group_concat(distinct P.port_name  order by SPC.port_id ) port_name ') 
//                    ->order('SPC.id')->group('SPC.id') ->paginate($page ,false,['query'=>request()->param()])
//                    ->each(function($item, $key){
//                            $id = $item["id"]; //获取数据集中的id
//                            $sql2 = $port_id ."where SPC.id =$id ";
//                            $num =  Db::name('ship_port_city')->query($port_id); ; //根据ID查询相关其他信息
//                            $item['num'] = $num; //给数据集追加字段num并赋值
//                            return $item;
//                            });                      
                            
                            
                      
             echo'<pre>';
             var_dump($result);
        echo '</pre>';
        exit; 
        
    } 
    
    public function ship_info($id){
      
        $sql="select S.* ,P.port_name "
                . " from  hl_ship_port SP "
                . " left join hl_port P on SP.port_id = P.id "
                . " left join hl_shipman S on S.shipport_id = SP.id  "
                . " where SP.ship_id = '$id'  order by SP.port_id , S.shipport_id";
        //var_dump($sql);exit;
        $res = Db::query($sql);
        return $res;
        
    }
      
      
}



?>