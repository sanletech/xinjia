<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
class Index extends Controller
{
   // 展示起始页导航
     public function index()
    {
     $this->redirect('OrderPort/orderPort');
    }
    //展示index首页
    public function index_body()
  //  public function index()
    {
       return $this->view->fetch('index/index');
    }
    //公共查询
    public function check()
    {
       return $this->view->fetch('index/check');
    }
    
    //集装箱出售
    public function container()
    {
       return $this->view->fetch('index/container');
    }
    //新闻中心
    public function news()
    {
       return $this->view->fetch('index/news');
    }
    //合伙人加盟
    public function join()
    {
       return $this->view->fetch('index/join');
    }
    //帮助与公告
    public function help()
    {
       return $this->view->fetch('index/help');
    }
    //个人中心
    public function personal()
    {
       return $this->view->fetch('index/personal');
    }

    //下单
    public function xxtx()
    {
       return $this->view->fetch('index/xxtx');
    }


}
