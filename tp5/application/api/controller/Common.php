<?php

namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Session;

class Common extends Controller {
    
    protected  $member_code;
    
    protected function _initialize()
    {  
       
        $this->member_code =Session::get('member_code');
        // var_dump($_SESSION);
        if(is_null($this->member_code)){
            $this->notlogin();
        }
    }
    
    //登陆检查
    public  function notlogin()
    {
        //如果登录常量为nll，表示没有登录
        if(is_null($this->member_code)){
            return json(array('status'=>0,'message'=>'未登录，无权访问'));
        }   
        return json(array('status'=>1,'message'=>'success'));
    }
    
    //重复登陆检查
    public function alreadylogin()
    {
        //如果登录常量为nll，表示没有登录
        if(!is_null($this->member_code)){
            return json(array('status'=>0,'message'=>'已登录，不可重复登录'));
        }   
       
    }
    // 登出
    public  function logout()
    {
        $name = Session::pull('name','wechat');
        //清空wechat下的值
        Session::clear('wechat');
        if( is_null(Session::get('member_code')) );
        {
            return json(array('status'=>1,'message'=>'logout'.$name));
        }        
       
    
       
    }

     //阿里云短信
    public function ali_sms($phone){
        //查询同一条手机号的发送时间是否超过五分钟
        $ctime = date('y-m-d H:i:s');
        $again_time = date('y-m-d H:i:s',strtotime("$ctime -2min"));
        $again = Db::name('ali_sms')->where('phone',$phone)->whereTime('ctime','<',$again_time)->find();
        if($again){
            return json(['message'=>'2分钟后再发送','status'=>0]);
        }
        $sms = new \Aliyun\SmS;
        //短信发送
        $code = rand (1000, 9999);
        $status = $sms->send_verify($phone,$code);
        $response=[];
        if (!$status) {
            $response= ['message'=>$sms->error,'status'=>0];
        }else{
            $response= ['message'=>'发送短信成功','status'=>1];
             //存贮发送时间，验证码,手机号到数据库里
            $res=Db::name('ali_sms')->insert(['phone'=>$phone,'code'=>$code,'ctime'=>$ctime]);
        }
        return json($response);
    }
    
    public function  data() {
        
        $data = Db::name('seaprice')->select();
        return json($data);
    }
}
