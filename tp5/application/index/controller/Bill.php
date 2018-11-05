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
                ->field('order_num,container_size,container_sum,comment,extra_info,'
                        . 'status,money_status,quoted_price,member_code')
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
        if($type=='done'){
            $money_status ='not null';
        }elseif($type=='undone'){
            $money_status = 0;
        }
      
        $page =$this->request->param('page',1,'intval');
        $limit =$this->request->param('limit',10,'intval');
        $tol = ($page-1)*$limit;
        $member_code =Session::get('member_code','think');
        $list =Db::name('order_bill')->where('member_code',$member_code)
                ->field('member_code',TRUE)->where('money_status',$money_status)
                ->order('ctime desc,mtime desc')->buildSql();
        $count = Db::table($list.' a')->count();
        $list = Db::table($list.' a')->limit($tol,$limit)->select();
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
                default:
                $list[$key]['status'] ='下单成功';
                break;
            }
            switch ($value['money_status']) {
                case 0:
                $list[$key]['money_status'] ='未付款';
                break;
                case 1:            
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