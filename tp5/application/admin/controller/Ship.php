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
        $list = $shiplist->shiplist();
        $count = count($list); 
        for($i=0;$i<$count;$i++){
           $ship_id=$list[$i]['ship_id'];
           $port_id=explode(',',$list[$i]['port_id'])  ;
           $port_name=  explode(',',$list[$i]['port_name']) ;
           $port_arr[$ship_id]= array_combine($port_id ,$port_name);
        }
       //$this->_v($port_arr);
        $page = $list->render();
        $this->assign('shiplist', $list);
        $this->assign('page' , $page);
        $this->assign('count',$count);
        $this->assign('port_arr',$port_arr);
        return $this->view->fetch('Ship/ship_list');
    }
    //针对船公司依照不同港口展示联系人的资料
    public function  ship_info(){
        $id= $this->request->get();
        var_dump($id);exit;
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