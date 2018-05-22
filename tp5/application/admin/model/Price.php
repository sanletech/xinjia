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
     //船运航价的展示
    public function  price_route_list($port_start,$port_over,$pages=5)
    {   
        $list = Db::name('seaprice')->alias('SP')
                ->join('hl_shipcompany S', 'S.id = SP.ship_id', 'left')
                ->join('hl_sealine SL','SL.id = SP.sl_id', 'left')
                ->join('hl_port P1', 'SL.sl_start=P1.id', 'left')
                ->join('hl_port P2', 'SL.sl_over=P2.id', 'left')
                ->field('SP.*, S.ship_short_name, P1.port_name as start_port , P2.port_name as over_port')->buildSql();
        
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($port_start){
            $list = Db::table($list.' a')->where('a.start_port', 'like', "%{$port_start}%")->buildSql();
            $pageParam['query']['start_port'] = $port_start;
        }
        if($port_over){
            $list = Db::table($list.' b')->where('b.over_port', 'like', "%{$port_over}%")->buildSql();
            $pageParam['query']['over_port'] = $port_over;
        }
        
        $lista =Db::table($list.' a')->paginate($pages,false,$pageParam);   
//            echo '<pre>';
//                var_dump($list);
//            echo '</pre>';exit;
//            echo Db::getLastSql() ;
        return $lista;
    }
    
    
     //船运航价的删除
     public function  price_route_del($seaprice_id)
    {
      $res = Db::name('seaprice')->where('id','in',$seaprice_id)->delete();
     // echo Db::getLastSql();exit;
      if($res){
           $response['success'][] = '删除seaprice表';  
      }else{ $response['fail'][] = '删除seaprice表';   }
    
    return  $response;
    }
}