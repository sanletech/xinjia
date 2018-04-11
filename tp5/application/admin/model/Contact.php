<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Contact extends Model
{
      protected $table = 'hl_cardata';
    
    //展示车队的对应信息
    public function carlist() {

    //1.将车队的信息赋值分页展示
    $res= Db::name('cardata')->where('id','>',0)->paginate(10);
    // 获取分页显示

    return $res;
    }
    
    public function  caredit($id){
     // 展示车队   
     $res= Db::name('cardata')->where('id',$id)->find();
      return $res;
    }

    

   public function getStatusAttr($value)
    {
    $status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
     return $status[$value];

    }

     public function getSymbiosisAttr($value)
    {
    $status = [1=>'长期合作',2=>'临时合作',3=>'暂无合作',4=>'中止合作'];
     return $status[$value];

    }
}



?>