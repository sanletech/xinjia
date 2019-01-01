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
    
    //信息列表
    public function userData($uid='') {
        $page = $this->request->param('page',1,'integer');
        $limit = $this->request->param('limit',10,'integer');
        $user_name = $this->request->param('user_name','','strval');
        $user_code = $this->request->param('user_code','','strval');
//        var_dump($page,$limit,$user_name,$user_code);exit;
        //区域表
        $map =[];
        $user_name?$map['U.user_name']=['like',"%$user_name%"] :'';
        $user_code?$map['U.user_code']=['like',"%$user_code%"] :'';
        $uid ?  $map['U.id']=['=',$uid] :$map['U.id']=['>',0];
        
        $data = Db::name('user')->alias('U')
                ->join('hl_team T','T.id = U.team_id','left')
                ->join('hl_auth_group_access AGA','U.id=AGA.uid','left')
                ->join('hl_auth_group AG','AG.id=AGA.group_id','left')
                ->join('hl_user_area UA','UA.user_id =U.id','left')
                ->join('hl_port P',"FIND_IN_SET(P.port_code ,UA.area_code) and P.status = 1 and UA.type = 'port'",'left')
                ->join('hl_city C',"FIND_IN_SET(C.city_id ,UA.area_code) and  UA.type = 'city'",'left')
                ->field('U.id,U.user_name,U.user_code,U.loginname,U.phone,U.email,'
                        . 'T.title,T.job,T.id as job_id,T.pid,'
                        . "group_concat(distinct AG.title order by AG.id ) as power,"
                        . "group_concat(distinct AG.id order by AG.id ) as power_id,"
                        . "group_concat(distinct P.port_name order by P.id) as port_name,"
                        . "group_concat(distinct P.port_code order by P.id) as port_code,"
                        . "group_concat(distinct C.city order by C.id) as city_name ,"
                        . "group_concat(distinct C.city_id order by C.id) as city_code ")
                ->group('U.id')->where($map)->page($page,$limit)->select();

        foreach ($data as $key=> $value){
//                       $this->_p($value);exit;
            if($value['pid']!=0){
                $parent_id = Db::name('team')->field('id,pid,title')
                        ->where('id',$value['pid'])->find();
                if($parent_id['pid']!=0){
                    $parent_id = Db::name('team')->field('id,pid,title')
                        ->where('id',$value['pid'])->find();
                }
                $data[$key]['department']=$parent_id['title'];
                $data[$key]['department_id']=$parent_id['id'];
            }  else {
                $data[$key]['department']= '';   
            }

        $data[$key]['power'] = explode(',', $value['power']);    
        $data[$key]['power_id'] = explode(',', $value['power_id']);
        $data[$key]['port_name'] = explode(',', $value['port_name']); 
        $data[$key]['port_code'] = explode(',', $value['port_code']); 
        $data[$key]['city_name'] = explode(',', $value['city_name']); 
        $data[$key]['city_code'] = explode(',', $value['city_code']); 
    }
       
        if($uid){
            $count = 1;
            $data = $data[0];
        }  else {
            $count = count($data);
        }
        return json(array('code'=>0,'msg'=>'','count'=>$count,'data'=>$data));
        

    }
    
    //个人信息重新分配
    public function userEdit() {
        $data = $this->request->param();
//        $this->_p($data);exit;
        $uid = $data['id'];
        $map =[];
        //修改个人信息 和职位
        $map['user'] = array(
            'user_name'=>$data['user_name'],'create_time'=>date('Y-m-d H:i:s'),
            'team_id'=>$data['title'], 'user_code'=>$data['user_code'],
            'phone'=>$data['phone'],'email'=>$data['email'],
            'loginname'=>$data['loginname'] );
        if(!empty($data['password'])){
            $map['user']['password'] =  md5($data['password']);
        }
        //权限
        foreach ($data['power'] as  $value) {
            $map['power'][] = array('uid'=>$uid,'group_id'=>$value);
        }
 
        //修改管理区域
        if(array_key_exists('port_code', $data)){
            $map['area']['area_code'] = implode(',', $data['port_code']);
            $map['area']['type'] = 'port';
        }elseif (array_key_exists('city_code', $data)) {
            $map['area']['area_code'] = implode(',', $data['city_code']);
            $map['area']['type'] = 'city';
        }
        //修改权限
        Db::startTrans();
        try{
            Db::name('user')->where('id',$data['id'])->update($map['user']);  //修改个人信息 和职位
            Db::name('auth_group_access')->where('uid',$uid)->delete(); //删除原有的
            Db::name('auth_group_access')->insertAll($map['power']); //再新增
            Db::name('user_area')->where('user_id',$uid)->update($map['area']);//更新区域
            
            Db::commit();    
        } catch (\Exception $e) {
                // 回滚事务
               Db::rollback();
               return array('status'=>0,'message'=>  json_encode($e->getMessage()));
        }
        return json(array('status'=>1,'message'=>'修改成功'));
      
    }
    
    //添加账户
    public function userAdd() {
        $data = $this->request->param();
        $map =[];
        //修改个人信息 和职位
        $map['user'] = array(
            'user_name'=>$data['user_name'],'create_time'=>date('Y-m-d H:i:s'),
            'team_id'=>$data['title'], 'user_code'=>$data['user_code'],
            'phone'=>$data['phone'],'email'=>$data['email'],
            'loginname'=>$data['loginname'], 'password'=>$data['password'] );
 
        //管理区域
        if(array_key_exists('port_code', $data)){
            $map['area']['area_code'] = implode(',', $data['port_code']);
            $map['area']['type'] = 'port';
        }elseif (array_key_exists('city', $data)) {
            $map['area']['area_code'] = implode(',', $data['city']);
            $map['area']['type'] = 'city';
        }
    
        Db::startTrans();
        try{
            $uid = Db::name('user')->insertGetId($map['user']);  //添加个人信息 和职位
            //权限
            foreach ($data['power'] as  $value) {
                $map['power'][] = array('uid'=>$uid,'group_id'=>$value);
            }
            Db::name('auth_group_access')->where('uid',$uid)->delete(); //删除原有的
            Db::name('auth_group_access')->insertAll($map['power']); //再新增
            $map['area']['user_id'] = $uid;
            Db::name('user_area')->insert($map['area']);//更新区域
            
            Db::commit();    
        } catch (\Exception $e) {
                // 回滚事务
               Db::rollback();
               return array('status'=>0,'message'=>  json_encode($e->getMessage()));
        }
        return json(array('status'=>1,'message'=>'天价成功'));
        
    }
    
    
    //部门列表
    public function teamList() {
        $data =Db::name('team')->select();
        $arr = $this->generateTree($data);
        return json($arr);
        
    }
    //权限列表
    public function authGroup() {
        $data =Db::name('auth_group')->where('status',1)->field('id,title')->select();
        return json($data);
    }
    
    public function aaa() 
    {
        var_dump($this->request->param());exit;
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
        // $this->_p($items);exit;
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
