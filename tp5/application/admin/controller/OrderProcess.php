<?php
/*
 *  订单拆分查看完整订单控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\OrderProcess as OrderPM;
use think\Validate;
use think\session;
class OrderProcess extends Base
{     
     //查看完成的详细订单
    public function OrderDetail() {
        $M= new OrderPM;
       // $res =$M->OrderDetail($order_num);
        $res =$M->OrderDetail('A906353031424552');
        $father =$res[0];
        $car = $res[1];
        $ship =$res[2];
        $status = $res[3];
        $orderInvoice =$res[4];
        $container_code =$res[5];
        $track_num  =$res[6];
       $statusArr = array_column($status, 'change_time','status');
      
        $this->view->assign([
           'father'=>$father,
            'car'=>$car,
            'ship'=>$ship,
            'statusArr'=>$statusArr,
            'container_code'=>$container_code,
            'track_num'=>$track_num    
        ]);
        return $this->view->fetch('OrderProcess\orderprocess_list'); 
    } 
    //拆订单
    public function orderSplit() {
//        $order_num =$this->request->param('order_num');  
        $order_num ='A906371578264503';
        $data =Db::name('order_son')->where('order_num',$order_num)->column('container_code,track_num');
        $tmpArr =[];
        $container_code = array_keys($data);
        $track_num = array_unique(array_values($data))[0];
        $tmpArr =['order_num'=>$order_num ,'track_num'=>$track_num,'container_code'=>$container_code];
      //  var_dump($tmpArr);exit;
        $this->view->assign('list',$tmpArr);
        return $this->view->fetch('OrderProcess\orderprocess_split'); 
    }
    
    //处理拆分订单
    public function toSplit() {
        $data =$this->request->param();  
        $this->_p($data);exit;
        $oldC =[]; //原有剩下的集装箱数组
        $oldNum =count($oldC); //剩下的集装箱子数目
        $spliceC =[]; //分出去的集装箱数组
        $newTrack_num ='';
        $spliceNum = count($spliceC);  //拆除去的集装箱数量
        //order_father 的container_sum 减去要拆分的集装箱号码
         //获取原有订单的 保险费 车装货费，送货费，海运费，利润
        $oldePrice = Db::name('order_father')->where('order_num')
                ->field('cost,premium,quoted_price,tax_rate，container_sum')
                ->find(); //查询对应的单个柜子成本(车运费海运费利润)，总共的保险费,总报价,税率，总共箱子数量
        //修改原有订单的箱子数量,和对应的order_son订单信息， 同时拆除去的订单复制原有订单的信息 
       // $old_order =Db::name('order_fahther')
        //修改派车表里的信息
        //  $cost = ($carprice_r + $carprice_s + $seaprice + $profit); //单个成本
    }
    
    
    //查看订单的进行状态
    public function OrderDynamic($order_num) {
        $res =Db::name('order_status')->where('order_num',$order_num)->select();
        return $res;
    } 
    
    
    //修改订单
    public function orderModify(){
        $M= new OrderPM;
       // $res =$M->OrderDetail($order_num);
        $res =$M->OrderDetail('A906353031424552');
        $father =$res[0];
        $car = $res[1];
        $ship =$res[2];
        $status = $res[3];
        $orderInvoice =$res[4];
        $container_code =$res[5];
        $track_num  =$res[6];
        $statusArr = array_column($status, 'change_time','status');
      
        $this->view->assign([
           'father'=>$father,
            'car'=>$car,
            'ship'=>$ship,
            'statusArr'=>$statusArr,
            'container_code'=>$container_code,
            'track_num'=>$track_num    
        ]);
        
        return $this->view->fetch('OrderProcess\orderprocess_edit'); 
    }
    
    //记录订单修改的状态和时间操作人
    public function orderRecord($order_num,$track_num,$status,$action) {
        $res =Db::name('order_status')->where('order_num',$order_num)
                ->where('track_num',$track_num)->where('status',$status)->find();
        if(empty($res)){
            $submit_man_code =  Session::get('user_info','think');
            $data =['order_num'=>$order_num,'status'=>$status,
                'change_time'=>date('y-m-d h:i:s'),'action'=>$action,
                'submit_man_code'=>$submit_man_code,
                'track_num'=>$track_num];
            $res =Db::name('order_status')->insert($data);
            return $res?['mesg'=>true]: ['mesg'=>FALSE];
        }
        return ['mesg'=>true];
    }
    
}