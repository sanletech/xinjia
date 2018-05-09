<?php
/*
 *  船对通讯录控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Ship as ShipM ;
use think\Request;
use think\Db;
class Ship extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    //分页展示船公司的列表信息
    public function ship_list() 
    {
        $shiplist = new ShipM;
        $res = $shiplist->shiplist();
        $count = count($res); 
      // $this->_p($res);
        $this->assign('shiplist', $res);
        $this->assign('count', $count);
        return $this->view->fetch('Ship/ship_list');
    }
    //针对船公司依照不同港口展示联系人的资料
    public function  ship_info(){
        $id= $this->request->get('id');
        $datainfo = new ShipM;
        $res = $datainfo->ship_info($id);
        $this->_p($res);
//        $data=  array_column($res, 'shipport_id');
//        $dataid=$data;
//        array_unique($data);
        $this->assign('list', $res);
        return $this->view->fetch('Ship/ship_info');
    }
    
    //船公司编辑
    public function ship_edit() {
        
    }
}