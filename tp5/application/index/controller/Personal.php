<?php
namespace app\index\controller;
use app\index\common\Base;
use think\Db;
use think\Session;
use app\index\model\Personal as dataM;
class Personal extends Base
{
    //个人中心
    public function steward()
    {   
       
        return $this->view->fetch('personal/steward');
    }
    //所有订单
    public function all_order()
    {
       return $this->view->fetch('personal/all_order');
    }
    //作废订单
    public function invalid()
    {
               //订单查询
        $order_num =  $this->request->param('order_num');
        $member_code =Session::get('member_code','think');
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM =new dataM;
       
        $list = $dataM->place_order($member_code,$tol,$limit,$order_num);
        $count =  count($list); 
        $this->view->assign('order_num',$order_num);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('list',$list); 
        return $this->view->fetch('personal/invalid');
    }
    //生产账单
    public function a_order()
    {
       return $this->view->fetch('personal/order_bill');
    }
    //账单表报
    public function form_order()
    {
       return $this->view->fetch('personal/bill_statement');
    }
    //个人信息
    public function info()
    {
       return $this->view->fetch('personal/info');
    }
    //公司信息
    public function company()
    {
       return $this->view->fetch('personal/company');
    }
    //常用信息
    public function common_info()
    {
       return $this->view->fetch('personal/common_info');
    }
    
    //港到港订单
    public function place_order()
    {   
        //订单查询
        $order_num =  $this->request->param('order_num');
        $member_code =Session::get('member_code','think');
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM =new dataM;
       
        $list = $dataM->place_order($member_code,$tol,$limit,$order_num);
        $count =  count($list); 
        $this->view->assign('order_num',$order_num);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('list',$list);
       return $this->view->fetch('personal/order_port');
    }

    //提交柜号资料
    public function track_data()
    { 
       $order_num = $this->request->param('order_num');
       $data = Db::name('order_truckage') ->where('order_num',$order_num)->column('container_code');
       return json($data);
    //    $this->view->assign('$track_data',$data);        
    //    return $this->view->fetch('personal/cabinet_number');
    }
    //处理提交柜号
    public function track_num() {
        $member_code =Session::get('member_code','think');
        $data = $this->request->param(); 
        var_dump($data);exit;
        //根据订单号 添加柜号和封条号
        foreach ($data as $v){
            $res =Db::name('order_truckage')->where(['order_num'=>$order_num,'container_code'=>$v['code']])
                    ->update([ 'container_code'=>$v['container_code'],'seal'=>$v['seal']]);
        }
    }




    public function downs(){    
        $order_name = $this->request->param('order_num');    //下载文件名  
        $type = $this->request->param('type'); //文件类型
        $file = Db::name('order_port')->where('order_num',$order_name)->value($type);
     //   var_dump($file);
        //将后缀修改成.
        $file_Extension= strstr(strrev($file),'_',true);
        $file_name = substr($file,0,strrpos($file, '_')).'.'.$file_Extension;     
//              var_dump($file_name);exit;
        $file_dir = ROOT_PATH . 'public' . DS . 'uploads';        //下载文件存放目录    
        //检查文件是否存在    
//            var_dump($file_dir .DS. $file_name);exit;
        if (! file_exists ($file_dir .DS. $file_name)) {    
            echo "文件找不到";    
            exit ();    
        } else {    
            //打开文件    
            $file = fopen ($file_dir .DS. $file_name, "r" );    
            //输入文件标签     
            Header ( "Content-type: application/octet-stream" );    
            Header ( "Accept-Ranges: bytes" );    
            Header ( "Accept-Length: " . filesize ($file_dir .DS. $file_name) );    
            Header ( "Content-Disposition: attachment; filename=" . $file_name );    
            //输出文件内容     
            //读取文件内容并直接输出到浏览器    
            echo fread ( $file, filesize ($file_dir .DS. $file_name) );    
            fclose ( $file );    
            exit ();    
        }    

    }
}
