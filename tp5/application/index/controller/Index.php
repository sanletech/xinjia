<?php
namespace app\index\controller;
use think\Db;
use think\Controller;

class Index  extends Controller
{
    public function index()
    {
       return $this->view->fetch('index/check');
    }
    

    
}
