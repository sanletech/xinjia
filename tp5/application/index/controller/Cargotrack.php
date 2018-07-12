<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use think\Session;
//use app\index\controller\Logistics as LogisticsC;
use app\index\controller\Logistics;
class Cargotrack extends Controller {
   //渲染物流查询页面
    public function query() {
        
       return $this->view->fetch('index/cargo_track');
    }
    
    //接受运单号,和集装箱号码 来查询物流动态
    public function tracking(){
        $waybillNum =  $this->request->post('waybillNum');
        if($waybillNum){  $this->view->assign('waybillNum',$waybillNum);}
        $boxNum = $this->request->post('boxNum');
        if($boxNum){  $this->view->assign('boxNum',$boxNum);}

    //$event = \think\Loader::controller('Logistics', 'event');;
       // $event =new  \application\index\controller\Logistics();
        $event =new Logistics();
   // var_dump($event);exit;
        $list = $event->zhonggu($waybillNum,$boxNum);
        return json($list);
    }
    
    
}
