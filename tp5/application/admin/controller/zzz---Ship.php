<?php
/*
 *  船公司通讯录控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Ship as ShipM ;
use think\Request;
use think\Db;
class Ship extends Base
{   

    //分页展示船公司的列表信息
    public function ship_list() 
    {   
        $data    = input('get.'); 
        $ship_name   = input('get.ship_name');
        if($ship_name){
            $this->assign('searchship', $ship_name);
        }
        $shiplist = new ShipM;
        $list  = $shiplist->shiplist($ship_name); 
       // $this->_v($list); exit;
        $count = count($list); 
        $page = $list->render();
        
        $this->assign('shiplist', $list);
        $this->assign('page' , $page);
        $this->assign('count',$count);
        return $this->view->fetch('ship/ship_list');
    }
    
    //针对船公司依照不同港口展示联系人的资料
    public function  ship_info(){
        $ship_id   = input('get.ship_id');
        $port_id   = input('get.port_id');
        $datainfo = new ShipM;
        $res = $datainfo->ship_info($ship_id ,$port_id);
         //$this->_p($res);exit;
        if(!$res){
        $res=array( 0=> Array('name' =>'','position' =>'','duty_line' =>'',
            'sn_tel' =>'', 'sn_mobile' =>'','sn_qq' => '','sn_fax' =>'',
            'port_name' =>'暂无','ship_short_name' =>'暂无',));
        }
         $this->assign('list', $res);
        return $this->view->fetch('ship/ship_info');
    }
    
   
   //展示添加页面
    public function  ship_add(){
        return $this->view->fetch('ship/ship_add'); 
   }
   
   public function shipto_add() {
        $data = $this->request->param();
//        $this->_p($data);exit;
        $ship_short_name = trim($data['ship_short_name']);
        $ship_name =  trim($data['ship_name']);
        $mtime = date('Y-m-d H:i:s');
        $res2 = Db::name('shipcompany')->where('ship_short_name',$ship_short_name)->whereOr('ship_name',$ship_name)->find();
        if(empty($res2)){
            $res = Db::name('shipcompany')->insert(['ship_name'=>$ship_name,'ship_short_name'=>$ship_short_name,'mtime'=>$mtime]);
            $res ? $response=['status'=>1,'message'=>'添加船公司成功'] :$response=['status'=>0,'message'=>'添加船公司失败'];
        }  else {
            $response=['status'=>0,'message'=>'船公司重名'];
        }
        $this->ship_js();
        return $response;   
       
   }
   
   //船公司编辑展示页面
    public function ship_edit() {
        $ship_id= $this->request->get('ship_id');
        $ship= new ShipM;
        $data = Db::name('shipcompany')->where('id',$ship_id)->find();
        $this->assign('ship', $data);
        return $this->view->fetch('ship/ship_edit'); 
    }
    
     //执行船公司编辑
    public function to_edit() {
        $data = $this->request->only('ship_id,ship_name,ship_short_name');
        $data['id'] =$data['ship_id']; unset($data['ship_id']);
        $res =Db::name('shipcompany')->where('id',$data['id'])->update($data);
        $response =[] ;
        $res ? $response=['status'=>1,'message'=>'修改船公司成功'] :$response=['status'=>0,'message'=>'修改船公司失败'];
        $this->ship_js();
        return $res;
    }
    
    //执行船公司删除
    public function to_del() {
        $data = $this->request->param(); 
        $res =Db::name('shipcompany')->where('id','in',$data['id'])->update(['status'=>0]);
        $response =[] ;
        $res ? $response=['status'=>1,'message'=>'删除船公司成功'] :$response=['status'=>0,'message'=>'删除船公司失败'];
        $this->ship_js();
        return $res;
    }
   
    public function ship_js() {
  
        $data = Db::name('shipcompany')->where('status',1)->field('ship_short_name ship_name,mtime',true)->select();
        $js_ship = json_encode($data);
        $js_ship = 'var JS_SHIP ='.$js_ship;
        $filename ="./static/admin/js/ship.js"; 
        if(file_exists($filename)){
            $handle = fopen($filename, "w");//写入文件
            fwrite($handle, $js_ship);
            fclose($handle);
        }  
    }
}