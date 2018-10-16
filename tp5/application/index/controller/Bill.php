<?php
//客户的账单
namespace app\index\controller;
use app\index\common\Base;
use think\Db;
use think\Session;
class Bill extends Base 
{    
    //海运运价
    public function billCreate($order_num){
        
        $data =Db::name('order_port')
                ->field('order_num,container_size,container_sum,comment,status,'
                        . 'quoted_price')
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
        $member_code =Session::get('member_code','think');
        $list =Db::name('order_bill')->where('member_code',$member_code)
                ->field('member_code',TRUE)->select();
        return $list;
    }

}