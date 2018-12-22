<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Session;
//订单模块
class Order extends Model
{
    private $order_status;
    private $page=5;

    protected function initialize()
    {
        parent::initialize();
        $this->order_status = config('config.order_status');
  
    }   
    

    public function order_public($page,$limit,$search,$state='3') {
        $map = [];
        $search['order_num']? $map['OP.order_num']=$search['order_num'] :'';
        $search['track_num']? $map['OP.track_num']=$search['track_num'] :'';
        $listSql =Db::name('order_port')->alias('OP')
            ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
            ->join('hl_seaprice SP',"SP.id= OP.seaprice_id and SP.status='1'",'left') //海运价格表
            ->join('hl_ship_route SR',"SR.id=SP.route_id and SR.status='1'",'left')//路线表
            ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
            ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id   
            ->join('hl_port P1','P1.port_code=SB.sl_start and P1.status=1','left')//起始港口
            ->join('hl_port P2','P2.port_code=SB.sl_end and P2.status=1','left')//目的港口
            ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶
            ->join('hl_sales_member SMB','SMB.member_code=OP.member_code','left') //业务员
            ->field('OP.id,OP.order_num,OP.track_num,OP.ctime,OP.container_size,OP.container_sum,'
                    . 'OP.cargo,OP.consigner,OP.container_buckle,SC.ship_short_name,B.boat_code,B.boat_name,'
                    . 'P1.port_name s_port_name ,P1.port_code s_port_code,'
                    . 'P2.port_name e_port_name,P2.port_code e_port_code,OP.status,'
                    . 'SMB.sales_name,SP.shipping_date,SP.cutoff_date,SP.sea_limitation, SB.id as SB_id' )
            ->group('OP.id')->where($map) ->where('OP.status','in',$state)->where('OP.type','door')
            ->buildSql();
//     var_dump($listSql);exit;
        $count =  Db::table($listSql.' A')->count();  //获取总页数
        // 查询出当前页数显示的数据
        $list = Db::table($listSql.' B')->order('B.id ,B.ctime desc')->page($page,$limit)->select();
        //查询对应的申请放柜驳回理由
        $statusSql = "(select b.* from  hl_order_port_status b right join "
                . " (SELECT order_num , max(mtime) as mtime from hl_order_port_status "
                . " where status =".$this->order_status['container_lock'] 
                . " group by order_num) a on a.mtime = b.mtime and a.order_num = b.order_num) ";
        $status_arr = Db::query($statusSql);
        foreach ($status_arr as $v){
            foreach ($list as $key=>$value){
                if($value['container_buckle']=='lock'){
                    if($v['order_num']==$value['order_num']){
                    $list[$key]['container_buckle_comment'] = $v['comment'];
                    }
                }
            }
        }
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
        return array('list'=>$list,'count'=>$count);
    }


    
    //录入派车信息
    public function  send_car($order_num,$track_num,$container_sum,$car_data,$type)
    {   
        $mtime = date('Y-m-d H:i:s');   $response=[];
//        //将派车信息插入到 order_car 里 查询是否存已经存在了对应的数据
   //     $order_car_id = Db::name('order_car')->where(['order_num'=>$order_num,'type'=>$type])->column('id');
        foreach ($car_data as $key => $value) {
            $car_data[$key]['mtime'] = $mtime;
            $car_data[$key]['type'] = $type;
            $car_data[$key]['order_num'] = $order_num;
        }
//        if(empty($order_car_id)){
//            $res2 = Db::name('order_car')->lock(true)->insertAll($car_data);
//            $res2 ?$response['success'][]='录入车队信息成功':$response['fail'][]='录入车队信息失败';
////        }  else {
            foreach ($car_data as  $value) {
                $res3 = Db::name('order_car')->where('id',$value['id'])->update($value);
                $res3 ?$response['success'][]='更新车队信息成功':$response['fail'][]='更新车队信息失败';
            }
//        }

        return $response;
    }

}
