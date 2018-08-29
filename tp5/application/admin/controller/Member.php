<?php
/*
 * 用户管理
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Member as MemberM ;
use think\Request;
use think\Db;
class Member extends Base
{    

 
    //用户列表
    public function memberList()
    {   
       $type = input('get.type'); //企业用户company 个人用户person
       $account = input('get.account');  //帐号搜索
        if($account){
             $this->view->assign('account',$account); 
        }
        if($type){
             $this->view->assign('type',$type); 
        }else{
            $type='company';
        }
        $user = new MemberM ;
        //var_dump($account,$type,1,'5');
        $list = $user->memberList($account,$type,1,'5');
//       $this->_p($list);exit;
        $page = $list->render();
        $this->view->assign('list',$list);
         $this->view->assign('page',$page);
        return $this->view->fetch('Member/member_list'); 
    }
    //精致使用帐号
    public function memberStop() {
        $id = $this->request->param();
        $res =Db::name('member')->where('id','in',$id)->update(['status'=>'0']);
        return $res ?TRUE:FALSE;
    }
    //恢复帐号的使用
    public function memberEnabled() {
        $id = $this->request->param();
        $res =Db::name('member')->where('id','in',$id)->update(['status'=>'1']);
        return $res ?TRUE:FALSE;
    }
    //帐号的删除
    public function memberDel() {
        $idArr = $this->request->param();
        $id =$idArr['id'];
        $res =Db::name('member')->where('id','in',$id)->delete();
        //var_dump(Db::getLastSql());exit;
        return $res ?TRUE:FALSE;
    }
    
    //业务对应客户的提成管理
    public function  pushMoneyList() {
        //$shipCompany = Db::query("select COLUMN_NAME,column_comment from INFORMATION_SCHEMA.Columns where table_name='hl_member_profit'");
        $type = input('get.type'); //业务sales 用户customer
        $account = input('get.account');  //帐号搜索
        if($account){
             $this->view->assign('account',$account); 
        }
        $type ?$type:'sales';
        $this->view->assign('type',$type); 
        $user = new MemberM ;
        $list = $user->pushMoneyList($type,$account,5);
        //所有业务的集合
        $salesArr =Db::name('salesman')->field('sales_code,sales_name')->where('status','1')->select();
        //船公司的集合
        $ship_nameArr = $list[0]['ship_name'];
       // $ship_name = Db::name('shipcompany')->column($field)
     //   $this->_p($ship_nameArr);exit;
      //  $page = $list->render();
        $this->view->assign('list',$list);
        $this->view->assign('salesArr',$salesArr);
        $this->view->assign('ship_nameArr',$ship_nameArr);
      //  $this->view->assign('page',$page);
        $this->view->assign('ship_nameArr',$ship_nameArr);
        return $this->view->fetch('Member/pushMoney_List'); 
    }
    //业务对应客户的提成管理的修改
    public function  pushMoneyEdit(){
        $data = $this->request->param();
        $arr= [];
        $dataArr = array_splice($data, -5);
        foreach ($dataArr as $key => $value) {
            $arr += $value;
        }
        $moneyArr = array_splice($data, 0,(count($data)/2));
        //形成以ship_id为键,money 为值的数组
       // $this->_p($data);$this->_p($moneyArr);exit;
        $money_ship = array_combine(array_column($data,'ship_id'),array_column($moneyArr,'money'));
       
        //sales_code
        $sales['sales_name'] = $arr['sales_name'];$sales['sales_code']= $arr['sales_code'];
        $member_code= $arr['member_code'];$id = $arr['customer_id'];
        $res =Db::name('sales_member')->where('member_code',$member_code)->update($sales);
        $status[] =$res ? true :false;
        unset($arr['sales_name'],$arr['sales_code'],$arr['member_code'],$arr['member_name'],$arr['customer_id']);
        $mtime =date('y-m-d h:i:s');
        foreach ($money_ship as $shipSql =>$moneySql){
            $res1 =Db::name('member_profit')->where('member_code',$member_code)
                    ->where('ship_id',$shipSql)
                    ->update(['money'=>$moneySql,'mtime'=>$mtime]);
            $status[] =$res1 ? true :false;
        }
        if(array_key_exists('false', $status)){
            return 0;
        }
        return 1;
    }
    
    //用户列表修改
    public function memberEdit()
    {
        $id = input('get.id');
        
        return $this->view->fetch('Member/member_edit'); 
    }
    //根据用户前台搜搜的记录做回访
    public function memberCallback()
    {
        return $this->view->fetch('Member/member_callback'); 
    }
    //用户状态修改
    public function state_edit()
    {
        return $this->view->fetch('Member/state_edit'); 
    }

    //禁用账号
    public function disableList()
    {   
        $type = input('get.type'); //企业用户company 个人用户person
        $account = input('get.account');  //帐号搜索
        if($account){
             $this->view->assign('account',$account); 
        }
        if($type){
             $this->view->assign('type',$type); 
        }else{
            $type='company';
        }
        $user = new MemberM ;
        $list = $user->memberList($account,$type,0,'5');
        $page = $list->render();
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('Member/disable_list'); 
    }

    public function mList()
    {
        //1.读取会员表的信息
        $member= User::where('status',1)->paginate(10);
               
        //2.将会员的信息赋值给模版
        $count = count($member); 
        // 获取分页显示
      $page = $member->render();
   
      $this->view->assign('member',$member);
      $this->view->assign('page',$page);
       $this->view->assign('count', $count);
        //3.渲染模版
     //  print_r($member);exit;
      return $this->view->fetch('Member/member_list'); 
       
    }
    
    public function mAdd()
    {
      
        return $this->view->fetch('member/member_add'); 
       
    }
    
      public function toAdd()
    {
      $data= array_filter($this->request->param());
    //修改数据库将
      $data['create_time']=  time();
      $data['status'] = 1;
      $data['password']=  md5($data['password']);
      unset($data['repassword']);
      var_dump($data);
     $res= User::insert($data);
       
     if(is_null($res)){
            $status=1;
            $message='更新失败';
        }else{
            $status=0;
            $message='更新成功';
        }    
        
    return ['status'=>$status];  
       
       
    }
    public function mDel()
       {
          return $this->view->fetch('member/member_del'); 

       }
      /*执行删除*/
    public function toDel()
   {
      $data= $this->request->param();
    // 将$data[id]数组组成查询
    //修改数据库将status 状态改为0
     $res= User::where('id','in',$data['id'])
             ->update(['status'=>0]);
     if(is_null($res)){
            $status=1;
            $message='更新失败';
        }else{
            $status=0;
            $message='更新成功';
        }      
    return ['status'=>$status,'message'=> $message];  
   }  
       
    public function mEdit()
       {
            //1.读取会员表的信息
        $member= User::get($this->request->param('id'));
       
        //2.将会员的信息赋值给模版
         $this->view->assign('member',$member);
        //3.渲染模版
           return $this->view->fetch('member/member_edit'); 

       }  
     
    


       public function mUpdate()
   {   
         
        //获取提交的数据，自动过滤一下空值
        $data= $this->request->param();
       
        $map=['id'=> $data['id']];
        if(array_key_exists('password',$data)){
            $data['password']=  md5($data['password']);
        }
       
        //修改数据库
        $res= User::update($data,$map);

       if(is_null($res)){
            $status=1;
            $message='更新失败';
        }else{
            $status=0;
            $message='更新成功';
        }  
        $arr = array('status'=>$status,'message'=>$message);
       // echo '111'; 
        //return json_encode($arr);
     return ['status'=>$status,'message'=> $message];
   }    
       
 
   
    public function mKiss()
    {

        return $this->view->fetch('member/member_kiss'); 

    }  
     public function mLevel()
    {

        return $this->view->fetch('member/member_level'); 

    }
      public function mPassword()
    {
         $member= User::getById($this->request->param('id'));
         $this->view->assign('member',$member);
        return $this->view->fetch('member/member_password'); 

    }
      public function mView()
    {

        return $this->view->fetch('member/member_view'); 

    }

}
