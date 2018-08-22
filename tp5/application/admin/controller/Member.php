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
      //  array_column($list, $list)
        
       // $this->_p($list);exit;
        $page = $list->render();
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('Member/pushMoney_List'); 
    }
    //业务对应客户的提成管理的修改
    public function  pushMoneyEdit(){
        $id = input('get.id'); //客户id
      //  查询对应业务 和利润设置价格
        $list = Db::name('sales_member')->alias('SM')
               ->join('hl_member_profit MP','MP.member_code = SM.member_code','left')
               ->field('SM.sales_name,SM.sales_code,SM.member_name,MP.*')
                ->where('MP.id',$id)
               ->group('MP.member_code')->order('SM.id');
        $salesList =Db::name('sales_member')->column('sales_code,sales_name');
        $this->view->assign('list',$list);
        $this->view->assign('salesList',$salesList);
        return $this->view->fetch('Member/member_edit');
        
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
