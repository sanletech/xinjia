<?php
namespace app\index\common;
use think\Controller;
use think\Session;
use think\Db;

class Base extends Controller
{
    protected $member_code='';
    protected function _initialize() {
        parent::_initialize();
        //在公共控制器的初始化方法中，创建一个常量用来判断用户是否登录或已经登录
        $this->member_code=  Session::get('member_code');
        //$user_id 为空则没有登录
        if(is_null($this->member_code)){
        $this->notlogin();}
//        }  else {
//            $this->alreadylogin();
//        }
      
        
    }
    
   

        protected function notlogin()
    {
        //如果登录常量为nll，表示没有登录
      if(is_null($this->member_code)){
          $this->error('未登录，无权访问','login/login');
          
      }   
    }
//      protected function alreadylogin()
//    {
//          
//          var_dump($_SESSION);
//        var_dump($this->member_code);exit;   
//        //如果登录常量为非nll，表示已经登录
//      if(!is_null($this->member_code)){
//          $this->error('请不要重复登录','login/login');
//          
//      }   
//    }
    
   
      protected function limit()
    {
    //根据session的user_id 判断其对应的权限
       //  $limit = $this->user_id;
         
    }
    
    //公共top页面
    protected function  top(){
        return $this->view->fetch('public/top'); 
    
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
    
    
}
