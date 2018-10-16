<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
class Personal extends Model
{
    
    public function place_order($member_code,$tol,$limit,$status,$order_num='') {
        if(empty($order_num)){
            $order_num = 'not NUll';
        }else{
            $order_num = trim($order_num);
        }
//        var_dump($order_num);echo'</br>';
        $data =Db::name('order_port')->alias('OP')
                ->join('hl_order_port_status OPS','OP.order_num = OPS.order_num','left')
                ->join('hl_seaprice SP','SP.id=seaprice_id','left')
                ->join('hl_shipcompany SC','SC.id= SP.ship_id','left')
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')
                ->join('hl_boat B','B.id=SP.boat_id','left')
                ->where('OP.member_code',$member_code)
                ->where('OP.order_num',$order_num)
                ->where('OP.status','in',$status)
                ->field('OP.*,OPS.title, OPS.status change_status,OPS.mtime change_mtime,'
                        . 'SC.ship_short_name,B.boat_code,B.boat_name,P1.port_name s_port,P2.port_name e_port')
                ->order('OPS.status DESC')->buildSql();

        $list=Db::table($data.' A')->group('A.id')->limit($tol,$limit)->select();
//        var_dump($list);
        return  $list;
        
    }



}