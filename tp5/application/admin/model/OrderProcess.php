<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\session;

class OrderProcess  extends Model{
      
//查看完成的详细订单
    public function OrderDetail($order_num) {
        //查询客户的订单编号order_father 查询对应的订单信息
        $order = Db::name('order_father')->alias('OF')
                ->join('hl_book_line BL','BL.id = OF.book_line_id','left')   //船运 车运 价格中间表
                ->join('hl_seaprice SP','SP.id= BL.seaprice_id','left')  //对应的船运价格表
                ->join('hl_ship_route SR','SR.id = SP.route_id','left') //船运路线表
                ->join('hl_sea_bothend SB','SB.sealine_id = SR.bothend_id','left') //船运路线 目的 和起运港表
                ->join('hl_port P1','P1.port_code = SB.sl_start','left') //船运起点港口
                ->join('hl_port P2','P2.port_code = SB.sl_end','left')   //船运目的港口
                ->join('hl_shipcompany SC','SC.id =SP.ship_id','left')//船公司
                ->join('hl_boat B','B.boat_code =SP.boat_code','left')//船公司合作的船舶
                ->join('hl_sales_member SM','SM.member_code = OF.member_code','left')  //业务对应客户表
                ->join('hl_user U',"U.user_code= SM.sales_code and U.type='sales'",'left')  //业务表
                ->join('hl_member MB','MB.member_code =OF.member_code' ,'left')//客户表
                ->join('hl_order_add OA','OA.id = OF.add_id ','left') //地址表
                ->join('hl_linkman LK1','OA.s_linkman_id=LK1.id','left') //送货人资料
                ->join('hl_linkman LK2','OA.r_linkman_id=LK2.id','left')//收货人资料
                ->join('hl_container_type CT','CT.id=OF.container_type_id','left')//柜子的种类
                ->join('hl_order_son OS','OS.order_num=OF.order_num','left')
                ->join('hl_order_comment OM','OM.order_num=OF.order_num','left')
                ->field('OF.id ,OF.order_num,U.user_name,'
                        .  'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
                        .  "OF.cargo,OF.container_size,CT.container_type,OF.container_sum,OF.member_code,"
                        . " OF.comment, "
                        . ' SC.ship_short_name,B.boat_code,B.boat_name,OF.mtime,'
                        . ' SP.shipping_date,SP.sea_limitation,SP.cutoff_date,'
                        . ' LK2.company ')
                ->group('OF.order_num')->where('OF.order_num',$order_num)->find();
        $this->_p($order);exit;
        //查询对应的order_comment 的补充信息
        $orderComment = Db::name('order_comment')->where('order_num',$order_num)->find(); 
        //查询对应的发票信息
        $orderInvoice =Db::name('invoice')->where('id',$listComment['invoice'])->where('member_code'.$list['member_code'])->find();
        
        return array($order,$orderComment,$orderInvoice);
    } 
}
