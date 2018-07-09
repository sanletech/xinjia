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
        $create_time =time();
        $phone = $data['phone'];
        $member_code = $data['member_code'];
                
        $sql= "insert into hl_member (membername,company,password,create_time,"
                . "phone,member_code) values"
                . "('$member_name','$company','$password','$create_time',"
                . "'$phone','$member_code')";
       // var_dump($sql);exit;
        $res =Db::execute($sql);
        if ($res){
            $status= true;
        } else {
             $status= false;
        }
        return $status;
    }
    
}
