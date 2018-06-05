<?php
/*
 *  港口添加修改
 * 
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\Port as PortM;
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
        $port_name = input('get.port_name');
        if($port_name){
            $this->assign('port_name',$port_name); 
        }
        $port_list = new PortM;
        $list = $port_list->port_list($port_name ,5);
       // $this->_p($list);exit;
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->view->fetch('port\port_list'); 
    }
    
    //添加港口页面
    public function port_add() 
    {
      return $this->view->fetch('port\port_add'); 
    }
      //添加港口执行
    public function port_toadd() 
    {
      return $this->view->fetch('port\port_add'); 
    }
    
    
    //删除港口
    public function port_del() 
    {
       //接受port_del 的id 数组
        $data = $this->request->param();
        $seaprice_id = $data['id'];
        $portdel = new PortM;
        $res = $portdel ->port_del($seaprice_id);
         if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    
    //修改港口页面
    public function port_edit() 
    {
        $id = input('get.id');
        $editPort =new PortM;
        $data = $editPort->port_edit($id);
        $this->assign('data',$data );
       // $this->_v($data);exit;
        return $this->view->fetch('port\port_edit'); 
    }
        //修改港口执行
    public function port_toedit() 
    {
      return $this->view->fetch('port\port_edit'); 
    }
    

    
} 