<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Price extends Model
{
     // 定义时间戳字段名
    protected $shipping_date = 'shipping_date';
    protected $cutoff_date = 'cutoff_date';
    protected $ETA = 'ETA';
    protected $EDD = 'EDD';

    public function  price_route_list($port_start,$port_over)
    {   
        $list = Db::name('seaprice')->alias('SP')
                ->join('hl_shipcompany S', 'S.id = SP.ship_id', 'left')
                ->join('hl_sealine SL','SL.id = SP.sl_id', 'left')
                ->join('hl_port P1', 'SL.sl_start=P1.id', 'left')
                ->join('hl_port P2', 'SL.sl_over=P2.id', 'left')
                ->field('SP.*, S.ship_short_name, P1.port_name as start_port , P2.port_name as over_port')
                ->select();
        //echo Db::getLastSql();exit;
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($port_start){
            $list = $list->where('start_port', 'like', "%{$port_start}%");
            $pageParam['query']['start_port'] = $port_start;
        }
        if($port_over){
            $list = $list->where('over_port', 'like', "%{$port_over}%");
            $pageParam['query']['over_port'] = $port_over;
        }
        
       $list = $list->paginate(2,false,$pageParam);      
       // echo Db::getLastSql() ; exit;
        return $list;
    }
}