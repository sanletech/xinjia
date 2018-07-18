<?php
namespace app\admin\model;
use think\Model;
use think\Db;
//订单模块
class Order extends Model
{
    //审核客户提交的订单
    public function order_audit($pages=5)
    {
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        //查询客户的订单编号order_father 查询对应的订单信息
        $fatherSql= Db::name('order_father')->alias('OF')
                ->join('hl_container_size CS','CS.id =OF.container_size','left')  //箱型20gp 40hq
                ->join('hl_book_line BL','BL.id = OF.book_line_id','left')   //船运 车运 价格中间表
                ->join('hl_seaprice SP','SP.id= BL.seaprice_id','left')  //对应的船运价格表
                ->join('hl_ship_route SR','SR.id = SP.route_id','left') //船运路线表
                ->join('hl_sea_bothend SB','SB.sealine_id = SR.bothend_id','left') //船运路线 目的 和起运港表
                ->join('hl_port P1','P1.port_code = SB.sl_start','left') //船运起点港口
                ->join('hl_port P2','P2.port_code = SB.sl_end','left')   //船运目的港口
                ->join('hl_shipcompany SC','SC.id =SP.ship_id','left')//船公司
                ->join('hl_boat B','B.boat_code =SP.boat_code','left')//船公司合作的船舶
                ->join('hl_sales_member SM','SM.member_code = OF.member_code','left')  //业务对应客户表
                ->join('hl_salesman SA','SA.sales_code= SM.sales_code','left')  //业务表
                ->join('hl_member MB','MB.member_code =OF.member_code' ,'left')//客户表
                ->field('OF.id ,OF.order_num,MB.phone,MB.membername,SA.salesname,'
                        . 'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
                        . 'OF.cargo,SC.ship_short_name,B.boat_code,B.boat_name,OF.mtime')
                ->group('OF.id')->where('OF.state','eq','1')
                ->order('OF.id ,OF.mtime desc ')->buildSql();

        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
        
        return $list;
       
    }
    // 展示需要处理的订单列表
    public function order_list($pages=5) {
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        //查询客户的订单编号order_father 查询对应的订单信息
        $fatherSql= Db::name('order_father')->alias('OF')
                ->join('hl_container_size CS','CS.id =OF.container_size','left')  //箱型20gp 40hq
                ->join('hl_book_line BL','BL.id = OF.book_line_id','left')   //船运 车运 价格中间表
                ->join('hl_seaprice SP','SP.id= BL.seaprice_id','left')  //对应的船运价格表
                ->join('hl_ship_route SR','SR.id = SP.route_id','left') //船运路线表
                ->join('hl_sea_bothend SB','SB.sealine_id = SR.bothend_id','left') //船运路线 目的 和起运港表
                ->join('hl_port P1','P1.port_code = SB.sl_start','left') //船运起点港口
                ->join('hl_port P2','P2.port_code = SB.sl_end','left')   //船运目的港口
                ->join('hl_shipcompany SC','SC.id =SP.ship_id','left')//船公司
                ->join('hl_boat B','B.boat_code =SP.boat_code','left')//船公司合作的船舶
                ->join('hl_sales_member SM','SM.member_code = OF.member_code','left')  //业务对应客户表
                ->join('hl_salesman SA','SA.sales_code= SM.sales_code','left')  //业务表
                ->join('hl_member MB','MB.member_code =OF.member_code' ,'left')//客户表
                ->join('hl_order_add OA','OA.id = OF.add_id ','left')
              //  ->join('hl_linkman LK1','OA.s_linkman_id=LK1.id','left') //送货人资料
                ->join('hl_linkman LK2','OA.r_linkman_id=LK2.id','left')//收货人资料
                ->field('OF.id ,OF.order_num,SA.salesname,'
                        . 'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
                        . 'OF.cargo,CS.type,OF.container_num,'
                        . 'SC.ship_short_name,B.boat_code,B.boat_name,OF.mtime,'
                        . 'SP.shipping_date,SP.sea_limitation,SP.cutoff_date,'
                        . 'LK2.company ')
                ->group('OF.id')->where('OF.state','eq','2')
               // ->where('OA.member_code = OF.member_code')
                ->order('OF.id ,OF.mtime desc')->buildSql();
        //var_dump($fatherSql);exit;
        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
        return $list;
    }
    
    
   //录入运单号码, 如果只有一个运单号码 就是所有的柜子为一个运单号, 反之 有多少个柜子就录入多少个运单号码
    //订单号 集装箱数量, 运单号, 运单号数量
     public function waybillNum ($order_num,$container_num,$track_num, $track_sum){
         //输入一个订单 就填充集装箱数量个订单号
        if($track_sum ==1){
            $j= $container_num;
            $track_num = array_fill(0,$container_num,$track_num['0']);
        }else{
            $j= $track_sum;
        }
        $str ='';
        for($i=0;$i<$j;$i++){
            $str .= "('$order_num','$track_num[$i]','$i') ,";   
        }
        $str = rtrim($str,',');
        $sql ="insert into hl_order_son(order_num,track_num,container_code) values".$str;
   //   var_dump($sql);exit;
        $res =Db::execute($sql);
        $respones =[];
        $res ? $respones[]['success']='添加运单号成功' :$respones[]['fail']='添加运单号失败';
       // var_dump($respones);exit;
        return $respones;
    }
    
 
    
    
}