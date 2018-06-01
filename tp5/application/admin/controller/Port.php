<?php
/*
 *  港口添加修改
 * 
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class Port extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    //港口列表
    public function port_list() 
    {
      return $this->view->fetch('port\port_list'); 
    }
    //添加港口
    public function port_add() 
    {
      return $this->view->fetch('port\port_add'); 
    }
    //修改列表
    public function port_edit() 
    {
      return $this->view->fetch('port\port_edit'); 
    }
} 