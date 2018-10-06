<?php
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\orderPort as OrderM;
use think\Db;

use think\config;
//引入七牛云的相关文件
use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class OrderPort extends Base
{   
    
    public function Upload()
    {
        // 获取表单上传文件,订单号，上传文件的类别 
        // sea_waybill 水运单  book_note 订舱单
        $file = request()->file('file');
        $order_num = $this->request->get('order_num');
        $type = $this->request->get('type');
        $rename = $order_num.'_'.$type;
         // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>15678,'ext'=>'text,txt,word,pdf'])
                 ->move(ROOT_PATH . 'public' . DS . 'uploads',$rename);
        $response =[];
        if($info){
            $reFile = str_replace('.','_',$info->getSaveName());
             // 将A926548346305370_book_note.pdf文档存进order_port表里
            $res = Db::name('order_port')->where('order_num',$order_num)
                    ->update([$type=>$reFile]);
            $res ? $response=['status'>1,'mssage'=>'提交成功']:$response=['status'>0,'mssage'=>'提交失败'];
            //文件上传成功后更新订单状态
            if($res){
                
            }
        }else{
            // 上传失败获取错误信息
            return['status'>0,'mssage'=>$file->getError()] ;
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

            //审核订单
    public function order_audit() 
    {
        $data = new OrderM;
        $list = $data->order_audit(5,2);
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('orderPort/order_audit'); 
    }
    //审核订单 的通过
    public function order_audit_pass() 
    { 
       if (request()->isAjax()){
            $idArr =$this->request->param();
            $res =Db::name('order_port')->where('id','in',$idArr['id'])->update(['status'=>3,'action'=>'通过审核>待录入运单号和上传订舱单']);
            $order_numArr = Db::name('order_port')->where('id','in',$idArr['id'])->column('order_num');
            $data = new OrderM;
            foreach ($order_numArr as $order_num) {
               $data->orderUpdate($order_num,3,'通过审核');
            }
           return json($res ? 1 : 0) ;
       }
    }
    
    
    //港到港订单页
    public function portList()
    {   
   
        return $this->view->fetch('orderPort/port_list');
    }
    //所有订单
    public function all_ordePport()
    {
        $data = new OrderM;
        $list = $data->order_status(5,array(3,4,5,6));
//        $this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('orderPort/all_ordePport');
    }
    //在线支付
    public function port_payment()
    {   
        $data = new OrderM;
        $list = $data->order_status(5,array(3,4,5,6),'cash');
//        $this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('orderPort/all_ordePport');
    }
    //月结
    public function port_month()
    {   
        $data = new OrderM;
        $list = $data->order_status(5,array(3,4,5,6),'monthly');
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('orderPort/all_ordePport');
    }
    //详情
    public function port_details()
    {   
        $order_num =  $this->request->get('order_num');
        $data = new OrderM;
        $dataArr = $data->orderData($order_num);
//        $this->_p($dataArr);exit;
        $list =$dataArr[0];
        $shipperArr= explode(',',$list['shipper']); 
        $consignerArr= explode(',',$list['consigner']);
        //如果存在特殊优惠
        $special_id = $list['special_id'];
        $container_size =  $list['container_size'];
        if($special_id!==0){
            $special =Db::name('discount_special')->where('id',$special_id)
                    ->field("promotion_title,id special_id ,".$container_size.'_promotion')
                    ->find();
        }  else {
            $special='';
        }
        //查询其他的优惠信息
        $member_code = $list['member_code'];
        $seaprice_id = $list['seaprice_id'];

        $discount =Db::name('discount_normal')->alias('DN')
                ->join('hl_seaprice SP','SP.ship_id = DN.ship_id','left')
                ->where(['SP.id'=>$seaprice_id,
                          'DN.member_code'=>$member_code
                        ])
                ->field('DN.'.$container_size.'_installment installment ,'.'DN.'.$container_size.'_month month,'.'DN.'.$container_size.'_cash cash')
                ->find();
        $discount['special']= $special;
//        $this->_p($dataArr[2]);exit;
        $this->assign([
            'list'  =>$list,
            'containerData' => $dataArr[1],
            'carData'=> $dataArr[2],
            'shipperArr'=>$shipperArr,
            'consignerArr'=>$consignerArr,
            'discount'=>$discount
        ]);
        return $this->view->fetch('orderPort/port_details');
    }

    //已完成订单
    public function port_end()
    {
        $data = new OrderM;
        $list = $data->order_status(5,array(3,4,5,6));
//        $this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('orderPort/port_end');
    }
    
    //订单删除
    public function  orderPortDel(){
        $idArr= $this->request->param();
        $res =Db::name('order_port')->where('id','in',$idArr['id'])->update([
            'status'=>505,'action'=>'订单删除']);
        $respones =[];
        $res ?$respones['success'][] ='订单删除成功':$respones['fail'][] ='订单删除失败';
        $dataM =new OrderM;
        $order_num =Db::name('order_port')->where('id','in',$idArr['id'])->column('order_num');
        $status = 505 ;$title = '订单删除';
        if($res){
        foreach ($order_num as  $value) {
            $res1=$dataM->orderUpdate($value,$status,$title) ;
            $res1 ?$respones['success'][] ='订单状态修改成功':$respones['fail'][] ='订单状态修改失败';
        }
        }
        if(array_key_exists('fail', $respones)){
            return array('status'>1,'mssage'>'删除成功');
        }else{
             return array('status'>0,'mssage'>'删除失败');
        }
    }
    
    //废弃订单
    public function cancel() {
        
        $data = new OrderM;
        $list = $data->order_status(5,array(3,4,5,6));
//        $this->_p($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page)
        
    }

}
