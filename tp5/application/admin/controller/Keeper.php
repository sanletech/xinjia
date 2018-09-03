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


    //划分区域列表
    public function areaList() 
    {  
        $list =Db::name('user')->alias('U')
                ->join('hl_user_area UA','UA.user_id=U.id','left')
                ->join('hl_user_team UT','UT.uid=U.id','left')
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
      
        $this->view->assign('list',$list);
        return $this->view->fetch('Keeper/teamList'); 
    }
    
    //职位调调
    public function userEdit() 
    {   
        
        return $this->view->fetch('Keeper/user_edit'); 
    }
    //停用账户
    public function userStop() {
        $id = $this->request->get('id');
        $status = $this->request->get('status');
        $res =Db::name('user')->where('id',$id)->update(['status'=>$status]);
        return $res?1:0;;
    }


    
    //部门划分
    public function teamList() {
       
        return $this->view->fetch('Keeper/teamList'); 
    }
    
    public function teamdata() {
       $array = Db::name('user_team')->alias('UT')
            ->join('hl_user U','UT.uid=U.id','left')
            ->field('UT.*,U.user_name')
            ->group('UT.id')
            ->select();
        $list = $this->generateTree($array);
      //  $this->_p($listArr);exit;
       // $list = $this->procHtml($listArr);
      //  $this->_p($list);exit;
      return json_encode($list,true);
    }
    
        //添加管理员
    public function userToAdd() 
    {
       
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