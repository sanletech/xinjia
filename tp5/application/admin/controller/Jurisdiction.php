<?php
/*
 *  权限管理
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
class Jurisdiction extends Base
{
    public function control() 
    {
        return $this->view->fetch('Jurisdiction/control');
    }
    
    
    public function userData($uid='') {
        //区域表
        $data = Db::name('user')->alias('U')
                ->join('hl_team T','T.id = U.team_id','left')
                ->join('hl_auth_group_access AGA','U.id=AGA.uid','left')
                ->join('hl_auth_group AG','AG.id=AGA.group_id','left')
                ->join('hl_user_area UA','UA.user_id =U.id','left')
                ->join('hl_port P',"FIND_IN_SET(P.port_code ,UA.area_code) and P.status = 1 and UA.type = 'port'",'left')
                ->join('hl_city C',"FIND_IN_SET(C.city_id ,UA.area_code) and  UA.type = 'city'",'left')
                ->field('U.id,U.user_name,U.user_code,T.title,T.job,T.pid,'
                        . "group_concat(distinct AG.title order by AG.id ) as power,"
                        . "group_concat(distinct P.port_name order by P.id) as port_name,"
                        . "group_concat(distinct C.city order by C.id) as city_name ")
                ->group('U.id')->select();
        $this->_p($data);exit;
        foreach ($data as $key=> $value){
            if($value['pid']!=0){
                
            }
        
        }
        $team = Db::name('team')->group('id')->select();
        
        $count = count($data);
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$data);
        

    }
    
    
    public function aaa() 
    {
        var_dump($this->request->param());exit;
    }

} 