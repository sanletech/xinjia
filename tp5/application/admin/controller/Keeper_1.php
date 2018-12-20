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
        return $this->view->fetch('keeper/admin_list'); 
    }
    //添加员工
    public function userAdd() {
        $array = Db::name('team')->group('id')->select();
        $list = $this->generateTree($array);
//        $this->_p($list);exit;
        $this->view->assign('jobList',$list);
        return $this->view->fetch('keeper/user_add');
        
    }
    //处理员工添加
    public function userToAdd() {
        $data= $this->request->param();
        //$this->_p($data);exit;
        $job =$data['job'];//team表里职位id
        $userdata=[];
        $userdata['user_name'] =$data['user_name'];
        $userdata['password'] =md5($data['password']);
        $teamJob =Db::name('team')->where('id',$job)->value('job');
        $userdata['type']=$teamJob;
//        var_dump($teamJob);exit;
        if(strstr($teamJob,'service')){
            $jobName='kf';
        }elseif (strstr($teamJob,'sale')) {
            $jobName='yw';
        }elseif (strstr($teamJob,'finance')){
            $jobName='cw';
        }
        $userdata['status'] =1;$userdata['phone']=$data['phone'];$userdata['email']=$data['email'];
        $user_max_id =Db::name('user')->max('id');
        if($user_max_id<10000){
            $user_max_id = sprintf("%05d",$user_max_id);
        }
        $userdata['user_code'] = $jobName.$user_max_id;
        $userdata['loginname'] = $userdata['user_code'];
        $userdata['create_time'] = date('Y-m-d H:i:s');
       
        $res =Db::name('user')->insert($userdata);
        $userId = Db::name('user')->getLastInsID();
//                var_dump($userId,$job);exit;
        $res2 =Db::name('user_team')->insert(['uid'=>$userId,'team_id'=>$job]);
        if($res&&$res2){
            return 1;
        }
        return 0;
        
    }

    //划分区域列表
    public function areaList() 
    {  
        $user_code =  $this->request->param('user_code');
        if($user_code){
            $this->view->assign('user_code',$user_code);
        }else{
            $user_code = 'not null';
        } 
        
        $list =Db::name('user')->alias('U')
                ->join('hl_user_team UT','UT.uid=U.id','left')
                ->join('hl_team T','T.id=UT.team_id','left')
                ->join('hl_auth_group_access AA','AA.uid=U.id','left')
                ->field('U.id,U.user_code,U.user_name,U.type,U.status,T.title')
                ->where('U.user_code',$user_code)
                ->group('U.id')->select();
                
        $areaList =Db::name('user_area')->alias('UA')
                ->join('hl_port P','P.port_code=UA.area_code','left')
                ->field('UA.user_id,P.port_code,P.port_name')->group('P.port_code')
                ->select();
        //  $this->_p($list);   $this->_p($areaList);exit;
        foreach ($list as $k=>$v){
            foreach ($areaList as $key => $value) {
                if($v['id']==$value['user_id']){
                    $list[$k]['area_list'][]=$value;
                }
            }
        }
//      $this->_p($list);exit;
        $this->view->engine->layout('Keeper/team_public');
        $this->view->assign('arealist',$list);
        return $this->view->fetch('keeper/area_list'); 
    }
    
    //员工信息
    public function userData() {
        $uid = $this->request->param('uid');
        if($uid){
           $uid = $uid?$uid:'not null';
        }
        $list =Db::name('user')->alias('U')
            ->join('hl_user_team UT','UT.uid=U.id','left')
            ->join('hl_team T','T.id=UT.team_id','left')
            ->join('hl_auth_group_access AA','AA.uid=U.id','left')
            ->field('U.id,U.user_code,U.user_name,U.type,U.status,T.title')
            ->where('U.id',$uid)->group('U.id')->select();
        
        $areaList =Db::name('user_area')->alias('UA')
            ->join('hl_port P','P.port_code=UA.area_code','left')
            ->field('P.port_code,P.port_name')->group('P.port_code')
            ->where('UA.user_id',$uid)->select();
        $list[0]['area_list']=$areaList;
        
        $this->view->engine->layout('Keeper/team_public');
        $this->view->assign('arealist',$list);
        return $this->view->fetch('keeper/area_list'); 

    }
    
    //职位列表
    public function userEdit() 
    {   
        $uid = $this->request->get('uid');
        $data = Db::name('user')->alias('U')
                ->join('hl_user_team UT','UT.uid=U.id','left')
                ->join('hl_team T','T.id=UT.team_id','left')
                ->field('U.id user_id,U.user_code,U.user_name,T.id jobID,T.title,T.job')
                ->where('U.id',$uid)
                ->group('U.id')->find();
        $areaArr =Db::name('user_area')->alias('UA')
                ->join('hl_port P','P.port_code=UA.area_code','left')
                ->where('UA.user_id',$uid) ->group('UA.area_code')
                ->field('UA.user_id,P.port_code,P.port_name')->select();
//        $this->_p($data);  $this->_p($areaArr);exit;

        $array = Db::name('team')->group('id')->select();
        $jobList = $this->generateTree($array);
//      $this->_p($data);$this->_p($areaArr);  $this->_p($jobList);exit;
        
        $this->view->assign('jobList',$jobList);
        $this->view->assign('areaArr',$areaArr);
        $this->view->assign('data',$data);
        return $this->view->fetch('keeper/user_edit'); 
    }
    
    //执行用户修改
    public function userToEdit(){
        $data= $this->request->param();
        $user_code = $data['user_code'];
        $job= $data['job'];
        $port=  implode(',',$data['port_code']);
        $res =Db::name('user')->update([
            ''
            
            ]);
        
    }


    //停用账户
    public function userStop() {
        $id = $this->request->get('id');
        $status = $this->request->get('status');
        $res =Db::name('user')->where('id',$id)->update(['status'=>$status]);
        return $res?1:0;;
    }


    

    //侧边栏数据
    public function teamdata() {
       $array = Db::name('team')->alias('T')
            ->join('hl_user_team UT','UT.team_id=T.id','left')   
            ->join('hl_user U','UT.uid=U.id','left')
            ->field('UT.uid,U.user_name,T.*')
            ->group('UT.id,T.id')
            ->select();
//    $this->_p($array);exit;
        $list = $this->generateTree($array);
       
       // $list = $this->procHtml($listArr);
//        $this->_p($list);exit;
      return json_encode($list,true);
    }
   
    
    //部门list
    public function teamList() 
    {
        $array = Db::name('team')->group('id')->select();
        $list = $this->generateTree($array);
//        $this->_p($list);exit;
        $tree= $this->procHtml($list);
        $this->view->assign('tree',$tree);
        $this->view->assign('list',$list);
        $this->view->engine->layout('Keeper/team_public');
        return $this->view->fetch('keeper/team_list');
    }
    
    //部门修改
    public function teamEdit() 
    {
        $array = Db::name('team')->group('id')->select();
        $list = $this->generateTree($array);
//        $this->_p($list);exit;
        $tree= $this->procHtml($list);
        $this->view->assign('list',$list);
        $this->view->assign('tree',$tree);
        $this->view->engine->layout('Keeper/team_public');
        return $this->view->fetch('keeper/team_edit');
    }
     
    //添加子类目
    public function teamSonAdd() 
    {   //获取节点的id
        $id= $this->request->param('id');
      
        $array = Db::name('team')->group('id')->select();
        $listfather= $this->getFatherTree($array,$id);
        array_multisort(array_column($listfather,'level'),SORT_DESC,$listfather);
        $this->view->assign('id',$id);
        $list = $this->generateTree($array);
        $this->view->assign('jobList',$list);
        $tree= $this->procHtml($list);
        $this->view->assign('tree',$tree);
        $this->view->assign('list',$listfather);
        $this->view->engine->layout('Keeper/team_public');
        return $this->view->fetch('keeper/team_sonadd');
    }
    
    //处理添加子类目
    public function toSonAdd(){
        $data =  $this->request->param();
        $father_id =$data['father_id'];
        $son_title = $data['$son_title'];
        $son_job = $data['$son_job'];
        foreach ($son_title as $key) {
            $res =Db::name('team')->insert(['pid'=>$father_id,'title'=>$son_title[$key],'job'=>$son_job[$key]]);
        }
      
    }




    //获取树节点的所有父节点
    public function getFatherTree($array,$id =0, $level = 0) {
        static $list = [];
        foreach ($array as $key => $value){
            if($value['id'] == $id && !($id==0)){
                $value['level'] = $level;
                $list[]=$value;
                unset($array[$key]);
                $this->getFatherTree($array,$value['pid'],$level+1);
            }
        }
        return $list;
    }
    
    
    
    //获取树节点
    public function getTree($array, $pid =0, $level = 0,&$list=array()) {
      //声明静态数组,避免递归调用时,多次声明导致数组覆盖
//        static $list = [];
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
        $i=1;
        foreach($array as $value){
            $items[$i] = $value;
            $i++;
        }
//        $this->_p($items);exit;
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
            $id=$t['id'];
            $strE =  url('@admin/keeper/teamEdit',"id=$id");
            $strA =  url('@admin/keeper/teamSonAdd',"id=$id");
            $strD =  url('@admin/keeper/teamDel',"id=$id");
          if(!isset($t['childMenus']))
            {   
                $html .= "<li >{$t['title']}"
                . "<a href='$strE' id='$id'>编辑</a>&nbsp"
                . "<a href='$strA'>子类目</a>&nbsp"
                . "<a href='$strD'>删除</a></li>&nbsp";
            }
            else
            {   
                $html .= "<li class='layui-nav-item'>{$t['title']}"
                . "<a href='$strE'; id='$id' >编辑</a>&nbsp"
                . "<a href='$strA'>子类目</a>&nbsp";
              
                $html .= $this->procHtml($t['childMenus']);
                $html = $html."</li>";
            }
        }
        return $html ? '<ul class="layui-nav-tree" lay-filter="">'.$html.'</ul>' : $html ;
    }

    //权限调整
    function power(){
        $array = Db::name('team')->group('id')->select();
        $list = $this->generateTree($array);
//        $this->_p($list);exit;
        $tree= $this->procHtml($list);
        $this->view->assign('list',$list);
        $this->view->assign('tree',$tree);
        $this->view->engine->layout('Keeper/team_public');
        return $this->view->fetch('keeper/power');
    }

    //权限调整
    function power_edit(){
        return $this->view->fetch('keeper/power_edit');
    }
} 