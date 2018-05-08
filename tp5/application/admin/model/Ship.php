<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Page;
class Ship extends Model
{
 
    protected $table = 'hl_shipcompany';
    
    public function shiplist($pages=5){
     
        $list =Db::name('ship_port_city')->alias('SPC')
                ->join('hl_ship_port SP','S.id = SP.ship_id','left')
                ->join('hl_city C','S.ship_city_code = C.city_id','left')
                ->join('hl_port P','SP.port_id = P.id','left')
                ->field('S.id , S.ship_short_name , S.ship_address ,C.city,GROUP_CONCAT(P.port_name order by P.id ) port_name')
                ->group('S.ship_short_name')
                ->order('S.id ')
                ->paginate($pages);
    
        return $list;
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