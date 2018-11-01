<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
class Personal extends Model
{
    
    private $order_status;
    private $page=5;

    protected function initialize()
    {
        parent::initialize();
        $this->order_status = config('config.order_status');
  
    }    
    
    public function place_order($member_code,$tol,$limit,$map) {
        $order_status_on=array($this->order_status['stop'] ,$this->order_status['cancel']
                        ,$this->order_status['order_audit'],$this->order_status['booking_note']
                        ,$this->order_status['up_container_code'],$this->order_status['sea_waybill']
                        ,$this->order_status['completion']) ;
//        var_dump($order_status_on);exit;
        $order_status_on = implode(',', $order_status_on);
        $statusSql ="(select a.* from (select * from hl_order_port_status where status in "
                . "($order_status_on) order by mtime desc) a group by a.order_num)";
        $data =Db::name('order_port')->alias('OP')
                ->join($statusSql.' OPS','OP.order_num = OPS.order_num','left')
                ->join('hl_seaprice SP','SP.id=seaprice_id','left')
                ->join('hl_shipcompany SC','SC.id= SP.ship_id','left')
                ->join('hl_ship_route SR','SR.id=SP.route_id','left')
                ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')
                ->join('hl_port P1','P1.port_code=SB.sl_start','left')
                ->join('hl_port P2','P2.port_code=SB.sl_end','left')
                ->join('hl_boat B','B.id=SP.boat_id','left')
                ->field('OP.*,OPS.title, OPS.status change_status,OPS.mtime change_mtime,'
                        . 'SC.ship_short_name,B.boat_code,B.boat_name,'
                        . 'P1.port_code s_port_code, P1.port_name s_port,'
                        . 'P2.port_code e_port_code, P2.port_name e_port')
                ->group('OP.order_num')->order('OP.ctime desc')->buildSql();
        $lists =Db::table($data.' A')->where($map)->group('A.id')->limit($tol,$limit)->select();

        //展示扣柜驳回的理由
        $container_buckle =Db::name('order_port_status')
                ->where('status',$this->order_status['container_lock'])
                ->order('mtime desc')->field('id,order_num,comment')->buildSql();
        $container_buckle =Db::table($container_buckle.' A')->group('A.order_num');
        foreach ($lists as $key =>$list){
            foreach ($container_buckle as $k=>$v){
                if($list['order_num']==$v['order_num']){
                    $lists[$key]['container_buckle_comment'] = $v['comment'];
                }  
            }
            if(!array_key_exists('container_buckle_comment', $lists[$key])){
                $lists[$key]['container_buckle_comment'] ='';
            }
        }       
        $this->_p($lists);
        return  $lists;
        
    }



}