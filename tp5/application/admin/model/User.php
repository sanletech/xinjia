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

    
    
    
}



?>