<?php

/*
 *  船公司个人通讯录控制器
 */

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\CarShipMan;
use think\Request;
use think\Db;

/*
 *  车队 船公司联系人的资料管理
 */

class ShipMan extends Base {

    public function man_list() {
        $seachData = $this->request->param();
        $ship_name = $this->request->param('ship_name');
        $port_name = $this->request->param('port_name');

       // $Manlist = new CarShipMan();
        $list = CarShipMan::ship_list($seachData);
        //  echo Db::getLastSql();exit;
        $page = $list->render();
        $count = count($list);
        $this->assign('searchship', $ship_name);
        $this->assign('searchport', $port_name);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('count', $count);
        return $this->view->fetch('carshipman/shipman_list');
    }

    //怎么把船公司及下面的港口传到页面上选择
    public function man_add() {
        $sql = "select group_concat(SP.port_id order by SP.seq) port_id,"
                . "group_concat(P.port_name order by SP.seq) port_name,"
                . "SP.ship_id ship_id, SC.ship_short_name ship_name "
                . "from hl_ship_port SP     "
                . "left join hl_port P on P.port_code = SP.port_id  "
                . "left join hl_shipcompany SC on SC.id = SP.ship_id   "
                . "group by SP.ship_id  order by SP.ship_id";
       // var_dump($sql);exit;m   
        $ship_arr = Db::query($sql);
        $sql1 = "select SP.ship_id ,P.city_id,SP.port_id from hl_ship_port SP "
                . "left join hl_port P on P.port_code = SP.port_id  "
                . "left join hl_shipcompany SC on SC.id = SP.ship_id  "
                . "left join hl_city C on C.city_id = P.city_id "
                . "order by SP.ship_id ,P.city_id,SP.seq";
        var_dump($sql1);exit;
           $this->_p($ship_arr);exit;
        $data = array();
        foreach ($ship_arr as $k =>$v){
            var_dump($v);
        }exit;
        $this->_p($data);exit;
               
        // json_encode($list);
        // $this->_p($list);exit;
        $this->assign('shiplist', $list);
        return $this->view->fetch('carshipman/shipman_add');
    }

    public function to_add() {
        $data = $this->request->param();
        $data['ship_id'] = explode('_', $data['ship']);
        $data['ship_id'] = $data['ship_id']['1'];
        $data['port_id'] = strstr($data['port'], '_', '1');
        unset($data['ship']);
        unset($data['port']);
        $res = Db::name('shipman')->insert($data);
        if ($res) {
            return $status = 1;
        } else {
            return $status = 0;
        }
    }

    public function man_del() {
        $id = $this->request->param();
        $ManDel = new CarShipMan();
        $id = implode(',', $id['id']);
        $res = $ManDel->ship_del($id);
        if (!array_key_exists('fail', $res)) {
            $status = 1;
        } else {
            $status = 0;
        }
        json_encode($status);

        return $status;
    }

//     public function man_add() 
//    {    
//        function resultSQL($sub_id) {
//            $sub_name = $sub_id; //需要拆分的字段的名字
//            $sub_id = 'SPC.'.$sub_id; //hl_ship_port_city 需要拆分的字段的
//            $sql = " SELECT SPC.id , SUBSTRING_INDEX(SUBSTRING_INDEX($sub_id, ',', S.seq), ',' ,- 1)$sub_name , S.seq "
//               . " FROM hl_sequence S CROSS JOIN hl_ship_port_city SPC  WHERE  S.seq  BETWEEN 1 AND "
//               . "(  SELECT 1 + LENGTH($sub_id) - LENGTH(REPLACE($sub_id, ',', ''))  ) order by SPC.ID ,S.seq"; 
//             return $sql;
//        }
//        $port_id_SQL = resultSQl('port_id');  
//        $sql = "select SPC.id ,SPC.ship_id ,SC.ship_short_name ,"
//                . "group_concat(RES.port_id order by RES.seq ) port_id  ,"
//                . "group_concat(P.port_name  order by RES.seq ) port_name   "
//                . "  from hl_ship_port_city SPC"
//                . "  left join ($port_id_SQL) "
//                . "  as RES on RES.id =SPC.id   "
//                . "  left join hl_port P on P.id = RES.port_id   "
//                . "  left join hl_shipcompany SC on SC.id =SPC.ship_id   "
//                . "group by SPC.id  "; 
//        $list = Db::query($sql);
//        for($i=0;$i<count($list);$i++ ){
//            $port_id = explode(',', $list[$i]['port_id']);
//            $port_name = explode(',', $list[$i]['port_name']);
//            $list[$i]['port_list']= array_map(null,$port_id, $port_name);
//            unset($list[$i]['port_id']);
//            unset($list[$i]['port_name']);
//        }
//           // json_encode($list);
//           // $this->_p($list);exit;
//            $this->assign('shiplist',$list);
//        return $this->view->fetch('carshipman/shipman_add');
//        
//    }
    
    
    
}
    
?>