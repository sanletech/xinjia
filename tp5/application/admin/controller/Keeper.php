<?php
/*
 *  权限管理
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class Keeper extends Base
{
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    //管理员列表
    public function admin_list() 
    {
      return $this->view->fetch('Keeper\admin_list'); 
    }
    //添加管理员
    public function admin_add() 
    {
      return $this->view->fetch('Keeper\admin_add'); 
    }
    //修改管理员
    public function admin_edit() 
    {
      return $this->view->fetch('Keeper\admin_edit'); 
    }

    //用户列表
    public function user_list() 
    {
        return $this->view->fetch('Keeper\user_list'); 
    }

    //修改用户
    public function user_edit() 
    {
        return $this->view->fetch('Keeper\user_edit'); 
    }
} 