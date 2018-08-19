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
        //插入之前先判断是否已经已经存在了运单号
        $num= Db::name('order_son')->where('order_num',$order_num)->find();
        if(!($num->isEmpty())){
             return $response['fail']='运单号已经输入过了';
        }
        
        $sql ="insert into hl_order_son(order_num,track_num,container_code,state) values".$str;
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
            $father=['order_num'=>[$order_num],'state'=>'2','action'=>'输入运单号完毕'];
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
                ->group('OF.order_num')->where('OS.state','eq',$state)
             //   ->group('OF.id')->where('OF.state','eq',$state)
               // ->where('OA.member_code = OF.member_code')
                ->order('OF.id ,OF.mtime desc')->buildSql();
        // var_dump($fatherSql);exit;
        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
        return $list;
    }
    
    //待配船 到港 卸船
    public function listShip($pages=5,$state='') {
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
                ->group('OF.order_num')->where('OS.state','in',$state)
             //   ->group('OF.id')->where('OF.state','eq',$state)
               // ->where('OA.member_code = OF.member_code')
                ->order('OF.id ,OF.mtime desc')->buildSql();
        // var_dump($fatherSql);exit;
        $list = Db::table($fatherSql.' A')->paginate($pages,false,$pageParam);
        return $list;
    }
    
     //录入派车信息
    public function tosendCar($data) 
    {  
        $order_num =$data['order_num'];
        $track_num =$data['track_num'];
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
        $mtime =  time();
        $response=[];
        // 启动事务
        Db::startTrans();
        try{
            
        for($i=0;$i< $container_sum;$i++){
            $arr1 = array_column($data, $i);
            $arr2[$i] = array_combine($arr,$arr1);
            $arr2[$i]['mtime'] =$mtime;  //添加时间戳
        }
        $res1 = Db::name('car_receive')->insertAll($arr2);
        $res1 ? $response['success'][]="添加派车信息成功" :$response['fail'][]="添加派车信息成功";
        
        if($res1){
            for($k=0;$k<$container_sum;$k++){
                // 将container_code 修改为实际的集装箱编码
                $res3 = Db::name('order_son')->where('track_num',$track_num[$k])->where('container_code',$container_code[$k])->setField('container_code', $container_id[$k]);
                $res3 ? $response['success'][]="修改$container_code[$k]柜子编码成功" :$response['fail'][]="修改$container_code[$k]柜子编码失败";
                if($res3){
                    $id = Db::name('car_receive')->where('track_num',$track_num[$k])->where('container_id',$container_id[$k])->value('id');
                    // 根据获取的 虚拟的集装箱编码 首先将派车信息id添加到order_son表里
                    $res2 = Db::name('order_son')->where('track_num',$track_num[$k])->where('container_code',$container_id[$k])->setField('car_receive_id', $id );
                    $res2 ? $response['success'][]="添加柜子$container_code[$k]:派车id成功" :$response['fail'][]="添加柜子$container_code[$k]:派车id失败";
                }
            }    
        }
       
        if(!array_key_exists('fail',$response)){
            Db::commit();
        } 
        
        } catch (\Exception $e) {
           // 回滚事务
        Db::rollback();
            $response['fail'][]=$e->getMessage();
        }
        
        //修改order_state的状态
        $father =['order_num'=>[$order_num],'state'=>3,'action'=>'录入派车信息完毕'];
        $son =['order_num'=>[$order_num],'container_code'=>$container_id,'state'=>3,'action'=>'录入派车信息完毕'];
        $res = self::updateState($father,$son);
        
        if(array_key_exists('fail',$res)){
            $response['fail'][]= $res['fail'];
        }else{
            $response['success'][]= $res['success'];
        }
        
        
        return $response;
    }
    
      //添加实际装货时间
    public function toLoadTime($order_num,$data) 
    {    
        $container_codeArr =$data['container_code'];
        $loading_timeArr = $data['loading_time'];
        $track_numArr =  $data['track_num'];
        $sqlData = array_combine($container_codeArr ,$loading_timeArr);
        $response=[];
           // 启动事务
        Db::startTrans();
        try{
        $i=0;  
        foreach ($sqlData as $key => $value) {
            $container_id =$key;
            $loading_time = strtotime($value);
            $track_num =$track_numArr[$i];
            $i++;
            $res = Db::name('car_receive')->where('track_num',$track_num)
                    ->where('container_id',$container_id)->update(['loading_time'=>$loading_time]);
   // var_dump(Db::getLastSql());
            $res ? $response['success'][]="添加柜子{$container_id}实际装货时间成功" :$response['fail'][]= "添加柜子{$container_id}:实际装货时间失败";
        }
        //修改order_state的状态
        $father =['order_num'=>[$order_num],'state'=>4,'action'=>'录入实际装货时间完毕'];
        $son =['order_num'=>[$order_num],'container_code'=>$container_codeArr,'state'=>4,'action'=>'录入实际装货时间完毕'];
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
    
     //处理报柜号
    public function toBaogui($data){
       
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
    //录入配船信息页面的 展示原有的航线详情信息
    public function cargoPlan($order_num){
        $data =Db::name('order_father')->alias('OF')
                ->join('hl_book_line BL','OF.book_line_id = BL.id','left')
                //->join('hl_car_listprice CLP_s',"CLP_s.id = BL.s_pricecar_id and  CLP_s.variable='s'",'left')//门到港送货价格表
                //->join('hl_car_listprice CLP_r',"CLP_r.id = BL.r_pricecar_id and  CLP_r.variable='r'",'left')//门到港装货价格表
                ->join('hl_seaprice SP','SP.id=BL.seaprice_id','left')//海运价格
                // ->join('hl_shipcompany SC','SC.id= SP.ship_id','left')//船公司
                ->join('hl_boat B','SP.boat_code= B.boat_code','left')//船舶
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//海运航线表
                ->join('hl_sea_middle SM','SM.sealine_id=SR.middle_id','left')//中间港口航线
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//两头港口航线
                ->join('hl_port P1','P1.port_code =SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code =SB.sl_end','left')//目的港口
                ->join('hl_port P3','P3.port_code =SM.sl_middle','left')//中间港口
                ->field("OF.order_num,B.boat_code,B.boat_name,SR.bothend_id,"
                        . "P1.port_code port_code_s,P1.port_name port_s ,"
                        . "P2.port_code port_code_e,P2.port_name port_e, SR.middle_id,"
                         . "group_concat(distinct P3.port_code  order by SM.sequence separator '_') port_middle_code,"
                        . "group_concat(distinct P3.port_name  order by SM.sequence separator '_') port_middle")
                ->where('OF.order_num',$order_num)->find();
//$this->_p($data);exit;
        return $data;
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
       // var_dump($loginname);exit;
        $change_time = time();  
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
    
    
      //order_ship表 贮存对应的航线信息$loadPort,$departurePort
    public function orderShip($order_num,$portArr,$portCodeArr) {
        //查询是否已经录入航线信息了
        $res1 =Db::name('order_ship')->where('order_id',$order_num)->find();
   
        if(empty($res1)){
        $sqlArr=[]; 
        $num =count($portArr)-1 ; //生成几行转车
        for($i=0;$i<$num;$i++){
            $field_status ='R_R_R_R_R_R_R';
            //第一条字段状态前面几个可写
            if($i==0){
                $field_status ='W_W_W_W_W_R_R';
            }
            $sqlArr[$i]= array('order_id'=>$order_num,
                'loadPort'=>$portCodeArr[$i],
                'loadPortName'=>$portArr[$i],
                'departurePort'=>$portCodeArr[$i+1], 
                'departurePortName'=>$portArr[$i+1],
                'sequence'=>($i+1),
                'field_status'=>$field_status );
        }
        $res = Db::name('order_ship')->insertAll($sqlArr);
        return $res ? true :false ;
        }
        return $res1 ? true :false ;
    }
    // 读取order_ship的数据
    public function orderShipInput($order_num) {
        $res= Db::name('order_ship')->where('order_id',$order_num)->select();
        foreach ($res as $key => $value) {
            $res[$key]['field_status'] =  explode( '_',$value['field_status']);
        }
        return $res;
    }  
    // 添加order_ship的数据
    public function toOrderShip($data,$order_id) {
       // $this->_v($data);exit;
        $response =[];
        unset($data['order_id'],$data['loadPort'],$data['loadPortName'],$data['departurePort'],$data['departurePortName']); //删除固定值
        $tmpArr=[]; $sqlArr=[]; //临时数组 ,最终数组
        $dataArr = array_keys($data); 
        $dataArr[]='mtime';
        $num =count($data['ship_name']);// 几行记录
        $mtime =  $mtime = date('Y-m-d h:i:s'); //修改时间
        $orderStatus=[]; //贮存状态
        for($i=0;$i<$num;$i++){
            $tmpArr =  array_column($data, $i);
            //排除空的
            $arrNum =count(array_filter($tmpArr));
            //$data["field_status"]的去除下划线_
            $string = preg_replace('/_/','',$tmpArr[8]);
            $W =strrpos($string,'W');
          // var_dump(array_filter($tmpArr),$arrNum,$string, $W);exit;
            $W= ($W!==false) ?$W :-1; //如果找不到W说明填满8个数据
      
            if(!(($W+3)== $arrNum||$arrNum==9)){ //判断传回来的数据的个数是否一致或者又不是填满状态
                $response['fail'][]="回来的数据的个数是不一致又不是填满状态";
                return $response;
            }
            
            switch ($W){
            case 4:
            $field_status = 'R_R_R_R_R_W_R';
            $orderStatus[] =506;//录入配船信息完毕
            break;
            case 5:
            $field_status = 'R_R_R_R_R_R_W';
            $orderStatus[] =507;//录入到港信息完毕    
            break;
            case 6:
            $field_status = 'R_R_R_R_R_R_R';//这一行数据填写完成同时 下一行前五个改成可写W
            if(($i+1)<$num){
                $goto =$i+1;
                $orderStatus[] =515;//录入卸船信息完毕转下一行
            }elseif ($i==$num) { //最后一航走完
                $orderStatus[] =800;//录入卸船信息完毕转代收钱     
            } 
            break;
            case -1:
            continue 2; //跳出此循环
            default:
            $response['fail'][]="找不到W，存在异常"; 
            return $response;
            }
            $tmpArr[8] =$field_status;
            $tmpArr[9] = $mtime;
           //$this->_p($dataArr);  $this->_p($tmpArr);exit;
            //过滤空的数据
            $sqlArr[$i] = array_filter(array_combine($dataArr, $tmpArr));
        }
        if(isset($goto)){
            $sqlArr[$goto]=array('sequence'=>$goto+1,'field_status'=>'W_W_W_W_W_R_R');
        }
       
        //最后的更新状态码数
        $sequence =max(array_column($sqlArr,'sequence'));
        $orderStatusMax =  max($orderStatus)+ (($sequence-1)*10);
          //更新数据库
        //$this->_p($sqlArr);exit;
        foreach($sqlArr as $key =>$sqldata){
            $res =Db::name('order_ship')->where('order_id',$order_id)->where('sequence',$sqldata['sequence'])
                    ->update($sqldata);
            $res ? $response['success'][]="{$key}条记录修改状态成功" :$response['fail'][]= "{$key}条记录修改状态失败";
        }
        $response['orderStatus']=$orderStatusMax;$response['sequence']=$sequence;
        return $response;
       
    }
    
    
    
}