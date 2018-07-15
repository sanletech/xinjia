<?php

namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
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

   // var_dump($price_sql);exit;
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
    
     //添加收/发货人的信息
    public function linkman($data)
    {
        $link_name = $data['link_name'];
        $phone = $data['phone'];
        $company = $data['company'];
        $add = $data['add'];
        $member_code =Session::get('member_code');
        $time = time();
    $sql= "insert into hl_linkman(name ,phone ,company ,address,mtime,member_code) "
        . " values('$link_name','$phone','$company','$add','$time','$member_code')";

    $res =  Db::execute($sql);
    $res ?  $response['success'][]='添加linkman表': $response['fail'][]='添加linkman表';
    return  $response;    
        
    }
    
    //处理客户提交的订单信息
    public function order_data($data,$book_line_id) {
        //添加数据到hl_order_fahter表里
        $mtime =  time();
        $member_code =Session::get('member_code');
       // var_dump($member_code);exit;
        $add_id = 1; //前台页面 将收货人 发货人 的联系地址 用ajax处理
        $order_num = $mtime.$member_code.rand(100,999);
       // var_dump($order_num);exit;
        $cargo = $data['cargo'];
        $container_size = $data['container_size'];
        $container_num = $data['container_num'];
        $weight = $data['weight'];
        $container_type = $data['container_type'];
//        $sea_id=$data['sea_id']; $rid =$data['rid']; $sid=$data['sid'];
//        $book_line = $this->book_line($sea_id, $rid, $sid);
        $cargo_cost= $data['cargo_cost'];
        $comment =$data['comment'];
        $state = 1;
        $sql ="insert into hl_order_father (order_num,cargo,container_size,"
                . "container_num,weight,cargo_cost,container_type_id"
                . ",comment,mtime,add_id ,book_line_id,member_code,state) "
                . "  values('$order_num','$cargo','$container_size',"
                . "'$container_num','$weight','$cargo_cost','$container_type',"
                . "'$comment','$mtime','$add_id' ,'$book_line_id','$member_code','$state')";
        
        
        //添加数据到 订单补充表 hl_order_comment表里
       
        if(array_key_exists('invoice_id', $data)){
            $invoice_id = 0;
        }else{
            $sql3 ="select id from hl_invoice where member_code = '$member_code' and mtime = (select max(mtime) from hl_invoice ) ";
            //$invoice_id = Db::name('invoice')->field('id')->where('member_code',$member_code)->where('mtime','(select max(mtime) from hl_invoice )');
            $invoice_id = Db::query($sql3);
            $invoice_id = $invoice_id['0']['id'];
        }
        if(array_key_exists('tax_rate', $data)){
            $tax_rate = $data['tax_rate'];
        }  else {
            $tax_rate =0;
        }
        $payer = $data['payer']  ;
        if($payer ==3){
            $payer = $data['payer_name'].'_'.$data['payer_phone'];
        }
        $payment_method = $data['payment_method'];
        if(array_key_exists('message_send', $data)){
            $message_send = 1;
        }  else {
            $message_send = 2;
        }
        if(array_key_exists('sign_receipt', $data)){
            $sign_receipt = 1;
        } else {
            $sign_receipt = 2;
        }
        $sql2 = "insert into hl_order_comment (order_num,payer,payment_method,tax_rate,invoice_id,message_send,sign_receipt,mtime)"
            . "values ('$order_num','$payer','$payment_method','$tax_rate','$invoice_id','$message_send','$sign_receipt','$mtime')";
       // var_dump($sql2);exit;
        $res =Db::execute($sql);
        $res2 =Db::execute($sql2);
        $respones = [];
        $res ? $respones['success'][]='order_father表' : $respones['fail'][]='order_father表';
        $res2 ? $respones['success'][]='order_comment表' : $respones['fail'][]='order_comment表';
        return $respones;
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
        $mtime =time();
        $title = $data['title'];
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
}
