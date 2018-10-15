<?php
//客户的账单
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Order as OrderM;
use think\Db;
use think\Session;
class Bill extends Base 
{    
    //海运运价
    public function billCreate(){
        $list =Db::name('order_port')->field('order_num,container_size,container_sum,comment,status');
        
    } 
    

}