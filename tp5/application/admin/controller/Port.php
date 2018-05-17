<?php
/*
 *  港口添加修改
 * 
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class Contact extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    
    public function port_list() 
    {
 
    
      return $this->view->fetch('contact\car_list'); 
    }
    

} 