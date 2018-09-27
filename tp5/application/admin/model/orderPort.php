<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\session;
class orderPort extends Model
{
    
    public function order_audit($pages,$state){
        $list =Db::name('order_port')->alias('OP')
            ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
            ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
            ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
            ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
            ->join('hl_shipcompany SC','SC.id=SP.ship_id','left')//船公司id                                                    //起始港终点港
            ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
            ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
            ->join('hl_boat B','B.boat_code =SP.boat_code','left')//船舶表     
            ->field('OP.*,HM.company,HM.name,SC.ship_short_name,B.boat_code,B.boat_name,P1.port_name s_port_name ,P2.port_name e_port_name')
            ->group('HM.id,OP.id,SP.id,SR.id,SB.id,SC.id,B.id')
            ->where('OP.status','in',$state)
            ->paginate($pages); 
//    $this->_p($list);exit; 
            return $list;
    } 

    //订单的状态
    public function order_status($pages,$state,$payment_method= array('monthly','pledge','cash','special')) {
//        var_dump($pages,$state,$payment_method);exit;
        $list =Db::name('order_port')->alias('OP')
                ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
                ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                ->join('hl_shipcompany SC','SC.id=SP.ship_id','left')//船公司id                                                    //起始港终点港
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
                ->join('hl_boat B','B.boat_code =SP.boat_code','left')//船舶表   
                ->field('OP.*,HM.company,SC.ship_short_name,P1.port_name s_port,P2.port_name e_port,B.boat_code,B.boat_name')
                ->group('OP.id,SP.id,SR.id,SB.id,SC.id,B.id')
                ->where('OP.status','in',$state)
                ->where('OP.payment_method','in',$payment_method)
                ->paginate($pages); 
//        var_dump(Db::getLastSql());exit;     
        return $list;
        
    }
    //订单的详细信息
    public function orderData($param) {
        $list =Db::name('order_port')->alias('OP')
                ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
                ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                ->join('hl_shipcompany SC','SC.id=SP.ship_id','left')//船公司id                                                    //起始港终点港
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
               ->field('OP.*,HM.company,SC.ship_short_name,');
        
    }
    
    //记录订单的更新状态和时间
    public function orderUpdate($order_num,$status,$title) {
        $submitter= Session::get('user_info','think');
        $mtime =  date('y-m-d h:i:s');
        $data=array('order_num'=>$order_num,'status'=>$status,'title'=>$title,'submitter'=>$submitter);
        $res =Db ::name('order_port_status')->insert($data);
        
    }
}