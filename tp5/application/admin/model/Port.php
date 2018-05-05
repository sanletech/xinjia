<?php
namespace app\admin\model;
use think\Model;
class Port extends Model
{
 
    protected $table = 'hl_port';
    
    //和车队表关联模型
    //belongsToMany(‘关联模型名’,’中间表名’,’外键名’,’当前模型关联键名’,[‘模型别名定义’]);
    public function car() {
        
        return $this->belongsToMany('Contact' , 'car_ship_port','car_data_id','port_id');
    }
    
}



?>