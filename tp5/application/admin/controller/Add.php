<?php
namespace app\admin\controller;
use think\Controller;

class Add extends Controller
{
    public function addurl() {
        
        return $this->view->fetch('public/address'); 
        
    }
}