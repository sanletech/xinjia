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
    public function  price_route_list($ship_name,$port_start,$port_over,$pages=5,$seaprice_id=0)
    {   
        $list =Db::name('seaprice')->alias('SP')
               ->join('hl_ship_route SR','SR.id=SP.route_id','left')
               ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
               ->join('hl_sea_middle SM','SR.middle_id=SM.sealine_id','left')
               ->join('hl_port P1','P1.port_code= SB.sl_start','left')
               ->join('hl_port P2','P2.port_code= SB.sl_end','left')
               ->join('hl_port P3','P3.port_code= SM.sl_middle','left')
               ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')
               ->join('hl_boat B','B.id=SP.boat_id','left')
               ->field("SP.id,SC.ship_short_name,SP.route_id,P1.port_name s_port,P2.port_name e_port,"
               . " group_concat(distinct P3.port_name order by SM.sequence separator '-') m_port,"
               . " SP.price_20GP,SP.price_40HQ,SP.shipping_date,SP.cutoff_date,"
               . " B.boat_name,SP.sea_limitation,SP.ETA,SP.EDD,SP.mtime,SP.generalize,SP.ship_id,SP.boat_id,price_description")
                ->order('SP.mtime DESC')->group('SP.id,SC.id,B.id,SR.id')->buildSql();
                
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($port_start){
            $list = Db::table($list.' a')->where('a.s_port', 'like', "%{$port_start}%")->buildSql();
            $pageParam['query']['s_port_name'] = $port_start;
        }
        if($port_over){
            $list = Db::table($list.' b')->where('b.e_port', 'like', "%{$port_over}%")->buildSql();
            $pageParam['query']['e_port_name'] = $port_over;
        }
        if($ship_name){
            $list = Db::table($list.' c')->where('c.ship_short_name', 'like', "%{$ship_name}%")->buildSql();
            $pageParam['query']['ship_name'] = $ship_name;
        }
        if($seaprice_id){
            $list = Db::table($list.' d')->where('d.id',"$seaprice_id")->buildSql();
        }
   
        $list =Db::table($list.' e')->paginate($pages,false,$pageParam);  
//        $this->_p($list);exit;
        return $list;
    }
    
    //船运航价的添加
     public function  price_route_add($data)
    {        
//         $this->_p($data);exit;             
        $pricedata['price_description']=$data['price_description'];
        $pricedata['ship_id'] = strstr($data['ship'],'_', true);
        $pricedata['price_20GP'] = $data['price_20GP'];
        $pricedata['price_40HQ'] = $data['price_40HQ'];
        $pricedata['shipping_date'] = date('Y-m-d H:i:s',strtotime($data['shipping_date']));
        $pricedata['cutoff_date'] = date('Y-m-d H:i:s',strtotime($data['cutoff_date']));
        $pricedata['boat_id'] = $data['boat'];
        $pricedata['sea_limitation'] = $data['sea_limitation'];
        $pricedata['ETA'] = date('Y-m-d H:i:s',strtotime($data['shipping_date'].'+ '.$data['sea_limitation'].'day'));
        $pricedata['EDD'] = date('Y-m-d H:i:s',strtotime("+3day",strtotime($pricedata['ETA'])));
        $pricedata['generalize'] = $data['generalize'];
        $pricedata['mtime'] = date('Y-m-d H:i:s');
        $pricedata['route_id'] =$data['route'];  
        //判断是否存在同一个船公司 船舶id  航线  船期同样的 
        $res =Db::name('seaprice')->where([
            'ship_id'=>$pricedata['ship_id'],
            'boat_id'=>$pricedata['boat_id'],
            'route_id'=>$pricedata['route_id'],
            'shipping_date'=> $pricedata['shipping_date']
            ])->find();
        if($res){
            $response['fail'] = '航线运价重复';
            return  $response;
        }
        $res3 = Db::name('seaprice')->insert($pricedata);
        $res3 ? $response['success'] = '添加数据成功':$response['fail'] = '添加数据失败';
        return  $response;
    }
    
          //航线详情list
    public function  route_select($sl_start,$sl_end)
    {      
        $list =Db::name('ship_route')->alias('SR')
             ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
             ->join('hl_sea_middle SM','SR.middle_id=SM.sealine_id','left')
             ->join('hl_port P1','P1.port_code= SB.sl_start','left')
             ->join('hl_port P2','P2.port_code= SB.sl_end','left')
             ->join('hl_port P3','P3.port_code= SM.sl_middle','left')
             ->field("SR.id,SR.bothend_id,SR.middle_id,"
                     . "P1.port_name s_port,P1.port_code s_port_code,"
                     . "P2.port_name e_port,P2.port_code e_port_code, "
                     . "group_concat(distinct P3.port_name order by SM.sequence separator '-') m_port")
             ->group('SR.id')->where(['P1.port_code'=>$sl_start,'P2.port_code'=>$sl_end])->select();  

        return $list;
    }
    

      //航运价格的修改更新
      public function  price_route_toedit($data)
    {          

        $pricedata['id'] = $data['id'];
        $pricedata['ship_id'] = strstr($data['ship'],'_', true);
        $pricedata['price_20GP'] = $data['price_20GP'];
        $pricedata['price_40HQ'] = $data['price_40HQ'];
        $pricedata['shipping_date'] = strtotime($data['shipping_date']);
        $pricedata['cutoff_date'] = strtotime($data['cutoff_date']);
        $pricedata['boat_id'] = $data['boat'];
        $pricedata['sea_limitation'] = $data['sea_limitation'];
        $pricedata['ETA'] = strtotime($data['shipping_date'].'+ '.$data['sea_limitation'].'day');
        $pricedata['EDD'] = strtotime("+3day",$pricedata['ETA']);
        $pricedata['generalize'] = $data['generalize'];
        $pricedata['mtime'] = date('Y-m-d H:i:s');
        $pricedata['route_id'] =$data['route_id'];  
        $res3 = Db::name('seaprice')->update($pricedata);
      // echo Db::getLastSql();exit;
        $res3 ? $response['success'] = '修改seaprice表':$response['fail'] = '修改seaprice表';
        return  $response;
    }
    
     //船运航价的删除
     public function  price_route_del($seaprice_id)
    {
        $res3 = Db::name('seaprice')->where('id','in',$seaprice_id)->delete();
        if($res3){
            $response['success'][] = '删除seaprice表';  
        }else{ $response['fail'][] = '删除seaprice表';   }
    
    return  $response;
    }
    
    
    
    //车队运价展示
    public function price_trailer_list($port_name ,$pages=15,$cl_id ='') {
        
         $list = Db::name('carprice')->alias('CP')
                ->join('hl_port P', 'CP.port_id =P.port_code', 'left')
                ->field('CP.*,P.port_name')
                ->order('CP.id')->buildSql();
        
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($port_name){
            $list = Db::table($list.' a')->where('a.port_name', 'like', "%{$port_name}%")->buildSql();
            $pageParam['query']['port_name'] = $port_name;
        }
        $lista =Db::table($list.' a')  ->paginate($pages,false,$pageParam);  
        
        return $lista;
        
    }
    //车队运价 删除
    public function price_trailer_del($carprice_id){
        $res = Db::name('carprice')->where('cl_id','in',$carprice_id )->delete();
        $res ?  $response['success'][] = '删除carprice表':$response['fail'][] = '删除carprice表';
        return $response ;
    }
    //车队运价的添加
    public function price_trailer_toadd($port_id, $address_data, $load, $send){
            
        
        $address_id= strstr($address_data,'_',true);
        $select_res = Db::name('carprice')->where(['port_id'=>$port_id, 'address_id'=>$address_id])->find();
        $mtime =  date('Y-m-d H:i:s');
        //如果没有查到就添加这条记录 否则就修改
        if(!$select_res){
            //对$address_id 做判断是县级还是镇级 ,获取县级code
            if(strlen($address_id)==9){
                $area_id = substr($address_id,0,-3); //镇级 
            }  else {
                $area_id =$address_id; //县级
            } 
            $address_name= ltrim($address_data ,$address_id.'_'); //地址 
          
            $add_name =Db::name('area')->alias('A')
                    ->join('hl_city C','A.father = C.city_id','left')
                    ->join('hl_province P','C.father = P.province_id','left')
                    ->where('A.area_id ',$area_id)
                    ->field('P.province,C.city,A.area')
                    ->find();
            $add_name = implode('', $add_name).$address_name;
         
            $data = array( 'r_20GP'=>$load['price_20GP'],'r_40HQ'=>$load['price_40HQ'],
                            's_20GP'=>$send['price_20GP'],'s_40HQ'=>$send['price_40HQ'],
                            'port_id'=>$port_id,'address_id'=>$address_id,
                            'address_name'=>$add_name,'mtime'=>$mtime );
            $res =Db::name('carprice')->insert($data);
        }  else {
            $up_data = ['r_20GP'=>$load['price_20GP'],'r_40HQ'=>$load['price_40HQ'],
                        's_20GP'=>$send['price_20GP'],'s_40HQ'=>$send['price_40HQ'],'mtime'=>$mtime];
            $res =Db::name('carprice')->where('id',$select_res['id']) ->update($up_data);
        }
        
        return $res ? true :FALSE ;
    }
    
    
    //车队运价的修改更新
    public function  price_trailer_toedit($data)
    {   
        $s_id = $data['s_id'];  //送货车队的 运线id
        $r_id = $data['r_id'];  //装货车队的 运线id
        
        //如果存在address_name就是原来的
        if( array_key_exists('address_name', $data)){
            $cl_id =  $data['cl_id']; //路线id
        }else{
             //根据港口和地址 贮存车队送货/装货线路
            if( array_key_exists('port_id', $data)){
                $port_id =  $data['port_id']; //港口id
            }  else {
                $port_id = strstr($data['port'],'_',true);//港口id
            }
            $address_data =  $data['town'] ? $data['town'] :$data['area']; 
          
            $cl_id = $this->lineCar($port_id,$address_data);
        }
         $mtime =  date('Y-m-d H:i:s'); //修改时间
        
       //装货和送货车队的id ,价格
        $load_car = strstr($data['car_load'],'_',true); 
        $load_price_20GP = $data['load']['price_20GP'];
        $load_price_40HQ = $data['load']['price_40HQ'];
        
        $send_car = strstr($data['car_send'],'_',true); 
        $send_price_20GP = $data['send']['price_20GP'];
        $send_price_40HQ = $data['send']['price_40HQ'];
        
        $sql = "update hl_carprice set cl_id ='$cl_id',car_id ='0',"
                . "price_20GP ='$load_price_20GP',price_40HQ ='$load_price_40HQ'"
                . ",mtime='$mtime'  where id ='$r_id'";
        $sql2 = "update hl_carprice set cl_id ='$cl_id',car_id ='0',"
                . "price_20GP ='$send_price_20GP',price_40HQ ='$send_price_40HQ'"
                . ",mtime='$mtime'  where id ='$s_id'";
        $res = Db::execute($sql); $res2 = Db::execute($sql2);
        $res ? $response['success'][] = '修改carprice表_装货':$response['fail'][] = '修改carprice表_装货';
        $res2 ? $response['success'][] = '修改carprice表_送货':$response['fail'][] = '修改carprice表_送货货';
        return $response;
    }
    
    //查询车队运输线是否存在 若存在就反悔线路id不存在就添加 
    //参数分别为 港口id, 目的地址, 
    public function  lineCar($port_id,$address_data){
        $address_id= strstr($address_data,'_',true);
      
        $res = Db::name('car_line')->where(['port_id'=>$port_id, 'address_id'=>$address_id])->limit(1)->find();
        //如果没有查到就添加这条记录 否则返回路线的id
        if(!$res){
            //对$address_id 做判断是县级还是镇级
            if(strlen($address_id)==9){
                $area_id = substr($address_id,0,-3); //镇级 
            }  else {
                $area_id =$address_id; //县级code
            } 
            $address_name= ltrim($address_data ,$address_id.'_'); //地址 
          
            $add_name =Db::name('area')->alias('A')
                    ->join('hl_city C','A.father = C.city_id','left')
                    ->join('hl_province P','C.father = P.province_id','left')
                    ->where('A.area_id ',$area_id)
                    ->field("concat_ws('','P.province','C.city','A.area') AS add_name")
                    ->find();
           
            $add_name =  $add_name['add_name'].$address_name;
            $id = Db::name('car_line')->insertGetId(['port_id'=>$port_id,'address_id'=>$address_id,'address_name'=>$add_name]);
        }  else {
            $id = $res['id'] ;   
        }
        return $id ;
    }
    
    public function priceIncidental($tol,$limit,$port_name,$ship_name){
        $param =[];
        if($port_name){
            $param['P.port_name'] =['like',"%{$port_name}%"];
        }
        if($ship_name){
            $param['SC.ship_short_name'] =['like',"%{$ship_name}%"];
        }
//        if(empty($param)){
//            $param['PI']=['>',0];
//        }
//        var_dump($param);exit;
        $listSql =Db::name('price_incidental')->alias('PI')
                ->join('hl_port P','P.port_code=PI.port_code','left')
                ->join('hl_shipcompany SC',"SC.id=PI.ship_id and SC.status='1'",'left')
                ->field('PI.*,P.port_name,SC.ship_short_name')
                ->where($param)->group('PI.id')->buildSql();
        
        $list = Db::table($listSql.' A')
                ->join("$listSql B","A.port_code=B.port_code and A.ship_id=B.ship_id and A.type='r'and B.type='s'")
                ->field('A.id rid,B.id sid,A.port_code,A.port_name,A.40HQ r40HQ,A.20GP r20GP,B.40HQ s40HQ,B.20GP s20GP ,A.ship_id ,A.ship_short_name')
                ->order('A.id ,A.mtime desc')->limit($tol,$limit)->select();
//        $this->_p($list);exit;
        $count =  count($list); 
        return array($list,$count);

    }
    
    
}