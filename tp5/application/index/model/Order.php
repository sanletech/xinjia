<?php

namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
class Order extends Model
{
    
   
    //前台页面展示门到门的价格表
    public function  price_sum($member_code,$start_add='',$end_add='',$load_time=''){
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        $nowtime= date('y-m-d h:i:s');//要设置船期
        
        $price_list = Db::name('seaprice')->alias('SP')
                ->join('hl_ship_route SR','SR.id =SP.route_id')//中间港口
                ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id')//起始,目的港口
                ->join('hl_car_line CLR','CLR.port_id =SB.sl_start') //装货路线
                ->join('hl_car_line CLS','CLS.port_id =SB.sl_end') //送货路线
                ->join('hl_carprice CPR',"CPR.cl_id = CLR.id and CPR.variable='r'") //拖车装货费
                ->join('hl_carprice CPS',"CPS.cl_id = CLS.id and CPS.variable='s'")//拖车送货费
                ->join('hl_price_incidental PIR',"PIR.ship_id=SP.ship_id and PIR.type ='r' and CLR.port_id = PIR.port_code") //起运港口杂费
                ->join('hl_price_incidental PIS',"PIS.ship_id=SP.ship_id and PIS.type ='s' and CLS.port_id = PIS.port_code") //目的港口杂费
                ->join('hl_member_profit MP',"MP.ship_id=SP.ship_id" ) //不同客户对应不同船公司的利润
                ->join('hl_shipcompany SC','SC.id = SP.ship_id')
                ->join('hl_boat BA','BA.boat_code =SP.boat_code')
                ->join('hl_port PR','PR.port_code = SB.sl_start')//起始港口
                ->join('hl_port PS','PS.port_code = SB.sl_end')//目的港口
                ->field('SP.id sea_id, CPR.id rid,CPS.id sid,PIR.id pir_id,PIS.id pis_id,SP.route_id,SC.ship_short_name,SP.shipping_date,'
                        . ' SP.cutoff_date,SP.boat_code,BA.boat_name,SP.sea_limitation,SP.ETA,SP.EDD,SP.generalize,SP.mtime,'
                        . ' SP.ship_id,SB.sl_start,SB.sl_end,'
                        . ' PR.port_name r_port_name,PS.port_name s_port_name,CLR.address_name r_add,CLS.address_name s_add,'
                        . ' (select SP.price_20GP + PIR.20GP + CPR.price_20GP + PIS.20GP + CPS.price_20GP + MP.money ) as price_20GP,'
                        . ' (select SP.price_40HQ + PIR.40HQ + CPR.price_40HQ + PIS.40HQ + CPS.price_40HQ + MP.money ) as price_40HQ')
                ->where('MP.member_code',$member_code)
                ->group('SP.id,CLR.id,CLS.id,CPR.id,CPS.id,PIR.id,PIS.id,MP.id,SC.id,PR.id,PS.id')
                ->buildSql();
//        var_dump($price_list);
//        if($load_time){
//            $price_list = Db::table($price_list.' E')->where('E.cutoff_date','>',$load_time)->buildSql();
//        }
        if($start_add){
            $price_list = Db::table($price_list.' F')->where('F.r_add','like',"%$start_add%")->buildSql();
        }
        if($end_add){
            $price_list = Db::table($price_list.' G')->where('G.s_add','like',"%$end_add%")->buildSql();
        }
        return $price_list;
             
    }
    //展示已经选择好的价格信息
    public function orderBook($sea_id,$r_car_id,$s_car_id,$container_size,$member_code,$pir_id,$pis_id) {
        if(!($container_size =='20GP'||$container_size =='40HQ')){
            return '参数错误';
        } 
        $list= $this->price_sum($member_code);
        //航线信息
        $res = Db::table($list.' A')
            ->where('sea_id',$sea_id)
            ->where('rid',$r_car_id)
            ->where('sid',$s_car_id)
            ->field('A.sea_id, A.rid, A.sid,A.pir_id,A.pis_id,A.ship_id, A.ship_short_name, A.shipping_date,'
                . 'A.boat_code, A.boat_name, A.sea_limitation,A.ETA,'
                . 'A.sl_start,A.r_port_name,A.r_add ,A.sl_end,A.s_port_name,A.s_add ,'
                . 'price_'.$container_size.' as price' )->find();
        // 将集装箱字的尺寸添加到数组中
        $res['container_size']=$container_size;
        
        return $res;            
    }
    //添加收/发货人的信息
    public function linkman($data)
    {
        $link_name = $data['link_name'];
        $phone = $data['phone'];
        $company = $data['company'];
        $add = $data['add'];
        $member_code =Session::get('member_code');
        $time = date("Y-m-d H:i:s"); 
        $sql= "insert into hl_linkman(name ,phone ,company ,address,mtime,member_code) "
         . " values('$link_name','$phone','$company','$add','$time','$member_code')";
        $res =  Db::execute($sql);
        $res ?  $response['success'][]='添加linkman表': $response['fail'][]='添加linkman表';
        return  $response;    
        
    }
    
    //处理客户提交的订单信息
    public function order_data($member_code,$data,$shipper,$consigner,$seaprice_id,$carprice_rid,$carprice_sid,
                 $carprice_r,$carprice_s,$seaprice,$premium,$profit,$cost,$quoted_price,$tax_rate) {
        //添加数据到hl_order_fahter表里
        $mtime =  date("Y-m-d H:i:s"); 
       // $add_id = 1; //前台页面 将收货人 发货人 的联系地址 用ajax处理
        //生成订单编号
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $order_num =  $yCode[intval(date('Y')) - 2018].strtoupper(dechex(date('m'))).date('d').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
       // var_dump($order_num);exit;
        //如果是第三付款则合拼第三方人的姓名电话
        if($data['payer'] =='thirdPayment'){
            $payer = $data['payer_name'].'_'.$data['payer_phone'];
        }  else {
            $payer =$data['payer'];
        }
        if(!isset($data['message_send'])){
            $data['message_send']='n';
        } 
        if(!isset($data['sign_receipt'])){
            $data['sign_receipt']='n';
        } 
        //更新车运表的last_order_time
        $res =Db::name('carprice')->where('id',$carprice_rid )->whereOr('id',$carprice_sid)->update(['last_order_time'=>$mtime]);
        
        $sqldata =['order_num'=>$order_num,'cargo'=>$data['cargo'],'container_size'=>$data['container'],
                'container_sum'=>$data['container_sum'],'weight'=>$data['weight'],
                'cargo_cost'=>$data['cargo_cost'], 'container_type_id'=>$data['container_type'],
                'comment'=>$data['comment'],
                'mtime'=>$mtime,'member_code'=>$member_code,'belong_order'=>0,'state'=>0,'action'=>'下单=>待审核',
                'ctime'=>$mtime,'payer'=>$payer,'payment_method'=>$data['payment_method'],'message_send'=>$data['message_send'],
                'sign_receipt'=>$data['sign_receipt'],'tax_rate'=>$tax_rate,'invoice_id'=>$data['invoice_id'],'shipper'=>$shipper,
                'consigner'=>$consigner,'seaprice_id'=>$seaprice_id,'carprice_rid'=>$carprice_rid,'carprice_sid'=>$carprice_sid,
                'carprice_r'=>$carprice_r,'carprice_s'=>$carprice_s,'seaprice'=>$seaprice,'premium'=>$premium,'profit'=>$profit,
                'cost'=>$cost,'quoted_price'=>$quoted_price,'shipper_id'=>$data['r_id'], 'consigner_id'=>$data['s_id']
            ];
         
        //根据有多少柜号生成多少order_son
         //设置虚拟运单号码 和虚拟柜号
        $date = date("md");
        $track_num = time();$sqlSonData=[];
        for($i=0;$i<count($data['container_sum']);$i++){
            $sqlSonData[] =['order_num'=>$order_num,'track_num'=>$track_num, 
            'container_code' =>$track_num.'d'.$date.'n'.$i,'action'=>'下单=>待审核',
            'state'=>0,    ];  //设置虚拟集装箱编码 等待派车后录入真正的集装箱编码再修改  
        }
        $response=[];
        $res1 =Db::name('order_son')->insert($sqlSonData);
        $res1 ? $response['success'][]='添加order_son表成功':$response['fail'][]='添加order_son表失败';
        $res =Db::name('order_father')->insert($sqldata);
        $res ? $response['success'][]='添加order_father表成功':$response['fail'][]='添加order_father表失败';
        return $response;
    }
    
    //保存订单的航线价格信息
    public function book_line($sea_id,$rid,$sid) {
      
        $sql ="select id from hl_book_line where seaprice_id ='$sea_id' and "
                . "s_pricecar_id ='$sid' and r_pricecar_id ='$rid'";
        $sql2 = "insert into hl_book_line(seaprice_id,s_pricecar_id,"
                . "r_pricecar_id) values ('$sea_id','$sid','$rid')";
        $res = Db::query($sql);
        if(!$res){
            $res2 = Db::execute($sql2); 
            $res = Db::query($sql);
        }
        return $res['0']['id'];
    }
    
    //保存发票信息
    public function invoice($data) {
        $mtime = date('y-m-d h:i:s');
        $title = $data['invoice_title'];
        $taxpayer_id =$data['taxpayer_id'];
        $registered_address =$data['registered_address'];
        $registered_phone = $data['registered_phone'];
        $deposit_bank = $data['deposit_bank'];
        $bank_account = $data['bank_account'];
        $member_code = $data['member_code'];
        $sql ="insert into hl_invoice (invoice_title,taxpayer_id,registered_address ,"
                . "registered_phone,deposit_bank,bank_account,member_code,mtime)  "
                . " values ('$title','$taxpayer_id' ,'$registered_address' ,"
                . "'$registered_phone' ,'$deposit_bank' ,'$bank_account','$member_code','$mtime')";
        $res = Db::execute($sql);
        $res ? $response['success'][]='添加invoice表':$response['fail'][]='添加invoice表';
        return $response;
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
    
    public function price_port($start_add='',$end_add='',$ship_id='') {
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        $nowtime= date('y-m-d h:i:s');//要设置船期
        
        $price_list = Db::name('seaprice')->alias('SP')
                ->join('hl_ship_route SR','SR.id =SP.route_id')//中间港口
                ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id')//起始,目的港口
//                ->join('hl_car_line CLR','CLR.port_id =SB.sl_start') //装货路线
//                ->join('hl_car_line CLS','CLS.port_id =SB.sl_end') //送货路线
//                ->join('hl_carprice CPR',"CPR.cl_id = CLR.id and CPR.variable='r'") //拖车装货费
//                ->join('hl_carprice CPS',"CPS.cl_id = CLS.id and CPS.variable='s'")//拖车送货费
//                ->join('hl_price_incidental PIR',"PIR.ship_id=SP.ship_id and PIR.type ='r' and CLR.port_id = PIR.port_code") //起运港口杂费
//                ->join('hl_price_incidental PIS',"PIS.ship_id=SP.ship_id and PIS.type ='s' and CLS.port_id = PIS.port_code") //目的港口杂费
//                ->join('hl_member_profit MP',"MP.ship_id=SP.ship_id" ) //不同客户对应不同船公司的利润
                ->join('hl_shipcompany SC','SC.id = SP.ship_id')
                ->join('hl_boat BA','BA.boat_code =SP.boat_code')
                ->join('hl_port PR','PR.port_code = SB.sl_start')//起始港口
                ->join('hl_port PS','PS.port_code = SB.sl_end')//目的港口
                ->field('SP.*,SC.ship_short_name,BA.boat_name,'
                        . 'PR.port_name r_port_name,PS.port_name s_port_name,'
                        . 'PR.port_code r_port_code,PS.port_code s_port_code,'
                        . 'SR.middle_id')
                ->group('SP.id,PR.id,PS.id')
                ->buildSql();
//        var_dump($price_list);
//        if($load_time){
//            $price_list = Db::table($price_list.' E')->where('E.cutoff_date','>',$load_time)->buildSql();
//        }
        if($start_add){
            $price_list = Db::table($price_list.' F')->where('F.r_port_name','like',"%$start_add%")->buildSql();
        }
        if($end_add){
            $price_list = Db::table($price_list.' G')->where('G.s_port_name','like',"%$end_add%")->buildSql();
        }
        return $price_list;
        
        
    }
    
    
    public function portBook($member_code,$sea_id,$container_size){
        if(!($container_size =='20GP'||$container_size =='40HQ')){
            return '参数错误';
        } 
        $list= $this->price_port();
        //航线信息
        $res = Db::table($list.' A')
            ->where('id',$sea_id)
            ->field('A.*')->find();
        // 将集装箱字的尺寸添加到数组中
        $res['container_size']=$container_size;
        if($container_size =='20GP'){
           $res['price'] = $res['price_20GP'];
           unset($res['price_20GP'],$res['price_40HQ']);
        }  else {
            $res['price'] = $res['price_40HQ'];
            unset($res['price_20GP'],$res['price_40HQ']);
        } 
        
        //查询有没有活动优惠
        $nowtime = date('y-m-d h:i:s');
        $discount_special = Db::name('discount_special')->where([
                        'discount_start'=>['ELT',$nowtime],
                        'discount_end'=>['EGT',$nowtime],
                        'status'=>['NEQ',0],
                        'ship_id'=>$res['ship_id'],
                       ])->field("{$container_size}_promotion promotion,"
                       . "promotion_title,id promotion_id")->find();
                       
        //查询出客户对应的月结,到港付,现款的优惠
        $discount =Db::name('discount_normal')->where([
                    'member_code'=>$member_code,
                    'ship_id'=>$res['ship_id']
                     ])
                ->field("{$container_size}_installment installment,"
                . "{$container_size}_month month,"
                . "{$container_size}_cash cash")
                ->find();
        $discount['special']=$discount_special;
      
//        $this->_p($res);exit;
        return array($res,$discount);            
        
    }
    
    //根据用户code 和sea_id海运价格id，柜子大小,付款方式 返回相应的单个柜子优惠价格
    public function dicountPrice($member_code,$sea_id,$container_size,$payment_method,$special='') {
        //根据sea_id 查询出对应的船公司的Id
        $ship_id =Db::name('seaprice')->where('id',$sea_id)->value('ship_id');
        if($special){
            $mtime= date('y-m-d h:i:s');
            $price =Db::name('discount_special')->where([
                        'discount_start'=>['ELT',$mtime],
                        'discount_end'=>['EGT',$mtime],
                        'ship_id'=>$ship_id,
                        'id'=>$special
                    ])->value($container_size.'_promotion');
        }else{
            $price =Db::name('discount_normal')->where([
                'member_code'=>$member_code,
                'ship_id'=>$ship_id,
            ])->value($container_size.'_'.$payment_method);
        }
        
        return $price;
        
    }
    //处理装货费用和送货费用  订单号码和 装货 送货的信息
    public function truckage($order_num,$truckageData){
        $container_code = time();   // 设置虚拟的柜号
        $insertR = $truckageData['r']; 
        $kr = array_keys($insertR);
//        $this->_p($insertR);$this->_p($kr);exit;
        $insertS = $truckageData['s'];
        $ks = array_keys($insertS);
//           $this->_p($insertS);$this->_p($ks);exit;
        $response =[];
        foreach ($insertR['num'] as $i => $v) {
            $tmp = array_combine($kr,array_column($insertR,$i));
            $tmp['order_num'] = $order_num;  $tmp['type']='r';
            for($k=0; $k<$tmp['num'];$k++){
                $tmp['container_code']=$container_code.$i.$k;
                $res =Db::name('order_truckage')->insert($tmp);
                $res ? $response['success'][]=$tmp['container_code'].'添加成功':$response['fail'][]=$tmp['container_code'].'添加失败';
            }
        }
          foreach ($insertS['num'] as $x => $value) {
            $temporary = array_combine($ks,array_column($insertS,$x));
            $temporary['order_num'] = $order_num;  $temporary['type']='s';
            ++$i;
            for($k=0; $k<$temporary['num'];$k++){
                $temporary['container_code']=$container_code.$i.$k;
                $res =Db::name('order_truckage')->insert($temporary);
                $res ? $response['success'][]= $temporary['container_code'].'添加成功':$response['fail'][]= $temporary['container_code'].'添加失败';
            }
        }
        if(array_key_exists('fail', $response)){
            return FALSE;
        }else{
            $priceR =Db::name('order_truckage')->where(['order_num'=>$order_num,'type'=>'r'])->sum('car_price');
            $priceS =Db::name('order_truckage')->where(['order_num'=>$order_num,'type'=>'S'])->sum('car_price');
            return array('carprice_r'=>$priceR,'carprice_s'=>$priceS);
        }
    }
    
    
    
}
