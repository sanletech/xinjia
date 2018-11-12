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
        'price_20GP' => 'require|number',
        'price_40HQ' => 'require|number',
        'shipping_date' => 'require|date|checkDate',
        'cutoff_date' => 'date',
        'sea_limitation' => 'number',
        'generalize' => 'require|in:0,1',
        'price_description' => 'max:50|min:5',
        'ETA' => 'date',
        'EDD' => 'date',
    ];
    
    public function _initialize(){

        $this->rule=11;
        
    }
    
    protected $message = [
        'shipping_date' => '时间错误',
     
    ];

    
    protected function checkName($value,$rule,$data)
    {
        $data=  date('y-m-d H:i:s');
        $value = date('y-m-d H:i:s', strtotime($value));
        return $value<$data? true : '名称错误';
    }
    protected function checkShip($value,$rule,$data){
        //查询船公司
    }

    
}
