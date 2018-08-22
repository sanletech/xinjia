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
                $sales_code =Db::name('salesman')->where('salesname',$salesName)->value('sales_code');
                $sales_code ?$sales_code :0;
                $data =['sales_code'=>$sales_code ,'sales_name'=>$salesName ,'member_code'=>$member_code ,'member_name'=>$member_name];
                $res = Db::name('sales_member')->insert($data);
                //同时默认设置客户的利润价格为200
                $data2 =['member_code'=>$sales_code ];
                $res2 = Db::name('member_profit')->insert($data2);
            }
        }
        
        ;
        if ($res){
            $status= true;
        } else {
             $status= false;
        }
        return $status;
    }
    
}
