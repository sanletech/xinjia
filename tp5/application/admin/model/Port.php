<?php
namespace app\admin\model;
use think\Model;
use think\db;
class Port extends Model
{
 
    public function  port_list($port_name ,$pages=5)
    {   
        $list = Db::name('port')->alias('P')
                ->join('hl_city C','P.city_id = C.city_id','left')
                ->field('P.id ,P.port_code , P.port_name ,P.city_id ,C.city ,P.mtime')
                ->order('P.id ,C.id ')
                ->buildSql();
               
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($port_name){
            $list = Db::table($list.' a')->where('a.port_name', 'like', "%{$port_name}%")->buildSql();
            $pageParam['query']['port_name'] = $port_name;
        }
        $lista =Db::table($list.' a')->paginate($pages,false,$pageParam);   
        
        return $lista;
    }
    
    //港口删除
    public function  port_del($port_id)
    {   
        $res = Db::name('port')->where('id','in',$port_id )->delete();
        $res ?  $response['success'][] = '删除port表':$response['fail'][] = '删除port表';
        $this->port_js();
        return $response ;
  
    }
        
    //港口修改页面获许原数据
    public function  port_edit($port_id)
    {   
        $sql = "select P.id , P.port_name ,P.city_id ,C.city  from hl_port P "
                . "left join hl_city C on C.city_id = P.city_id "
                . "where P.id = '$port_id'";
        $data = Db::query($sql);
        return $data['0'];
    }
      //港口执行修改
    public function  port_toedit($id,$city,$port_code,$port_name)
    {  
        $mtime = time();
        $sql ="update hl_port set port_code ='$port_code',port_name ='$port_name',"
                . "city_id ='$city' ,mtime ='$mtime' where id ='$id'";
//        var_dump($sql);exit;
        $res = Db::execute($sql);
        $res ?  $response['success'][] = '修改port表':$response['fail'][] = '修改port表';
        $this->port_js();
        return $response ;
        
    }
    
    //港口添加
    public function port_add($city_id ,$port_array)
    {   
        $mtime = time();
        $port_code = Db::name('port')->where('city_id',"'$city_id'")->max('port_code');
        if($port_code < $city_id * 1000){
            $port_code = $city_id * 1000+1;
        }else { 
            $port_code = $port_code + 1; 
        }
        $sql = "insert into hl_port(port_code, port_name, city_id, mtime)  values";
        
        $str ='';
        foreach ($port_array as $k=>$v){
            $port_code++;
            $str .= "('$port_code','$v','$city_id','$mtime'),";
        }
        $str= trim($str,',');
        $sql = $sql.$str;
//        var_dump($sql);exit;
        $res = Db::execute($sql);
        $res ?  $response['success'][] = '添加port表':$response['fail'][] = '添加port表';
        $this->port_js();
        return $response ;
    }
    
    //港口js文件管理 ,其他的方法进行港口的增删改,都需要执行这个函数
    public function port_js() {
        $sql = "select P.port_code , P.port_name ,P.city_id ,C.city  from hl_port P "
                . "left join hl_city C on C.city_id = P.city_id "
                . "where P.id > 0";
        $data = Db::query($sql);
        $result =   array();
        //依照ship_id 分组对应的port_id
        foreach($data as $k=>$v){
            $result[$v['city_id']][] = array('id'=>$v['port_code'],'port_name'=>$v['port_name']);
        } 
//        //测试的时候使用查看城市是否对应的上
//        foreach($data as $k=>$v){
//            $result[$v['city_id']][] = $v;
//        } 
//        $this->_p($result);
        
        $js_port = json_encode($result);
        $js_port = 'var JS_PORT ='.$js_port;
        $filename ="./static/admin/js/port.js"; 
        if(file_exists($filename)){
            $handle = fopen($filename, "w");//写入文件
            fwrite($handle, $js_port);
            fclose($handle);
        }  
    }
    
 
    
    
    
      //航线详情list
       public function  shiproute_list($sl_start,$sl_end ,$pages=5,$sl_start_id=0,$sl_end_id=0)
    {         
        $middleSql =Db::name('sea_middle')->alias('SM')
                    ->join('hl_port P','P.port_code =SM.sl_middle','left')
                    ->field('SM.sealine_id,group_concat(SM.sl_middle order by SM.sequence) middle_port ,'
                            . 'group_concat(P.port_name order by SM.sequence) port_name')
                    ->group('SM.sealine_id')->order('SM.sealine_id')->buildSql();
        //下面的可以拼接两个字段为一个字符串
//          ->field("SM.sealine_id,"
//                        . "group_concat(concat_ws('_',SM.sl_middle ,P.port_name) "
//                        . " order by SM.sequence separator '>>') middle_port")
        
        $bothendSql =Db::name('sea_bothend')->alias('SB')
                    ->join('hl_port P1','P1.port_code = SB.sl_start','left')
                    ->join('hl_port P2','P2.port_code = SB.sl_end','left')
                    ->field('SB.sealine_id,SB.sl_start,P1.port_name s_port_name,SB.sl_end ,P2.port_name e_port_name')
                    ->group('SB.sealine_id')->order('SB.sealine_id')->buildSql();
        
        $list = Db::name('ship_route')->alias('SR')
                ->join("$bothendSql T1",'SR.bothend_id =T1.sealine_id','left')
                ->join("$middleSql T2",'SR.middle_id =T2.sealine_id','left')
                ->field('SR.*, T1.sealine_id s_id ,T1.sl_start ,T1.s_port_name, T1.sl_end ,T1.e_port_name ,'
                        . 'T2.sealine_id m_id,T2.middle_port,T2.port_name')
                ->order('SR.id')->buildSql();
              
       // var_dump($list);exit;
        $pageParam  = ['query' =>[]]; //设置分页查询参数
        if(!empty($sl_start) && isset($sl_start)){
            $list = Db::table($list.' a')->where('a.s_port_name', 'like', "%{$sl_start}%")->buildSql();
            $pageParam['query']['sl_start'] = $sl_start;
        }
        if(!empty($sl_end) && isset($sl_end)){
            $list = Db::table($list.' b')->where('b.e_port_name', 'like', "%{$sl_end}%")->buildSql();
            $pageParam['query']['sl_end'] = $sl_end;
        }
        if(!empty($sl_start_id) && isset($sl_start_id)){
            $list = Db::table($list.' e')->where('e.sl_start',"$sl_start_id")->buildSql();
        }
        if(!empty($sl_end_id) && isset($sl_end_id)){
            $list = Db::table($list.' f')->where('f.sl_end',"$sl_end_id")->buildSql();
        }
        
        $lista =Db::table($list.' c')->paginate($pages,false,$pageParam);   
//        echo  Db::getLastSql(); exit;
        return $lista;
    }
    
    //航线详情删除
    public function  shiproute_del($shiproute)
    {   
        $res = Db::name('ship_route')->where('id','in',$shiproute)->delete();
        $response =  [];
        $res ?  $response['success'][] = '删除ship_route表':$response['fail'][] = '删除ship_route表';
        return  $response ;
    }
        
       //航线详情添加
    public function  shiproute_add($port_arr)
    {   
        $sl_start = array_shift($port_arr);
        $sl_end = array_pop($port_arr);
        $bothend_id =  $this->bothEndLine($sl_start,$sl_end);
        $sl_middle = $port_arr;
       
        if(!empty($sl_middle)){
            $middle_id =  $this->middleLine($sl_middle);
        } else {
            $middle_id = 0;
        }
        
        $mtime = time();
        $sql1 ="select id from hl_ship_route where bothend_id ='$bothend_id' and "
                . "  middle_id ='$middle_id'";
        
        $res = Db::query($sql1);
       
        if(empty($res)){
        $sql = "insert into hl_ship_route(bothend_id ,middle_id ,mtime)  "
                . " values('$bothend_id','$middle_id','$mtime')";
        $res = Db::execute($sql);
        $res ?  $response['success'][] = '添加boat表':$response['fail'][] = '添加boat表';
        }else{
            $response['success'] = '添加boat表已存在';
        }
        return $response ;
    }
    
        //查询航线是否存在 参数分别为 起始港口id, 目的港口id, 
    public function  bothEndLine($sl_start,$sl_end){
        $sql = "select sealine_id from hl_sea_bothend where sl_start = '$sl_start'  "
                . "and  sl_end = '$sl_end'";
        $res = Db::query($sql);
        if(empty($res)){
            $sealine_id = Db::name('sea_bothend')->max('sealine_id')+1;
            $sql2 = "insert into hl_sea_bothend(sl_start,sl_end,sealine_id)  "
                    . " values('$sl_start','$sl_end','$sealine_id')";
            $res = Db::execute($sql2);
        }else{
            $sealine_id = $res['0']['sealine_id'] ;
        }
        return $sealine_id ;
    }
    
        //查询航线是否存在 参数为中间港口的id依照航行顺序排列的数组
    public function  middleLine($sl_middle){
     
        $v = implode(',', $sl_middle);
        $k = implode(',', array_keys($sl_middle));
        $sql1 = "select sealine_id, group_concat(distinct sl_middle order by sequence ) as middle_str, group_concat(distinct sequence order by sequence) as sequence_str from hl_sea_middle group by sealine_id";
        $sql2 = "select sealine_id from ($sql1) as STR  where  STR.middle_str like '$v' and STR.sequence_str like '$k'"; 
        $res = Db::query($sql2);
        if(empty($res)){
            $sealine_id = Db::name('sea_middle')->max('sealine_id')+1;
            $str = '';
            $mtime = time();
            for($i=0;$i<count($sl_middle);$i++){
                $str .="  ('$sealine_id', '$sl_middle[$i]', '$i', '$mtime')  ,";
            }
            $str = trim($str, ',');
            $sql3 = "insert into hl_sea_middle(sealine_id, sl_middle, sequence, mtime)  values".$str;
           
            $res = Db::execute($sql3);
        }else{
            $sealine_id = $res['0']['sealine_id'] ;
        }
     
        return $sealine_id ;
    }
    
//       //将航情写入js文件
//        public function ship_rote_js() {
//        $sql = "select s "
//                . " from hl_boat B  left join hl_shipcompany  SC on SC.id = B.ship_id ";
//        $data = Db::query($sql);
//       
//        //依照ship_id 分组对应的port_id
//        $sql2= "select B.ship_id ,SC.ship_short_name ship_name from hl_boat B "
//                . " left join hl_shipcompany SC on SC.id = B.ship_id  group by B.ship_id";
//        $ship_arr = Db::query($sql2);
//        $result=[];
//        for($i=0;$i<count($data);$i++){
//            for($j=0;$j<count($ship_arr);$j++){
//                if($data[$i]['ship_id'] ==$ship_arr[$j]['ship_id']){
//                    $ship_arr[$j]['boat_list'][]=array('id'=>$data[$i]['id'],'boat_code'=>$data[$i]['boat_code'],'boat_name'=>$data[$i]['boat_name']);
//                }    
//                
//            }
//        }
//        $js_boat = json_encode($ship_arr);
//        $js_boat = 'var JS_BOAT ='.$js_boat;
//        $filename ="./static/admin/js/ship_boat.js"; 
//        if(file_exists($filename)){
//            $handle = fopen($filename, "w");//写入文件
//            fwrite($handle, $js_boat);
//            fclose($handle);
//        } 
    
    
       
    //船名list
       public function  boat_list($ship_name , $boat_name, $pages=5)
    {   
        $list = Db::name('boat')->alias('B')
                ->join('hl_shipcompany SC','SC.id = B.ship_id','left')
                ->field('B.id ,B.ship_id ,SC.ship_short_name AS ship_name,'
                        . 'B.boat_code ,B.boat_name ,B.mtime')
                ->order('B.id ,B.ship_id ')
                ->buildSql();
            $pageParam  = ['query' =>[]]; //设置分页查询参数
        if($boat_name){
            $list = Db::table($list.' a')->where('a.boat_name', 'like', "%{$boat_name}%")->buildSql();
            $pageParam['query']['boat_name'] = $boat_name;
        }
        if($ship_name){
            $list = Db::table($list.' b')->where('b.ship_name', 'like', "%{$ship_name}%")->buildSql();
            $pageParam['query']['ship_name'] = $ship_name;
        }
        $lista =Db::table($list.' c')->paginate($pages,false,$pageParam);  

       // echo  Db::getLastSql(); exit;
        return $lista;
    }
    
    //船名删除
    public function  boat_del($boat_id)
    {   
        $res = Db::name('boat')->where('id','in',$boat_id )->delete();
        $res ?  $response['success'][] = '删除boat表':$response['fail'][] = '删除boat表';
        $this->boat_js();
        return $response ;
  
    }
        
       //船名添加
    public function boat_add($ship_id, $boat_code,$boat_name)
    {   
        $mtime = time();
        $sql = "insert into hl_boat(ship_id ,boat_code,boat_name ,mtime) "
                . "values('$ship_id','$boat_code','$boat_name','$mtime')";
        $res = Db::execute($sql);
        $res ?  $response['success'][] = '添加boat表':$response['fail'][] = '添加boat表';
        $this->boat_js();
        return $response ;
    }
    
    
        public function boat_js() {
        $sql = "select B.id,B.ship_id, B.boat_code, B.boat_name  "
                . " from hl_boat B  left join hl_shipcompany  SC on SC.id = B.ship_id ";
        $data = Db::query($sql);
       
        //依照ship_id 分组对应的port_id
        $sql2= "select B.ship_id ,SC.ship_short_name ship_name from hl_boat B "
                . " left join hl_shipcompany SC on SC.id = B.ship_id  group by B.ship_id";
        $ship_arr = Db::query($sql2);
        $result=[];
        for($i=0;$i<count($data);$i++){
            for($j=0;$j<count($ship_arr);$j++){
                if($data[$i]['ship_id'] ==$ship_arr[$j]['ship_id']){
                    $ship_arr[$j]['boat_list'][]=array('id'=>$data[$i]['id'],'boat_code'=>$data[$i]['boat_code'],'boat_name'=>$data[$i]['boat_name']);
                }    
                
            }
        }
        $js_boat = json_encode($ship_arr);
        $js_boat = 'var JS_BOAT ='.$js_boat;
        $filename ="./static/admin/js/ship_boat.js"; 
        if(file_exists($filename)){
            $handle = fopen($filename, "w");//写入文件
            fwrite($handle, $js_boat);
            fclose($handle);
        }  
    }
    
}
?>