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
use think\Session;
class Order extends Base
{       
    public $order_status;
    public $page=5;

//    public function _initialize()
//    {   parent::_initialize();
//        $this->order_status=config('config.order_status');
//    }

    //审核详情页
    public function audit_page()
    {   
      
        $order_num =  $this->request->get('order_num');
        $data = new OrderProcessM;
        $dataArr = $data->order_details($order_num);
        
        if(!in_array($dataArr['list']['s_port_name'], $this->area_code)){
            echo "<script>alert('不是你归于的区域')</script>";
        }
        $this->assign([
            'list'  =>$dataArr['list'],
            'shipperArr'=>$dataArr['shipperArr'],
            'consignerArr'=>$dataArr['consignerArr'],
        ]);;
        return $this->view->fetch('order/audit_page');
    }
    
        
    
    //录入派车信息 装货 和送货 
    public function send_car() 
    {  
        $data =$this->request->param();
//        $this->_p($data);exit;
        $order_num = $this->request->param('order_num');
        $track_num = $this->request->param('track_num');
//        $contact = $data['contact'];
        $car_data = $data['car_data'];
        $type =$this->request->param('type'); //load装货，send送货
        //查询提交的司机信息个数是否和柜子数量一致
        $container_sum = Db::name('order_port')->where('order_num',$order_num)->value('container_sum');
        if($container_sum!== count($car_data)){
            return json(array('status'=>0,'message'=>'司机信息与柜子数量不符'));
        }
        if(!in_array($type, array('load','send') ) ){
            return json(array('status'=>0,'message'=>'参数不对'));
        }
        //判断下送货的柜子和的装货时候是否一样
        if($type == 'send'){
            //装货的柜号
            $container_code =  Db::name('order_car')
                    ->where(['order_num'=>$order_num,'type'=>'load'])
                    ->column('container_code');
            //比对输入的送货柜号是否一致
            if(array_diff($container_code,array_column($car_data, 'container_code'))){
                return json(array('status'=>0,'message'=>'柜号不对'));
            }
        }
        $OrderM= new OrderM;
        $res = $OrderM->send_car($order_num,$track_num,$container_sum,$car_data,$type);
        if(!array_key_exists('fail', $res)){
            //更新订单状态同时记录操作
            $OrderProcessM = new OrderProcessM();
            if($type == 'load'){
                $status=$this->order_status['loading'];$title='录入派车信息->待装货';
            }  else {
                $status=$this->order_status['completion'];$title='录入送货信息->待完成订单';
            }
            $response = $OrderProcessM->orderUpdate($order_num,$status,$title);
            
        }else {
            $response =['msg'=>implode(',', $response['fail']) ,'status'=>0]; 
        } 
        return json($response);
    }
    
   //带装货 的 柜号封条 司机 信息获取
    public function LoadData() 
    { 
        $order_num =$this->request->get('order_num');
        $type = $this->request->get('type');
        $type ?$type :$type='load';
        $data = Db::name('order_car')
                ->where(['order_num'=>$order_num,'type'=>$type])
                ->field('id,container_code,seal,driver_name,phone,type')
                ->select();
        //如果为空就生成数据
        if(empty($data)){
            $container_sum = Db::name('order_port')
                    ->where('order_num',$order_num)
                    ->value('container_sum');
            if($container_sum>0){
                $insert_data =[];
                //如果是送货，就从装货里获取相应的封条号码
                if($type=='send'){
                    $insert_data = Db::name('order_car')
                    ->where(['order_num'=>$order_num,'type'=>'load'])
                    ->field('order_num,container_code,seal')->select();
                    foreach ($insert_data as $key => $value) {
                        $insert_data[$key]['type']='send';
                    }
                }elseif ($type=='load') {
                    for($i=0;$i<$container_sum;$i++){
                        $insert_data[] = array(
                        'order_num'=>$order_num,'type'=>$type); 
                    }
                
                }
                
            }  else {
                return array('status'=>0,'message'=>'订单不存在');
            }
            $res = Db::name('order_car')
                    ->insertAll($insert_data);
            if($res){
                $data = Db::name('order_car')
                    ->where(['order_num'=>$order_num,'type'=>$type])
                    ->field('id,container_code,seal,driver_name,phone,type')
                    ->select();
            }
           
        }
        
        return json($data);
    }
    
    //带装货 添加装货时间
    public function addLoadTime() 
    {   
//      $this->_p($this->request->param());exit;
        $order_num =$this->request->param('order_num');
        $track_num = $this->request->param('track_num');
        $modify = $this->request->param('modify'); //true修改  false不修改
        $lists = $this->request->only('list');
        $lists =$lists['list'];
        //更新order_car的时间
        $arr=[];
        $mtime = date('Y-m-d H:i:s');
        foreach ($lists as $value){
            $value['mtime']=$mtime;
            $id = $value['id'];
            if(!$modify){
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
        $lists = $lists['list'];
        // $this->_p($lists);exit;
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
                    ->join('hl_seaprice SP',"SP.id= OP.seaprice_id and SP.status='1'",'left') //海运价格表
                    ->join('hl_ship_route SR',"SR.id=SP.route_id and SR.status='1'",'left')//路线表
                    ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
                    ->join('hl_sea_middle SM','SR.middle_id=SM.sealine_id','left') //中间港口表    
                    ->join('hl_port P1','P1.port_code=SB.sl_start and P1.status=1','left')//起始港口
                    ->join('hl_port P2','P2.port_code=SB.sl_end and P2.status=1','left')//目的港口
                    ->join('hl_port P3','P3.port_code=SM.sl_middle and P3.status=1','left')//中间港口
                    ->field('P1.port_name s_port_name ,P1.port_code s_port_code,'
                    . 'P2.port_name e_port_name,P2.port_code e_port_code,'
                    . 'group_concat(distinct P3.port_name order by SM.sequence) m_port_name,'
                    . 'group_concat(distinct P3.port_code order by SM.sequence) m_port_code')
                    ->group('OP.id')->where('OP.type','door')->where('OP.order_num',$order_num)
                    ->fetchSql(false)->find();

            $insert_data =[];
            $insert_data[0]['order_num'] = $order_num;
            $insert_data[0]['sequence'] = 0;
            $insert_data[0]['port_name'] = $port_arr['s_port_name'];
            $insert_data[0]['port_code'] = $port_arr['s_port_code'];
            $m_port_name = explode(',', $port_arr['m_port_name']);
            $m_port_code = explode(',', $port_arr['m_port_code']);
            //中间港口不为空
            $i=0;
            if($port_arr['m_port_code']){
                for($i=0;$i<count($m_port_code);$i++) {
                    $insert_data[$i+1]['order_num'] = $order_num;
                    $insert_data[$i+1]['sequence'] = $i+1;
                    $insert_data[$i+1]['port_name'] = $m_port_name[$i];
                    $insert_data[$i+1]['port_code'] = $m_port_code[$i];
                }  
            }
      
            $insert_data[$i+2]['order_num'] = $order_num;
            $insert_data[$i+2]['sequence'] = $i+2;
            $insert_data[$i+2]['port_name'] = $port_arr['e_port_name'];
            $insert_data[$i+2]['port_code'] = $port_arr['e_port_code'];
           
            $insert_data = array_values($insert_data);
          
            $res = Db::name('order_ship')->insertAll($insert_data);
            foreach ($insert_data as $k=>$v){
                $insert_data[$k]['arrival_time']='';
                $insert_data[$k]['dispatch_time']='';
                 $insert_data[$k]['ship_name']='';
            }
            return json( $res ?  $insert_data: FALSE);    
        }
        return json($data); 
    }
    
    //待配船  判断倒数第二个港口离港时间录完就自动申请放柜
    public function  cargo_plan(){
        $order_num = $this->request->param('order_num');
        $lists  =  $this->request->only('list');
        $lists  = $lists['list'];
        $tmp =[]; $data_tmp=[];
        $mtime =  date('Y-m-d H:i:s');
        //判断港口数量是否对应的上
        $data = Db::name('order_ship')
                ->where('order_num',$order_num)
                ->field('port_code,port_name,ship_name,arrival_time,dispatch_time')
                ->order('sequence')->select();
        
        if(count($data) !== count($lists)){
            return json(array('status'=>0,'message'=>'参数错误'));
        }
        
        foreach ($data as $key => $value) {
            //对数据和数据库做比对
            $result = array_diff_assoc($data[$key],$lists[$key]);
            
            array_key_exists('port_code', $result)? $tmp[$key]['port_code'] = $value['port_code'] :FALSE;
            array_key_exists('port_name', $result)? $tmp['port_name'][$key] = $value['port_name'] :FALSE;
            array_key_exists('ship_name', $result)? $tmp[$key]['ship_name'] = $value['ship_name'] :FALSE;
            array_key_exists('arrival_time', $result)? $tmp[$key]['arrival_time'] = $value['arrival_time'] :FALSE;
            array_key_exists('dispatch_time', $result)? $tmp[$key]['dispatch_time'] = $value['dispatch_time'] :FALSE;
            
            //如果存在 说明有更改的,记录更改的港口
            if(isset($tmp[$key])){
                $data_tmp[]=$value['port_code'];
                continue;
            }
        } 
        //不符合数据库的数据
        if(array_key_exists('port_code', $tmp)){
            return json(array('status'=>0,'message'=>'数据冲突'.implode(',', $tmp['port_code']) ));
        }  
        $port_code_arr = array_column($data,'port_name','port_code'); //港口_code _name 数组
//        $this->_p($data_tmp);exit;
        //数据验证无问题，更新数据库
        foreach ($lists as $list){
            //过滤空的数据
            if(count(array_filter($list))==1){
                continue;
            }
            //过滤和数据库里没有变化的数据
            if(in_array($list['port_code'], $data_tmp)){
            $list['mtime']=$mtime;
            $port_code = $list['port_code'];
            $res = Db::name('order_ship')
                    ->where(['order_num'=>$order_num,'port_code'=>$port_code])
                    ->update($list);
            $res ? $tmp['success'][]=$port_code_arr[$port_code] :$tmp['fail'][]=$port_code_arr[$port_code];
            }  
        }
        
        if(array_key_exists('fail', $tmp)){
            return json(array('status'=>0,'message'=>'更新失败'.implode(',', $tmp['fail'])));
        }  elseif(array_key_exists('success', $tmp)) {
            //判断是否是倒数第二个港口更新了
            $num = count($port_code_arr);
            $map = array_keys($port_code_arr);
            $penult  = $map[$num-2]; //倒数第二个
            $tailender =$map[$num-1]; //倒数第一个
            if(in_array($penult, $data_tmp)){
                $this->apply_cargo($order_num); //提交扣柜申请
            }  
            if(in_array($tailender, $data_tmp)) {
                //更新订单状态同时记录操作
                $OrderProcessM = new OrderProcessM();
                $response = $OrderProcessM->orderUpdate($order_num,$status=$this->order_status['sea_waybill'],$title='船期录入完成->待上传水运单');
            }
            return json(array('status'=>1,'message'=>'更新成功'.implode(',', $tmp['success'])));
        }  else {
            return json(array('status'=>1,'message'=>'未更改'));
        }
        
    }
    
    //订单处理页面
    public function order_public() {
        return $this->view->fetch('order/order_public');
    }
    
    //订单处理页面 数据
    public function order_data() {
        $data= $this->request->except('limit,page');   
        $limit= $this->request->param('limit',10,'intval'); //获取每页显示的条数
        $page= $this->request->param('page',1,'intval');   //获取当前页数
        $search = array_key_exists('search', $data)? $data['search']:''; //搜索条件
        $status = array_key_exists('status', $data)? $data['status']:array(); //状态选择
        $status_arr = array_intersect_key($this->order_status, array_flip($status));
        //订单取消增加一种
        if(array_key_exists('stop', $status_arr)){
            $status_arr['cancel']= $this->order_status['cancel'];
        } 
        //订单完成增加一种
        if(array_key_exists('completion', $status_arr)){
            $status_arr['check_bill']= $this->order_status['check_bill'];
        }
        $dataM = new OrderM;
        $data = $dataM->order_public($page,$limit,$search,$status_arr);
        $list =$data['list']; //分页数据

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
    //    $this->_p($this->request->param());exit;
        $OrderProcessC =  new OrderProcessC();
        $res =$OrderProcessC->Upload($order_num,$type,$file);
        //上传成功就更改状态
        if($res['status']=='1'){
                $OrderM = new OrderProcessM();
                if($type=='book_note'){
                    $status =  $this->order_status['send_car'];
                    $title ='上传订舱单->待派车';
                    if(!empty(trim($track_num))){
                        $map =['track_num'=>$track_num];
                        $res= Db::name('order_port')->where('order_num',$order_num)->update($map);
                    }
                }elseif ($type=='sea_waybill') {
                    $status =  $this->order_status['unloading'];
                    $title ='上传水运单->待送货';
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
        //记录提操作者修改的时间
        if($res){
            $status = $this->order_status['container_appley'];
            $OrderM = new OrderProcessM();
            $res1 = $OrderM->orderUpdate($order_num,$status,$title='申请放柜子');
            return $res1; 
        }
        return $res? array('status'=>1,'mssage'=>'提交成功'):array('status'=>0,'mssage'=>'提交失败');     
    }



 
} 
