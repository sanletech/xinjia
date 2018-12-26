<?php
/*
 *  权限管理
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class Jurisdiction extends Base
{
    public function control() 
    {
        return $this->view->fetch('Jurisdiction/control');
    }

    public function aaa() 
    {
        var_dump($this->request->param());exit;
    }

} 