<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\session;

class OrderProcess  extends Model{
      
//查看完成的详细订单
    public function OrderDetail($order_num) {
        //查询客户的订单编号order_father 查询对应的订单信息
        $order_father = Db::name('order_father')->alias('OF')
                ->join('hl_seaprice SP','SP.id= OF.seaprice_id','left')  //对应的船运价格表
                ->join('hl_ship_route SR','SR.id = SP.route_id','left') //船运路线表
                ->join('hl_sea_bothend SB','SB.sealine_id = SR.bothend_id','left') //船运路线 目的 和起运港表
                ->join('hl_port P1','P1.port_code = SB.sl_start','left') //船运起点港口
                ->join('hl_port P2','P2.port_code = SB.sl_end','left')   //船运目的港口
                ->join('hl_shipcompany SC','SC.id =SP.ship_id','left')//船公司
                ->join('hl_boat B','B.boat_code =SP.boat_code','left')//船公司合作的船舶
                ->join('hl_sales_member SM','SM.member_code = OF.member_code','left')  //业务对应客户表
                ->join('hl_user U',"U.user_code= SM.sales_code and U.type='sales'",'left')  //业务表
                ->join('hl_member MB','MB.member_code =OF.member_code' ,'left')//客户表
                ->join('hl_linkman LK1','OF.shipper_id=LK1.id and OF.member_code=LK1.member_code','left') //发货人资料
                ->join('hl_linkman LK2','OF.consigner_id=LK2.id and OF.member_code=LK2.member_code','left')//收货人资料
                ->join('hl_container_type CT','CT.id=OF.container_type_id','left')//柜子的种类
                ->join('hl_order_son OS','OS.order_num=OF.order_num','left')
                ->join('hl_order_comment OM','OM.order_num=OF.order_num','left')
                ->field('OF.*,CT.container_type,P1.port_code s_port_code,P1.port_name s_port_name,'
                        . 'P2.port_code e_port_code,P2.port_name e_port_name,SC.ship_short_name,'
                        . 'U.user_name sales,MB.name member_name,'
                        . 'SP.shipping_date,SP.cutoff_date,SP.boat_code,B.boat_name,'
                        . 'LK1.name r_name,LK1.phone r_phone,LK1.address r_address,LK1.company r_company,'
                        . 'LK2.name s_name,LK2.phone s_phone,LK2.address s_address,LK2.company s_company')
                ->group('OF.order_num')->where('OF.order_num',$order_num)->find();

            $order_son = Db::name('order_son')->alias('OS') //派车送货信息
                    ->join('hl_car_receive CR','CR.track_num =OS.track_num and CR.container_id =OS.container_code','left')
                    ->group('CR.track_num,CR.container_id')->where('OS.order_num',$order_num)->select();
            $container_code = Db::name('order_son')->where('order_num',$order_num)->column('container_code');
            
            $order_ship = Db::name('order_ship')->alias('OS')//配船信息
                    ->where('OS.order_id',$order_num)
                    ->select();  
//                var_dump($order_ship);exit;
            $order_status =Db::name('order_status')->where('order_num',$order_num)->select(); //状态信息
            $order_track_num =Db::name('order_son')->where('order_num',$order_num)->value('track_num');//运单号
        //查询对应的发票信息
        $orderInvoice =Db::name('invoice')->where('id',$order_father['invoice_id'])->where('member_code',$order_father['member_code'])->find();
        
        return array($order_father,$order_son,$order_ship,$order_status,$orderInvoice,$container_code ,$order_track_num);
    } 
}
