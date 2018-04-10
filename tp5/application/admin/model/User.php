<?php
namespace app\admin\model;
use think\Model;
class User extends Model
{
 protected $autoWriteTimestamp = 'datetime';
    
   public function getStatusAttr($value,$data) {
        $status = [1=>'已启用',0=>'禁用'];
        return $status[$data['status']];
    }
//    
//     public function getLogintimeAttr($value) {
//         return date("Y-m-d",$value);
//        
//    }
//    
    
//      public function profile()
//    {
//      //  return $this->hasOne('Profile');
//    }

    
    
    
}



?>