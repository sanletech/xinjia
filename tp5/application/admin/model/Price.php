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
    public function  price_route_list($port_start,$port_over,$pages=5)
    {   
        $list = Db::name('seaprice')->alias('SP')
                ->join('hl_shipcompany S', 'S.id = SP.ship_id', 'left')
                ->join('hl_sealine SL','SL.id = SP.sl_id', 'left')
                ->join('hl_port P1', 'SL.sl_start=P1.id', 'left')
                ->join('hl_port P2', 'SL.sl_over=P2.id', 'left')
                ->field('SP.*, S.ship_short_name, P1.port_name as start_port , P2.port_name as over_port')->buildSql();
        
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($port_start){
            $list = Db::table($list.' a')->where('a.start_port', 'like', "%{$port_start}%")->buildSql();
            $pageParam['query']['start_port'] = $port_start;
        }
        if($port_over){
            $list = Db::table($list.' b')->where('b.over_port', 'like', "%{$port_over}%")->buildSql();
            $pageParam['query']['over_port'] = $port_over;
        }
        
        $lista =Db::table($list.' a')->paginate($pages,false,$pageParam);   
//            echo '<pre>';
//                var_dump($list);
//            echo '</pre>';exit;
//            echo Db::getLastSql() ;
        return $lista;
    }
    
    //船运航价的添加
     public function  price_route_add($data)
    {
        $pricedata['ship_id'] = strstr($data['ship'],'_', true);
        $pricedata['price_20GP'] = $data['price_20GP'];
        $pricedata['price_40HQ'] = $data['price_40HQ'];
        $pricedata['shipping_date'] = strtotime($data['shipping_date']);
        $pricedata['cutoff_date'] = strtotime($data['cutoff_date']);
        $pricedata['boat_name'] = $data['boat_name'];
        $pricedata['sea_limitation'] = $data['sea_limitation'];
        $pricedata['ETA'] = strtotime($data['shipping_date'].'+ '.$data['sea_limitation'].'day');
        $pricedata['EDD'] = strtotime("+3day",$pricedata['ETA']);
        $pricedata['generalize'] = $data['generalize'];
        $pricedata['mtime'] = time();
        $response= array();
        $port_id = $data['port_name'];
        $port_length = count($port_id); 
        if($port_length<2){
            return  $response['fail'][] = '航线缺少起始目的港口';
        }
        if($port_length >= 2){
            $sl_start = $port_id['0'];
            $sl_over  = $port_id[$port_length-1];
            $sl_middle = array_splice($port_id,'1','-1');
            $sl_startover = $this->lineStart($sl_start, $sl_over);
            if( !$sl_startover ){
                $res =  Db::name('sealine')->insert(['sl_start'=>$sl_start,'sl_over'=>$sl_over]);
                $res ?($response['success'][] = '添加sealine表') :($response['fail'][] = '添加sealine表') ;
                $pricedata['sl_id'] = Db::name('sealine')->getLastInsID();
                
            }  else { $pricedata['sl_id'] = $sl_startover ; }
            
            if( $sl_middle){    //$sl_middle 为空说明没有中间的港口航线
                $sl_middle_id = $this->line($sl_middle);  //如果返回id说明中间港口线路存在 空则新增线路
//                var_dump($sl_middle); var_dump($sl_middle_id);exit;
                if(!$sl_middle_id){
                    $sealine_id = Db::name('sea_middle')->max('sealine_id');
                    $sealine_id = $sealine_id  +1 ;
                    $str='';
                    foreach ($sl_middle as $k =>$v){
                        $str .= "('$sealine_id','$v','$k'),";
                    }
                    $str=rtrim("$str",',');

                    $sql = "insert into hl_sea_middle(sealine_id,middle_port,sequence) values".$str;
                    $res2 =Db::execute($sql); 
                    $pricedata['sm_id'] = $sealine_id ;
                    $res2 ?($response['success'][] = '添加sea_middle表') :($response['fail'][] = '添加sea_middle表') ;
                }else{  $pricedata['sm_id'] = $sl_middle_id ;}
              
            }else{  $pricedata['sm_id'] = '' ;}
        
            }
              
        $res3 = Db::name('seaprice')->insert($pricedata);
         // echo Db::getLastSql();exit;
        $res3 ? $response['success'][] = '添加seaprice表':$response['fail'][] = '添加seaprice表';
        return  $response;
    }
    
    //航运价格的修改页面的原始数据
      public function  price_route_edit($seaprice_id,$sl_id,$sm_id) 
    {   
        $res[] = Db::name('seaprice')->alias('SP')
                ->join('hl_shipcompany S', 'S.id = SP.ship_id', 'left')
                ->join('hl_sealine SL','SL.id = SP.sl_id', 'left')
                ->join('hl_port P1', 'SL.sl_start=P1.id', 'left')
                ->join('hl_port P2', 'SL.sl_over=P2.id', 'left')
                ->field('SP.*, S.ship_short_name, P1.port_name as start_port ,'
                        . ' P2.port_name as over_port,SL.sl_start,SL.sl_over')
                ->where('SP.id',$seaprice_id)->find();
        $res[] = Db::name('sea_middle')->alias('SM')
                ->join('hl_port P','P.id = SM.middle_port','left')
                ->where('sealine_id',$sm_id)
                ->field('SM.sequence,SM.middle_port,P.port_name')
                ->order('SM.sequence')
                ->select();
        return $res;
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
        $pricedata['boat_name'] = $data['boat_name'];
        $pricedata['sea_limitation'] = $data['sea_limitation'];
        $pricedata['ETA'] = strtotime($data['shipping_date'].'+ '.$data['sea_limitation'].'day');
        $pricedata['EDD'] = strtotime("+3day",$pricedata['ETA']);
        $pricedata['generalize'] = $data['generalize'];
        $pricedata['mtime'] = time();
        $response= array();
        $port_id = $data['port_name'];
        $port_length = count($port_id); 
        if($port_length<2){
            return  $response['fail'][] = '航线缺少起始目的港口';
        }
        if($port_length >= 2){
            $sl_start = $port_id['0'];
            $sl_over  = $port_id[$port_length-1];
            $sl_middle = array_splice($port_id,'1','-1');
            $sl_startover = $this->lineStart($sl_start, $sl_over);
            if( !$sl_startover ){
                $res =  Db::name('sealine')->insert(['sl_start'=>$sl_start,'sl_over'=>$sl_over]);
                $res ?($response['success'][] = '修改sealine表') :($response['fail'][] = '修改sealine表') ;
                $pricedata['sl_id'] = Db::name('sealine')->getLastInsID();
                
            }  else { $pricedata['sl_id'] = $sl_startover ; }
            
            if( $sl_middle){    //$sl_middle 为空说明没有中间的港口航线
                $sl_middle_id = $this->line($sl_middle);  //如果返回id说明中间港口线路存在 空则新增线路
//                var_dump($sl_middle); var_dump($sl_middle_id);exit;
                if(!$sl_middle_id){
                    $sealine_id = Db::name('sea_middle')->max('sealine_id');
                    $sealine_id = $sealine_id  +1 ;
                    $str='';
                    foreach ($sl_middle as $k =>$v){
                        $str .= "('$sealine_id','$v','$k'),";
                    }
                    $str=rtrim("$str",',');

                    $sql = "insert into hl_sea_middle(sealine_id,middle_port,sequence) values".$str;
                    $res2 =Db::execute($sql); 
                    $pricedata['sm_id'] = $sealine_id ;
                    $res2 ?($response['success'][] = '修改sea_middle表') :($response['fail'][] = '修改sea_middle表') ;
                }else{  $pricedata['sm_id'] = $sl_middle_id ;}
              
            }else{  $pricedata['sm_id'] = '' ;}
        
            }
              
        $res3 = Db::name('seaprice')->update($pricedata);
         // echo Db::getLastSql();exit;
        $res3 ? $response['success'][] = '修改seaprice表':$response['fail'][] = '修改seaprice表';
        return  $response;
    }
    
     //船运航价的删除
     public function  price_route_del($seaprice_id)
    {
//        $sl_id = Db::name('seaprice')->where('id','in',$seaprice_id)->column('sl_id');
//        $res1 = Db::name('sealine')->where('id','in',$sl_id)->delete();
//        $res2 = Db::name('sea_middle')->where('id','in',$sl_id)->delete();
        $res3 = Db::name('seaprice')->where('id','in',$seaprice_id)->delete();
     // echo Db::getLastSql();exit;
//        if($res1){
//            $response['success'][] = '删除sealine表';  
//        }else{ $response['fail'][] = '删除sealine表';   }
//        if($res2){
//            $response['success'][] = '删除sea_middle表';  
//        }else{ $response['fail'][] = '删除sea_middle表';   }
        if($res3){
            $response['success'][] = '删除seaprice表';  
        }else{ $response['fail'][] = '删除seaprice表';   }
    
    return  $response;
    }
    
    //查询航线是否存在 参数为中间港口的id依照航行顺序排列的数组
    public function  line($sl_middle){
        $v = implode(',', $sl_middle);
        $k = implode(',', array_keys($sl_middle));
        $sql2 = "select sealine_id, group_concat(middle_port) as middle_str, group_concat(sequence) as sequence_str from hl_sea_middle group by sealine_id";
        $sql3 = "select sealine_id from ($sql2) as STR  where  STR.middle_str like '$v' and STR.sequence_str like '$k'"; 
        $res = Db::query($sql3);
        $id=  $res ? ($id = $res['0']['sealine_id']) :($id =0);
        
        return $id ;
    }
    
    //查询航线是否存在 参数分别为 起始港口id, 目的港口id, 
    public function  lineStart($sl_start,$sl_over){
        $sql = "select id from hl_sealine where sl_start = '$sl_start' and  sl_over = '$sl_over'";
        $res = Db::query($sql);
        $id=  $res ? ($id = $res['0']['id']) :($id =0);
       
        return $id ;
    }
    
    
    
    //车队运价展示
    public function price_trailer_list($port_name ,$pages=5,$cl_id ='') {
        
         $list = Db::name('carprice')->alias('CP')
                ->join('hl_car_line L', 'CP.cl_id = L.id', 'left')
                ->join('hl_cardata CD','CP.car_id = CD.id', 'left')
                ->join('hl_port P', 'L.port_id =P.id', 'left')
                ->field('CP.id ,CP.cl_id ,L.address_id ,L.address_name ,CP.car_id ,'
                        . ' CD.car_name ,L.port_id , P.port_name ,'
                        . 'CP.price_20GP ,CP.price_40HQ ,variable , '
                        . 'CP.latest_order_time')
                ->order('CP.cl_id, CP.variable')->buildSql();
        
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($port_name){
            $list = Db::table($list.' a')->where('a.port_name', 'like', "%{$port_name}%")->buildSql();
            $pageParam['query']['port_name'] = $port_name;
        }
        if($cl_id){
            $list = Db::table($list.' c')->where('c.cl_id','=',"$cl_id")->buildSql();
            $pageParam['query']['cl_id'] = $cl_id;
        }
        
        
        $lista =Db::table($list.' a')
                ->join("($list) as b",'b.cl_id =a.cl_id AND a.variable <> b.variable','left')
                ->field('a.cl_id ,a.port_id ,a.port_name,a.address_id ,a.address_name ,a.id s_id,'
                        . ' a.car_id s_carid ,a.car_name s_carname ,'
                        . 'a.price_20GP s_20GP ,a.price_40HQ s_40HQ ,'
                        . 'a.latest_order_time s_ordertime , a.variable s_variable , '
                        . 'b.id r_id , b.car_id r_carid ,b.car_name r_carname ,'
                        . 'b.price_20GP r_20GP ,b.price_40HQ r_40HQ '
                        . ',b.latest_order_time r_ordertime , b.variable r_variable ')
                ->group('a.cl_id, b.cl_id')
                ->paginate($pages,false,$pageParam);   
//            echo '<pre>';
//                var_dump($lista);
//            echo '</pre>';exit
//            echo Db::getLastSql() ; 
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
        $cl_id = $this->lineCar($port_id,$address_data);
        $mtime =  time();
       // var_dump($load);exit;
        $load_car =$load['car'];
        $load_price_20GP = $load['price_20GP'];
        $load_price_40HQ = $load['price_40HQ'];
        
        $send_car =$send['car'];
        $send_price_20GP = $send['price_20GP'];
        $send_price_40HQ = $send['price_40HQ'];
        //送货 s 装货 r
        $sql = "insert into hl_carprice(cl_id ,car_id ,price_20GP,price_40HQ , variable ,mtime)  "
                . " values('$cl_id','$load_car','$load_price_20GP','$load_price_40HQ','r','$mtime'),"
                . "       ('$cl_id','$send_car','$send_price_20GP','$send_price_40HQ','s','$mtime')  ";
        $res = Db::execute($sql);
        $res ? $response['success'][] = '添加carprice表':$response['fail'][] = '添加carprice表';
        return $response ;
    }
    
    //车队运价的修改页面的原始数据
    public function  price_trailer_edit($data) 
    {   
        $cl_id = $data['cl_id'];
        $port_name = '';
        $res = $this->price_trailer_list($port_name ,$pages=5,$cl_id);
        
        return $res;
    }
    //车队运价的修改更新
    public function  price_trailer_toedit($data)
    {          
        $pricedata['id'] = $data['id'];
        $pricedata['ship_id'] = strstr($data['ship'],'_', true);
        $pricedata['price_20GP'] = $data['price_20GP'];
        $pricedata['price_40HQ'] = $data['price_40HQ'];
        $pricedata['shipping_date'] = strtotime($data['shipping_date']);
        $pricedata['cutoff_date'] = strtotime($data['cutoff_date']);
        $pricedata['boat_name'] = $data['boat_name'];
        $pricedata['sea_limitation'] = $data['sea_limitation'];
        $pricedata['ETA'] = strtotime($data['shipping_date'].'+ '.$data['sea_limitation'].'day');
        $pricedata['EDD'] = strtotime("+3day",$pricedata['ETA']);
        $pricedata['generalize'] = $data['generalize'];
        $pricedata['mtime'] = time();
        $response= array();
        $port_id = $data['port_name'];
        $port_length = count($port_id); 
        if($port_length<2){
            return  $response['fail'][] = '航线缺少起始目的港口';
        }
        if($port_length >= 2){
            $sl_start = $port_id['0'];
            $sl_over  = $port_id[$port_length-1];
            $sl_middle = array_splice($port_id,'1','-1');
            $sl_startover = $this->lineStart($sl_start, $sl_over);
            if( !$sl_startover ){
                $res =  Db::name('sealine')->insert(['sl_start'=>$sl_start,'sl_over'=>$sl_over]);
                $res ?($response['success'][] = '修改sealine表') :($response['fail'][] = '修改sealine表') ;
                $pricedata['sl_id'] = Db::name('sealine')->getLastInsID();
                
            }  else { $pricedata['sl_id'] = $sl_startover ; }
            
            if( $sl_middle){    //$sl_middle 为空说明没有中间的港口航线
                $sl_middle_id = $this->line($sl_middle);  //如果返回id说明中间港口线路存在 空则新增线路
//                var_dump($sl_middle); var_dump($sl_middle_id);exit;
                if(!$sl_middle_id){
                    $sealine_id = Db::name('sea_middle')->max('sealine_id');
                    $sealine_id = $sealine_id  +1 ;
                    $str='';
                    foreach ($sl_middle as $k =>$v){
                        $str .= "('$sealine_id','$v','$k'),";
                    }
                    $str=rtrim("$str",',');

                    $sql = "insert into hl_sea_middle(sealine_id,middle_port,sequence) values".$str;
                    $res2 =Db::execute($sql); 
                    $pricedata['sm_id'] = $sealine_id ;
                    $res2 ?($response['success'][] = '修改sea_middle表') :($response['fail'][] = '修改sea_middle表') ;
                }else{  $pricedata['sm_id'] = $sl_middle_id ;}
              
            }else{  $pricedata['sm_id'] = '' ;}
        
            }
              
        $res3 = Db::name('seaprice')->update($pricedata);
         // echo Db::getLastSql();exit;
        $res3 ? $response['success'][] = '修改seaprice表':$response['fail'][] = '修改seaprice表';
        return  $response;
    }
    
    
         //查询车队运输线是否存在 若存在就反悔线路id不存在就添加 
         //参数分别为 港口id, 目的地址, 
    public function  lineCar($port_id,$address_data){
        $address_id= strstr($address_data,'_',true);
        
        $sql = "select id from hl_car_line where port_id = '$port_id,' and  address_id = '$address_id'";
        $res = Db::query($sql);
        //如果没有查到就添加这条记录 否则返回路线的id
        if(!$res){
            $address_name=  trim(strrchr($address_data, '_'),'_'); //镇级地址
            $area_id = substr($address_id,0,-3);  //县级code
            $add_sql = "select group_concat(P.province,C.city,A.area) AS add_name "
                    . " from hl_area A left join hl_city C on A.father = C.city_id "
                    . "left join hl_province P on C.father = P.province_id"
                    . " where A.area_id = '$area_id'";
           
            $add_name = Db::query($add_sql);
            $add_name =  $add_name['0']['add_name'].$address_name;
            $sql2 = "insert into hl_car_line(port_id ,address_id ,address_name) values('$port_id','$address_id','$add_name')";  
            $res2 = Db::execute($sql2);
            $id = Db::name('car_line')->getLastInsID();
        }  else {
            $id = $res['0']['id'] ;   
        }
       
        return $id ;
    }
    
    
}