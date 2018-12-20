<?php
namespace app\api\model;
use think\Model;
use think\Db;
class Wechat extends Model
{
    
    
    protected function initialize()
    {
        parent::initialize();
        $this->order_status = config('config.order_status');
    }   
    
     //门到门 订单查询
    ////状态 已完成completion，待支付payment，已取消cancel，审核中audit_in，审核通过audit_pass，已订舱book，派车中send_car，
    //状态 已完成，待支付，已取消，信息处理中，承运中，已订舱，派车中，
    public function orderQuery($member_code,$limit,$page,$status,$order_num,$s_port,$e_port){
           //根据状态来做判断
        $map = array();
        switch ($status){
            case 'all':
            $map = array('A.status'=>['in',$this->order_status]); 
            break;
            case 'completion':
            $map =array('A.status'=>['in',[$this->order_status['completion'],$this->order_status['check_bill']]]);
            break;
            case 'payment':
            $map =array('A.money_status'=>'nodo');  
            break;
            case 'cancel':
            $map =array('A.status'=>['in',[$this->order_status['stop'],$this->order_status['cancel']]]);  
            break;
            case 'audit_in':
            $map =array('A.status'=>$this->order_status['order_audit']);  
            break;
            case 'audit_pass':
            $map =array('A.status'=>['not in',[$this->order_status['order_audit'],$this->order_status['stop'],$this->order_status['cancel']]]);
            break;
            case 'book':
            $map =array('A.status'=>$this->order_status['send_car']);  
            break;
            case 'send_car':
            $map =array('A.status'=>$this->order_status['loading']);  
            break;
        }
        // var_dump($map);exit;
        if($order_num){
            $map = array('A.order_num'=> strtoupper($order_num));
        }
        if($s_port){
            $map['A.s_port_code']= $s_add;
        }
        if($e_port){
            $map['A.e_port_code']= $e_add;
        }
        $listSql =Db::name('order_port')->alias('OP')
            ->join('hl_member HM','HM.member_code = OP.member_code and HM.status=1','left')//客户信息表
            ->join('hl_seaprice SP',"SP.id= OP.seaprice_id and SP.status='1'",'left') //海运价格表
            ->join('hl_ship_route SR',"SR.id=SP.route_id and SR.status='1'",'left')//路线表
            ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
            ->join('hl_port P1','P1.port_code=SB.sl_start and P1.status=1')//起始港口
            ->join('hl_port P2','P2.port_code=SB.sl_end and P2.status=1')//目的港口
            ->field('OP.id,OP.order_num,OP.track_num,OP.ctime,OP.member_code,'
                    . 'P1.port_name s_port_name ,P1.port_code s_port_code,P1.city_id s_city_id,'
                    . 'P2.port_name e_port_name,P2.port_code e_port_code,P2.city_id e_city_id,'
                    . 'OP.status,OP.money_status,OP.container_buckle,OP.container_status,OP.type')
            ->group('OP.id')->where('OP.member_code',$member_code)
            ->buildSql();
        // $this->_p($map);exit;
        // 查询出当前页数显示的数据
        $list = Db::table($listSql.' A')->where($map)->order('A.id ,A.ctime desc')->fetchSql(false)->page($page,$limit)->select();
//        var_dump($list);EXIT;
        //转换状态
        foreach ($list as $key=>$value){
            switch ($value['status']){
                case $this->order_status['stop']:
                case $this->order_status['cancel']:
                    $list[$key]['status'] = '中止';
                    break;
                case $this->order_status['order_audit']:
                    $list[$key]['status']= '审核中';
                    break;
                case $this->order_status['booking_note']:
                    $list[$key]['status']= '待订舱';
                    break;
                case $this->order_status['send_car']:
                    $list[$key]['status']= '待派车';
                    break;
                case $this->order_status['loading']:
                    $list[$key]['status']= '待装货';
                    break;
                case $this->order_status['up_container_code']:
                    $list[$key]['status']= '待报柜号';
                    break;
                case $this->order_status['load_ship']:
                    $list[$key]['status']= '待配船';
                    break;
                case $this->order_status['payment_status']:
                    $list[$key]['status']= '确认收款';
                    break;
                case $this->order_status['sea_waybill']:
                    $list[$key]['status']= '上传水运单';
                    break;
                case $this->order_status['container_appley']:
                    $list[$key]['status']= '申请放柜中';
                    break;
                case $this->order_status['container_lock']:
                    $list[$key]['status']= '申请放柜';
                    break;
                case $this->order_status['container_unlock']:
                    $list[$key]['status']= '同意放柜';
                    break;
                case $this->order_status['unloading']:
                    $list[$key]['status']= '待送货';
                    break;
                case $this->order_status['completion']:
                    $list[$key]['status']= '订单完成';
                    break;
                case $this->order_status['check_bill']:
                    $list[$key]['status']= '对账完成';
                    break;
            }
        }

        return $list; 
    }
    
    
    public function orderDetail($member_code,$order_num){
        $list =Db::name('order_port')->alias('OP')
            ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
            ->join('hl_seaprice SP',"SP.id= OP.seaprice_id and SP.status='1'",'left') //海运价格表
            ->join('hl_ship_route SR',"SR.id=SP.route_id and SR.status='1'",'left')//路线表
            ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
            ->join('hl_sea_middle SM','SB.sealine_id=SM.sealine_id','left') //中间港口表    
            ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id   
            ->join('hl_port P1','P1.port_code=SB.sl_start')//起始港口
            ->join('hl_port P2','P2.port_code=SB.sl_end')//目的港口
            // ->join('hl_port P3','P3.port_code=SM.sl_middle')//中间港口
            ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶
            ->join('hl_sales_member SMB','SMB.member_code=OP.member_code','left') //业务员
            ->field('OP.id ,OP.order_num ,OP.cargo ,OP.container_size ,OP.container_sum ,'
                    . ' OP.weight,OP.cargo_cost, OP.container_type ,OP.comment ,'
                    . ' OP.extra_info,OP.ctime ,OP.shipper , OP.consigner ,'
                    . ' OP.price_description ,OP.premium ,OP.status ,OP.track_num,'
                    . ' OP.quoted_price,OP.container_buckle,OP.seaprice,'
                    . ' SC.ship_short_name,B.boat_code,B.boat_name,'
                    . ' P1.port_name s_port_name ,P1.port_code s_port_code,'
                    . ' P2.port_name e_port_name,P2.port_code e_port_code,OP.status,'
                    . ' SMB.sales_name,SP.shipping_date,SP.cutoff_date,SP.sea_limitation,SP.EDD' )
            ->where('OP.member_code',$member_code) 
            ->where('OP.order_num',$order_num)->fetchSql(false)->find();
                
        //查询对应的申请放柜驳回理由
        $statusSql = "(select b.* from  hl_order_port_status b right join "
                . " (SELECT order_num , max(mtime) as mtime from hl_order_port_status "
                . " where status =".$this->order_status['container_lock']
                . " group by order_num) a on a.mtime = b.mtime "
                . "and a.order_num = b.order_num and a.order_num = '$order_num' ) ";

        $status_arr = Db::query($statusSql);
        if($status_arr){
            if($list['container_buckle']=='lock'){
                $list['container_buckle_comment']= $status_arr[0]['comment'];
            }
        }  else {
            $list['container_buckle_comment']='';
        }
    
        //转换状态
        switch ($list['status']){
            case $this->order_status['stop']:
            case $this->order_status['cancel']:
                $list['status'] = '中止';
                break;
            case $this->order_status['order_audit']:
                 $list['status'] = '审核中';
                break;
            case $this->order_status['booking_note']:
                 $list['status'] = '待订舱';
                break;
            case $this->order_status['send_car']:
                 $list['status'] = '待派车';
                break;
            case $this->order_status['loading']:
                 $list['status'] = '待装货';
                break;
            case $this->order_status['up_container_code']:
                 $list['status'] = '待报柜号';
                break;
            case $this->order_status['load_ship']:
                 $list['status'] = '待配船';
                break;
            case $this->order_status['payment_status']:
                 $list['status'] = '确认收款';
                break;
            case $this->order_status['sea_waybill']:
                 $list['status'] = '上传水运单';
                break;
            case $this->order_status['container_appley']:
                 $list['status'] = '申请放柜中';
                break;
            case $this->order_status['container_lock']:
                 $list['status'] = '申请放柜';
                break;
            case $this->order_status['container_unlock']:
                 $list['status'] = '同意放柜';
                break;
            case $this->order_status['unloading']:
                 $list['status'] = '待送货';
                break;
            case $this->order_status['completion']:
                 $list['status'] = '订单完成';
                break;
            case $this->order_status['check_bill']:
                 $list['status'] = '对账完成';
                break;
        }

        return $list ;
         
         
    }
}
?>
