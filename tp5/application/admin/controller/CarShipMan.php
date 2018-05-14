<?php
/*
 *  车队 船公司联系人的资料管理
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class CarShipMan  extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    
    public function man_list() 
    {    
        
        
    }
}