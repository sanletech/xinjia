<?php
namespace app\admin\model;
use think\Model;
use think\Db;
//订单模块
class Order extends Model
{
    public function order_check(){
       //查询客户的订单编号order_sum 查询对应的子订单
       $orderSQL = Db::name('order_sum')->alias('A')
               ->join('hl_order_add OD','OD.order_code =A.order_code','left')
               ->field('OD.*, A.order_sum')
               ->order('A.seq,A.mtime')->buildSql();
               
       //根据子订单查询对应的价格表
       
       
    }
    
}