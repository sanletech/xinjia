<?php
/*
 *  订单管理控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\Order as OrderM;
use app\admin\controller\OrderProcess as OrderProcessC;
use app\admin\model\OrderProcess as  OrderProcessM;
use think\Validate;
class Order extends Base
{       
    private $order_status;
    private $page=5;

    public function _initialize()
    {  
        $this->order_status=config('config.order_status');
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
            'containerData' => '',
            'carData'=> '',
            'shipperArr'=>$dataArr['shipperArr'],
            'consignerArr'=>$dataArr['consignerArr'],
            'discount'=>''
        ]);;
        return $this->view->fetch('order/audit_page');
    }
    

        
    //查看订单
    public function order_edit() 
    {
        return $this->view->fetch('order/order_edit'); 
    }
    //废弃订单
    public function order_waste() 
    {  
        $data = new OrderM;
        $list = $data->order_audit($pages=5,$state=404040);
//        var_dump($list);exit;
        $page =$list->render();
        $count =  count($list);
        $this->view->assign('count',$count);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('order/order_waste'); 
    }
     //废弃订单的恢复
    public function order_waste_pass() 
    { 
       if (request()->isAjax()){
            $idArr =$this->request->param();
            $res =Db::name('order_father')->where('id','in',$idArr['id'])->update(['state'=>0,'action'=>'通过恢复>待审核']);
            $order_numArr = Db::name('order_father')->where('id','in',$idArr['id'])->column('order_num');
            foreach ($order_numArr as $order_num) {
               action('OrderProcess/orderRecord', ['order_num'=>$order_num,'status'=>0,'action'=>'通过恢复>待审核'], 'controller');
            }
           return json($res ? 1 : 0) ;
       }
    }
    
      //废弃订单 的删除
    public function order_waste_del() 
    {
         if (request()->isAjax()){
            $idArr =$this->request->param();
            $res =Db::name('order_father')->where('id','in',$idArr)->delete();
            $order_numArr = Db::name('order_father')->where('id','in',$idArr['id'])->column('order_num');
     
           return json($res ? 1 : 0) ;
       }
        
    }
        
    
    
    //录入派车信息 装货 和送货 
    public function send_car() 
    {  
        $data =$this->request->param();
//        $this->_p($data);exit;
        $order_num = $this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $contact = $data['contact'];
        $car_data = $data['car_data'];
        $type =$this->request->param('type'); //load装货，send送货
        //查询提交的司机信息个数是否和柜子数量一致
        $container_sum = Db::name('order_port')->where('order_num',$order_num)->value('container_sum');
        if($container_sum!== count($car_data)){
            return json(array('status'=>0,'message'=>'司机信息与柜子数量不符'));
        }
        $OrderM= new OrderM;
        $res = $OrderM->send_car($order_num,$track_num,$container_sum,$contact,$car_data,$type);
        if(!array_key_exists('fail', $res)){
            //更新订单状态同时记录操作
            $OrderProcessM = new OrderProcessM();
            $response = $OrderProcessM->orderUpdate($order_num,$status=$this->order_status['loading'],$title='录入派车信息->待装货');
       
        }else {
            $response =['msg'=>implode(',', $response['fail']) ,'status'=>0]; 
        } 
        return json($response);
    }
    
   //带装货 的 柜号封条 司机 信息获取
    public function LoadData() 
    { 
        $order_num =$this->request->get('order_num');
        $data = Db::name('order_car')
                ->where('order_num',$order_num)
                ->field('id,container_code,seal_No,driver_name')
                ->select();
        return json($data);
    }
    
    //带装货 添加装货时间
    public function addLoadTime() 
    {   
//      $this->_p($this->request->param());exit;
        $order_num =$this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $modify = $this->request->param('modify');
        $lists = $this->request->only('list');
        //更新order_car的时间
        $arr=[];
        $mtime = date('Y-m-d H:i:s');
        foreach ($lists as $value){
            $value['mtime']=$mtime;
            $id = $value['id'];
            if($modify){
                $map= array('work_time'=>$value['work_time']);
            }  else {
                $map=$value;
            }
            $res = Db::name('order_car')->where('id',$id)->update($map);
            $res ? $arr['success'][] = $id :$arr['fail'][] = $id ;
        }
        if(array_key_exists('fail', $arr)){
            return json(array('status'=>0,'message'=> '更新失败'.implode(',', $arr['fail'])));
        }  else {
              //更新订单状态同时记录操作
            $OrderProcessM = new OrderProcessM();
            $response = $OrderProcessM->orderUpdate($order_num,$status=$this->order_status['up_container_code'],$title='录入装货时间->待报柜号');
            return json($response);
        }
    
    }
        

    
    //待报柜号
    public function report_container () {
        $order_num =$this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $modify = $this->request->param('modify');//是否修改
        $lists = $this->request->only('list');
        //更新order_car的时间
        $arr=[];
        $mtime = date('Y-m-d H:i:s');
        if($modify){
            foreach ($lists as $value){
                $value['mtime']=$mtime;
                $id = $value['id'];
                $res = Db::name('order_car')->where('id',$id)->update($value);
                $res ? $arr['success'][] = $id :$arr['fail'][] = $id ;
            }
            if(array_key_exists('fail', $arr)){
                return json(array('status'=>0,'message'=> '更新失败'.implode(',', $arr['fail'])));
            }
        }
       
        //更新订单状态同时记录操作
        $OrderProcessM = new OrderProcessM();
        $response = $OrderProcessM->orderUpdate($order_num,$status=$this->order_status['load_ship'],$title='报柜号完毕->待配船');
        return json($response);
       
    }
    
    //待配船 的港口信息
    public function  cargo_plan_data(){
        $order_num =$this->request->param('order_num');
        $data = Db::name('order_ship')
                ->where('order_num',$order_num)
                ->order('sequence')->select();
        //如果为空 就生成 对应的数据
        if(empty($data)){
            $port_arr  = Db::name('order_port')->alias('OP')
                    ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
                    ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
                    ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                    ->join('hl_sea_middle SM','SB.sealine_id=SM.sealine_id','left') //中间港口表    
                    ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
                    ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
                    ->join('hl_port P3','P3.port_code=SM.sl_middle','left')//中间港口
                    ->field('P1.port_name s_port_name ,P1.port_code s_port_code,'
                    . 'P2.port_name e_port_name,P2.port_code e_port_code,'
                    . 'group_concat(distinct P3.port_name order by SM.sequence) m_port_name,'
                    . 'group_concat(distinct P3.port_code order by SM.sequence) m_port_code')
                    ->group('OP.id')->where('OP.order_num',$order_num)->find();
            $insert_data =[];
            $insert_data[0]['order_num'] = $order_num;
            $insert_data[0]['sequence'] = 0;
            $insert_data[0]['port_name'] = $port_arr['s_port_name'];
            $insert_data[0]['port_code'] = $port_arr['s_port_code'];
            $m_port_name = explode(',', $port_arr['m_port_name']);
            $m_port_code = explode(',', $port_arr['m_port_code']);
            foreach ($m_port_name as $key=>$value){
                $insert_data[$key+1]['order_num'] = $order_num;
                $insert_data[$key+1]['sequence'] = $key+1;
                $insert_data[$key+1]['port_name'] = $m_port_name[$key];
                $insert_data[$key+1]['port_code'] = $m_port_code[$key];
            }
            $insert_data[$key+2]['order_num'] = $order_num;
            $insert_data[$key+2]['sequence'] = $key+2;
            $insert_data[$key+2]['port_name'] = $port_arr['s_port_name'];
            $insert_data[$key+2]['port_code'] = $port_arr['s_port_code'];
            $res = Db::name('order_ship')->insertAll($insert_data);
            foreach ($insert_data as $k=>$v){
                $insert_data[$k]['arrival_time']='';
                $insert_data[$k]['dispatch_time']='';
            }
            return json( $res ?  $insert_data: FALSE);    
        }
        return json($data); 
    }
    
    //待配船  判断倒数第二个港口离港时间录完就自动申请放柜
    public function  cargo_plan(){
        // $this->_p( $this->request->param());exit;
        $order_num = $this->request->param('order_num');
        $modify = $this->request->param('modify');//是否修改 true or false
        $lists  =  $this->request->param('list');
        $tmp =[];
        //判断港口数量是否对应的上
        $data = Db::name('order_ship')
                ->where('order_num',$order_num)
                ->field('port_name,port_code,ship_name,arrival_time,dispatch_time')
                ->order('sequence')->select();
        if(count($data) !== count($list)){
            return json(array('status'=>0,'message'=>'参数错误'));
        }else{
            foreach ($data as $key => $value) {
                //对数据和数据库做比对
                $result = array_diff_assoc($lists[$key],$data[$key]);
                //如果为空 说明有未更改的,记录未更改的港口
                if(empty($result)){
                    $tmp['port_code'][]=$value['port_code'];
                }
                $tmp['port_code'] = array_key_exists('port_code', $result)? true :false;
                $tmp['port_name'] = array_key_exists('port_name', $result)? true :false;
                $tmp['ship_name'] = array_key_exists('ship_name', $result)? true :false;
                //不符合数据库的数据
                if(in_array(true, $tmp)){
                    $illegal_data  = array_intersect_key($value,array_flip(array_keys($tmp,true)) );
                    return json(array('status'=>0,'message'=>'数据冲突'.implode(',', $illegal_data) ));
                }
            } 
            $mtime =  date('Y-m-d H:i:s');
            //数据验证无问题，更新数据库
            foreach ($lists as $list){
                //过滤空的数据
                if(count(array_filter($list))==1){
                    continue;
                }
                //过滤和数据库里没有变化的数据
                if(in_array($list['port_code'],$tmp['port_code'])){
                    continue;
                }
                $list['mtime']=$mtime;
                $res = Db::name('order_ship')
                        ->where(['order_num'=>$order_num,'port_code'=>$list['port_code']])
                        ->update($list);
                $res ? $tmp['success'][]=$list['port_name'] :$tmp['fail'][]=$list['port_name'];
            }
            if(array_key_exists('fail', $tmp)){
                return json(array('status'=>0,'message'=>'更新失败.'.implode(',', $tmp['fail'])));
            }  else {
                return json(array('status'=>1,'message'=>'更新成功.'.implode(',', $tmp['success'])));
            }
        }
        
    }
    
    //订单处理页面
    public function order_public() {
        return $this->view->fetch('Order/order_public');
    }
    
    //订单处理页面 数据
    public function order_data() {
        $data= $this->request->except('limit,page');   
        $limit= $this->request->param('limit',10,'intval'); //获取每页显示的条数
        $page= $this->request->param('page',1,'intval');   //获取当前页数
        $search = array_key_exists('search', $data)? $data['search']:''; //搜索条件
        $status = array_key_exists('status', $data)? $data['status']:array(); //状态选择
        $status_arr = array_intersect_key($this->order_status, array_flip($status));
        $dataM = new OrderM;
        $data = $dataM->order_public($page,$limit,$status_arr);
        $list =$data['list']; //分页数据
    //    $this->_p($list);exit;
        $count = $data['count'];// 总页数
        
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$list);
       
    }
    //上传订舱单 和水运单
    public function waybill_upload(){
        
        $file = request()->file('file');
        $order_num = $this->request->param('order_num');
        $type = trim($this->request->param('type'));
        if(!($type=='book_note'||$type=='sea_waybill')){
            return FALSE;
        }
        $track_num= $this->request->param('track_num');
//        $this->_p($this->request->param());exit;
        $OrderProcessC =  new OrderProcessC();
        $res =$OrderProcessC->Upload($order_num,$type,$file);
        //上传成功就更改状态
        if($res['status']=='1'){
                $OrderM = new OrderProcessM();
                if($type=='book_note'){
                    $status =  $this->order_status['send_car'];
                    $title ='上传订舱单->待派车';
                    if(!empty(trim($track_num))){
                        $map =['track_num'=>$track_num,'container_status'=>'do'];
                        $res= Db::name('order_port')->where('order_num',$order_num)->update($map);
                    }
                }elseif ($type=='sea_waybill') {
                    $status =  $this->order_status['send_car'];
                    $title ='上传水运单->待申请放柜';
                }
                //更改状态
                $res1 = $OrderM->orderUpdate($order_num,$status,$title);
                return $res1; 
        }  else {
            
            return $res;
        }
    }
    //申请放柜
    public function apply_cargo() {
        $order_num =  $this->request->param('order_num');
        $mtime =  date('Y-m-d H:i:s');
        $res = Db::name('order_port')->where('order_num',$order_num)->update(['container_buckle'=>'apply','mtime'=>$mtime]);
        //记录客户修改的时间
        if($res){
            $data=[];
            $data['submitter'] = Session::get('user_info','think');
            $data['mtime']=$mtime;
            $data['order_num']=$order_num;
            $data['status']=  $this->status['container_appley'];
            $res2 = Db::name('order_port_status')->insert($data);
        }
        return $res? array('status'=>1,'mssage'=>'提交成功'):array('status'=>0,'mssage'=>'提交失败');     
    }
    

    
    
    
    //查看订单
    public function order_aaa()
    {
        $data= $this->request->param();
        $this->_p($data);exit;
    }
 
} 
