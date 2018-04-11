<?php
/*
 *  车队和船务公司通讯录控制器
 * 
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Contact as ContactM ;
use think\Request;
use think\Db;
class Contact extends Base
{   
    public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }
    
    public function car_list() 
    {
 
        $car= new ContactM;
//       $a= $car->get('1');
//       $this->_p($a->status); exit;
        $carlist= $car->carlist();
//        $this->_p($carlist->a );
//         foreach ($carlist as $vl) {
//              foreach ($vl as $value){
//                print_r($value);
//                  //将status 和 symbiosis 的状态转成汉字
//                $value['status']==1? $value['status']='正常':$value['status']='禁用';
//                if($value['symbiosis']==1){
//                    $value['symbiosis']='长期合作'; 
//                }elseif($value['symbiosis']==2)
//                {
//                    $value['symbiosis']='临时合作'; 
//                }else{
//                    $value['symbiosis']='暂无合作'; 
//                }
//              }
//            
//         }
        $count = count($carlist); 
          
        //将车队信息赋值给模板
        $this->view->assign('count',$count);
        $this->view->assign('carlist',$carlist);
    
      return $this->view->fetch('contact\car_list'); 
    }
    public function  car_edit(){
          //获取需要修改的车队id
        $id= $this->request->get('id'); 
        
        //获取对应ID的信息
        $sql="select * from hl_cardata where id=$id";
        $carinfo=Db::query($sql);
        
        //获取对应ID的symbiosis信息
        $symbiosis= ContactM::get($id);
        $sb= $symbiosis->symbiosis;
        
        $this->view->assign('carinfo',$carinfo);
         $this->view->assign('symbiosis',$sb);
        return $this->view->fetch('contact\car_edit'); 
    }
    
    public function search() {
        var_dump("这个歌搜索时");EXIT;
//        $data= $this->request->param();
//        var_dump($data);exit;
    }
} 