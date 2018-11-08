<?php
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\orderPort as OrderM;
use think\Db;
use app\index\model\Order as IndexOrderM;

class OrderPort extends Base
{   
    
    private $order_status;
    private $page=5;

    public function _initialize()
    {  
        $this->order_status=config('config.order_status');
    }


    public function Upload($order_num,$type,$file)
    {
        // 获取表单上传文件,订单号，上传文件的类别 
        // sea_waybill 水运单  book_note 订舱单
//        $file = request()->file('file');
//        $order_num = $this->request->get('order_num');
//        $type = $this->request->get('type');
        $rename = $order_num.'_'.$type;
         // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'text,txt,pdf,docx,doc,docm,dotx,dotm'])
                 ->move(ROOT_PATH . 'public' . DS . 'uploads/files',$rename);
        $response =[];
       
        if($info){
            // var_dump($info);exit;
            $reFile = str_replace('.','_',$info->getSaveName());
             // 将A926548346305370_book_note.pdf文档存进order_port表里
            $res = Db::name('order_port')->where('order_num',$order_num)
                    ->update([$type=>$reFile]);            
            $response=['code'=>0,'msg'=>'提交成功','data'=>[]];
            //文件上传成功后更新订单状态
            //if($res){
                  // 上传成功后更新订单和账单的状态
                
            return $response;
           // }
        }else{
            // 上传失败获取错误信息
            return['status'=>0,'mssage'=>$file->getError()];
        }


    }
    
    public function downs(){    
            $order_name = $this->request->param('order_num');    //下载文件名 
            $type = $this->request->param('type'); //文件类型
            $file = Db::name('order_port')->where('order_num',$order_name)->value($type);
            var_dump($file);
            //将后缀修改成.
            $file_Extension= strstr(strrev($file),'_',true);
            $file_name = substr($file,0,strrpos($file, '_')).'.'.$file_Extension;     
//              var_dump($file_name);exit;
            $file_dir = ROOT_PATH . 'public' . DS . 'uploads/files';        //下载文件存放目录    
            //检查文件是否存在    
//            var_dump($file_dir .DS. $file_name);exit;
            if (! file_exists ($file_dir .DS. $file_name)) {    
                echo "文件找不到";    
                exit ();    
            } else {    
                //打开文件
//                var_dump($file_dir .DS. $file_name);
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

    //审核订单的列表页面
    public function order_audit() 
    {
        $data = new OrderM;
        $list = $data->order_audit($this->page,$this->order_status['order_audit']);
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('orderPort/order_audit'); 
    }
        //审核详情页
    public function audit_page()
    {   
        $order_num =  $this->request->get('order_num');
        $data = new OrderM;
        $dataArr = $data->orderData($order_num);
//        $this->_p($dataArr);exit;

        $this->assign([
            'list'  =>$dataArr['list'],
            'containerData' => $dataArr['containerData'],
            'carData'=> $dataArr['carData'],
            'shipperArr'=>$dataArr['shipperArr'],
            'consignerArr'=>$dataArr['consignerArr'],
            'discount'=>$dataArr['discount']
        ]);;
        return $this->view->fetch('orderPort/audit_page');
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
        $data = new OrderM;
        $response = $data->orderUpdate($order_num,$status,$title,$comment);
//        var_dump($response);exit;
        return json($response);
    }
    
  
    //上传订舱单文件,运单号和 水运单文件
    //参数 type= booking_note或者sea_waybill
    //订单号码
    public function waybill_upload(){
        $data= $this->request->param();
        // var_dump($data);exit;
        $file = request()->file('file');
        $order_num = $this->request->param('order_num');
        $type = $this->request->param('type');
        $track_num= $this->request->param('track_num');
        if(!empty(trim($track_num))){
           $res1= Db::name('order_port')->where('order_num',$order_num)->update(['track_num'=>$track_num]);
        }
        $res =$this->Upload($order_num,$type,$file);
        return $res;
        
    }

    //港到港 处理订单公共页
    public function portList()
    {   
        return $this->view->fetch('orderPort/port_list');
    }
    //所有订单
    public function portlist_data()
    {    
        //客户单位,订单号,运单号,
        //审核中apply,扣货lock,已放货unlock,上传水运单
        // 在线付,月结,到港付 -
        // 上传订舱单,客户提交柜号,上传水运单
        //  -已经完成订单/未完成
    //    $canshu = $this->request->param();
    //    $this->_p($canshu);exit;
        $map =[];
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
        $have_money?$map['A.money_status'][]=0:'';
        $no_money =$this->request->param('no_money');//已经收款
        $no_money?$map['A.money_status'][]=1:'';
        if(array_key_exists('A.money_status', $map)){
            array_unshift($map['A.money_status'],'in'); //插入条件in
        } 
        
        $completion = $this->request->param('completion','undone','strval'); //订单完成 默认未完成
        if($completion=='undone' && (!isset($map['status'])) ){
            $map['A.status'][]='in';
            $map['A.status'][]=array($this->order_status['booking_note'],$this->order_status['up_container_code'],$this->order_status['sea_waybill']);
        }elseif($completion=='done'){
            $map['A.status']=null;//清空选择的上传订舱和水运单
            $map['A.status'][]=$this->order_status['completion'];
        }elseif ($completion=='all') {
            $map['A.status']=null;//清空选择的上传订舱和水运单
            $map['A.status'][]='in';
            $map['A.status'][]=array($this->order_status['booking_note'],$this->order_status['up_container_code'],$this->order_status['sea_waybill'],$this->order_status['completion']);
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
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $data = new OrderM;
        $lists = $data->order_status($tol,$limit,$map);
//        $this->_p($lists);exit;
        return array('code'=>0,'msg'=>'','count'=>$lists['count'],'data'=>$lists['list']);
         
    }


    //详情
    public function port_details()
    {   
        $order_num =  $this->request->get('order_num');
        $data = new OrderM;
        $dataArr = $data->orderData($order_num);
//        $this->_p($dataArr['list']);exit;
        $this->assign([
            'list'  =>$dataArr['list'],
            'containerData' => $dataArr['containerData'],
            'carData'=> $dataArr['carData'],
            'shipperArr'=>$dataArr['shipperArr'],
            'consignerArr'=>$dataArr['consignerArr'],
            'discount'=>$dataArr['discount']
        ]);
        return $this->view->fetch('orderPort/port_details');
    }


        //港到港订单详情页面
        public function orderPortDetail() {
        
            $order_num =  $this->request->get('order_num');
            //访问后台的 model\orderPort\orderData 方法
            $data = new \app\admin\model\orderPort();
            $dataArr = $data->orderData($order_num);
           
            $this->assign([
                    'list'  =>$dataArr['list'],
                    'containerData' => $dataArr['containerData'],
                    'carData'=> $dataArr['carData'],
                    'shipperArr'=>$dataArr['shipperArr'],
                    'consignerArr'=>$dataArr['consignerArr'],
                    'discount'=>$dataArr['discount']
            ]);
            //渲染后台的详情页面
            return $this->view->fetch('orderPort/order_port_detail');
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
    //港到港的订单修改
    public function  orderEdit() {
        $data = $this->request->param();
//        $this->_p($data);exit;
        $order_num =$data['order_num'];  
//        //根据订单号查询
        $sqlData =Db::name('order_port')->where('order_num',$order_num)->field('member_code,seaprice_id,container_size')->find();
        $mtime= date('Y-m-d H:i:s');
        //对支付方式做判断
        $payment_method= $data['payment_method'];
        if(intval($payment_method)){
            $special= $payment_method;
            $payment_method='special';
        }  else {
            $special= 0;
        }
        $Pirce =new IndexOrderM;
        //计算单个柜优惠的金额
        $discount = $Pirce->dicountPrice($sqlData['member_code'],$sqlData['seaprice_id'],$sqlData['container_size'], $payment_method, $special);
        //计算装货费用和送货费用
        $truckageData = array('r'=>['container_code'=>$data['r_container_code'],'car_price'=>$data['r_car_price'],'num'=>$data['r_num'],'add'=>$data['r_add'],'link_man'=>$data['r_link_man'],'shipper'=>$data['shipper'],
                    'load_time'=>$data['r_load_time'],'link_phone'=>$data['r_link_phone'],'car'=>$data['r_car'],'comment'=>$data['r_comment']], 
                    's'=>['container_code'=>$data['s_container_code'],'car_price'=>$data['s_car_price'],'num'=>$data['s_num'],'add'=>$data['s_add'],'car'=>$data['s_car'], 'comment'=>$data['s_comment']] );
        $dataM  =  new OrderM;
        $truckagePrice = $dataM->truckage($order_num,$data['container_sum'], $truckageData);
        //计算出对应的海运费
        $seaPrice = Db::name('seaprice')->where('id',$sqlData['seaprice_id'])->value('price_'.$sqlData['container_size']);
  
        //计算总共的成本 (海运费 -优惠)*柜子数量 + 保险金额*6 + 装货费 +送货费;
        $quoted_price= ($seaPrice-$discount)*$data['container_sum'] + ($data['cargo_cost']*6) +$truckagePrice['carprice_r']+$truckagePrice['carprice_s'];
//        var_dump($seaPrice,$discount,$truckagePrice['carprice_r'],$truckagePrice['carprice_s']);exit;
     
        if(!(abs($quoted_price- $data['price_sum'])<0.00001)){
            return array('status'=>0,'mssage'=>'报价错误');
        } 
        if(!array_key_exists('invoice_if',$data)){
            $data['invoice_if']=0;
        }
        
        $shipper = implode(',',array($data['r_name'],$data['r_company'],$data['r_phone']));
        $consigner = implode(',',array($data['s_name'],$data['s_company'],$data['s_phone']));
        $fatherData= array('cargo'=>$data['cargo'],'track_num'=>$data['track_num'],
        'weight'=>$data['weight'],'cargo_cost'=>$data['cargo_cost'],
        'container_type_id'=>$data['container_type'],'comment'=>$data['comment'],'mtime'=>$mtime,
        'payment_method'=>$payment_method,'special_id'=>$special,'invoice_id'=>$data['invoice_if'],
        'shipper'=>$shipper,'consigner'=>$consigner,'seaprice'=>$data['money'],'premium'=>$data['premium'],'discount'=>$discount,
        'carprice_r'=>$truckagePrice['carprice_r'],'carprice_s'=>$truckagePrice['carprice_s'],'quoted_price'=>$quoted_price);
        //查询是否已经有了同样的订单了 判断依据是金额相同,创建时间相差90S内
        $starttime=date("Y-m-d H:i:s", strtotime("-90 seconds", time()));
        $res = Db::name('order_port')->where('order_num',$order_num)->where('mtime','between',[$starttime,$mtime])->find();
        if(empty($res)){
            $res1 = Db::name('order_port')->where('order_num',$order_num)->update($fatherData); 
            return $res1 ? array('status'=>1,'mssage'=>'修改成功'):array('status'=>0,'mssage'=>'修改失败');
        } else {
            return array('status'=>0,'mssage'=>'订单重复修改');
        }
        
    }
}
