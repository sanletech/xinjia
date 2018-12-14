<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Session;

class Login extends Controller
{
    
    //登陆
    public function wechatLogin($account,$password) {
   
        $password = md5($password);
        $member =Db::name('member')->where('phone',$account)
                ->field('password,member_code,name,wechat_openid')
                ->find();
        if(!$member){
            return json(array('status'=>0,'message'=>'账号不存在'));   
        }  
        if($password !== $member['password']){
            return json(array('status'=>0,'message'=>'密码错误'));     
        }
        //验证无误 就写入 session
        Session::set('member_code',$member['member_code'],'wechat');
        Session::set('name',$member['name'],'wechat');
        if(empty($member['wechat_openid'])){
            return json(array('status'=>1,'message'=>'unboundWechat','session_id'=>session_id()));     
        }  else {
            return json(array('status'=>1,'message'=>'登录成功','session_id'=>session_id()));     
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
    
  
}
