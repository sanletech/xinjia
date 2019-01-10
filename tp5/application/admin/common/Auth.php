<?php

namespace app\admin\common;
use think\Controller;
use think\db;
use think\Session;
/**
 * Description of Auth
 *
 * @author Administrator
 */
//http://www.thinkphp.cn/topic/4029.html
class Auth extends Controller{
    //默认配置
    protected $_config = array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'hl_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'hl_auth_group_access', //用户组明细表
        'AUTH_RULE' => 'hl_auth_rule', //权限规则表
        'AUTH_USER' => 'hl_user',//用户信息表
        'AUTH_AREA' => 'hl_user_area'//用户负责区域
    );
//    public function __construct() {
//        if (C('AUTH_CONFIG')) {
//            //可设置配置项 AUTH_CONFIG, 此配置项为数组。
//            $this->_config = array_merge($this->_config, C('AUTH_CONFIG'));
//        }
//    }
    //获得权限$name 可以是字符串或数组或逗号分割， uid为 认证的用户id， $or 是否为or关系，为true是， name为数组，只要数组中有一个条件通过则通过，如果为false需要全部条件通过。
    public function check($name, $uid, $relation='or') {
        if (!$this->_config['AUTH_ON'])
            return true;
//        var_dump($name, $uid); exit;
        $authList = $this->getAuthList($uid);
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        $list = array(); //有权限的name
  
        foreach ($authList as $val) {
            if (in_array($val, $name))
                $list[] = $val;
        }
       
        if ($relation=='or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation=='and' and empty($diff)) {
            return true;
        }
        return false;
    }
    //获得用户组，外部也可以调用
    public function getGroups($uid) {
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        $user_groups = Db::table($this->_config['AUTH_GROUP_ACCESS'] . ' a')
                ->where("a.uid='$uid' and g.status='1'")
                ->join($this->_config['AUTH_GROUP'].' g', "a.group_id=g.id")
                ->select();
        $groups[$uid]=$user_groups?$user_groups:array();
        return $groups[$uid];
    }
    //获得权限列表
    protected function getAuthList($uid) {
        static $_authList = array();
        if (isset($_authList[$uid])) {
            return $_authList[$uid];
        }
       
//        if(Session::has('_AUTH_LIST_'.$uid)){
//            return Session::get('_AUTH_LIST_'.$uid);
//        }
        //读取用户所属用户组
        $groups = $this->getGroups($uid);

        $ids = array();
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid] = array();
            return array();
        }
       
        //读取用户组所有权限规则
        $map=array(
            'id'=>array('in',$ids),
            'status'=>1
        );
        $rules = Db::table($this->_config['AUTH_RULE'])->where($map)->select();
        //循环规则，判断结果。
        $authList = array();
        foreach ($rules as $r) {
            if (!empty($r['condition'])) {
                //条件验证
                $user = $this->getUserInfo($uid);
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $r['condition']);
                //dump($command);//debug
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = strtolower($r['name']);
                }
            } else {
                //存在就通过
                $authList[] = strtolower($r['name']);
            }
        }
        $_authList[$uid] = $authList;
          
        if($this->_config['AUTH_TYPE']==2){
            //session结果
            Session::set('_AUTH_LIST_'.$uid,$authList);
        }
        return $authList;
    }
    //获得用户资料,根据自己的情况读取数据库
    protected function getUserInfo($uid) {
        static $userinfo=array();
        if(!isset($userinfo[$uid])){
             $userinfo[$uid]=M()->table($this->_config['AUTH_USER'])->find($uid);
        }
        return $userinfo[$uid];
    }

}
