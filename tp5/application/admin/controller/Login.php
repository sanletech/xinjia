<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\model\User;
use think\Session;
class Login extends Controller
{
    //渲染登录界面
    public function index()
    {  
        //调用是否已经登录方法
        //$this->alreadylogin();
        return $this->view->fetch('login/login');
       
    }
        //验证用户登录身份
    public function check()
    { 
        
       //设置status
        $status=0;
       // 获取表单提交的数据，并报错在变量中
        $data= $this->request->param();
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
        }elseif($user->password !== $passWord){
            //设置密码提示信息
             $message = '密码不正确';
        }else {
         //用户通过验证 修改返回信息
            $status = 1;
            $message = '验证通过请点击确定进入后台';
            // 更新表中登录的次数与最后登录时间
           $user ->save(['logintime'=>date('Y-m-d H:i:s')]);
            //将用户登录的信息保存到session中,供其他控制器使用
            Session::set('user_id',$user['id']);
            Session::set('user_info',$user['loginname']);
        }
        return json(array('status'=>$status,'message'=>$message));
        
       
       
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
