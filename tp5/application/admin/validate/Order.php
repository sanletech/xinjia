<?php
namespace app\admin\validate;
use think\Validate;
use think\Db;
class Order extends Validate
{
    protected $rule = [
        'track_num'  => 'checkTrack_num:1',
    ];
    protected function checkTrack_num($value,$rule,$data){
//        var_dump($value);    echo '</br>';
//      var_dump($data);     echo '</br>';
//        var_dump($rule); 
        $order_num =$data['order_num'];//订单号码
        $container_num =$data['container_num'];
        $track_sum =$data['track_sum'];//输入的运单号数量
        $response=true;
        if(!($track_sum==$container_num || $track_sum==1)){
            return  $response='输入的运单号码个数与订单中的集装箱个数不匹配';
        }
        $str ='';
        for($i=0;$i<count($value);$i++){
            $str.= "or (track_num ='$value[$i]') ";
        }
        $str = trim($str,'or');
        //判断是否已经有同样的记录存在 和是否
        $sql ="select track_num from hl_order_son where ".$str." and order_num = '$order_num' limit 1 ";
        $res = Db::query($sql);
        if(!empty($res)){
            return  $response='输入的运单号码:'.$res['0']['track_num'].'已存在';
        }
        return $response;
    }
    
    
}



