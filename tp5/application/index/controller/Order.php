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
    
        //确认下单
    public function lrdd()
    {
       return $this->view->fetch('index/lrdd');
    }
}
