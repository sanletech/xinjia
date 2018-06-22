<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
class Login extends Controller 
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
}
