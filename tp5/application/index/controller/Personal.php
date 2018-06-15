<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
class Personal extends Controller 
{
    //个人中心
    public function steward()
    {
       return $this->view->fetch('personal/steward');
    }

}
