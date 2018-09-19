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
    
    public function memberList($account,$type,$status,$pages='5'){
        //'id ,membername ,create_time ,logintime ,phone ,email ,status ,remark,update_time ,meber_leve ,member_code '
        $list =Db::name('member')->alias('a')
                ->join('hl_sales_member b','b.member_code = a.member_code','left')
                ->where('a.type','in',$type)
                ->where('status',$status)
                ->field('a.id,a.name,a.create_time,a.logintime,a.phone,a.email,a.status,a.company,a.member_code,b.sales_name')
                ->buildSql();
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($account){
          
            $list = Db::table($list.' a')->where('a.name','like',"%{$account}%")->whereOr('a.member_code','like',"%{$account}%")->buildSql();
            $pageParam['query']['account'] = $account;
        }
    
        $list =Db::table($list.' b')->order('id,logintime')->paginate($pages,false,$pageParam);  
        return $list;
        
    }
    
     //业务对应客户的提成管理
        public function  pushMoneyList($type,$account,$pages='10') {
            
           $list = Db::name('member_profit')->alias('MP')
               ->join('hl_sales_member SM','MP.member_code = SM.member_code','left')
                ->join('hl_shipcompany SC','SC.id = MP.ship_id','left')
               ->field('MP.id,MP.member_code,SM.member_name,SM.sales_name,SM.sales_code,'
                       . "group_concat(MP.ship_id order by MP.ship_id separator ',') ship_id,"
                       . "group_concat(SC.ship_short_name order by MP.ship_id separator ',') ship_name,"
                       . "group_concat(MP.money order by MP.ship_id separator ',') money")
               ->group('MP.member_code')->order('SM.id')->buildSql();    
//var_dump($list);exit;

        $pageParam  = ['query' =>[]]; //设置分页查询参数  
        if($type=='sales'&&!empty($account)){
            $list =Db::table($list.' b')
                ->where('b.sales_name','like',"%{$account}%")
                ->whereOr('b.sales_code','like',"%{$account}%")
                ->buildSql(); 
        } 
    
        if($type=='customer'&&!empty($account)){
            $list =Db::table($list.' c')
                ->where('c.member_name','like',"%{$account}%")
                ->whereOr('c.member_code','like',"%{$account}%")   
                ->buildSql(); 
        }
            $pageParam['query']['account'] = $account;
            $pageParam['query']['type'] = $type;
            //var_dump($list);exit; //->paginate(20,false,$pageParam)
        $list =Db::table($list.' d')->order('d.id')->select();  
        $listArr=[];
        foreach ($list as $key=> $value) {
//            var_dump($value);exit;
               $list[$key]['ship_id'] = explode(',', $value['ship_id']);
               $list[$key]['ship_name'] = explode(',', $value['ship_name']);
               $list[$key]['money'] = explode(',', $value['money']);
             
        }
  //  $this->_p($list);exit;
        return $list;       
    }

    
    public function  pushMoneyEdit($memberID){
        $res =Db::name('member_profit')->alias('MP')
                ->join('hl_sales_member SM','SM.member_code=MP.member_code','left')
                ->where('MP.id',$memberId)
                ->group('MP.member_code')->order('SM.id')
                ->select();
    }
    
    public function  discountList($type,$account,$pages='10'){
                    
           $list = Db::name('discount_normal')->alias('DN')
                ->join('hl_member M','M.member_code=DN.member_code','left')
                ->join('hl_shipcompany SC','SC.id = DN.ship_id','left')
                ->field('DN.*,M.name member_name,SC.ship_short_name')
               ->group('DN.member_code')->order('DN.id')->buildSql();    

        $pageParam  = ['query' =>[]]; //设置分页查询参数  
        if($type=='ship_name'&&!empty($account)){
            $list =Db::table($list.' b')
                ->where('b.ship_short_name','like',"%{$account}%")
                ->buildSql(); 
        } 
        if($type=='customer'&&!empty($account)){
            $list =Db::table($list.' c')
                ->where('c.member_name','like',"%{$account}%")
                ->whereOr('c.member_code','like',"%{$account}%")   
                ->buildSql(); 
        }
            $pageParam['query']['account'] = $account;
            $pageParam['query']['type'] = $type;
        $list =Db::table($list.' d')->order('d.id')->paginate($pages,false,$pageParam);  
        return $list; 
    }

    public function  discountSpecial($type,$account,$pages='10'){
                            
        $list = Db::name('discount_special')->alias('DS')
                ->join('hl_shipcompany SC','SC.id = DS.ship_id','left')
                ->field('DS.*,SC.ship_short_name')
                ->group('DS.id')->order('DS.id')->buildSql();    

        $pageParam  = ['query' =>[]]; //设置分页查询参数  
        if($type=='ship_name'&&!empty($account)){
            $list =Db::table($list.' b')
                ->where('b.ship_short_name','like',"%{$account}%")
                ->buildSql(); 
        } 
        if($type=='discount_title'&&!empty($account)){
            $list =Db::table($list.' c')
                ->where('c.discount_title','like',"%{$account}%")
                ->buildSql(); 
        }
            $pageParam['query']['account'] = $account;
            $pageParam['query']['type'] = $type;
        $list =Db::table($list.' d')->order('d.id')->paginate($pages,false,$pageParam);  
        return $list;
        
    }
 }
?>