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
    
    //部门添加
    public function addTeam(){
        return $this->view->fetch(); 
    }
    public function addtoTeam(){
    
    }
    
     //部门修改
    public function modifyTeam(){
        
        
    }
     //部门删除
    public function deleteTeam(){
        
        
    }
    //部门查询
    public function selectTeam(){
        
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
    
    
} 