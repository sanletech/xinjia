<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\session;
//订单模块
class Order extends Model
{
    
    //审核客户提交的订单
    public function order_audit($pages,$state){
        $list =Db::name('order_port')->alias('OP')
            ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
            ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
            ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
            ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
            ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id                                                    //起始港终点港
            ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
            ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
            ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶 
            ->field('OP.*,HM.company,HM.name,SC.ship_short_name,B.boat_code,B.boat_name,P1.port_name s_port_name ,P2.port_name e_port_name')
            ->group('OP.id')
            ->where('OP.status','in',$state)
            ->where('OP.type','door')
            ->paginate($pages);
        return $list;
    } 
    
      //订单的详细信息
    public function orderData($order_num) {
        $list =Db::name('order_port')->alias('OP')
                ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
                ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id                                                    //起始港终点港
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
                ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶
                ->field('OP.*,HM.company,SP.ship_id,SC.ship_short_name')
                ->where('OP.order_num',$order_num)
                ->group('OP.id,SP.id,SR.id,SB.id,SC.id,B.id')
                ->find();
        switch ($list['payment_method'])
       {
            case 'month':
                $list['payment_method']='月结付款';
                break; 
            case 'cash':
                $list['payment_method']='在线支付';
                break; 
            case 'installment':
                $list['payment_method']='到港付款';
                break; 
            case 'pledge':
                $list['payment_method']='压柜付款';
                break; 
        }
        switch ($list['money_status'])
       {
            case '0':
                $list['money_status']='未付款';
                break; 
            case '1':
                $list['money_status']='已付款';
                break; 
        }
        $shipperArr= explode(',',$list['shipper']); 
        $consignerArr= explode(',',$list['consigner']);
//        'containerData'=>$containerData,'carData'=>$carData,'discount'=>$discount,
        return array('list'=>$list ,'shipperArr'=>$shipperArr,'consignerArr'=>$consignerArr);
    }
    
    
        //订单页面list 
    public function listOrder($tol,$limit,$state='100') {
        
        //查询客户的订单编号order_father 查询对应的订单信息
        $listSql = Db::name('order_father')->alias('OF')
                ->join('hl_seaprice SP','SP.id= OF.seaprice_id','left')  //对应的船运价格表
                ->join('hl_ship_route SR','SR.id = SP.route_id','left') //船运路线表
                ->join('hl_sea_bothend SB','SB.sealine_id = SR.bothend_id','left') //船运路线 目的 和起运港表
                ->join('hl_port P1','P1.port_code = SB.sl_start','left') //船运起点港口
                ->join('hl_port P2','P2.port_code = SB.sl_end','left')   //船运目的港口
                ->join('hl_shipcompany SC',"SC.id =SP.ship_id and SC.status='1'",'left')//船公司
                ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶
                ->join('hl_sales_member SM','SM.member_code = OF.member_code','left')  //业务对应客户表
                ->join('hl_user U',"U.user_code= SM.sales_code and U.type='sales'",'left')  //业务表
                ->join('hl_member MB','MB.member_code =OF.member_code' ,'left')//客户表
                //  ->join('hl_linkman LK1','OF.shipper_id=LK1.id','left') //发货人资料
                ->join('hl_linkman LK2','OF.consigner_id=LK2.id','left')//收货人资料
                ->join('hl_order_son OS','OS.order_num=OF.order_num','left')
                ->field('OF.id ,OF.order_num,U.user_name, '
                        . 'SB.sl_start,P1.port_name s_port_name,SB.sl_end,P2.port_name e_port_name,'
                        . " OF.cargo,OF.container_size,OF.container_sum, count(OS.container_code) container_count, "
                        . " OS.track_num,"
                        . " group_concat(distinct OS.container_code order by OS.id separator '_') container_code, "
                        . ' SC.ship_short_name,B.boat_code,B.boat_name,OF.ctime,'
                        . ' SP.shipping_date,SP.sea_limitation,SP.cutoff_date,'
                        . ' LK2.company ')
                ->group('OS.order_num,OS.track_num')->whereOr('OS.state','in',$state)
                ->buildSql();
        //获取总页数
        $count =  Db::table($listSql.' A')->count(); 
        // 查询出当前页数显示的数据
        $list = Db::table($listSql.' B')->order('B.id ,B.ctime desc')->limit($tol,$limit)->select();
        //下单时间和当前时间相差多少天
        foreach ($list as $key => $value) {
            $differ_day =1 + ceil((time()-strtotime($value['ctime']))/60/60/24);
            $list[$key]['differ_day']= $differ_day;
        }
//        $this->_p($list);exit;
      //  var_dump(Db::getLastSql());exit;
        return array($list,$count);
    }
    

   //录入运单号码, 如果只有一个运单号码 就是所有的柜子为一个运单号, 反之 有多少个柜子就录入多少个运单号码
    //订单号 集装箱数量, 运单号, 运单号数量
    public function waybillNum ($order_num,$container_sum,$track_num,$newTrack_num,$track_sum){
//         //输入一个订单 就填充集装箱数量个订单号
//        if($track_sum ==1){
//            $j= $container_sum;
//            $track_num = array_fill(0,$container_sum,$track_num);
//        }else{
//            $j= $track_sum;
//        }
        $response =[];
        if($track_sum !==1){
           return $response['fail']='运单号不是一个';
        }
      
        //插入之前先判断是否已经已经存在了运单号
        $num = Db::name('order_son')->where('order_num',$order_num)->find();
        if(!(empty($num))){
            return $response['fail']='运单号已经输入过了';
        }
        //修改在下单时录入的运单号
        $res =Db::name('order_son')->where('order_num',$order_num)
                ->where('track_sum',$track_sum)
                ->update(['track_num'=>$newTrack_num,'state'=>200,'action'=>'录入运单号>待订车']);    
        $res ? $response['success'][]='添加运单号成功' :$response['fail'][]='添加运单号失败';
       // 修改父订单的状态
        if(!empty($res)){
            $res2 =Db::name('order_father')->where('order_num',$order_num)->update(['state'=>200,'action'=>'录入运单号>待订车']);
            action('OrderProcess/orderRecord', ['order_num'=>$order_num,'status'=>200,'action'=>'录入运单号>待订车'], 'controller');
            $res2 ? $response['success'][]='修改待订舱状态成功' :$response['fail'][]='修改待订舱状态失败';
        }
        return $response;
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
        
        //删除单个的数组的 container_code order_num container_sum  
        unset($data['container_code'],$data['order_num'],$data['container_sum'],$data['track_num']);
        //将$data数组由行转成列
        $arr =[];$arr1 =[]; $arr2=[];
 //     $this->_p($data);exit;
        $arr= array_keys($data);
        $mtime = date('Y-m-d H:i:s');
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
                $res3 = Db::name('order_son')->where('order_num',$order_num)
                        ->where('track_num',$track_num[$k])
                        ->where('container_code',$container_code[$k])
                        ->setField('container_code', $container_id[$k]);
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
        if(array_key_exists('fail',$response)){
            return $response ;
        }else{
            //修改order_state的状态
             $res4 = $this->updateState($order_num,$track_num,$container_id,'300','录完派车信息>待装货');
             $res4?$response['success'][]="修改状态成功" :$response['fail'][]="修改状态失败";
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
       // $this->_p($sqlData);exit;
           // 启动事务
        Db::startTrans();
        try{
        $i=0;  
        foreach ($sqlData as $key => $value) {
            $container_id =$key;
            $loading_time = $value;
            $track_num =$track_numArr[$i];
            $i++;
            $res = Db::name('car_receive')->where('track_num',$track_num)
                    ->where('container_id',$container_id)->update(['loading_time'=>$loading_time]);
//          var_dump(Db::getLastSql());
            $res ? $response['success'][]="添加柜子{$container_id}实际装货时间成功" :$response['fail'][]= "添加柜子{$container_id}:实际装货时间失败";
        }
    
        //修改order_state的状态
        $res1 = $this->updateState($order_num,$track_numArr[0],$container_codeArr,'400','录完实际装货时间>待配船');
        
        $res1 ?$response['success'][]="修改状态成功" :$response['fail'][]="修改状态失败";
       
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
     //  var_dump($response);exit;
        return  $response;
            
    }


    //录入配船信息页面的 展示原有的航线详情信息
    public function cargoPlan($order_num,$track_num){
        $data =Db::name('order_father')->alias('OF')
                //->join('hl_car_listprice CLP_s',"CLP_s.id = BL.s_pricecar_id and  CLP_s.variable='s'",'left')//门到港送货价格表
                //->join('hl_car_listprice CLP_r',"CLP_r.id = BL.r_pricecar_id and  CLP_r.variable='r'",'left')//门到港装货价格表
                ->join('hl_order_son OS','OS.order_num =OF.order_num','left')//中间港口
                ->join('hl_seaprice SP','SP.id= OF.seaprice_id','left')  //对应的船运价格表
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
                ->where('OS.order_num',$order_num)->where('OS.track_num',$track_num)
                ->group('OS.order_num,OS.track_num')->find();
//$this->_p($data);exit;
        return $data;
    }
    
    
    
    
/** 
* updateState
* 在变更father和son订单的状态同时 在order_state 做登记
* @access public 
* @param  $father 父订单的 array(订单order_num数组 state  action) $father=['order_num','state','action']
* @param  $son    子订单的 array(订单order_num container_code数组, state, action) $son=['order_num','container_code','state','action']
*/  
        public function updateState($order_num,$track_num,$container_code=array(),$state,$action) 
    { 
//            var_dump($order_num,$container_code,$state,$action);exit;
        // 取值（当前作用域）
        $loginname= Session::get('user_info','think');
       // var_dump($loginname);exit;
        $change_time = date('Y-m-d H:i:s');
        $response=[];
    
        //先查询提交的柜子是不是对订单的全部柜子,不是则提示需要拆掉或者重新确认
//        $order_num= $son['order_num'];
//        $container_code = $son['container_code'];
//        $state = $son['state'];  $action = $son['action'];
        $sqlContainer = Db::name('order_son')->where('order_num',$order_num)->where('track_num',$track_num)->column('container_code');
  
        $miss = array_diff($sqlContainer,$container_code);
        $more = array_diff($container_code,$sqlContainer);
//        var_dump($miss,$more);exit;
        if( $miss||$more){
            $str1= implode('_', $miss);
            $str2= implode('_', $more);
            $miss ? $response['fail'][]= '柜子申报缺失柜号'.$str1 :'';
            $more ? $response['fail'][]= '柜子申报多报柜号'.$str2 :'';
            return $response;
        }
        //检查同一个订单下的柜子状态是否一样的
        $stateArr = Db::name('order_son')->where('container_code','in',$container_code)->where('track_num',$track_num)->where('order_num',$order_num)->column('state');
        $stateCount = array_count_values($stateArr);
        list($key,$value) = each($stateCount);
        if($value !== count($stateArr)){
            $response['fail']= '订单的状态不一致';
            return $response;
        }
        if($key>$state){
            $response['fail']= '订单的状态已经更新过';
            return $response;
        }
        //同时更新更新状态
             // 启动事务
        Db::startTrans();
        try{
        $mtime = date('Y-m-d H:i:s');
        $res  = Db::name('order_son')->where('container_code','in',$container_code)->where('order_num',$order_num)->update(['state'=>$state,'action'=>$action,'mtime'=>$mtime]);
        //$res2 = Db::name('order_father')->where('order_num',$order_num)->update(['state'=>$state,'action'=>$action,'mtime'=>$mtime]);
        
        action('OrderProcess/orderRecord', ['order_num'=>$order_num,'track_num'=>$track_num,'status'=>$state,'action'=>$action], 'controller');
        
        $res ? $response['success'][]="登记order_son{$order_num}的{$state}{$action}成功" :$response['fail'][]= "登记order_son{$order_num}的{$state}{$action}失败";
        $res2 ? $response['success'][]="登记order_fathe{$order_num}的{$state}{$action}成功" :$response['fail'][]= "登记order_fathe{$order_num}的{$state}{$action}失败";
        //记录是谁何时操作的状态    
        if(!array_key_exists('fail',$response)){
            Db::commit();
        } 
        
        } catch (\Exception $e) {
           // 回滚事务
        Db::rollback();
            $response['fail'][]=$e->getMessage();
        }
        return $response;
    }
    
    
      //order_ship表 贮存对应的航线信息$loadPort,$departurePort
    public function orderShip($order_num,$track_num,$portArr,$portCodeArr) {
        //查询是否已经录入航线信息了
        $res1 =Db::name('order_ship')->where('order_id',$order_num)->where('track_num',$track_num)->find();
   
        if(empty($res1)){
        $sqlArr=[]; 
        $num =count($portArr)-1 ; //生成几行转车
        for($i=0;$i<$num;$i++){
            $field_status ='R_R_R_R_R_R_R';
            //第一条字段状态前面几个可写
            if($i==0){
                $field_status ='W_W_W_W_W_R_R';
            }
            $sqlArr[$i]= array('order_num'=>$order_num,
                'track_num'=>$track_num,
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
    public function orderShipInput($order_num,$track_num) {
        $res= Db::name('order_ship')->where('order_num',$order_num)->where('track_num',$track_num)->select();
        foreach ($res as $key => $value) {
            $res[$key]['field_status'] =  explode( '_',$value['field_status']);
        }
        return $res;
    }  
    // 添加order_ship的数据
    public function toOrderShip($data,$order_num,$track_num){
       // $this->_v($data);exit; 
        $order_id='';
        $response =[];
        unset($data['order_id'],$data['loadPort'],$data['loadPortName'],$data['departurePort'],$data['departurePortName']); //删除固定值
        $tmpArr=[]; $sqlArr=[]; //临时数组 ,最终数组
        $dataArr = array_keys($data); 
        $dataArr[]='mtime';
        $num =count($data['ship_name']);// 几行记录
        $mtime =  $mtime = date('Y-m-d H:i:s'); //修改时间
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
            }elseif (($i+1)==$num) { //最后一航走完
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
       // var_dump(array_column($sqlArr,'sequence'),$orderStatus);
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
