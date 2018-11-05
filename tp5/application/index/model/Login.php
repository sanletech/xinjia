<?php

namespace app\index\model;
use think\Model;
use think\Db;
class Login extends Model
{
    public function register($data) {
        $map['name']=$data['name'];
     //   $map['company'] =$data['company'];
        $map['password'] =md5($data['password']);
        $map['create_time'] = date('y-m-d');
        $map['phone'] = $data['phone'];//手机号码
        $IDCode = controller('IDCode');
        //查询用户表最大的id 生成零时客户member_code
        $id =Db::name('member')->max('id')+1;
        $member_code = $IDCode->create($id, 'zh');
        $map['member_code'] = $member_code;//登录帐号
        if(!empty($company)){
            $type ='company';
        }else{
            $type ='person';
        }        
        $res =Db::name('member')->insert($map);
       
        $this->memberProfit($member_code);
    //     $this->memberDiscount($member_code);
    //    var_dump($res);exit;
        return $res?true:false;;
    }
    //设置客户的利润,提成点
    public function memberProfit($member_code) {
            $ship_name =Db::name('shipcompany')->column('id');
            $data=[]; $mtime =date('y-m-d h:i:s');
            foreach ($ship_name as $key=>$value) {
                $data[$key]['member_code']=$member_code;
                $data[$key]['ship_id']= $value;
                $data[$key]['40HQ']= 200;
                $data[$key]['20GP']= 100;
                $data[$key]['mtime']= $mtime;
            }
//            $this->_v($data);exit;
            //同时默认设置客户的利润价格为200
            $res = Db::name('member_profit')->insertAll($data);
            return $res ? TRUE : FALSE;
    }
    
    //设置客户的在线支付优惠
    public function memberDiscount() {
            $ship_name =Db::name('shipcompany')->column('id');
            $data=[]; $mtime =date('y-m-d h:i:s');
            foreach ($ship_name as $key=>$value) {
                $data[$key]['ship_id']= $value;
                $data[$key]['40HQ']= 200;
                $data[$key]['20GP']= 100;
                $data[$key]['mtime']= $mtime;
            }
//            $this->_v($data);exit;
            //同时默认设置客户的初始在线支付优惠价格
            $res = Db::name('discount')->insertAll($data);
            return $res ? TRUE : FALSE;
            //var_dump($res);exit;
    }
    
}
