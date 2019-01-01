<?php
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\OrderPort as OrderM;
use think\Db;
use app\admin\model\OrderProcess as  OrderProcessM;
use app\admin\controller\OrderProcess as OrderProcessC;
use app\index\model\Order as IndexOrderM;

class OrderPort extends Base
{   
    

    //审核详情页
    public function audit_page()
    {   
        $order_num =  $this->request->get('order_num');
         $data = new OrderProcessM;
        $dataArr = $data->order_details($order_num);
        $this->assign([
            'list'  =>$dataArr['list'],
            'containerData' => $dataArr['containerData'],
            'carData'=> $dataArr['carData'],
            'shipperArr'=>$dataArr['shipperArr'],
            'consignerArr'=>$dataArr['consignerArr'],
        ]);
        return $this->view->fetch('orderPort/audit_page');
    }
  
    //上传订舱单文件,运单号和 水运单文件
    //参数 type= booking_note或者sea_waybill
    //订单号码
    public function waybill_upload(){
        $file = request()->file('file');
        $order_num = $this->request->param('order_num');
        $type = trim($this->request->param('type'));
        if(!($type=='book_note'||$type=='sea_waybill')){
            return FALSE;
        }
        $track_num= $this->request->param('track_num');
        $OrderProcessC =  new OrderProcessC;
        $res =$OrderProcessC->Upload($order_num,$type,$file);
        //上传成功就更改状态
        if($res['status']=='1'){
                $OrderProcessM = new OrderProcessM();
                if($type=='book_note'){
                    $status =  $this->order_status['send_car'];
                    $title ='上传订舱单->待客户提交柜号';
                    if(!empty(trim($track_num))){
                        $map =['track_num'=>$track_num];
                        $res= Db::name('order_port')->where('order_num',$order_num)->update($map);
                    }
                }elseif ($type=='sea_waybill') {
                    $status =  $this->order_status['send_car'];
                    $title ='上传水运单->待通过放柜';
                }
                //更改状态
                $res1 = $OrderProcessM->orderUpdate($order_num,$status,$title);
                return $res1; 
        }  else {
            
            return $res;
        }
        
    }

    //港到港 处理订单公共页
    public function portList()
    {   
        return $this->view->fetch('orderPort/port_list');
    }
    
    //所有订单
    public function portlist_data()
    {    
        $map =[];
        //起运港口,目的港口
        $start_port = $this->request->param('stat_id');
            $start_port ? $map['A.sl_start']=['=',$start_port]:'';
        $end_port = $this->request->param('end_id');
            $end_port ? $map['A.sl_end']=['=',$end_port]:'';
        
        //客户单位 船公司
        $member_company= $this->request->param('company');
            $member_company ? $map['A.company']=['=',$member_company]:'';
        $ship_id = $this->request->param('ship_id');
            $ship_id ? $map['A.ship_id']=['=',$ship_id]:'';
        
        $cash = $this->request->param('cash');//在线付款
        $cash?$map['A.payment_method'][]=['=','cash']:'';
        $month = $this->request->param('month'); //月结付款
        $month?$map['A.payment_method'][]=['=','month']:'';
        $installment = $this->request->param('installment'); //到港付
        $installment?$map['A.payment_method'][]=['=','installment']:'';
        if(isset($map['A.payment_method'])){
            (count($map['A.payment_method'])-1) ? $map['A.payment_method'][]='or':$map['A.payment_method']=$map['A.payment_method']['0'];
        }
        $book_note = $this->request->param('book_note'); //订单进度 待上传订舱单文件
        $book_note ? $map['A.status'][]=['=',$this->order_status['booking_note']]:'';
        $container_code = $this->request->param('container_code'); //订单进度 客户待提交柜号
        $container_code ? $map['A.status'][]=['=',$this->order_status['up_container_code']]:'';
        $sea_waybille = $this->request->param('sea_waybill'); //订单进度 待上传水单
        $container_code ? $map['A.status'][]=['=',$this->order_status['sea_waybill']]:'';
        if(isset($map['A.status'])){
            (count($map['A.status'])-1) ? $map['A.status'][]='or':$map['A.status']= $map['A.status']['0'];
        }
        $have_money =$this->request->param('have_money'); //未收款
        $have_money?$map['A.money_status'][1][]='nodo':'';
        $no_money =$this->request->param('no_money');//已经收款
        $no_money?$map['A.money_status'][1][]='do':'';

        if(array_key_exists('A.money_status', $map)){
            array_unshift($map['A.money_status'],'in'); //插入条件in
        } 
//        var_dump($map['A.money_status']);exit;
        $completion = $this->request->param('completion','undone','strval'); //订单完成 默认未完成
        if(!array_key_exists('A.status', $map)){
            if($completion=='undone'){
                $map['A.status'][]='in';
                $map['A.status'][]=array($this->order_status['booking_note'],
                    $this->order_status['up_container_code'],
                    $this->order_status['sea_waybill']);
            }elseif($completion=='done'){
                $map['A.status']=null;//清空选择的上传订舱和水运单
                $map['A.status'][]='in';
                $map['A.status'][]=$this->order_status['completion'];
            }elseif ($completion=='all') {
                $map['A.status']=null;//清空选择的上传订舱和水运单
                $map['A.status'][]='in';
                $map['A.status'][]=$this->order_status;
            }
        }
        $container_buckle = $this->request->param('container_buckle','apply','strval');
        if($container_buckle='all'){
            $map['A.container_buckle']=[['=','apply'],['=','lock'],['=','unlock'],'or'];
        }  else {
            $map['A.container_buckle']=$container_buckle;
        }
     
        $company  = $this->request->param('company'); //公司查询
        $company?$map['A.company']=$company:'';
        $order_num = $this->request->param('order_num');//订单号查询
        $company?$map['A.order_num']=$order_num:'';
        $track_num = $this->request->param('track_num');//运单号查询
        $company?$map['A.track_num']=$track_num:'';
        $page =$this->request->param('page',1,'intval');
        $limit =$this->request->param('limit',10,'intval');
        $tol = ($page-1)*$limit;
     
        //订单查询
        $order_num =  $this->request->param('order_num');
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        $data = new OrderM;
        $lists = $data->order_status($page,$limit,$map);
//        $this->_p($lists);exit;
        return array('code'=>0,'msg'=>'','count'=>$lists['count'],'data'=>$lists['list']);
         
    }

    //废弃订单
    public function order_cancel() {
        
        //订单查询
        $order_num =  $this->request->param('order_num');
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $data = new OrderM;
        $map['A.status']=array('in',[$this->order_status['cancel'],$this->order_status['stop']] );
        $lists = $data->order_status($tol,$limit,$map);
        $count =$lists['count'];
        $list=$lists['list'];
        $cancel_list =Db::name('order_port_status')->alias('OPS')
                ->join('hl_user U','U.user_code=OPS.submitter','left')
                ->where('OPS.status','in',[$this->order_status['cancel'],
                $this->order_status['stop']])->group('OPS.order_num')
                ->field('OPS.*,U.user_name')->select();
        
        foreach ($list as $k => $v) {
            foreach ($cancel_list as $key =>$value){
                if($value['order_num']==$v['order_num']){
                    $list[$k]['cancel_comment']= $value['comment'];
                    $list[$k]['cancel_time']= $value['mtime'];
                    $list[$k]['cancel_user']=$value['user_name'];
                }
            }
        }
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        $this->view->assign('order_num',$order_num);
        $this->view->assign('limit',$limit); 
        return $this->view->fetch('orderPort/order_cancel');
    }
    

}
