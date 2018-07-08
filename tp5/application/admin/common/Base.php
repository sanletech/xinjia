<?php
namespace app\admin\common;
use think\Controller;
use think\Session;

use think\Db;
class Base extends Controller
{
    protected $user_id='';
    protected function _initialize() {
        parent::_initialize();
        //在公共控制器的初始化方法中，创建一个常量用来判断用户是否登录或已经登录
        $this->user_id =  Session::get('user_id');
        //$user_id 为空则没有登录
        if(is_null($this->user_id)){
            $this->notlogin();
        }
       // $this->middle();
        
    }
    
    public function _p($value){
        echo'<pre>';
        print_r($value);
        echo '</pre>';
    }
     public function _v($value){
        echo'<pre>';
        var_dump($value);
        echo '</pre>';
    }

        protected function notlogin()
    {
        //如果登录常量为nll，表示没有登录
      if(is_null($this->user_id)){
          $this->error('未登录，无权访问','login/index');
          
      }   
    }
    
      protected function alreadylogin()
    {
        //如果登录常量为非nll，表示没有登录
      if(!is_null($this->user_id)){
          $this->error('请不要重复登录','index/index');
          
      }   
    }
    
   
      protected function limit()
    {
    //根据session的user_id 判断其对应的权限
       //  $limit = $this->user_id;
         
    }
    //左边栏公共页面
    protected function  middle(){
//        $cardata= Db::name('cardata')->where('status',1)->column('car_name','id');
//        $this->view->assign('cardata', $cardata);
            //3.渲染模版
     //   return $this->view->fetch('public/middle'); 
    
   }
    
    
}
