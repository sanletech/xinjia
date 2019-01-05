<?php
namespace app\admin\common;
use think\Controller;
use think\Session;
//use app\admin\common\Auth as AuthC;
use think\Db;
class Base extends Controller
{
    protected $user_id='';
    protected $area_code;
    protected $order_status;
    public $page=50;
    
    protected function _initialize() {
        parent::_initialize();
        //在公共控制器的初始化方法中，创建一个常量用来判断用户是否登录或已经登录
        $this->user_id =  Session::get('user_id');
        //$user_id 为空则没有登录
        if(is_null($this->user_id)){
            $this->notlogin();
        }
        $this->order_status=config('config.order_status');
         //区域限制
        $this->area_code = $this->portLimit();    
        import('app.admin.common.Auth');//加载类库
     //  $auth=new AuthC;
        $request= \think\Request::instance();	

        //$module = $request->module();//模块名

        $MODULE_NAME = $request->controller();//控制器名

        $ACTION_NAME= $request->action();//方法名
     
        $auth=new Auth();
        $is_rigth =$auth->check($MODULE_NAME.'-'.$ACTION_NAME,session('uid'));
    
        if(!$is_rigth){
//            return $this->view->fetch('/view/auth.html');
          //  return $this->view->fetch(APP_PATH.request()->module().'/view/auth/auth.html');
        }
  
        
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
    
        //根据登陆的用户区域限制来查看港口
        protected function portLimit()
    {
        $user_id = $this->user_id;
        $area = Db::name('user_area')->where('user_id',$user_id)
                ->field('area_code,type')->find();
        if(empty($area)){
            return FALSE;
        }
        if($area['type']=='city'){
            $area['area_code'] = Db::name('port')
                ->where(['status'=>1,'city_id'=>['in',$area['area_code']]])
                    ->column('port_code');
        }elseif ($area['type']=='port'){
            $area['area_code']= explode(',', $area['area_code']);
        }
        $area_code = array_unique($area['area_code']);
        Session::set('area_code',$area_code);
        return $area_code;
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
