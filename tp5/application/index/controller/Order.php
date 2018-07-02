<?php

namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Order as OrderM;
class Order extends Base 
{
    
    //海运运价
    public function hyyj()
    {
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->price_sum();
        $page= $list->render();
        $this->view->assign('page',$page);
        $this->view->assign('list',$list);
      //  $this->_p($list);exit;
       return $this->view->fetch('index/hyyj');
    }
    
       //海运详情
    public function route_detail()
    { 
        $middle_id = $this->request->param(); 
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->price_sum();
        $page= $list->render();
        $this->view->assign('page',$page);
        $this->view->assign('list',$list);
      //  $this->_p($list);exit;
       return $this->view->fetch('index/hyyj');
    }
    
        //确认下单
    public function confirm_order()
    {   $price= $this->request->param(); 
        //var_dump($price);exit;
        $sea_pirce =new OrderM;
        $data = $sea_pirce->confirm_order($price);
        return $this->view->fetch('index/lrdd');
    }
}
