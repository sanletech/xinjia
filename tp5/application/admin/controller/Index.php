<?php
namespace app\admin\controller;
use think\Db;
use app\admin\common\Base;

class Index extends Base
{
    public function index()
    {
        //调用是否未登录方法
       // $this->islogin();
      
        return $this->view->fetch('index/index'); 
       
    }
        public function welcome()
    {
        //调用是否未登录方法
       // $this->islogin();
      
        return $this->view->fetch('index/welcome'); 
       
    }
    
}
