<?php
/*
 *  权限管理
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class Keeper extends Base
{

    //管理员列表
    public function admin_list() 
    {
        return $this->view->fetch('Keeper/admin_list'); 
    }
    //添加管理员
    public function admin_add() 
    {
      return $this->view->fetch('Keeper/admin_add'); 
    }
    //修改管理员
    public function admin_edit() 
    {
        return $this->view->fetch('Keeper/admin_edit'); 
    }

    //划分区域列表
    public function area_list() 
    {  
        $list =Db::name('user')->alias('U')
                ->join('hl_user_area UA','UA.user_id=U.id','left')
                ->join('hl_user_team UT','UT.uid=U.id','left')
                 ->join('hl_auth_group AG','AG.id=UT.team_id','left')
                ->join('hl_auth_group_access AA','AA.uid=U.id','left')
                ->field('U.id,U.user_code,U.user_name,U.type,U.status,UA.area_code,UA.area_type,UT.title')
                ->group('U.id')->select();
        foreach($list as $key =>$value ){
            $type =$value['area_type'];
            $area_code =$value['area_code'];
            if($type=='city'){
                $list[$key]['area_list']= Db::name('city')->where('city_id','in',$area_code)->column('city');//,'city_id'
            }elseif ($type=='port') {
                $list[$key]['area_list']= Db::name('port')->where('id','in',$area_code)->column('port_name');//,'port_code'
            }
        }
        //$this->_p($list);exit;
        $this->view->assign('list',$list);
        return $this->view->fetch('Keeper/user_list'); 
    }
    
    //停用账户
    public function userStop() {
        $id = $this->request->get('id');
        $status = $this->request->get('status');
        $res =Db::name('user')->where('id',$id)->update(['status'=>$status]);
        return $res?1:0;;
    }

    //修改用户
    public function userEdit() 
    {   
        
        return $this->view->fetch('Keeper/user_edit'); 
    }
} 