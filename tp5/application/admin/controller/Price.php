<?php
/*
 *  运价设置
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use app\admin\model\Price as PriceM;
use app\admin\model\Port as PortM;
use think\Db;

class Price extends Base
{   

     //航线运价
    public function price_route() 
    {   
        $ship_name =  input('get.ship_name');
        $port_start = input('get.s_port_name');
        $port_over = input('get.e_port_name');
        $port_start? $this->assign('s_port_name',$port_start):''; 
        $ship_name ? $this->assign('ship_name',$ship_name):''; 
        $port_over ? $this->assign('e_port_name',$port_over):''; 
        
        $route = new PriceM;
        $ship_name=trim($ship_name); $port_start=trim($port_start); $port_over=trim($port_over);
        $list = $route->price_route_list($ship_name,$port_start,$port_over ,5);
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->view->fetch('price/price_route'); 
    }
    //航线详情添加展示页面
    public function route_add(){
        $message =$this->quickMessage();
        $this->view->assign('message',$message);
        return $this->view->fetch('price/route_add');
    }
    //传递根据前面选择的起点和终点的中间线路行情
    public function route_select(){
        $data = $this->request->param();
     //   $this->_v($data);exit;
        $sl_start =$data['sl_start'];
        $sl_end =$data['sl_end'];
        //使用PortM 里的行情list方法查询对应的中间港口
        $ship_route = new PriceM;
        $list =$ship_route->route_select($sl_start,$sl_end);
        return json($list);    
    }
    //航线添加
    public function route_toadd(){
        $data = $this->request->param();
//       $this->_p($data);  exit;
        $seaprice = new PriceM;
        $res = $seaprice->price_route_add($data); 
        if(!array_key_exists('fail', $res)){
            $response=['status'=>1,  'message'=>'添加成功'];
        }else {
            $response=['status'=>0,  'message'=>$res['fail']];
        } 
      
        return $response; 
    }
    //航线运价重新发布
    public function route_again() {
        $seaprice_id = input('get.seaprice_id');
        $seaprice = new PriceM;
        $res = $seaprice-> price_route_list('','','',100,$seaprice_id);
//        $this->_p($res['0']);exit;
        $message =$this->quickMessage();
        $this->view->assign('message',$message);
        $this->assign('data',$res['0']);
        $this->assign('readOnly','true');//是否禁用
        $this->assign('toURL',url('admin/Price/route_toedit','type=again'));
        return $this->view->fetch("price/route_edit");
    }
    
    //航线修改页面
    public function route_edit(){
        $seaprice_id = input('get.seaprice_id');
        $seaprice = new PriceM;
        $res = $seaprice-> price_route_list('','','',100,$seaprice_id);
//        $this->_p($res['0']);exit;
        $message =$this->quickMessage();
        $this->view->assign('message',$message);
        $this->assign('data',$res['0']);
        $this->assign('readOnly','fales'); //是否禁用
        $this->assign('toURL',url('admin/Price/route_toedit','type=edit'));
        return $this->view->fetch("price/route_edit");
    }
    //航线执行修改
    public function route_toedit(){
        $data = $this->request->except('type');
        $type = $this->request->param('type');//修改还是重新发布
      $this->_p($data);var_dump($type);exit;
        $seaprice = new PriceM;
        if($type=='edit'){
            $res = $seaprice->price_route_toedit($data);    
        }elseif ($type=='again') {
            $res = $seaprice->price_route_add($data); 
        }
        var_dump($res);exit;   
        if(!array_key_exists('fail', $res)){
            $response =['status'=>1,'message'=>$res['success']]; 
        }else {
            $response =['status'=>0,'message'=>$res['fail']]; 
        } 
        return $response; 
    }
    //航线运价删除
    public function route_del(){
        //接受price_route_del 的id 数组
        $data = $this->request->param();
        $seaprice_id = $data['id'];
        $seaprice = new PriceM;
        $res = $seaprice->price_route_del($seaprice_id);
         if(!array_key_exists('fail', $res)){
            $status =1; 
        }else {$status =0;} 
        json_encode($status);   
        return $status;   
    }
    
    
        //拖车运价
    public function price_trailer() 
    {      
        $port_name = input('get.port_name');
        $port_name =  trim($port_name);
        if($port_name){
            $this->assign('port_name',$port_name); 
        }
    
        $route = new PriceM;
        $list = $route->price_trailer_list($port_name ,15);
//        $this->_p($list);exit;
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->view->fetch('price/price_trailer'); 
    }

    
    //拖车添加展示页面
    public function trailer_add(){
        
        return $this->view->fetch("price/trailer_add");
    }
    //拖车添加执行页面
    public function trailer_toadd(){
        $data = $this->request->param();
//       $this->_v($data);exit;
        //根据港口和地址 贮存车队送货/装货线路
        $port_id = strstr($data['port'],'_',true); 
        $address_data =  $data['town'] ? $data['town'] :$data['area']; 
        $carprice = new PriceM;
        $res = $carprice->price_trailer_toadd($port_id, $address_data, $data);
        return json( $res ? array('status'=>1,'message'=>'添加成功') : array('status'=>0,'message'=>'添加失败') );   
      
    }
    
    //拖车运价修改页面
    public function trailer_edit(){
        $id = $this->request->param('id'); 
        $data = Db::name('carprice')->alias('CP')
                ->join('hl_port P', 'CP.port_id =P.port_code', 'left')
                ->field('CP.*,P.port_name')
                ->where('CP.id',$id)->find();
//         $this->_p($data);EXIT;
        $this->view->assign('data',$data);
        return $this->view->fetch("price/trailer_edit");
    }
        //拖车运价执行修改
    public function trailer_toedit(){
        $data = $this->request->param();
//        $this->_p($data);exit;
        if(!array_key_exists('port_id', $data)){
            $port_id = strstr($data['port'],'_',TRUE);
            $port_name = ltrim($data['port'], $port_id.'_');
        }  else {
            $port_id = $data['port_id'];
            $port_name = $data['port_name'];
        }
        if(!array_key_exists('address_id', $data)){
            $area_id = strstr($data['area'], '_',TRUE);
            $add_name =Db::name('area')->alias('A')
                    ->join('hl_city C','A.father = C.city_id','left')
                    ->join('hl_province P','C.father = P.province_id','left')
                    ->where('A.area_id ',$area_id)
                    ->field('P.province,C.city,A.area')
                    ->find();
            $address_id =strstr($data['town'], '_',TRUE);
            $address_name = implode('', $add_name).ltrim($data['town'],$address_id.'_');
        }  else {
            $address_id = $data['address_id'];
            $address_name = $data['address_name'];
        }
        $id = $data['id'];
        $mtime = date('Y-m-d H:i:s');
        $up_data =array('port_id'=>$port_id,'address_id'=>$address_id,
            'address_name'=>$address_name,'r_20GP'=>$data['r_20GP'],
            'r_40HQ'=>$data['r_40HQ'],'s_20GP'=>$data['s_20GP'],
            's_40HQ'=>$data['s_40HQ'],'mtime'=>$mtime);
        $res =Db::name('carprice')->where('id',$id)->update($up_data);
        return json($res ? array('status'=>1,'message'=>'修改成功'): array('status'=>0,'message'=>'修改失败')); 
    }
        //拖车运价执行删除
    public function traile_del(){
        //接受price_route_del 的id 数组
        $data = $this->request->param();
        $carprice_id = $data['id'];
        $res =Db::name('carprice')->where('id','in',$carprice_id)->update(['status'=>0]);
        return json($res ? array('status'=>1,'message'=>'删除成功'): array('status'=>0,'message'=>'删除失败'));    
    }
    //港口杂费
    public function priceIncidental(){
        //获取每页显示的条数
        $limit= $this->request->param('limit',10,'intval');
        //获取当前页数
        $page= $this->request->param('page',1,'intval');  
        //获取查询条件
        $port_name =$this->request->param('port_name');
        $ship_name =$this->request->param('ship_name');  
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        $dataM = new PriceM;
        $listArr = $dataM->priceIncidental($tol,$limit,$port_name,$ship_name);
        //分页数据
        $list =$listArr['list'];
        // 总页数
        $count = $listArr['count'];
        $this->view->assign('port_name',$port_name);
        $this->view->assign('ship_name',$ship_name);
        $this->view->assign('list',$list);
        $this->view->assign('page',$page); 
        $this->view->assign('count',$count); 
        $this->view->assign('limit',$limit); 
        $this->view->assign('page_url',url('admin/price/priceIncidental'));
        return $this->view->fetch('price/price_incidental'); 
        
    }
    
    //港口杂费修改
    public function incidentalEdit(){
        $id = $this->request->param('id');
        $list =Db::name('price_incidental')->alias('PI')
                ->join('hl_port P','P.port_code=PI.port_code','left')
                ->join('hl_shipcompany SC',"SC.id=PI.ship_id and SC.status='1'",'left')
                ->field('PI.*,P.port_name,SC.ship_short_name')
                ->where('PI.id',$id)->find();
        $this->view->assign('list',$list);
        return $this->view->fetch('price/price_incidentaledit'); 
    }

    //港口杂费执行修改
    public function incidentalToEdit(){
        $data = $this->request->param();
        $id = $data['id'];
        $ship_id = $data['ship_id'];
        $port_code =$data['port_code'];
        $start_40HQ_fee =$data['start_40HQ_fee'];
        $start_20GP_fee =$data['start_20GP_fee'];
        $end_40HQ_fee = $data['end_40HQ_fee'];
        $end_20GP_fee = $data['end_20GP_fee'];
        $mtime = date('Y-m-d H:i:s');
        $res =Db::name('price_incidental')
            ->where(array('ship_id'=>$ship_id,'port_code'=>$port_code))
            ->update(array('r_40HQ'=>$start_40HQ_fee,'r_20GP'=>$start_20GP_fee,
                's_40HQ'=>$end_40HQ_fee,'s_20GP'=>$end_20GP_fee,'mtime'=>$mtime));
        
        return json($res?$response=['status'=>1,'mssage'=>'修改成功']:$response=['status'=>0,'message'=>'修改失败']);
    }
    
    //港口杂费删除
    public function incidentalDel(){
        $id = $this->request->param(['id']);
        $res =Db::name('price_incidental')->where('id','in',$idArr)->update(['status'=>0]);
        return  $res ?$response=['status'=>1,'mssage'=>'删除成功']:$response=['status'=>0,'message'=>'删除失败'];
    }
        //添加港口杂费
    public function incidentalAdd(){
        return $this->view->fetch('price/price_incidentaladd'); 
    }
    public function incidentalToAdd(){
        $data =  $this->request->only(['ship','port_code','end_20GP_fee','end_40HQ_fee','start_20GP_fee','start_40HQ_fee'],'post');
        $mtime = date('Y-m-d H:i:s');
        $port_code=$data['port_code'][0]; $ship_id=$data['ship'];
        $response =[];
        //先查询是否已经存在港口了
        $res1 = Db::name('price_incidental')->where(['ship_id'=>$ship_id,'port_code'=>$port_code])->find();
        if(!$res1){
            $insertData = ['port_code'=>$port_code,'ship_id'=>$ship_id,'r_40HQ'=>$data['start_40HQ_fee'],'r_20GP'=>$data['start_20GP_fee'],
                    's_40HQ'=>$data['end_40HQ_fee'],'s_20GP'=>$data['end_20GP_fee'],'mtime'=>$mtime];
            $res= Db::name('price_incidental')->insert($insertData);
            return  $res ?$response=['status'=>1,'mssage'=>'添加成功']:$response=['status'=>0,'message'=>'添加失败'];
        }  else {
            return $response=['status'=>0,'message'=>'已经存在此港口请先删除再做添加'];;
        }
      
    }
    // 航线运价 的订单的价格说明
    public function quickMessage() {
        $list =Db::name('quick_message')->select();
        return $list;
    }
    
    public function addMessage() {
        $data = $this->request->param('data');
        if(empty($data)){
            return false;
        }
        $id = Db::name('quick_message')->insertGetId(['message'=>$data]);
        return $id;
    }
    public function delMessage() {
        $id = $this->request->param('id');
        $list =Db::name('quick_message')->where('id',$id)->delete();
    }
}