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

    public function memberList($account,$type,$identification,$status,$pages='5'){
        $list =Db::name('member')->alias('a')
                ->join('hl_sales_member b','b.member_code = a.member_code','left')
                ->where('a.type','in',$type)
                ->where('a.status',$status)
                ->where('a.identification',"$identification")
                ->field('a.id,a.name,a.create_time,a.logintime,a.phone,a.email,'
                        . 'a.status,a.company,a.member_code,a.identification,a.file_path,b.sales_name')
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
    public function  pushMoneyList($tol,$limit) {
        $sql = " (SELECT M.id,M.member_code,M.name,SC.status ,SC.id AS ship_id"
                . " FROM hl_member M CROSS JOIN hl_shipcompany SC "
                . " WHERE SC.status = '1' and M.status='1' order by M.id,SC.id)";

        $list = Db::table($sql.' A')
            ->join('hl_member_profit MP','MP.member_code=A.member_code and A.ship_id=MP.ship_id','left')
            ->join('hl_sales_member SM','SM.member_code = A.member_code','left')
            ->field('A.*,MP.40HQ,MP.20GP,SM.sales_name,SM.sales_code')
            ->group('A.member_code,A.ship_id')->order('A.id,A.ship_id')->buildSql();  

        $count = (Db::name('shipcompany')->where('status',1)->count());
        $limit = $count*$limit;
        $tol =  $count*$tol;
        $list =Db::table($list.' d')->limit($tol,$limit)->select();;
        $tem =[]; 
//        $this->_p($list);exit;
        foreach ($list as $row) {
            $key= $row['member_code'];
      
            $tem[$key]['id'] = $row['id'];
            $tem[$key]['member_code'] = $row['member_code'];  
            $tem[$key]['name'] = $row['name'];
            $tem[$key]['status'] = $row['status'];
            $tem[$key]['sales_name'] = $row['sales_name'];
            $tem[$key]['sales_code'] = $row['sales_code'];
            $tem[$key]['40HQ'][] = $row['40HQ'];
            $tem[$key]['20GP'][] = $row['20GP'];
            $tem[$key]['ship_id'][] = $row['ship_id'];

        }
        $tem = array_values($tem);
        foreach ($tem as $key => $value) {
            foreach ($tem[$key]['40HQ'] as $k => $v) {
                $tem[$key]['40HQ_'.$k]=$v;
            }
            unset($tem[$key]['40HQ']);
        }
        foreach ($tem as $key => $value) {
            foreach ($tem[$key]['20GP'] as $k => $v) {
                $tem[$key]['20GP_'.$k]=$v;
            }
            unset($tem[$key]['20GP']);
        }
        
       
        return array('list'=>$tem,'count'=>$count);       
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

    public function  discount($type,$account,$status,$pages='10'){
                            
        $list = Db::name('discount')->alias('DS')
                ->join('hl_shipcompany SC',"SC.id = DS.ship_id and SC.status='1'",'left')
                ->field('DS.*,SC.ship_short_name')
                ->where('DS.status',$status)
                ->group('DS.id')->order('DS.add_time')->buildSql();    

        $pageParam  = ['query' =>[]]; //设置分页查询参数  
        if($type=='ship_name'&&!empty($account)){
            $list =Db::table($list.' b')
                ->where('b.ship_short_name','like',"%{$account}%")
                ->buildSql(); 
        } 
        if($type=='title'&&!empty($account)){
            $list =Db::table($list.' c')
                ->where('c.title','like',"%{$account}%")
                ->buildSql(); 
        }
        $pageParam['query']['account'] = $account;
        $pageParam['query']['type'] = $type;
        $pageParam['query']['status'] = $status;
        $list =Db::table($list.' d')->order('d.id')->paginate($pages,false,$pageParam);  
       
        return $list;
    }
 }
?>