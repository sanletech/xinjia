<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Ship extends Model
{
 
    public function shiplist($ship_name,$pages=5){
        
         $list = Db::name('shipcompany')
                ->field('id,ship_short_name,ship_name,mtime')
                ->buildSql();
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($ship_name){
            $list = Db::table($list.' b')->where('b.ship_short_name', 'like', "%{$ship_name}%")->buildSql();
            $pageParam['query']['ship_name'] = $ship_name;
        }
        $list =Db::table($list.' C')->order('C.mtime DESC')->paginate($pages,false,$pageParam);   
        
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
    
    

    
        //展示原有的信息
    public function ship_edit($ship_id){ 

        $res = Db::name('shipcompany')->where('id',$ship_id)->find();
        return $res;
    }
    

  
    
}



?>