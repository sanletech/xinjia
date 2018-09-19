<?php

namespace app\index\model;
use think\Model;
use think\Db;
class Login extends Model
{
    public function register($data) {
        $member_name=$data['membername'];
        $company =$data['company'];
        $password =md5($data['password']);
        $create_time = date('y-m-d');
        $phone = $data['phone'];//手机号码
        $member_code = $data['member_code'];//登录帐号

        if(!empty($company)){
            $type ='company';
        }else{
            $type ='person';
        }        
        $sql= "insert into hl_member (name,company,password,create_time,"
                . "phone,member_code,type) values"
                . "('$member_name','$company','$password','$create_time',"
                . "'$phone','$member_code','$type')";
       // var_dump($sql);exit;
        $res =Db::execute($sql);
        //同时将推荐人和业务员绑定
        if(array_key_exists('sales',$data)){
            $salesName =$data['sales'];
            if(!empty($salesName)){
                //根据业务姓名查询其业务编号 如果没有就填0
               // $sql= "select sales_code from hl_salesman where salesname ='$salesName'";
                $sales_code =Db::name('user')->where('user_name',$salesName)->where('type','sales')->value('user_code');
                $sales_code = $sales_code ?$sales_code :0;
            }
           
        } else {
            $sales_code=0; $salesName='nobody';
        }
        $sales_code = $sales_code ?$sales_code :0;
        //将业务对应客户的关系插入
        $data =['sales_code'=>$sales_code ,'sales_name'=>$salesName ,'member_code'=>$member_code ,'member_name'=>$member_name];
        $res = Db::name('sales_member')->insert($data);
         
        $this->memberProfit($member_code);
        $this->memberDiscount($member_code);
        if ($res){
            $status= true;
        } else {
             $status= false;
        }
        return $status;
    }
    //设置客户的利润
    public function memberProfit($member_code) {
            $ship_name =Db::name('shipcompany')->column('id');
            $data=[]; $mtime =date('y-m-d h:i:s');
            foreach ($ship_name as $key=>$value) {
                $data[$key]['member_code']=$member_code;
                $data[$key]['ship_id']= $value;
                $data[$key]['money']= 200;
                $data[$key]['ctime']= $mtime;
            }
//            $this->_v($data);exit;
            //同时默认设置客户的利润价格为200
            $res = Db::name('member_profit')->insertAll($data);
             //var_dump($res);exit;
    }
    
    //设置客户的在线支付优惠
    public function memberDiscount($member_code) {
            $ship_name =Db::name('shipcompany')->column('id');
            $data=[]; $mtime =date('y-m-d h:i:s');
            foreach ($ship_name as $key=>$value) {
                $data[$key]['member_code']=$member_code;
                $data[$key]['ship_id']= $value;
                $data[$key]['40HQ']= 200;
                $data[$key]['20GP']= 100;
                $data[$key]['mtime']= $mtime;
            }
//            $this->_v($data);exit;
            //同时默认设置客户的初始在线支付优惠价格
            $res = Db::name('member_profit')->insertAll($data);
             //var_dump($res);exit;
    }
    
}
