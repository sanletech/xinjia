<?php

namespace app\index\model;
use think\Model;
use think\Db;
class Order extends Model
{


    //前台页面展示门到门的价格表
    public function price_sum($pages=5){
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        $nowtime= time();//要设置船期
        
        $price_sea = Db::name('seaprice')->alias('SP')
            ->join('hl_ship_route SR','SR.id =SP.route_id','left')
            ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
            ->join('hl_port P1','P1.port_code=SB.sl_start','left')
            ->join('hl_port P2','P2.port_code=SB.sl_end','left')
            ->field('SP.*,SB.sl_start,P1.port_name s_port_name,SB.sl_end,SR.middle_id,P2.port_name e_port_name')
            ->order('SP.route_id')->buildSql();
          
        $price_car = Db::name('carprice')->alias('CP')
            ->join('hl_car_line CL','CL.id =CP.cl_id','left')
            ->field('CP.*,CL.port_id,CL.address_name')
            ->order('CP.cl_id')->buildSql();
        
        $price_sql = Db::table($price_sea.' A')
                ->join($price_car.' B','A.sl_start =B.port_id and  B.variable = "r"','left')
                ->join($price_car.' C','A.sl_end =C.port_id and C.variable = "s"','left')
                ->join('hl_shipcompany SC','SC.id = A.ship_id')
                ->join('hl_boat BA','BA.boat_code =A.boat_code')
                ->field('A.id,B.id rid,C.id sid,A.route_id,A.middle_id,A.ship_id,SC.ship_short_name,A.shipping_date,'
                        . 'A.cutoff_date,A.boat_code,BA.boat_name,A.sea_limitation,'
                        . 'A.ETA,A.EDD,A.generalize,A.mtime,'
                        . 'A.sl_start,A.s_port_name,A.sl_end,A.e_port_name,'
                        . '(select A.price_20GP + B.price_20GP + C.price_20GP  ) as price_20GP,'
                        . '(select A.price_40HQ + B.price_40HQ + C.price_40HQ  ) as price_40HQ')
                ->buildSql();
   // var_dump($price_sql);exit;

        $list = Db::table($price_sql.' D')->paginate($pages,false,$pageParam);   
        return $list;
    }
      public function route_detail($sealine_id)
    { 
        $sql = "select P.port_name from hl_sea_middle SM "
            . "left join hl_port P on SM.sl_middle = P.port_code"
            . " where SM.sealine_id = '$sealine_id' order by SM.sequence";    
        $res =Db::query($sql);
        $data = array_column($res, 'port_name');
        return $data;
    }
    
     public function confirm_order($price)
    { 
         
    }
}
