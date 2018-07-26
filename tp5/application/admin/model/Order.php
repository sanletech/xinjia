<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\session;
//订单模块
class Order extends Model
{
    //审核客户提交的订单
    public function order_audit($pages=5,$state='0')
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
                ->group('OF.id')->where('OF.state','eq',$state)
                ->order('OF.id ,OF.mtime desc ')->buildSql();
        
        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
        return $list;
    }
    
    // 待订舱页面的list
    public function listBook($pages=5,$state='1') {
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
                ->join('hl_order_add OA','OA.id = OF.add_id ','left') //地址表
              //  ->join('hl_linkman LK1','OA.s_linkman_id=LK1.id','left') //送货人资料
                ->join('hl_linkman LK2','OA.r_linkman_id=LK2.id','left')//收货人资料
                ->field('OF.id ,OF.order_num,SA.salesname,'
                        . 'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
                        . 'OF.cargo,CS.type,OF.container_sum,'
                        . 'SC.ship_short_name,B.boat_code,B.boat_name,OF.mtime,'
                        . 'SP.shipping_date,SP.sea_limitation,SP.cutoff_date,'
                        . 'LK2.company ')
                ->group('OF.id')->where('OF.state','eq',$state)
               // ->where('OA.member_code = OF.member_code')
                ->order('OF.id ,OF.mtime desc')->buildSql();
        //var_dump($fatherSql);exit;
        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
        return $list;
    }
    
   //录入运单号码, 如果只有一个运单号码 就是所有的柜子为一个运单号, 反之 有多少个柜子就录入多少个运单号码
    //订单号 集装箱数量, 运单号, 运单号数量
    public function waybillNum ($order_num,$container_sum,$track_num, $track_sum){
         //输入一个订单 就填充集装箱数量个订单号
        if($track_sum ==1){
            $j= $container_sum;
            $track_num = array_fill(0,$container_sum,$track_num['0']);
        }else{
            $j= $track_sum;
        }
        $str ='';
        $date = date("md");
        for($i=0;$i<$j;$i++){
            $container_code = $track_num[$i].'d'.$date.'n'.$i;  //设置虚拟集装箱编码 等待派车后录入真正的集装箱编码再修改
            $str .= "('$order_num','$track_num[$i]','$container_code','2') ,";   
        }
        $str = rtrim($str,',');
        $sql ="insert into hl_order_son(order_num,track_num,container_code,state) values".$str;
   //   var_dump($sql);exit;
        $response =[];
        $res =Db::execute($sql);
        $res ? $response['success'][]='添加运单号成功' :$response['fail'][]='添加运单号失败';
       
//        if(!empty($res)){
//            //订单状态显示0待确认 1待订舱 2待派车 3待装货 4待报柜号 5待配船 6待到港 7待卸船 8待收钱 9待送货
//            //将对应的order_father 的 state 状态改为2  从录入运单号之后，需要修改father和 son订单的两个状态
//            $sql2 = "update hl_order_father set state = '2' where order_num = '$order_num' ";
//            $res2 =Db::execute($sql2);
//            $res2 ? $response['success'][]='修改待订舱成功' :$response['fail'][]='修改待订舱失败';
//        }
         
        if(!empty($res)){
            $father=['order_num'=>$order_num,'state'=>'2','action'=>'待派车'];
            $res2=  $this->updateState($father) ;
            $res2 ? $response['success'][]='修改待订舱状态成功' :$response['fail'][]='修改待订舱状态失败';
        }
     
        return $response;
    }
    
    //待派车页面list
    public function listSendCar($pages=5,$state='2') {
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
                ->join('hl_order_add OA','OA.id = OF.add_id ','left') //地址表
              //  ->join('hl_linkman LK1','OA.s_linkman_id=LK1.id','left') //送货人资料
                ->join('hl_linkman LK2','OA.r_linkman_id=LK2.id','left')//收货人资料
                ->join('hl_order_son OS','OS.order_num=OF.order_num','left')
                ->field('OF.id ,OF.order_num,SA.salesname,'
                        . 'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
                        . "OF.cargo,CS.type,OF.container_sum, group_concat(distinct OS.track_num order by OS.id separator '_') track_num,"
                        . " group_concat(distinct OS.container_code order by OS.id separator '_') container_code, "
                        . 'SC.ship_short_name,B.boat_code,B.boat_name,OF.mtime,'
                        . 'SP.shipping_date,SP.sea_limitation,SP.cutoff_date,'
                        . 'LK2.company ')
                ->group('OF.id')->where('OF.state','eq',$state)
               // ->where('OA.member_code = OF.member_code')
                ->order('OF.id ,OF.mtime desc')->buildSql();
      //  var_dump($fatherSql);exit;
        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
        return $list;
    }
     //录入派车信息
    public function tosendCar($data) 
    {  
        $order_num =$data['order_num'];
        $container_code = $data['container_code'];  //虚拟的集装箱编码 
        $container_code = explode('_', $container_code); //转换为数组 
        $container_id = $data['container_id']; //录入的集装箱编码 
        $container_sum = $data['container_sum']; //一个订单里的集装箱数量
       
       
        //将装货时间处理成时间戳
        $arrTime = $data['load_time'];
        $arr_time =[];
        for($j=0;$j<$container_sum;$j++){
            $arr_time[] = strtotime($arrTime[$j]);
        }
        //删除单个的数组的 container_code order_num container_sum  load_time
        unset($data['container_code'],$data['order_num'],$data['container_sum'],$data['load_time']);
         
        $data['load_time'] =$arr_time;  //将时间戳数组 添加到数组中
        //将$data数组由行转成列
        $arr =[];$arr1 =[]; $arr2=[];
        $arr= array_keys($data);
        $response=[];
        
        $mtime =  time();
        // 启动事务
        Db::startTrans();
        try{
            
        for($i=0;$i< $container_sum;$i++){
            $arr1 = array_column($data, $i);
            $arr2 = array_combine($arr,$arr1);
            $arr2['mtime'] =$mtime;  //添加时间戳
            $res1 = Db::name('car_receive')->insert($arr2);
            $res1 ? $response['success'][]="添加第$i个柜子派车信息成功" :$response['fail'][]="添加第$i个柜子派车信息成功";
            $id[] = Db::name('car_receive')->getLastInsID();
        }
       
        // 根据获取的 虚拟的集装箱编码 首先将派车信息id添加到order_son表里
        // 再将container_code 修改为实际的集装箱编码
        
        for($k=0;$k< $container_sum;$k++){
            $res2 = Db::name('order_son')->where('container_code',$container_code[$k])->setField('car_receive_id', $id[$k]);
            $res2 ? $response['success'][]="修改$id[$k]派车id成功" :$response['fail'][]="修改$id[$k]派车id失败";
            
            $res3 = Db::name('order_son')->where('container_code',$container_code[$k])->setField('container_code', $container_id[$k]);
            $res3 ? $response['success'][]="修改$container_code[$k]集装箱成功" :$response['fail'][]="修改$container_code[$k]集装箱失败";

        }
       
        //同时修改状态
        $res4 = Db::name('order_father')->where('order_num',$order_num)->setField('state',3);
        $res4 ? $response['success'][]='修改order_father待订舱成功' :$response['fail'][]='修改order_father待订舱失败';
        
        $res5 = Db::name('order_son')->where('order_num',$order_num)->setField('state',3);
        $res5 ? $response['success'][]='修改order_son待订舱成功' :$response['fail'][]='修改order_son待订舱失败';
       
        
        if(array_key_exists('fail', $response)){
            throw new \Exception('fail');
        } 
         Db::commit();
        } catch (\Exception $e) {
           // 回滚事务
        Db::rollback();
            if($e->getMessage()=='fail'){
                $response['fail'][]= '提交派车信息失败数据回滚';
            }
           
        }
        // var_dump($response);exit;
        return $response;
    }
    
      //添加实际装货时间
    public function toLoadTime($order_num,$data) 
    { 
        $container_id1=$data['container_code'][0];
        $loading_time1=$data['loading_time'][0];
        $container_id2=$data['container_code'][1];
        $loading_time2=$data['loading_time'][1];
        $res1 = Db::name('car_receive')->where('order_num',$order_num)
                ->where('container_id',$container_id1)->update('loading_time',$loading_time1);
        $res2 = Db::name('car_receive')->where('order_num',$order_num)
                ->where('container_id',$container_id2)->update('loading_time',$loading_time2);
        if(!($res1 && $res2)){
            $status =['msg'=>'录入装货时间失败','status'=>0];
        }  else {
            $status =['msg'=>'录入装货时间成功','status'=>1];
         
        }
            return $status;
            
            
            
            
    }
    
/** 
* updateState
* 在变更father和son订单的状态同时 在order_state 做登记
* @access public 
* @param  $father 父订单的 array(订单order_num数组 state  action) $father=['order_num','state','action']
* @param  $son    子订单的 array(订单order_num数组 container_code数组, state, action) $son=['order_num','container_code','state','action']
*/  
    public function updateState($father='',$son='') 
    { 
        // 取值（当前作用域）
        $loginname= Session::get('user_info','think');
        $change_time = time();  
        $response=[];
        $fatherFuc = function() use($father,$loginname,$change_time){ 
            $state =$father['state'];
            $order_num = $father['order_num'];
            $action =$father['action'];
            $idArr = Db::name('order_father')->where('order_num','in',$order_num)->column('id');
            $response=[];
            //修改订单状态
            $res1 = Db::name('order_father')->where('id','in',$idArr)->setField('state',$state);
           
            $id= implode('_', $idArr);
            $res1 ? $response['success'][]="修改order_father:{$id}待订舱成功" :$response['fail'][]= "修改order_father:{$id}待订舱失败";
            //登记到order_status
            foreach($idArr as $v){
            $data[] = ['state'=>$state,'action'=>$action,'order_father_id'=>$v,'change_time'=> $change_time,'submit_man_code'=>$loginname];
            }
            $res2 = Db::name('order_status')->insertAll($data);
            echo  Db::getLastSql();
            $res2 ? $response['success'][]="登记order_father的state成功" :$response['fail'][]= "登记order_father的state失败";
            return $response;
        };
        
        $sonFuc = function() use($son,$loginname,$change_time){ 
            $state =$son['state'];
            $order_num =$son['order_num'];
            $container_code = $son['container_code'];
            $action =$son['action'];
            $idArr = Db::name('order_son')->where('order_num','in',$order_num)->where('container_code','in',$container_code)->column('id');
            $response=[];
            $res = Db::name('order_son')->where('id','in',$idArr)->setField('state',$state);
            $id= implode('_', $idArr);
            $res ? $response['success'][]="修改order_son:{$id}待订舱成功" :$response['fail'][]="修改order_son{$id}待订舱失败";
            //登记到order_status
            foreach($son['id'] as $v){
                $data[] = ['state'=>$state,'action'=>$action,'order_son_id'=>$v,'change_time'=> $change_time,'submit_man_code'=>$loginname];
            }
            $res2 = Db::name('order_status')->insertAll($data);
            $res2 ? $response['success'][]="登记order_son的state成功" :$response['fail'][]= "登记order_son的state失败";
            return $response;
        };
        
        if(!empty($father)){
            $fatherFuc();
        } 
        if(!empty($son)){
            $sonFuc();
        } 
        
        if(!array_key_exists('fail', $response)){
            $status = true;
        }else   {
            $status =false; 
        }
        return $status ;
    }
    
    
}