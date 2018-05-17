<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Ship extends Model
{
 
    protected $table = 'hl_shipcompany';
    
       //$pageParam  = ['query' =>[]] 
    public function shiplist($data=array(),$page=5){
         //解析字符拆成分行展示
        function resultSQL($sub_id) {
            $sub_name = $sub_id; //需要拆分的字段的名字
            $sub_id = 'SPC.'.$sub_id; //hl_ship_port_city 需要拆分的字段的
            $sql = " SELECT SPC.id , SUBSTRING_INDEX(SUBSTRING_INDEX($sub_id, ',', S.seq), ',' ,- 1)$sub_name , S.seq "
               . " FROM hl_sequence S CROSS JOIN hl_ship_port_city SPC  WHERE  S.seq  BETWEEN 1 AND "
               . "(  SELECT 1 + LENGTH($sub_id) - LENGTH(REPLACE($sub_id, ',', ''))  ) order by SPC.ID ,S.seq"; 
             return $sql;
        }
//            $sql="select SPC.id , SPC.city_id ,SPC.city_name , SPC.port_id , group_concat(distinct P.port_name  order by RES.port_id ) port_name "
//               . "from hl_ship_port_city SPC left join ($port_id_SQL) AS RES on RES.id =SPC.id left join hl_port P on P.id = RES.port_id "
//               . "group by SPC.id order by SPC.id";
//        
//         $city_id_SQL   =  resultSQL('city_id');
//         $city_name_SQL =  resultSQL('city_name');
           $port_id_SQL   =  resultSQl('port_id');
        
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if( array_key_exists('city_name', $data)){
            $city_name = $data['city_name'];
            $pageParam['query']['city_name'] = $city_name;
            $sql = "select id from hl_ship_port_city where city_name like '%{$city_name}%' ";
            $city_id = Db::query($sql);
            $city_id = array_column($city_id ,'id');
        } else{ $city_id =array();}
        
        if( array_key_exists('port_name', $data)){
            $port_name = $data['port_name'];
            $pageParam['query']['port_name'] = $port_name; 
            $sql2 = "select SPC.id from hl_ship_port_city SPC left join ($port_id_SQL) AS RES on RES.id =SPC.id  "
                    . "left join hl_port P on P.id = RES.port_id  where P.port_name like '%$port_name%'  ";
            $port_id = Db::query($sql2);
            $port_id = array_column($port_id ,'id');
        } else{ $port_id =array();}
           
        if(empty($city_id) || empty($port_id)){
            $id_arr = array_merge($city_id,$port_id);
        }else{
            $id_arr = array_intersect($city_id,$port_id);  
        }
        
       // var_dump($id_arr);exit;
        if(count($id_arr)>0){
            $pageParam['query']['id']=  $id_arr;
            $result =Db::name('ship_port_city')->alias('SPC')
                ->join('('.$port_id_SQL. ') AS RES','RES.id =SPC.id' ,'left')   
                ->join('hl_port P','P.id = RES.port_id' ,'left')
                ->join('hl_shipcompany S','S.id = SPC.ship_id' ,'left')
                ->field('SPC.id ,SPC.ship_id ,S.ship_short_name ,SPC.city_id ,SPC.city_name ,'
                        . ' SPC.port_id , group_concat(distinct P.port_name  order by RES.seq ) port_name ') 
                ->where('SPC.id','in',$id_arr)->group('SPC.id')->order('SPC.id')
                ->paginate($page,false,$pageParam);
           //var_dump(Db::getLastSql());exit;
        }else{
            $result =Db::name('ship_port_city')->alias('SPC')
                ->join('('.$port_id_SQL. ') AS RES','RES.id =SPC.id' ,'left')   
                ->join('hl_port P','P.id = RES.port_id' ,'left')
                ->join('hl_shipcompany S','S.id = SPC.ship_id' ,'left')
                ->field('SPC.id ,SPC.ship_id ,S.ship_short_name ,SPC.city_id ,SPC.city_name ,'
                        . ' SPC.port_id , group_concat(distinct P.port_name  order by RES.seq ) port_name ') 
                ->group('SPC.id')->order('SPC.id')
                ->paginate($page);
        }
       
        return $result;
    } 
    
    //展示原有的信息
    public function ship_edit($id){ 
       $sql="select SPC.*,S.ship_short_name,S.ship_name from hl_ship_port_city SPC "
               . "  left join hl_shipcompany S on SPC.ship_id = S.id  where SPC.id =$id"; 
       $res= Db::query($sql);
       $city_id = explode(',' , $res['0']['city_id']);
       $city_name = explode(',' , $res['0']['city_name']);
       $city = array_combine( $city_id,$city_name);
       $port_id = $res['0']['port_id'];
       $sql2 = "select id,port_name from hl_port where id in ($port_id) order by id ";
       $res2 = Db::query($sql2);
       $port =  array_column($res2  ,'port_name','id');
       $sql3 = "select ship_name , ship_short_name  from hl_shipcompany where id = {$res['0']['ship_id']}";
       $res3 = Db::query($sql3);
       unset($res['0']['city_id']);
       unset($res['0']['city_name']);
       unset($res['0']['port_id']);
       $res['0']['port']=$port;
       $res['0']['city']=$city;
       return $res;
    }
    
    public function ship_info($id)
{       //var_dump($id);exit;
        $ship_id = $id['ship_id'];
        $port_id = $id['port_id'];
        
        $sql="select S.name, S.position, S.duty_line, S.sn_tel, S.sn_mobile, S.sn_qq, S.sn_fax,   "
                . "P.port_name, SC.ship_short_name "
                . " from hl_shipman S"
                . " left join hl_port P on P.id = S.port_id "
                . " left join hl_shipcompany SC on SC.id = S.ship_id  "
                . " where S.ship_id = '$ship_id' and S.port_id = '$port_id'"
                . " order by S.position_level";
        // var_dump($sql);exit;
        $res = Db::query($sql);
        return $res;
        
    }
    
    public function to_edit($shipinfo,$port_id,$city_id,$city_name)
    { 
        $SPC_ID =$shipinfo['SPC_ID'];
        $ship_id =$shipinfo['ship_ID'];
        $ship_name = $shipinfo['ship_name'];
        $ship_short_name = $shipinfo['ship_short_name']; 
        $sql ="update hl_ship_port_city set city_id ='$city_id',  "
               . "city_name = '$city_name' , port_id = '$port_id' where id = '$SPC_ID' and  "
               . " ship_id = '$ship_id'  ";
       // var_dump($sql);exit;
        $sql2 = "update hl_shipcompany set ship_name = '$ship_name' ,  "
                . "   ship_short_name = '$ship_short_name' where id = '$ship_id'";
        $res = Db::execute($sql);
        $res2 = Db::execute($sql2);
       
        if($res!== false){
           $response['success'][] = '修改ship_port_city表';
        }else{ $response['fail'][] = '修改ship_port_city表';}
        if($res2){
           $response['success'][] = '修改shipcompany表';
        }else{ $response['fail'][] = '修改shipcompany表';}

        return $response;
        
    }
      
    public function to_del($SPC_id) {
        $SPC_id= implode(',', $SPC_id);
        $ship_id = Db::name('ship_port_city')->where('id','in',$SPC_id)->column('ship_id');
        $ship_id = implode(',', $ship_id);
        $sql = "delete from hl_ship_port_city where  id  in ($SPC_id) ";
        $sql2= "delete from hl_shipcompany where id in ($ship_id) ";
        $res =  Db::execute($sql);
        $res2 = Db::execute($sql2); 
        if($res){
           $response['success'][] = '删除ship_port_city表';
        }else{ $response['fail'][] = '删除ship_port_city表';}
        if($res2){
           $response['success'][] = '删除shipcompany表';
        }else{ $response['fail'][] = '删除shipcompany表';}
    }  
    
    public function to_add($shipinfo,$port_id,$city_id,$city_name) {
        $data = array('ship_name'=>$shipinfo['ship_name'],'ship_short_name'=>$shipinfo['ship_short_name']);
        $res = Db::name('shipcompany')->insert($data);
        $ship_id = Db::name('shipcompany')->getLastInsID();
        $data2 = array('city_id'=>$city_id , 'city_name'=>$city_name ,'port_id'=>$port_id, 'ship_id'=>$ship_id);
        $res2 = Db::name('ship_port_city')->insert($data2);
        
        if($res){
           $response['success'][] = '添加ship_port_city表';
        }else{ $response['fail'][] = '添加ship_port_city表';}
        if($res2){
           $response['success'][] = '添加shipcompany表';
        }else{ $response['fail'][] = '添加shipcompany表';}
        
        return $response;
    } 
    
}



?>