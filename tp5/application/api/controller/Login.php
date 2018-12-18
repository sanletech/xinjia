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
    
     //用户注册 或者手机号码绑定 与 weixin_code 绑定
    public function wechatRegister ($wechat_code,$phone,$code,$password,$repassword='') {
         //20分钟内有效
        $valid_time  = array(date('Y-m-d H:i:s',strtotime('-20min')),date('Y-m-d H:i:s'));
        $res_code = Db::name('ali_sms')->where('phone',$phone)
                ->where('ctime','between time',$valid_time)
                ->order('ctime desc')->column('code');
        if(!in_array($code,$res_code)){
            return json(array('status'=>0,'message'=>'验证码不正确'));
        }
        //获取微信openId
        $wechat_openid = $this->wechatOpenid($wechat_code);
        //查询手机号码的信息
        $member_info  = Db::name('member')->where('phone',$phone)
                ->field('id','name','password','member_code')->find();
        //如果为空就是登录
        if(is_null($repassword)){
            if($member_info){
                //存在就比对密码是否正确
                if(md5($password)!== $member_info['password'] ){
                    return json(array('status'=>0,'message'=>'密码不正确'));
                }       
                $res_login = Db::name('member')->where('id',$member_info['id'])
                        ->update(['wechat_openid'=>$wechat_openid,
                            'logintime'=>$this->mtime]);
                $member_code = $member_info['member_code'];
                $res_login ? $message ='success_login':$message ='fail_login';
            }  else {
                //不存在说明没有注册过
                return json(array('status'=>0,'message'=>'Not_registered'));
            }
        }
        //不为空就是注册
        if($repassword){
                if($repassword !== $password){
                    return json(array('status'=>0,'message'=>'两次密码不正确'));
                }
                if($member_info){
                    return json(array('status'=>0,'message'=>'已经注册过'));
                }
                $IDCode = new \app\index\controller\IDCode();
                //查询用户表最大的id 生成零时客户member_code
                $id =Db::name('member')->max('id')+1;
                $member_code = $IDCode->create($id, 'zh');
                $map['member_code'] = $member_code; //唯一帐号
                $map['wechat_openid'] = $wechat_openid; 
                $map['create_time'] = $this->mtime; 
                $map['password'] = md5($password); 
                $map['type'] = 'person'; 
                $res_register = Db::name('member')->insert($map);
                $res_register ? $message = 'success_register':$message = 'fail_register';
        }
        //操作成功后，写入session 信息将用户
        if(strstr($message,'_',true)=='success'){ 
            Session::set('member_code',$member_code);
            //注册设置默认利润
            if($message=='success_register'){
                $member_profit =  new \app\index\model\Login();
                $member_profit->member_profit($member_code);
            } 
        }
        return $res_phone ? array('status'=>1,'message'=>$message.'success','session_id'=>session_id()): array('status'=>0,'message'=>$message.'fail');
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
