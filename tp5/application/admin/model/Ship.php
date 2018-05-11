<?php
namespace app\admin\model;
use think\Model;
use think\Db;
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
            //   $result = Db::name('ship_port_city')->query($sql);
            //   return $result;
            //   var_dump($sql);exit;   ORDER BY  SPC.id
             return $sql;
        }
//         $city_id   =  resultSQL('city_id');
//         $city_name =  resultSQL('city_name');
           $port_id   =  resultSQl('port_id');
           
            $sql="select SPC.id , SPC.city_id ,SPC.city_name , SPC.port_id , group_concat(distinct P.port_name  order by RES.port_id ) port_name "
           . "from hl_ship_port_city SPC left join ($port_id) AS RES on RES.id =SPC.id left join hl_port P on P.id = RES.port_id "
           . "group by SPC.id order by SPC.id";

         
            $result =Db::name('ship_port_city')->alias('SPC')
                    ->join('('.$port_id. ') AS RES','RES.id =SPC.id' ,'left')   
                    ->join('hl_port P','P.id = RES.port_id' ,'left')
                    ->join('hl_shipcompany S','S.id = SPC.ship_id' ,'left')
                    ->field('SPC.id ,SPC.ship_id ,S.ship_short_name ,SPC.city_id ,SPC.city_name ,'
                            . ' SPC.port_id , group_concat(distinct P.port_name  order by RES.port_id ) port_name ') 
                    ->group('SPC.id')->order('SPC.id') ->paginate($pages);    
         
            return $result;
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