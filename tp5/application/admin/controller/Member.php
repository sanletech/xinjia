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
       $type = $this->request->param('type'); //企业用户company 个人用户person
       $account = $this->request->param('account');  //帐号搜索
        if($account){
             $this->view->assign('account',$account); 
        }
        if($type){
             $this->view->assign('type',$type); 
        }else{
            $type='company';
        }
        $identification= $this->request->param('identification');
        $identification= isset($identification)?$identification:'2';
        $this->view->assign('identification',$identification); 
        $user = new MemberM ;
        $list = $user->memberList($account,$type,$identification,1,'15');
    //    $this->_p($list);exit;
        $page = $list->render();
        $this->view->assign('image_path',ROOT_PATH . 'public' . DS . 'uploads/images');
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
        return $this->view->fetch('member/member_list'); 
    }
    //客户的认证通过与否 1未认证，2待认证,3为认证不通过，4为认证通过
    public function member_identification() {
        $id = $this->request->param('id');
        $identification = $this->request->param('audit');
        if($identification=='yes'){
            $identification =4;
        }else{
            $identification =3;
        }

        $res =Db::name('member')->where('id','in',$id)->update(['identification'=>$identification]);
        return $res ?TRUE:FALSE;
    }
    
    //禁止使用帐号
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
    public function  pushMoney(){
          //船公司的集合
        $ship_nameArr =Db::name('shipcompany')->where('status',1)->order('id')->column('ship_short_name');
        if(empty($ship_nameArr)){
            $this->error('船公司未添加,请先添加船公司的数据','admin/port/ship_list');
        }  else {
            $this->view->assign('ship_nameArr',$ship_nameArr);
        }
    //    $this->_p($ship_nameArr);exit;
        //所有业务的集合
        $salesArr =Db::name('user')->field('user_code,user_name')
                ->where('status','1')->where('type','sales')->select();
        $this->view->assign('salesArr',$salesArr);

          //获取每页显示的条数
          $limit= $this->request->param('limit',10,'intval');
          //获取当前页数
          $page= $this->request->param('page',1,'intval');  
          //计算出从那条开始查询
          $tol=($page-1)*$limit;
          $user = new MemberM ;
          $data = $user->pushMoneyList($tol,$limit);
        //  $this->_p($data['lists']);exit;
      $this->view->assign('lists',$data['lists']);
      $this->view->assign('count',$data['count']);
        return $this->view->fetch('member/pushMoney_List'); 
    }
    
    // public function  pushMoneyList() {
        
    //     //获取每页显示的条数
    //     $limit= $this->request->param('limit',10,'intval');
    //     //获取当前页数
    //     $page= $this->request->param('page',1,'intval');  
    //     //计算出从那条开始查询
    //     $tol=($page-1)*$limit;
    //     $user = new MemberM ;
    //     $lists = $user->pushMoneyList($tol,$limit);
    // //    $this->_p($lists);exit;
        
    //     return array('code'=>0,'msg'=>'','count'=>$lists['count'],'data'=>$lists['list']);
    // }
    
    //业务对应客户的提成管理的修改
    public function  pushMoneyEdit(){
     
        $data = $this->request->param();
        $data = $data['dataArray'];
//        $this->_p($data);exit;
        $arr= [];
        $dataArr = array_splice($data, -5);
        foreach ($dataArr as $key => $value) {
            $arr += $value;
        }
       
        $moneyArr = array_splice($data, 0,(count($data)/2));
        //形成以ship_id为键,money 为值的数组
//        $this->_p($data);$this->_p($moneyArr);exit;
        $money_ship = array_combine(array_column($data,'ship_id'),array_column($moneyArr,'money'));
        
        $sales['sales_name'] = $arr['sales_name'];
        $sales['sales_code']= $arr['sales_code'];
        $member_code= $arr['member_code'];
        //更新业务表客户对应的业务员
        $res =Db::name('sales_member')->where('member_code',$member_code)->update($sales);
        $status[] =$res ? true :false;
        unset($arr['sales_name'],$arr['sales_code'],$arr['member_code'],$arr['member_name'],$arr['customer_id']);
        $mtime =date('Y-m-d H:i:s');
        foreach ($money_ship as $shipSql =>$moneySql){
            //如果客户对应的船公司如果存在就更新 否则就添加
            $res2 =Db::name('member_profit')->where('member_code',$member_code)
                    ->where('ship_id',$shipSql)->find();
            if(empty($res2)){
                $res1 =Db::name('member_profit')
                    ->insert(['member_code'=>$member_code,'ship_id'=>$shipSql,'money'=>$moneySql,'mtime'=>$mtime]);
            }else{
                $res1 =Db::name('member_profit')->where('member_code',$member_code)
                    ->where('ship_id',$shipSql)
                    ->update(['money'=>$moneySql,'mtime'=>$mtime]);
            }            
            $status[] =$res1 ? true :false;
        }
        if(array_key_exists('false', $status)){
            return array('status'=>0,'message'=>'客户提成修改失败');
        }else{
            return array('status'=>1,'message'=>'客户提成修改成功');
        }
  
    }
    
    //在线支付优惠 和临时在线优惠
    public function discount() {
        $type = input('get.type'); //船公司ship_name //活动名称promotion_title
        $account = input('get.account');  //帐号搜索条件
        $status = input('get.status');//优惠状态   
        if($account){
            $this->view->assign('account',$account);
        }
        $type?$type:$type='ship_name';
        $status?$status:$status=1;         
        $this->view->assign('type',$type); 
        $this->view->assign('status',$status); 
        
        $user = new MemberM ;
        $list = $user->discount($type,$account,$status,5);
//        $this->_p($list);exit;
        $page = $list->render();
        $this->view->assign('list',$list);
        $this->view->assign('page',$page);
      
        
        return $this->view->fetch('discount/discount_list'); 
        
    }
    
    
    //用户列表修改
    public function memberEdit()
    {
        $id = input('get.id');
        
        return $this->view->fetch('member/member_edit'); 
    }
    //根据用户前台搜搜的记录做回访
    public function memberCallback()
    {
        return $this->view->fetch('member/member_callback'); 
    }
    //用户状态修改
    public function state_edit()
    {
        return $this->view->fetch('member/state_edit'); 
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
        return $this->view->fetch('member/disable_list'); 
    }

    //    //客户对不同柜子不同船公司的优惠的list
    //    public function  discountList() {
    //        $type = input('get.type'); //船公司ship_name 用户customer
    //        $account = input('get.account');  //帐号搜索
    //        if($account){
    //             $this->view->assign('account',$account); 
    //        }
    //        $type ?$type:'customer';
    //        $this->view->assign('type',$type); 
    //        $user = new MemberM ;
    //        $list = $user->discountList($type,$account,5);
    ////        $this->_p($list);exit;
    //        $page = $list->render();
    //        $this->view->assign('list',$list);
    //        $this->view->assign('page',$page);
    //        return $this->view->fetch('discount/discount_list'); 
    //    }
    //    
    //    //客户对不同柜子不同船公司的优惠 的修改
    //    public function discountEdit() {
    //        $data = $this->request->param();
    //        $this->_p($data);exit;
    //       
    //    }
    
    

}
