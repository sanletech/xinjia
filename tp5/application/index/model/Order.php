<?php

namespace app\index\model;
use think\Model;
use think\Db;
class Order extends Model
{


    //前台页面展示门到门的价格表
    public function  price_sum($start_add='',$end_add='',$load_time=''){
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        $nowtime= time();//要设置船期
        
        $price_sea = Db::name('seaprice')->alias('SP')
            ->join('hl_ship_route SR','SR.id =SP.route_id','left')
            ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
            ->join('hl_port P1','P1.port_code=SB.sl_start','left')
            ->join('hl_port P2','P2.port_code=SB.sl_end','left')
            ->field('SP.*,SB.sl_start,P1.port_name s_port_name,SB.sl_end,SR.middle_id,P2.port_name e_port_name')
            //->where('SP.cutoff_date','>',$nowtime)
            ->group('SP.id')->order('SP.route_id')->buildSql();
        if($load_time){
            $price_sea = Db::table($price_sea.' E')->where('E.cutoff_date','>',$load_time)->buildSql();
        }
        $price_car = Db::name('carprice')->alias('CP')
            ->join('hl_car_line CL','CL.id =CP.cl_id','left')
            ->field('CP.*,CL.port_id,CL.address_name')
            ->order('CP.cl_id')->buildSql();
        $price_sql = Db::table($price_sea.' A')
                ->join($price_car.' B','A.sl_start =B.port_id','left')
                ->join($price_car.' C','A.sl_end =C.port_id','left')
                ->join('hl_shipcompany SC','SC.id = A.ship_id')
                ->join('hl_boat BA','BA.boat_code =A.boat_code')
//                ->field('A.id,B.id rid,C.id sid,A.route_id,A.middle_id,A.ship_id,SC.ship_short_name,A.shipping_date'
//                     )
                ->field('A.id sea_id,B.id rid,C.id sid,A.route_id,A.middle_id,A.ship_id,SC.ship_short_name,A.shipping_date,'
                        . 'A.cutoff_date,A.boat_code,BA.boat_name,A.sea_limitation,'
                        . 'A.ETA,A.EDD,A.generalize,A.mtime,'
                        . 'A.sl_start,A.s_port_name,B.address_name r_add ,A.sl_end,A.e_port_name,C.address_name s_add ,'
                        . '(select A.price_20GP + B.price_20GP + C.price_20GP  ) as price_20GP,'
                        . '(select A.price_40HQ + B.price_40HQ + C.price_40HQ  ) as price_40HQ')
                ->where('B.variable', '=',"r")->where('C.variable', '=',"S")
                ->buildSql();
        if($start_add){
            $price_sql = Db::table($price_sql.' F')->where('F.r_add','like',"%$start_add%")->buildSql();
        }
        if($end_add){
            $price_sql = Db::table($price_sql.' G')->where('G.s_add','like',"%$end_add%")->buildSql();
        }
//       var_dump($price_sea); echo"</br>";
//       var_dump($price_car); echo"</br>";
//       var_dump($price_sql);exit;
       // $list = Db::table($price_sql.' D')->paginate($pages,false,$pageParam);   
        return $price_sql;
       
    }
    //展示已经选择好的价格信息
    public function book($data) {
        $sea_id = $data['sea_id'];
        $r_car_id = $data['r_car_id'];
        $s_car_id = $data['s_car_id'];
        $container_size = $data['container_size'];
        
        if($container_size ==1){
            $price_size ='A.price_20GP';
        }elseif($container_size ==2) {
            $price_size ='A.price_40HQ';
        } 
     //   var_dump($container_size);exit;
        $list= $this->price_sum();
       
       $res = Db::table($list.' A')
            ->where('sea_id',$sea_id)
            ->where('rid',$r_car_id)
            ->where('sid',$s_car_id)
            ->field('A.sea_id, A.rid, A.sid, A.ship_short_name, A.shipping_date,'
                . 'A.boat_code, A.boat_name, A.sea_limitation,A.ETA,'
                . 'A.sl_start,A.s_port_name,A.r_add ,A.sl_end,A.e_port_name,A.s_add ,'
                . $price_size.' price' )->find();
             // 将集装箱字的尺寸添加到数组中
            $res['container_size']=$container_size;
            
            //   var_dump($res);exit;
        return $res;            
          
    }
}
