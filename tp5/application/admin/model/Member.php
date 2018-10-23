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
//        var_dump($list);exit;
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
        $sql = " (SELECT M.id,M.member_code,M.name,SC.status ,SC.id AS ship_id"
                . " FROM hl_member M CROSS JOIN hl_shipcompany SC "
                . " WHERE SC.status = '1' and M.status='1' order by M.id,SC.id)";

        $list = Db::table($sql.' A')
            ->join('hl_member_profit MP','MP.member_code=A.member_code and A.ship_id=MP.ship_id','left')
            ->join('hl_sales_member SM','SM.member_code = A.member_code','left')
            ->field('A.*,MP.money,SM.sales_name,SM.sales_code')
            ->group('A.member_code,A.ship_id')->order('A.id,A.ship_id')->buildSql();  
//var_dump($list);exit;
        $pageParam  = ['query' =>[]]; //设置分页查询参数  
        if($type=='sales'&&!empty($account)){
            $list =Db::table($list.' b')
                ->where('b.sales_name','like',"%{$account}%")
                ->whereOr('b.sales_code',$account)
                ->buildSql(); 
        } 
    
        if($type=='customer'&&!empty($account)){
            $list =Db::table($list.' c')
                ->where('c.name','like',"%{$account}%")
                ->whereOr('c.member_code',$account)   
                ->buildSql(); 
        }
        $pageParam['query']['account'] = $account;
        $pageParam['query']['type'] = $type;
        //var_dump($list);exit; //->paginate(20,false,$pageParam)
        $pages = (Db::name('shipcompany')->where('status',1)->count())*$pages;
//        var_dump($list);exit; var_dump($pages);exit;

        $list =Db::table($list.' d')->paginate($pages,false,$pageParam);
        $page = $list->render();
        $tem =[]; 
        foreach ($list as  $row) {
           $key= $row['member_code'];
        $tem[$key]['id'] = $row['id'];
        $tem[$key]['member_code'] = $row['member_code'];  
        $tem[$key]['name'] = $row['name'];
        $tem[$key]['status'] = $row['status'];
        $tem[$key]['sales_name'] = $row['sales_name'];
        $tem[$key]['sales_code'] = $row['sales_code'];
        $tem[$key]['money'][] = $row['money'];
        $tem[$key]['ship_id'][] = $row['ship_id'];
       
        }
        $tem = array_values($tem);
        return array($tem,$page);       
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
                ->join('hl_shipcompany SC',"SC.id = DN.ship_id and SC.status='1'",'left')
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
                            
        $list = Db::name('discount')->alias('DS')
                ->join('hl_shipcompany SC',"SC.id = DS.ship_id and SC.status='1'",'left')
                ->field('DS.*,SC.ship_short_name')
                >where('DS.status','')
                ->group('DS.id')->order('DS.add_time')->buildSql();    

        $pageParam  = ['query' =>[]]; //设置分页查询参数  
        if($type=='ship_name'&&!empty($account)){
            $list =Db::table($list.' b')
                ->where('b.ship_short_name','like',"%{$account}%")
                ->buildSql(); 
        } 
        if($type=='promotion_title'&&!empty($account)){
            $list =Db::table($list.' c')
                ->where('c.promotion_title','like',"%{$account}%")
                ->buildSql(); 
        }
            $pageParam['query']['account'] = $account;
            $pageParam['query']['type'] = $type;
            
        $list =Db::table($list.' d')->order('d.id')->paginate($pages,false,$pageParam);  
       
        return $list;
    }
 }
?>