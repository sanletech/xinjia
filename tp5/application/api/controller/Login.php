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
        //验证无误 就写入 session 更新登录时间
        Session::set('member_code',$member['member_code']);
        Session::set('name',$member['name']);
        $mtime =  date('Y-m-d H:i:s');
        $res = Db::name('member')->where('phone',$account)
               ->update(['logintime'=>$mtime]);
        if(empty($member['wechat_openid'])){
            return json(array('status'=>1,'message'=>'unboundWechat','session_id'=>session_id()));     
        }  else {
            return json(array('status'=>1,'message'=>'登录成功','session_id'=>session_id()));     
        }
    }
    
    public function  checkWechat($code) {
        //接受到随机code 验证 oppenID 是否存在
        $wechat = new \app\api\controller\Wechat();
        $openID =$wechat->wechatOpenid($code);
        if(!array_key_exists('openID', $openID)){
            return array('status'=>0,'message'=>$openID['error']); //错误信息
        }
        //验证数据存在
        $member_data = Db::name('member')
                ->where('wechat_openid',$openID['openID'])
                ->order('logintime','ASC')->limit(1)
                ->field('member_code,name')->find();
        if($member_data){
              //验证无误 就写入 session 更新登录时间
            Session::set('member_code',$member_data['member_code']);
            Session::set('name',$member_data['name']);
            $mtime =  date('Y-m-d H:i:s');
            $res = Db::name('member')->where('phone',$account)
                    ->update(['logintime'=>$mtime]);
            return json(array('status'=>1,'message'=>'登录成功','session_id'=>session_id()));     
        }  else {
            return json(array('status'=>1,'message'=>'no_account_exists','session_id'=>'')); 
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
