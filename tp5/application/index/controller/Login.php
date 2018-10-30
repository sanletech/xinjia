<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use app\index\model\Login as LoginM;
use think\Session;
use Aliyun\SmS as AliyunM;
class Login extends Controller
{
    //登陆
    public function login()
    {
       return $this->view->fetch('login/login');
    }
    
        //注册
    public function register()
    {
        return $this->view->fetch('login/register');
       
    }
        //验证用户登陆
    public function check_login()
    { 
       //设置status
        $status=0;
       // 获取表单提交的数据，并报错在变量中
        $data= $this->request->param();
        $loginName = $data['loginname'];
        $passWord = md5($data['password']);
        //在member 表中进行查询
        $member =Db::name('member')->where('member_code|phone',$loginName)->field('password,member_code')->find();
  
        //将用户名与密码分开验证
        //如果没有查询到该用户
        if(is_null($member)){
            //设置返回信息
           $message = '用户名不正确';
        }elseif($member['password'] != $passWord){
            //设置密码提示信息
             $message = '密码不正确';
        }else {    
         //用户通过验证 修改返回信息
            $status = 1;
            $message = '验证通过请点击确定进入后台';
            // 更新表中登录的次数与最后登录时间
            $res =Db::name('member')->where('member_code',$member['member_code'])->update(['logintime'=>date('Y-m-d H:i:s')]);
          
            //将用户登录的信息保存到session中,供其他控制器使用
            Session::set('member_code',$member['member_code']);
           // Session::set('user_info',$user['loginname']);
        }
        return array('status'=>$status,'message'=>$message);
    }
    
    //阿里云短信
    public function ali_sms(){
        $data = $this->request->only('phone');
        $phone =$data['phone'];
        //查询同一条手机号的发送时间是否超过五分钟
        $ctime = date('y-m-d H:i:s');
        $again_time = date('y-m-d H:i:s',strtotime("$ctime -2min"));
        $again = Db::name('ali_sms')->where('phone',$phone)->whereTime('ctime','<',$again_time)->find();
        if($again){
            $response= ['message'=>'2分钟后再发送','status'=>0,'code'=>''];
            return json($response);
        }
        $sms = new AliyunM;
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
        $response['code']= md5($response['code']);
        return json($response);
    }


    public function check_register() {
        $data = input('post.');
//         var_dump($data);exit;
//         判断验证码是否正确
        $code = $data['code'];
        $phone = $data['phone'];
        $res_code = Db::name('ali_sms')->where('phone',$phone)
                ->order('ctime desc')->limit(1)->value('code');
        if($res_code!==$code){
            return array('status'=>0,'message'=>'验证码不正确');
        }
        // 数据验证
        $result = $this->validate($data,'Login');
        //  var_dump($result);exit;
        if (true !== $result) {
            return array('status'=>0,'message'=>$result);
        }
        $phone =$data['phone'];
        $member = new LoginM;
        // 数据保存
        $res =$member ->register($data);
        if($res){
            return array('status'=>0,'message'=>'注册成功');
        } else {
            return array('status'=>0,'message'=>'注册失败');
        }
    }
    
    
        //退出登录
    public function logout()
    {
      //删除当前用户的session 值
      Session::delete('member_code');
      Session::delete('user_info');
      //执行成功,返回登录页面
      $this->success('注销成功,正在返回首页','index/index/index');
    }

    //忘记密码
    public function forget_pwd(){
        return $this->view->fetch('login/forget_pwd');
    }
    //修改密码
    public function new_pwd(){
        $data = $this->request->param();
               $res_code = Db::name('ali_sms')->where('phone',$phone)
                ->order('ctime desc')->limit(1)->value('code');
        if($res_code!==$code){
            return array('status'=>0,'message'=>'验证码不正确');
        }
        $map
        
    }
    
}
