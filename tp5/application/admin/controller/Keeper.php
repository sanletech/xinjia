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
    public function adminList() 
    {
        return $this->view->fetch('Keeper/admin_list'); 
    }
    //添加员工
    public function userAdd() {
        $array = Db::name('team')->group('id')->select();
        $list = $this->generateTree($array);
        $this->view->assign('jobList',$list);
        return $this->view->fetch('Keeper/user_add');
        
    }
    public function userToAdd() {
        $data= $this->request->param();
        $job =$data['job'];//team表里职位id
        $userdata=[];
        $userdata['user_name'] =$data['user_name'];
        $userdata['passwoed'] =$data['password'];
        $teamJob =Db::name('team')->where('id',$job)->value('job');
        $userdata['type']=$teamJob;
        if(strstr($teamJob,'service')){
            $jobName='kf';
        }elseif (strstr($teamJob,'sale')) {
            $jobName='yw';
        }elseif (strstr($teamJob,'finance')){
            $jobName='cw';
        }
        $user_max_id =Db::name('user')->max(id);
        if($user_max_id<10000){
            $user_max_id = sprintf("%05d",$user_max_id);
        }
        $userdata['user_code'] = $jobName.$user_max_id;
        $userdata['loginname'] = $user_code;
        $userdata['create_time'] = date('y-m-d h:i:s');
        $res =Db::name('user')->insert($userdata);
        $userId = Db::name('user')->getLastInsID();
        $res2 =Db::name('user_team')->insert(['uid'=>$userId,'team_id'=>$job]);
        if($res&&$res2){
            return 1;
        }
        return 0;
        
    }

    //划分区域列表
    public function areaList() 
    {  
        $list =Db::name('user')->alias('U')
                ->join('hl_user_area UA','UA.user_id=U.id','left')
                ->join('hl_user_team UT','UT.uid=U.id','left')
                ->join('hl_team T','T.id=UT.team_id','left')
                ->join('hl_auth_group_access AA','AA.uid=U.id','left')
                ->field('U.id,U.user_code,U.user_name,U.type,U.status,UA.area_code,UA.area_type,T.title')
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
      
        $this->view->assign('list',$list);
        return $this->view->fetch('Keeper/teamList'); 
    }
    
    //职位调调
    public function userEdit() 
    {   
        $uid = $this->request->get('uid');
        $data = Db::name('user')->alias('U')
                ->join('hl_user_area UA','UA.user_id=U.id','left')
                ->join('hl_user_team UT','UT.uid=U.id','left')
                ->join('hl_team T','T.id=UT.team_id','left')
                ->field('U.user_code,U.user_name,UA.area_code,UA.area_type,T.title,T.job')
                ->group('U.id')->find();
        $this->view->assign('data',$data);
        return $this->view->fetch('Keeper/user_edit'); 
    }
    //停用账户
    public function userStop() {
        $id = $this->request->get('id');
        $status = $this->request->get('status');
        $res =Db::name('user')->where('id',$id)->update(['status'=>$status]);
        return $res?1:0;;
    }


    
    //部门调整
    public function teamEdit() {
       
    }
    
    public function teamdata() {
       $array = Db::name('team')->alias('T')
            ->join('hl_user_team UT','UT.team_id=T.id','left')   
            ->join('hl_user U','UT.uid=U.id','left')
            ->field('UT.uid,U.user_name,T.*')
            ->group('UT.id,T.id')
            ->select();
        $list = $this->generateTree($array);
//        $this->_p($array);exit;
       // $list = $this->procHtml($listArr);
      //  $this->_p($list);exit;
      return json_encode($list,true);
    }
    
    //部门调整
    public function teamEidt() 
    {
        $array = Db::name('team')->group('id')->select();
        $list = $this->generateTree($array);
        $this->view->assign('jobList',$list);
        return $this->view->fetch('Keeper/user_add');
    }
    
        //部门调整处理
    public function teamToEidt() 
    {
        $array = Db::name('team')->group('id')->select();
        $list = $this->generateTree($array);
        $this->view->assign('jobList',$list);
        return $this->view->fetch('Keeper/user_add');
    }
    
    
    
    //获取树节点
    public function getTree($array, $pid =0, $level = 0) {
      //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['id'], $level+1);

            }
        }
        return $list;
    }
    
    //引用方式无限极
    function generateTree($array){
        //第一步 构造数据
        $items = array();
        foreach($array as $value){
            $items[$value['id']] = $value;
        }
        //第二部 遍历数据 生成树状结构
        $tree = array();
        foreach($items as $key => $item){
            if(isset($items[$item['pid']])){
                $items[$item['pid']]['childMenus'][] = &$items[$key];
            }else{
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }
    
    function procHtml($tree)
    {
        $html = '';
        foreach($tree as $t)
        {   
          if( @$t['son'] =='')
            {
                $html .= "<li class='layui-nav-item'>{$t['title']}--{$t['user_name']}--{$t['job']}--{$t['id']}</li>";
            }
            else
            {
                $html .= '<li class="layui-nav-item">'."{$t['title']}--{$t['user_name']}--{$t['job']}--{$t['id']}";
               @$html .= $this->procHtml($t['son']);
                $html = $html."</li>";
            }
        }
        return $html ? '<ul class="layui-nav layui-nav-tree" lay-filter="">'.$html.'</ul>' : $html ;
    }

} 