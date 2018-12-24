<?php
/*
 *  订单拆分查看完整订单控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\OrderProcess as OrderProcessM;
use think\Validate;
use think\session;
class OrderProcess extends Base
{     
    private $order_status;
    private $page=5;

    public function _initialize()
    {  
        $this->order_status=config('config.order_status');
    }

    
        //审核订单的列表页面
    public function order_audit() 
    {
        $type = $this->request->param('type');
        if($type=='port'|| $type=='door'){
           $url= ($type=='port')? url('admin/OrderPort/audit_page'):url('admin/Order/audit_page');
        }
        $data = new OrderProcessM;
        $list = $data->order_audit($this->page,$this->order_status['order_audit'],$type);
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        $this->view->assign('audit_page_url',$url);
        return $this->view->fetch('order/order_audit'); 
    }
    
    
    public function Upload($order_num,$type,$file)
    {
        // 获取表单上传文件,订单号，上传文件的类别 
        // sea_waybill 水运单  book_note 订舱单
        $rename = $order_num.'_'.$type;
         // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'text,txt,pdf,docx,doc,docm,dotx,dotm'])
                 ->move(ROOT_PATH . 'public' . DS . 'uploads/files',$rename);
        if($info){
            $reFile = str_replace('.','_',$info->getSaveName());
            $res = Db::name('order_port')->where('order_num',$order_num)
                    ->update([$type=>$reFile]);    
            return  array('status'=>1,'mssage'=>'提交成功');
           // }
        }else{
            // 上传失败获取错误信息
            return array('status'=>0,'mssage'=>$file->getError());
        }


    }
    
    public function downs(){    
            $order_name = $this->request->param('order_num');    //下载文件名 
            $type = trim($this->request->param('type')); //文件类型  
            if(!($type=='book_note'||$type=='sea_waybill')){
                echo '类型错误';             
                return FALSE;
            }
            $file = Db::name('order_port')->where('order_num',$order_name)->value($type); 
            //将后缀修改成.
            $file_Extension= strstr(strrev($file),'_',true);
            $file_name = substr($file,0,strrpos($file, '_')).'.'.$file_Extension;     
            $file_dir = ROOT_PATH . 'public' . DS . 'uploads/files';        //下载文件存放目录    
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
    
        //审核页面的 订单通过或取消
    public function order_judgment() {
        $order_num = $this->request->param('order_num');
        $type = $this->request->param('type');
        $comment = $this->request->param('reject');
        if($type=='pass'){
            $title='订单审核pass';
            $status =  $this->order_status['booking_note'];
        }elseif ($type=='fail') {
         
            $title='订单审核fail';
            $status =  $this->order_status['cancel'];
        }
        $data = new OrderProcessM();
        $response = $data->orderUpdate($order_num,$status,$title,$comment);

        return json($response);
    }
    
        //添加额外的备注信息
    public function extra_info() {
        $extra_info = $this->request->param('extra_info');
        $order_num = $this->request->param('order_num');
        $extra_info =  trim($extra_info);
        $sql ="update hl_order_port  set `extra_info` =concat(ifnull(`extra_info`,''),',$extra_info') where order_num= '$order_num'";
//        var_dump($sql);exit;
        $res = Db::execute($sql);
        $OrderProcessM = new OrderProcessM();
        if($res!==FALSE){
            $OrderProcessM->orderUpdate($order_num,$status='0',$title='添加备注');
            return array('status'=>1,'message'=>'执行成功');
        }
        return  array('status'=>0,'message'=>'执行失败');
    }
    
    //详情页面的数据
    public function order_port_detail (){
        $order_num = $this->request->param('order_num');
        //根据订单号的信息判断是港到港还是门到门
        $oder_type = substr($order_num, 0,1);
        $data = new OrderProcessM();
        $dataArr = $data->order_details($order_num,$oder_type);
//        $this->_p($dataArr);exit;
        if($oder_type =='P'){
            $this->assign([
               'list'  =>$dataArr['list'],
               'containerData' => $dataArr['containerData'],
               'carData'=> $dataArr['carData'],//车队
               'shipperArr'=>$dataArr['shipperArr'],//发货
               'consignerArr'=>$dataArr['consignerArr'],//收货
            ]);
            return $this->view->fetch('orderPort/port_detail');
            
        }  else {
            $this->assign([
                'list'  =>$dataArr['list'],
                'containerData' => $dataArr['containerData'],
                'carData'=> $dataArr['carData'],
                'shipperArr'=>$dataArr['shipperArr'],
                'consignerArr'=>$dataArr['consignerArr'],
                'shipData'=> $dataArr['shipData'],
            ]);
            return $this->view->fetch('order/order_detail');
        }
    }
    
    //订单修改
    public function orderModify(){
        $data = $this->request->param();
        
        $this->_p($data);exit;
        $list = $data['list'];
        $carData = $data['carData'];
        $shipData = $data['shipData'];
        $shipperArr = $data['shipperArr'];
        $consignerArr = $data['consignerArr'];
        $containerData = $data['containerData'];
        $carData = $data['carData'];
        $order_num = null;
        $shipper = '';
        $consigner = '';
        $res = Db::name('order_port')
                ->where('order_num',$order_num)
                ->field('track_num,cargo,container_type,comment')
                ->update($map);
        //同时记录操作
    }
    
}