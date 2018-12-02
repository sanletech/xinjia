<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Session;

class OrderProcess  extends Model{
    
    private $order_status;
    private $page=5;

    protected function initialize()
    {
        parent::initialize();
        $this->order_status = config('config.order_status');
  
    }   

    //审核客户提交的订单
    public function order_audit($pages,$state,$type){
        $list =Db::name('order_port')->alias('OP')
            ->join('hl_member HM','HM.member_code = OP.member_code','left')//客户信息表
            ->join('hl_seaprice SP','SP.id= OP.seaprice_id','left') //海运价格表
            ->join('hl_ship_route SR','SR.id=SP.route_id','left')//路线表
            ->join('hl_sea_bothend SB','SB.sealine_id=SR.bothend_id','left')//起始港 终点港 
            ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')//船公司id                                                    //起始港终点港
            ->join('hl_port P1','P1.port_code=SB.sl_start','left')//起始港口
            ->join('hl_port P2','P2.port_code=SB.sl_end','left')//目的港口
            ->join('hl_boat B','B.id =SP.boat_id','left')//船公司合作的船舶 
            ->field('OP.*,HM.company,HM.name,SC.ship_short_name,B.boat_code,B.boat_name,P1.port_name s_port_name ,P2.port_name e_port_name')
            ->group('OP.id')
            ->where('OP.status','in',$state)
            ->where('OP.type',$type)
            ->paginate($pages);
        return $list;
    } 
    
    //记录订单的更新状态和时间
        public function orderUpdate($order_num,$status,$title,$comment='',$submitter='') {
        if(empty($submitter)){
            $submitter= Session::get('user_info','think');
        }
        $mtime =  date('Y-m-d H:i:s');
        $data=array('order_num'=>$order_num,'status'=>$status,'title'=>$title,'comment'=>$comment,'submitter'=>$submitter,'mtime'=>$mtime);

        $res =Db ::name('order_port_status')->insert($data); //记录操作
        $order_status= $this->order_status;
        if(in_array($status,$order_status)){
                Db::startTrans();
                try{
                    Db::name('order_port')->where('order_num',$order_num)
                        ->update(['status'=>$status,'action'=>$title,'mtime'=>$mtime]);
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return array('status'=>0,'message'=>'操作失败'.$e->getMessage());
                }      
                    return array('status'=>1,'message'=>'操作成功');
        }else{
            
            return array('status'=>0,'message'=>'状态类型错误');
        }
    }
    
}
