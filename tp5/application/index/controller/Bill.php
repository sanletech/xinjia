<?php
//客户的账单
namespace app\index\controller;
use app\index\common\Base;
use think\Db;
use think\Session;
class Bill extends Base 
{    
    public $order_status;
    public $page=5;

    public function _initialize()
    {  
        $this->order_status=config('config.order_status');
    }
    
    //海运运价
    public function billCreate($order_num){
        $data =Db::name('order_port')
                ->field('order_num,container_size,container_sum,comment,'
                        . 'quoted_price,member_code,type')
                ->where('order_num',$order_num)->find();
        $data['ctime']=  date('Y-m-d H:i:s');
        //插入订单的数据
        $ID =Db::name('order_bill')->insertGetId($data);
        if($ID){
             //根据返回的自增Id 生成账单编码
            $IDCode = controller('IDCode');
            $bill_num = $IDCode->create($ID,'ZD',3);
            $res= Db::name('order_bill')->where('id',$ID)->update(['bill_num'=>$bill_num]);
            $res?$response['success'] ='账单生成功' :$response['fail'] ='账单生成失败'; 
        }  else {
            $response['fail'] ='账单添加数据失败';
        }
        return $response;
    }
    //账单展示
    public function billList() {
        //所用的账单 done 未完成 undone
        $type = $this->request->param('type');
//        var_dump($type);exit;
        if($type=='done'){
            $money_status = 'do';
        }elseif($type=='undone'){
            $money_status = 'nodo';
        }elseif ($type=='all' ) {
            $money_status ='not null';
        }
      
        $page =$this->request->param('page',1,'intval');
        $limit =$this->request->param('limit',10,'intval');
    
        $member_code =Session::get('member_code','think');
        $list =Db::name('order_bill')->alias('OB')
                ->join('hl_order_port OP','OB.order_num=OP.order_num','left')
                ->where('OB.member_code',$member_code)
                ->field('OB.*,OP.extra_info,OP.money_status,OP.container_buckle,OP.container_status,OP.status')->where('OP.money_status',$money_status)
                ->order('OB.ctime desc,OB.mtime desc')->buildSql();
//var_dump($list);exit;
        $count = Db::table($list.' a')->count();
        $list = Db::table($list.' a')->page($page,$limit)->select();
        //转换付款状态,账单状态，备注信息  $this->order_status
        foreach ($list as $key => $value) {
            switch ($value['status']) {
                case $this->order_status['stop']:
                case $this->order_status['cancel']:
                $list[$key]['status'] ='订单中止';
                break;
                case $this->order_status['completion']:            
                $list[$key]['status'] ='订单完成';
                break;
                case $this->order_status['order_audit']:            
                $list[$key]['status'] ='订单审核中';
                break;
                default:
                $list[$key]['status'] ='订单进行中';
                break;
            }
            switch ($value['money_status']) {
                case 'nodo':
                $list[$key]['money_status'] ='未付款';
                break;
                case 'do':            
                $list[$key]['status'] ='已付款';
                break;
                default:
                $list[$key]['status'] ='参数有变化';
                break;
            }
            $list[$key]['comment']= trim(($value['comment'].'</br>'.$value['extra_info']),' ');
        }
//        $this->_p($list);exit;
        
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$list);
    }
}