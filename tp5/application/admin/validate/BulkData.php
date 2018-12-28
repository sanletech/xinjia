<?php

namespace app\admin\validate;
use think\Validate;
use think\db;

class BulkData extends Validate
{
 
    protected $rule = [
        'ship_id'  => 'require|number',
        'route_id' => 'require|number',
        'boat_id' => 'require|number',
        'price_20GP' => 'require|number|egt:0|elt:6000',
        'price_40HQ' => 'require|number|egt:0|elt:6000',
        'shipping_date' => 'require|date|checkDate',
        'cutoff_date' => 'date',
        'sea_limitation' => 'number',
        'generalize' => 'require|in:0,1',
        'price_description' =>'require',
        'ETA' => 'date',
        'EDD' => 'date',
    ];
    
    protected function checkDate($value,$rule,$data)
    {
        $data= date('Y-m-d H:i:s');
        $value = strtotime($value);
        return $value>$data? true : '船期不能晚于'.$data;
    }
    
    protected function checkShip($value,$rule,$data){
        //查询船公司
        $ship_id = Db::name('shipcompany')->where('status',1)->order('id')->column('id');
        return in_array($value, $ship_id) ? true:'船公司ID不在范围内';
    }
    
    protected function checkBoat($value,$rule,$data){
        //查询船公司
        $ship_id = Db::name('boat')->where('status',1)->order('id')->column('id');
        return in_array($value, $ship_id) ? true:'船舶ID不在范围内';
    }

    
}
