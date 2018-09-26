<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class orderPort extends Model
{
    
    public function order_audit($pages=5,$state='0'){
        
       $list = Db::name('order_port')->where('status',2)->paginate($pages);
       return $list;
    } 

    //订单的状态
    public function order_status($param) {
        $list =Db::name('order_port')->alias('OP')
                ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
                ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                ->join('hl_shipcompany SC','SC.id=SP.ship_id','left')//船公司id                                                    //起始港终点港
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
               ->field('OP.*,HM.company,SC.ship_short_name,');
                
                //还需要增加五个字段 订舱单 订舱单文件， 柜号，水运单文件 付款状态
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
}