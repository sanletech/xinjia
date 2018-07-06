<?php
namespace app\index\controller;
use think\Db;
use app\index\common\Base;
class Login extends Base 
{
    //登陆
    public function login()
    {
       return $this->view->fetch('login/login');
    }
    //注册
    public function logout()
    {
       return $this->view->fetch('login/logout');
    }
    
        //渲染登录界面
    public function index()
    {  
        //调用是否已经登录方法
        //$this->alreadylogin();
        return $this->view->fetch('login/login');
       
    }
        //验证用户登录身份
    public function check(Request $request)
    { 
        
       //设置status
        $status=0;
       // 获取表单提交的数据，并报错在变量中
        $data=$request->param();
     
        $loginName = $data['loginname'];
        $passWord = md5($data['password']);
        //在user 表中进行查询
        $map = array('loginname'=> $loginName);
        $user = User::get($map);
        //将用户名与密码分开验证
      
        //如果没有查询到该用户
        if(is_null($user)){
            //设置返回信息
           $message = '用户名不正确';
        }elseif($user->password != $passWord){
            //设置密码提示信息
             $message = '密码不正确';
        }else {
         //用户通过验证 修改返回信息
            $status = 1;
            $message = '验证通过请点击确定进入后台';
            // 更新表中登录的次数与最后登录时间
     
           $user ->save(['logintime'=>time()]);
            
            //将用户登录的信息保存到session中,供其他控制器使用
            Session::set('user_id',$user['id']);
            Session::set('user_info',$user['loginname']);
        }
       
        $arr=  array('status'=>$status,'message'=>$message);
           
        return $arr;
        
       
       
    }
        //退出登录
    public function logout()
    {
      //删除当前用户的session 值
      Session::delete('user_id');
      Session::delete('user_info');
      //执行成功,返回登录页面
      $this->success('注销成功,正在返回首页','login/index');
    }
    
}
