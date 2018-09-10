<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Ship extends Model
{
 
    public function shiplist($ship_name,$port_name,$pages=5){
        $list = Db::name('ship_port')->alias('SPC')
                ->join('hl_port P','P.port_code=SPC.port_id','left')
                ->join('hl_shipcompany SC','SC.id=SPC.ship_id','left')
                ->field('SPC.ship_id,SC.ship_short_name ship_name,SPC.port_id,P.port_name')
                ->order('SPC.ship_id,SPC.seq')
                ->buildSql();
        
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($port_name){
            $list = Db::table($list.' a')->where('a.port_name', 'like', "%{$port_name}%")->buildSql();
            $pageParam['query']['port_name'] = $port_name;
        }
        if($ship_name){
            $list = Db::table($list.' b')->where('b.ship_name', 'like', "%{$ship_name}%")->buildSql();
            $pageParam['query']['ship_name'] = $ship_name;
        }
        $list =Db::table($list.' c')->order('c.ship_id' )->paginate($pages,false,$pageParam);   
        
        return $list;
    } 
    
    //船公司对应港口的人员资料
    public function ship_info($ship_id ,$port_id){
        $sql="select S.name, S.position, S.duty_line, S.sn_tel, S.sn_mobile, S.sn_qq, S.sn_fax,   "
                . "P.port_name, SC.ship_short_name "
                . " from hl_shipman S"
                . " left join hl_port P on P.port_code = S.port_id "
                . " left join hl_shipcompany SC on SC.id = S.ship_id  "
                . " where S.ship_id = '$ship_id' and S.port_id = '$port_id'"
                . " order by S.position_level";
        // var_dump($sql);exit;
        $res = Db::query($sql);
        return $res;
        
    }
    
    
    public function to_del($arr) {
        foreach ($arr as $k=>$v){
           $ship_id =$k;
           $port_id = implode(',', $v);
           $sql = "delete from hl_ship_port where ship_id ='$ship_id' and port_id in ($port_id ) ";
           $sql2 = "select id from hl_ship_port where ship_id ='$ship_id'" ;
           $res = Db::execute($sql);
           $res ? $response['success'][] = '删除ship_port表船id'. $ship_id :$response['success'][] = '删除ship_port表船id'. $ship_id ;
           $res2 =Db::query($sql2);
           if(!$res2){
                $sql3 = "delete from hl_shipcompany where id ='$ship_id'";
                $res3 = Db::execute($sql3);
                $res ? $response['success'][] = '删除shipcompany表船id'. $ship_id :$response['success'][] = '删除shipcompany表船id'. $ship_id ;
            }
        }
        return $response;
    }  
    
    public function to_add($ship_name ,$ship_short_name) {
        
        $sql = "insert into hl_shipcompany(ship_name, ship_short_name) "
                . "values('$ship_name','$ship_short_name') ";
        $res = Db::execute($sql);
//        $ship_id = Db::name('shipcompany')->getLastInsID();
//        $str ='';
//        foreach ($port_arr as $k=>$v){
//            $k = $k + 1;
//            $str .= "($v,$k,$ship_id) , ";
//        }
//        $str = rtrim($str ,', ');
//        $sql2 = "insert into hl_ship_port(port_id,seq,ship_id)  values".$str;
//        $res1 =Db::execute($sql2); 
        
        $res ? $response['success'][] = '添加shipcompany表': $response['fail'][] = '添加shipcompany表';
        //$res1 ? $response['success'][] = '添加ship_port表': $response['fail'][] = '添加ship_port表';
        return $response;
    }
    
        //展示原有的信息
    public function ship_edit($ship_id ,$port_id){ 
        $sql = "select * from hl_shipcompany where id = '$ship_id' ";
        $sql2 = "select SP.port_id ,P.port_name from hl_ship_port SP left join hl_port P on P.port_code = SP.port_id "
                . " where SP.ship_id = '$ship_id'";
        $res =array();
        $res1 = Db::query($sql);
        $res[] = Db::query($sql2);
        $res[] = $res1[0];
        return $res;
    }
    
    public function to_edit($ship_id ,$ship_short_name ,$ship_name ,$port_code){ 
          //先删除hl_ship_port原有的数据 ,再重新插入  
        $sql = "delete from hl_ship_port where ship_id ='$ship_id'";
        $str ='';
        foreach ($port_code as $k=>$v){
            $str .= "($v,$k,$ship_id) , ";
        }
        $str = rtrim($str ,', ');
        $sql2 = "insert into hl_ship_port(port_id,seq,ship_id)  values".$str;
        $mtime = date('y-m-d h:i:s'); 
        $sql3 = "update hl_shipcompany set ship_short_name ='$ship_short_name' ,"
                . "  ship_name ='$ship_name',mtime ='$mtime ' where id = '$ship_id' ";    
        $res1 =Db::execute($sql); 
        $res1 ? $response['success'][] = '删除ship_port表': $response['fail'][] = '删除ship_port_city表';
        if($res1){
            $res2 =Db::execute($sql2); 
            $res2 ? $response['success'][] = '修改ship_port表': $response['fail'][] = '修改ship_port_city表';
            $res3 =Db::execute($sql3); 
            $res3 ? $response['success'][] = '修改shipcompany表': $response['fail'][] = '修改shipcompany表';
        }
        return $response;
        
    }
  
    
}



?>