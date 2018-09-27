<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use app\index\model\Login as LoginM;
use think\Session;
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
        $sql = "select password,member_code from hl_member where member_code ='$loginName' or phone ='$loginName'";
        $member = Db::query($sql);
//        var_dump($member);exit;
        //将用户名与密码分开验证
        //如果没有查询到该用户
        if(is_null($member)){
            //设置返回信息
           $message = '用户名不正确';
        }elseif($member[0]['password'] != $passWord){
            //设置密码提示信息
             $message = '密码不正确';
        }else {
         //用户通过验证 修改返回信息
            $status = 1;
            $message = '验证通过请点击确定进入后台';
            // 更新表中登录的次数与最后登录时间
            
           $sql2 ="update hl_member set logintime = "; 
            //将用户登录的信息保存到session中,供其他控制器使用
            Session::set('member_code',$member['0']['member_code']);
           // Session::set('user_info',$user['loginname']);
        }
       
        $arr=  array('status'=>$status,'message'=>$message);
        return $arr;
    }
    
    public function check_register() {
         $data = input('post.');
         //var_dump($data);exit;
        // 数据验证
        $result = $this->validate($data,'Login');
      //  var_dump($result);exit;
        if (true !== $result) {
            return ['message'.':'.$result];
        }
        $member = new LoginM;
        // 数据保存
        $res =$member ->register($data);
        if($res){
            return '[message:注册成功 ]';
        } else {
            return '[message:注册失败 ]';
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
    
}
