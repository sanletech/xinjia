<?php
namespace app\index\controller;
use app\index\common\Base;
use think\Db;
use think\Session;
use app\index\model\Personal as dataM;
class Personal extends Base
{    
    //个人中心
    public function steward()
    {   
        return $this->view->fetch('personal/steward');
    }
    //修改密码
    public function pwd_edit()
    {
       return $this->view->fetch('personal/pwd_edit');
    }
     //修改密码
    public function pwd_toedit()
    {
        $data= $this->request->only('password,newpassword,repassword');
        if($data['newpassword']!==$data['repassword']){
            return array('status'=>0,'message'=>'两次密码不一致') ;
        }
        //查询密码是否和数据库的一致
        $member_code =Session::get('member_code','think');
        $password =Db::name('member')->where('member_code',$member_code)->value('password');
        $data_password=md5($data['password']);
        $data_new_password=md5($data['newpassword']);
        if($data_password==$password){
            $res =Db::name('member')->where('member_code',$member_code)->update(['password'=>$data_new_password]);
            $res ?$response=['status'=>1,'message'=>'修改成功']:$response=['status'=>0,'message'=>'修改失败'];
            return $response;
        }  else {
            return array('status'=>0,'message'=>'密码不正确') ;
        }
    }
       //个人信息
    public function info()
    {
       $member_code=  Session::get('member_code','think');
       $member_data =Db::name('member')->where('member_code',$member_code)->field('password',true)->find();
       $this->view->assign('member_data',$member_data);
//       var data = "<{$member_data}>";
       return $this->view->fetch('personal/info');
    }
    
    //个人信息的修改
    public function info_edit()
    {
        $data= $this->request->param();  
        $file = request()->file('file');
//      $this->_p($data);exit;
        $member_code=  Session::get('member_code','think');
        $type= $data['type']; $email= $data['email'];
        $name =$data['name']; $add = $data['add'];
        //将图片放到 
        $file_path=[];
        $response=[];
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])
                ->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads/images');
        if($info){
            // 成功上传后 获取上传信息
            $file_path=$info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                return array('status'=>0,'message'=>$file->getError());
            }    
        //修改用户信息
        $res =Db::name('member')->where('member_code',$member_code)
                ->update(['type'=>$type,'name'=>$name,
                    'email'=>$email,'type'=>$type,'add'=>$add,
                    'file_path'=>$file_path,'identification'=>1]);
        $res ?$response=['status'=>1,'message'=>'更新成功']:$response=['status'=>0,'message'=>'更新失败'];
        return $response;
    }

    
    
    //作废订单
    public function invalid()
    {
        //订单查询
        $order_num =  $this->request->param('order_num');
        $member_code =Session::get('member_code','think');
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM =new dataM;
       
        $list = $dataM->place_order($member_code,$tol,$limit,array(404,505),$order_num);
        $count =  count($list); 
        $this->view->assign('order_num',$order_num);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('list',$list); 
        return $this->view->fetch('personal/invalid');
    }
    //生产账单
    public function a_order()
    {
       return $this->view->fetch('personal/order_bill');
    }
    //账单表报
    public function form_order()
    {
       return $this->view->fetch('personal/bill_statement');
    }
 
    //公司信息
    public function company()
    {
       return $this->view->fetch('personal/company');
    }
    //常用信息
    public function common_info()
    {
       return $this->view->fetch('personal/common_info');
    }

    //港到港账单生成  所有 和未付款
    public function port_bill(){
//        $Bill = controller('Bill');
//        $list =$Bill->billList();
////        $this->_p($list);exit;
        $this->view->assign('billUrl', url("index/Bill?billList"));
        return $this->view->fetch('personal/port_bill');
    }
    
        public function port_bill_money(){

        $this->view->assign('billUrl', url("index/Bill?billList"));
        return $this->view->fetch('personal/port_bill_money');
    }

    
    
    //港到港订单
    public function place_order_port()
    {   
        $map =[];
        //订单查询
        $order_num =  $this->request->param('order_num');
        if($order_num){
            $this->view->assign('order_num',$order_num);
            $map['A.order_num']=$order_num;
        }
        //港口查询
        $start_add =$this->request->param('start_add');
        $start_name =$this->request->param('start_name');
        if($start_add){
            $this->view->assign(['start_add'=>$start_add,'start_name'=>$start_name]);
            $map['A.s_port_code']=$start_add;
        }
        $end_name =$this->request->param('end_name');
        $end_add =$this->request->param('end_add');
        if($end_add){
            $this->view->assign(['end_add'=>$end_add,'end_name'=>$end_name]);
            $map['A.e_port_code']=$end_add;
        }
      
        //下单时间查询
        $date_start = $this->request->param('date_start');
        $date_end = $this->request->param('date_end');
        if($date_start&&$date_end){
            $this->view->assign(['date_start'=>$date_start,'date_end'=>$date_end]);
            $map['A.ctime']=['between time',[$date_start,$end_add]];
        }
        //订单状态
        $order_status = $this->request->param();
        $order_status=  array_key_exists('order_status', $order_status)?$order_status['order_status']:'';
//        $this->_p($order_status);exit;
        if($order_status){
            $this->view->assign('order_status',$order_status);
            foreach ($order_status as $status){
                switch ($status) {
                    case 'order_audit':
                        $map['A.status'][]=['=',  $this->order_status['order_audit']];
                    break;
                    case 'cancel':
                        $map['A.status'][]=['in',  [$this->order_status['cancel'], $this->order_status['stop']]]; 
                    break;
                    case 'order_success':
                        $map['A.status'][]=['in', array_slice($this->order_status, 3, -1)];
                    break;
                    case 'completion':
                        $map['A.status'][]=['=',  $this->order_status['completion']];
                    break;
                    default :
                        die('参数错误');   
                }
            }
            if(count($map['A.status'])>1){
                $map['A.status'][]='or';
            }else{
                $map['A.status']=$map['A.status'][0];
            }
        }
        $member_code =Session::get('member_code','think');
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM =new dataM;
       
        $list = $dataM->place_order($member_code,$tol,$limit,$map);
//        $this->_p($list);exit;
        $count =  count($list); 
        $this->view->assign('order_num',$order_num);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('list',$list);
       return $this->view->fetch('personal/place_order_port');
    }

    //提交柜号资料
    public function track_data()
    { 
        $order_num = $this->request->param('order_num');
        $data = Db::name('order_truckage') ->where(['order_num'=>$order_num,'type'=>'s'])->field('id,container_code,seal')->select();
        return json($data);
    }
    //处理提交柜号
    public function track_num() {
       // $member_code =Session::get('member_code','think');
        $data = $this->request->param(); 
//        $this->_p($data);exit;
        $order_num =$data['order_num'];
        $id  =$data['id'];
        $container_code =$data['container_code'];//
        $seal =$data['seal'];
        if(count($seal)==count($container_code)){
           $response =[];
            //根据订单号 添加 修改柜号和封条号
            for($i=0;$i<count($container_num);$i++){
                //如果为空就跳过此次循环
                if(empty($container_code[$i])){
                    continue;
                }
                $res =Db::name('order_truckage')->where(['order_num'=>$order_num,'id'=>$id[$i]])
                        ->update([ 'container_code'=>$container_code[$i],'seal'=>$seal[$i]]);
                $res ? $response['success'][]= '提交柜号成功':$response['fail'][]= '提交柜号失败';
            }
            if(array_key_exists('fail', $response)){
               $status = array('status'=>0,'message'=>'报柜号失败');
            }  else {
                $status = array('status'=>1,'message'=>'报柜号成功'); 
                    //提交的柜号和封条号和id对应的上，订单就修改报柜号完毕
                    if(count($seal) == count($id)){
                        $res3 =Db::name('order_port')->where('order_num',$order_num)->update(['statu'=>5,'container_status'=>1]);
                        $res3 ? $status = array('status'=>1,'message'=>'柜号封条号全部录完不可修改'):$status = array('status'=>0,'message'=>'录柜号状态修改失败');
                    }
            }
        }  else {
                $status = array('status'=>0,'message'=>'柜号和封条号数量不对');       
                }

        return $status;    
    }




    public function downs(){  
        $order_name = $this->request->param('order_num');    //下载文件名  
        $type = $this->request->param('type'); //文件类型
        $member_code = Session::get('member_code','think');
        $data = Db::name('order_port')->where('order_num',$order_name)->field($type.',member_code')->find();
        if($data['member_code']!==$member_code){
            echo"无权限下载";exit;
        }
        $file =$data[$type];
     //   var_dump($file);
        //将后缀修改成.
        $file_Extension= strstr(strrev($file),'_',true);
        $file_Extension =  strrev($file_Extension);
        $file_name = substr($file,0,strrpos($file, '_')).'.'.$file_Extension;     
//              var_dump($file_name);exit;
        $file_dir = ROOT_PATH . 'public' . DS . 'uploads';        //下载文件存放目录    
        //检查文件是否存在    
//            var_dump($file_dir .DS. $file_name);exit;
        if (! file_exists ($file_dir .DS. $file_name)) {    
            echo "文件找不到";    
            exit ();    
        } else {    
            //打开文件    
            $file = fopen ($file_dir .DS. $file_name, "r" );    
            //输入文件标签     
            Header ( "Content-type: application/octet-stream" );    
            Header ( "Accept-Ranges: bytes" );    
            Header ( "Accept-Length: " . filesize ($file_dir .DS. $file_name) );    
            Header ( "Content-Disposition: attachment; filename=" . $file_name );    
            //输出文件内容     
            //读取文件内容并直接输出到浏览器    
            echo fread ( $file, filesize ($file_dir .DS. $file_name) );    
            fclose ( $file );    
            exit ();    
        }    
    }
    
    //设置默认的收货,送货地址
    public function default_address(){
        $data =  $this->request->only('id,default'); 
        $member_code =Session::get('member_code','think');
        //先查询有没有设置默认值
        $default_id =Db::name('linkman')->where(['member_code'=>$member_code,'default'=>'not null'])->value('id');
        $response=[];
        if($default_id){
            $res =Db::name('linkman')->where(['member_code'=>$member_code,'id'=>$default_id])->update(['default'=>'null']);
            $res?$response['success'][]='更新原有数据成功':$response['fail'][]='更新原有数据失败' ;
        }
        $res1 =Db::name('linkman')->where(['member_code'=>$member_code,'id'=>$data['id']])->update(['default'=>$data['default']]);
        $res1?$response['success'][]='设置成功':$response['fail'][]='设置失败' ;
        if(array_key_exists('fail', $response)){
            return json(array('status'=>0,'message'=>'设置失败')) ;
        }           
       return json(array('status'=>1,'message'=>'设置成功')) ;
    } 
}
