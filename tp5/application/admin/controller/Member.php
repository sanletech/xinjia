<?php
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\User ;
use think\Request;
use think\Db;
class Member extends Base
{    

  public function __construct(Request $request = null) {
        parent::__construct($request);
        $this->request= $request;
    }

    public function mList()
    {
        //1.读取会员表的信息
        $member= User::where('status',1)->paginate(10);
               
        //2.将会员的信息赋值给模版
        $count = count($member); 
        // 获取分页显示
      $page = $member->render();
   
      $this->view->assign('member',$member);
      $this->view->assign('page',$page);
       $this->view->assign('count', $count);
        //3.渲染模版
     //  print_r($member);exit;
      return $this->view->fetch('member/member_list'); 
       
    }
    
    public function mAdd()
    {
      
        return $this->view->fetch('member/member_add'); 
       
    }
    
      public function toAdd()
    {
      $data= array_filter($this->request->param());
    //修改数据库将
      $data['create_time']=  time();
      $data['status'] = 1;
      $data['password']=  md5($data['password']);
      unset($data['repassword']);
      var_dump($data);
     $res= User::insert($data);
       
     if(is_null($res)){
            $status=1;
            $message='更新失败';
        }else{
            $status=0;
            $message='更新成功';
        }    
        
    return ['status'=>$status];  
       
       
    }
    public function mDel()
       {
          return $this->view->fetch('member/member_del'); 

       }
      /*执行删除*/
    public function toDel()
   {
      $data= $this->request->param();
    // 将$data[id]数组组成查询
    //修改数据库将status 状态改为0
     $res= User::where('id','in',$data['id'])
             ->update(['status'=>0]);
     if(is_null($res)){
            $status=1;
            $message='更新失败';
        }else{
            $status=0;
            $message='更新成功';
        }      
    return ['status'=>$status,'message'=> $message];  
   }  
       
    public function mEdit()
       {
            //1.读取会员表的信息
        $member= User::get($this->request->param('id'));
       
        //2.将会员的信息赋值给模版
         $this->view->assign('member',$member);
        //3.渲染模版
           return $this->view->fetch('member/member_edit'); 

       }  
     
    


       public function mUpdate()
   {   
         
        //获取提交的数据，自动过滤一下空值
        $data= $this->request->param();
       
        $map=['id'=> $data['id']];
        if(array_key_exists('password',$data)){
            $data['password']=  md5($data['password']);
        }
       
        //修改数据库
        $res= User::update($data,$map);

       if(is_null($res)){
            $status=1;
            $message='更新失败';
        }else{
            $status=0;
            $message='更新成功';
        }  
        $arr = array('status'=>$status,'message'=>$message);
       // echo '111'; 
        //return json_encode($arr);
     return ['status'=>$status,'message'=> $message];
   }    
       
 
   
    public function mKiss()
    {

        return $this->view->fetch('member/member_kiss'); 

    }  
     public function mLevel()
    {

        return $this->view->fetch('member/member_level'); 

    }
      public function mPassword()
    {
         $member= User::getById($this->request->param('id'));
         $this->view->assign('member',$member);
        return $this->view->fetch('member/member_password'); 

    }
      public function mView()
    {

        return $this->view->fetch('member/member_view'); 

    }

}
