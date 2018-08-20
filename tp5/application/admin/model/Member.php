<?php
namespace app\admin\model;
use think\Model;
use think\db;
class Member extends Model
{
    public function  getJoinDateAttr($value)
    {
        return date('Y-m-d ',$value);
    }
    
      public function  getSexAttr($value)
    { 
         $status = [0=>'男',1=>'女'];
        return  $status[$value];
    }
    
    public function memberList($account,$type,$pages='5'){
        //'id ,membername ,create_time ,logintime ,phone ,email ,status ,remark,update_time ,meber_leve ,member_code '
        $list =Db::name('member')->alias('a')
                ->join('hl_sales_member b','b.member_code = a.member_code')
                ->where('a.type',$type)
                ->field('a.id,a.name,a.create_time,a.logintime,a.phone,a.email,a.status,a.company,a.member_code,b.sales_name')
                ->buildSql();
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($account){
          
            $list = Db::table($list.' a')->where('a.membername','like',"%{$account}%")->whereOr('a.member_code',"%{$account}%")->buildSql();
            $pageParam['query']['account'] = $account;
        }
      // var_dump($list);exit;
        $list =Db::table($list.' b')->order('id,logintime')->paginate($pages,false,$pageParam);  
        return $list;
        
    }
    
}



?>