<?php /*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\admin\model;

/**
 * Description of orderzuofei
 *
 * @author Administrator
 */
class orderzuofei {
    //put your code here
    
    
            // 待订舱页面的list
    public function listBook($pages=5,$state='100') {
//        $pageParam  = ['query' =>[]]; //设置分页查询参数
//        //查询客户的订单编号order_father 查询对应的订单信息
//        $fatherSql= Db::name('order_father')->alias('OF')
//                ->join('hl_container_size CS','CS.id =OF.container_size','left')  //箱型20gp 40hq
//                ->join('hl_book_line BL','BL.id = OF.book_line_id','left')   //船运 车运 价格中间表
//                ->join('hl_seaprice SP','SP.id= BL.seaprice_id','left')  //对应的船运价格表
//                ->join('hl_ship_route SR','SR.id = SP.route_id','left') //船运路线表
//                ->join('hl_sea_bothend SB','SB.sealine_id = SR.bothend_id','left') //船运路线 目的 和起运港表
//                ->join('hl_port P1','P1.port_code = SB.sl_start','left') //船运起点港口
//                ->join('hl_port P2','P2.port_code = SB.sl_end','left')   //船运目的港口
//                ->join('hl_shipcompany SC','SC.id =SP.ship_id','left')//船公司
//                ->join('hl_boat B','B.boat_code =SP.boat_code','left')//船公司合作的船舶
//                ->join('hl_sales_member SM','SM.member_code = OF.member_code','left')  //业务对应客户表
//                ->join('hl_salesman SA','SA.sales_code= SM.sales_code','left')  //业务表
//                ->join('hl_member MB','MB.member_code =OF.member_code' ,'left')//客户表
//                ->join('hl_order_add OA','OA.id = OF.add_id ','left') //地址表
//              //  ->join('hl_linkman LK1','OA.s_linkman_id=LK1.id','left') //送货人资料
//                ->join('hl_linkman LK2','OA.r_linkman_id=LK2.id','left')//收货人资料
//                
//                                ->field('OF.id ,OF.order_num,SA.salesname,'
//                        . 'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
//                        . "OF.cargo,CS.type,OF.container_sum, group_concat(distinct OS.track_num order by OS.id separator '_') track_num,"
//                        . " group_concat(distinct OS.container_code order by OS.id separator '_') container_code, "
//                        . 'SC.ship_short_name,B.boat_code,B.boat_name,OF.mtime,'
//                        . 'SP.shipping_date,SP.sea_limitation,SP.cutoff_date,'
//                        . 'LK2.company ')
//                ->group('OF.order_num')->where('OS.state','in',$state)
//                ->order('OF.id ,OF.mtime desc')->buildSql();
//                
//                
//                ->field('OF.id ,OF.order_num,SA.salesname,'
//                        . 'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
//                        . 'OF.cargo,CS.type,OF.container_sum,'
//                        . 'SC.ship_short_name,B.boat_code,B.boat_name,OF.mtime,'
//                        . 'SP.shipping_date,SP.sea_limitation,SP.cutoff_date,'
//                        . 'LK2.company ')
//                ->group('OF.id')->where('OF.state','eq',$state)
//               // ->where('OA.member_code = OF.member_code')
//                ->order('OF.id ,OF.mtime desc')->buildSql();
//        //var_dump($fatherSql);exit;
//        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
//        return $list;
    }
    
        //待配船 到港 卸船
    public function listShip($pages=5,$state='') {
//        $pageParam  = ['query' =>[]]; //设置分页查询参数
//        //查询客户的订单编号order_father 查询对应的订单信息
//        $fatherSql= Db::name('order_father')->alias('OF')
//                ->join('hl_container_size CS','CS.id =OF.container_size','left')  //箱型20gp 40hq
//                ->join('hl_book_line BL','BL.id = OF.book_line_id','left')   //船运 车运 价格中间表
//                ->join('hl_seaprice SP','SP.id= BL.seaprice_id','left')  //对应的船运价格表
//                ->join('hl_ship_route SR','SR.id = SP.route_id','left') //船运路线表
//                ->join('hl_sea_bothend SB','SB.sealine_id = SR.bothend_id','left') //船运路线 目的 和起运港表
//                ->join('hl_port P1','P1.port_code = SB.sl_start','left') //船运起点港口
//                ->join('hl_port P2','P2.port_code = SB.sl_end','left')   //船运目的港口
//                ->join('hl_shipcompany SC','SC.id =SP.ship_id','left')//船公司
//                ->join('hl_boat B','B.boat_code =SP.boat_code','left')//船公司合作的船舶
//                ->join('hl_sales_member SM','SM.member_code = OF.member_code','left')  //业务对应客户表
//                ->join('hl_salesman SA','SA.sales_code= SM.sales_code','left')  //业务表
//                ->join('hl_member MB','MB.member_code =OF.member_code' ,'left')//客户表
//                ->join('hl_order_add OA','OA.id = OF.add_id ','left') //地址表
//              //  ->join('hl_linkman LK1','OA.s_linkman_id=LK1.id','left') //送货人资料
//                ->join('hl_linkman LK2','OA.r_linkman_id=LK2.id','left')//收货人资料
//                ->join('hl_order_son OS','OS.order_num=OF.order_num','left')
//                ->field('OF.id ,OF.order_num,SA.salesname,'
//                        . 'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
//                        . "OF.cargo,CS.type,OF.container_sum, group_concat(distinct OS.track_num order by OS.id separator '_') track_num,"
//                        . " group_concat(distinct OS.container_code order by OS.id separator '_') container_code, "
//                        . 'SC.ship_short_name,B.boat_code,B.boat_name,OF.mtime,'
//                        . 'SP.shipping_date,SP.sea_limitation,SP.cutoff_date,'
//                        . 'LK2.company ')
//                ->group('OF.order_num')->where('OS.state','in',$state)
//                ->order('OF.id ,OF.mtime desc')->buildSql();
//        // var_dump($fatherSql);exit;
//        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
//        return $list;
    }
    
        
    
    public function updateStatefeiqi($father='',$son='') 
    { 
       
        // 取值（当前作用域）
        $loginname= Session::get('user_info','think');
       // var_dump($loginname);exit;
        $change_time = date('y-m-d h:i:s');  
        $response=[];
    
        $fatherFuc = function() use($father,$loginname,$change_time){ 
            $state =$father['state'];
            $order_num = $father['order_num'];
            $action =$father['action'];
            $idArr = Db::name('order_father')->where('order_num','in',$order_num)->column('id');
           // var_dump(Db::getLastSql());
            $response=[];
            //先查询状态是否是最新的 如果不是就返回
            $stateMax = Db::name('order_father')->where('id','in',$idArr)->max('state');
            if($state<=$stateMax){
                $response['fail']= 'order_father提交的信息已更新过';
                return  $response;
            }
            //查询order_son 下面的 同一个订单编号的是否更新过了 
            
            //修改订单状态
            $res1 = Db::name('order_father')->where('id','in',$idArr)->setField('state',$state); 
           // var_dump(Db::getLastSql());
            $id= implode(' and ', $idArr);
            $res1 ? $response['success'][]="修改order_father:{$id}状态成功" :$response['fail'][]= "修改order_father:{$id}状态失败";
            //登记到order_status
            $data=[];
            foreach($idArr as $v){
                $data[] = ['state'=>$state,'action'=>$action,'order_father_id'=>$v,'change_time'=> $change_time,'submit_man_code'=>$loginname];
            }
            $res2 = Db::name('order_status')->insertAll($data);          
          //  var_dump(Db::getLastSql());
            $res2 ? $response['success'][]="登记order_father的state成功" :$response['fail'][]= "登记order_father的state失败";
            // var_dump($response);exit;
            return  $response;
        };
        
        $sonFuc = function() use($son,$loginname,$change_time){ 
            
            $state =$son['state'];
            $order_num =$son['order_num'];
            $container_code = $son['container_code'];
            $action =$son['action'];
            $response=[];
            //先查询状态是否是最新的 如果不是就返回
            foreach ($container_code as $container){
                $stateMax = Db::name('order_son')->where('container_code',$container)->value('state');
                if($state<$stateMax){
                    $response['fail']= '提交的信息已经更新过';
                    return  $response;
                }
            }
           //var_dump(Db::getLastSql());exit;
        
            $idArr = Db::name('order_son')->where('order_num','in',$order_num)
                    ->where('container_code','in',$container_code)->column('id'); 
            
            $res = Db::name('order_son')->where('id','in',$idArr)->setField('state',$state);
           // var_dump(Db::getLastSql());
            $id= implode(' and ', $idArr);
            $res ? $response['success'][]="修改order_son:{$id}的state成功" :$response['fail'][]="修改order_son{$id}的state失败";
            
            //登记到order_status
            $data=[];
            foreach($idArr  as $v){
                $data[] = ['state'=>$state,'action'=>$action,'order_son_id'=>$v,'change_time'=> $change_time,'submit_man_code'=>$loginname];
            }
            $res2 = Db::name('order_status')->insertAll($data);
            //var_dump(Db::getLastSql());
            $res2 ? $response['success'][]="登记order_son的state成功" :$response['fail'][] = "登记order_son的state失败";
            return  $response;
        };
    
        if(!empty($father)){
            $res1=$fatherFuc();
            if(array_key_exists('success', $res1)){
                $response['success'][]=$res1['success'];
            }else{
                $response['fail'][]=$res1['fail'];
            }
        } 
        if(!empty($son)){
            $res2 = $sonFuc();
            if(array_key_exists('success', $res2)){
                $response['success'][]=$res2['success'];
            }else{
                $response['fail'][]=$res2['fail'];
            }
        }   
//        var_dump($response);exit;
        return $response;
    }
    
        public function toBaogui111($data){
       
            // 启动事务
        Db::startTrans();
        $response= [];
        try{
            foreach ($data as $key=>$value){
                //$key 是柜子编码, $value是订单编号
                $res = Db::name('order_son')->where('container_code',$key)->where('order_num',$value)->setField('state',5);
                //var_dump(Db::getLastSql());
                $res ? $response['success'][]="柜子{$value}修改状态成功" :$response['fail'][]= "柜子{$value}修改状态失败";
            }
        $order_num = array_values($data);
        $container_codeArr =  array_keys($data);
         //查询同一个订单下 箱子code是否都提交进来, 如果是就更新father的状态
        $containerSql = Db::name('order_son')->where('order_num',$value)->column('container_code');
        //如果$containerSql 和 提交进来$container_codeArr对比有差异 说明 还有部分没有提交
        $diff_arr = array_diff($containerSql,$container_codeArr);
        if(!empty($diff_arr)){
            $father=['order_num'=>$order_num,'state'=>505,'action'=>'申报柜号完毕'];
        }  else {
            $father ='';
        }
        //修改order_state的状态
        
        $son =['order_num'=>$order_num,'container_code'=>$container_codeArr,'state'=>505,'action'=>'申报柜号完毕'];
        $res =$this->updateState($father,$son) ;    
        if(array_key_exists('fail',$res)){
            $response['fail'][]= $res['fail'];
        }else{
            $response['success'][]= $res['success'];
        }
            
        if(array_key_exists('fail', $response)){
            $msg = implode('', $response['fail']);
            throw new \Exception($msg);
        }  else {
            Db::commit();
        } 
     
        } catch (\Exception $e) {
           // 回滚事务
        Db::rollback();
            $response['fail']= $e->getMessage();
        }
        
        return  $response;
     
    }
}

