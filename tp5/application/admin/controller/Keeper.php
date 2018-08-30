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

    //管理员列表
    public function admin_list() 
    {
        return $this->view->fetch('Keeper/admin_list'); 
    }
    //添加管理员
    public function admin_add() 
    {
      return $this->view->fetch('Keeper/admin_add'); 
    }
    //修改管理员
    public function admin_edit() 
    {
        return $this->view->fetch('Keeper/admin_edit'); 
    }

    //划分区域列表
    public function area_list() 
    {  
        $list =Db::name('user')->alias('U')
                ->join('hl_user_area UA','UA.user_id=U.id','left')
                ->join('');
        
      //  return $this->view->fetch('Keeper/user_list'); 
    }

    //修改用户
    public function user_edit() 
    {
        return $this->view->fetch('Keeper/user_edit'); 
    }
} 