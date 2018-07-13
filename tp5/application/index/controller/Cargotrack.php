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
        $bz =  substr($waybillNum, 0,1);
        switch($bz){
            case 'Z';
                $function_name ='zhonggu';
                break;
            case 'A';
                $function_name ='antong';
                break;
            case 'P';
                $function_name ='zhongyuan';
                break;
            default;
                return ['message'=>'无此记录'];
        }          
        $event =new Logistics();
        $list = $event->$function_name($waybillNum,$boxNum);
        
        return json($list);
        
    }
}
    
  
