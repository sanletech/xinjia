<?php
/*
 *  运价设置
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\Price as PriceM;
use think\Db;

class Price extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
     //航线运价
    public function price_route() 
    {   
        $port_start = input('get.port_start');
        $port_over = input('get.port_over');
        if($port_start){
            $this->assign('port_start',$port_start); 
        }
        if($port_over){
            $this->assign('port_over',$port_over); 
        }
        $route = new PriceM;
        $list = $route->price_route_list($port_start,$port_over);
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->view->fetch('Price/price_route'); 
    }
    //拖车运价
    public function price_trailer() 
    {               
        return $this->view->fetch('Price/price_trailer'); 
    }

    
   //航线添加
    public function route_add(){
        
        return $this->view->fetch('Price/route_add');
    }
    //航线修改
    public function route_edit(){
        return $this->view->fetch("Price/route_edit");
    }
    //拖车添加
    public function trailer_add(){
        return $this->view->fetch("Price/trailer_add");
    }
    //拖车修改
    public function trailer_edit(){
      
        return $this->view->fetch("price/trailer_edit");
    }
}