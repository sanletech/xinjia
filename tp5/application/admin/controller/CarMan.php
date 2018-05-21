<?php
/*
 *  车队 联系人的资料管理
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\CarShipMan;
use think\Request;
use think\Db;
class CarMan  extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    
    public function man_list() 
    {   
        $seachData =  $this->request->param();
        $car_name =  $this->request->param('car_name');
        $Manlist =new CarShipMan();
        $list = $Manlist->car_list($seachData);
        //$this->_p($list);exit;
      //  echo Db::getLastSql();exit;
        $page = $list->render();
        $count= count($list);
        $this->assign('searchcar',$car_name);
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('count',$count);
        return $this->view->fetch('carshipman/carman_list');
           
    }
    //怎么把及下面的港口传到页面上选择
      public function man_add() 
    {   
       $car_list = Db::name('cardata')->field('id,car_name') ->select(); 
     //  json_encode($car_list);
      // $this->_v($car_list);exit;
        $this->assign('list',$car_list);
       return $this->view->fetch('carshipman/carman_add');
        
    }
      public function to_add() 
    {    
        $data =  $this->request->param();
        $data['car_data_id'] = strstr($data['car'],'_','1');
        //$this->_v($data);exit;
        unset($data['car']);
        $res = Db::name('carinfo')->insert($data);
        if($res){
            return $status =1;
        }else{return $status = 0;}
    }
      public function man_del() 
    {    
        $id =  $this->request->param();
        $ManDel =new CarShipMan();
        $id = implode(',', $id['id']);
        $res = $ManDel->car_del($id);
        if(!array_key_exists('fail', $res)){
                  $status =1; 
              }else {
                  $status =0;  
                    }
        json_encode($status);   
        
         return $status;   
    }
}