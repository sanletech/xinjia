<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Port extends Model
{
 
    public function  port_list($city_name,$port_name ,$pages=5)
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
        if($city_name){
            $list = Db::table($list.' a')->where('a.city', 'like', "%{$city_name}%")->buildSql();
            $pageParam['query']['city_name'] = $city_name;
        }
        $list =Db::table($list.' a')->order('a.mtime DESC')->paginate($pages,false,$pageParam);  
        return $list;
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
        $mtime = date('Y-m-d H:i:s');
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
        $response=[];
        //先查询是否存在同名的港口
        $res2 =Db::name('port')->where('city_id',$city_id)->where('port_name','in',$port_array)->column('port_name');
        if($res2){
            $port_name_list=  implode(',', $res2);
            $response['fail'][] = '添加port表存在港口'.$port_name_list;
            return $response;
        }
        $mtime = date('Y-m-d H:i:s');
        $port_code = Db::name('port')->where('city_id',"'$city_id'")->max('port_code');
        if($port_code < ($city_id * 1000)){
            $port_code = $city_id * 1000+1;
        }else { 
            $port_code = $port_code + 1; 
        }
        $sql = "insert into hl_port(port_code, port_name, city_id, mtime)  values";
        
        $str ='';
        foreach ($port_array as $k=>$v){
            $str .= "('$port_code','$v','$city_id','$mtime'),";
            ++$port_code;
        }
        $str= trim($str,',');
        $sql = $sql.$str;
//        var_dump($sql);exit;
        $res = Db::execute($sql);
        $res ?  $response['success'][] = '添加港口成功':$response['fail'][] = '添加港口失败';
        $this->port_js();
        
        return $response ;
    }
    
    
           //港口js文件管理 ,其他的方法进行港口的增删改,都需要执行这个函数
    public function port_js() {
        
        function  query($str ,$group ,$map='P.id>0'){
            $sql = "select " .$str
                    . " from hl_port P  "
                    . "left join hl_city C on C.city_id = P.city_id "
                    . "left join hl_province PR on C.father=PR.province_id  "
                    . "where $map group by ".$group;
            //var_dump($sql); echo"</br>";
            $data = Db::query($sql);
            return $data;
        
        }
        
        //$str = "P.port_code portCode, P.port_name portName,P.city_id cityCode ,C.city cityName,PR.province_id provinceCode,PR.province provinceName";
       
        $strP = "PR.province_id provinceCode,PR.province provinceName";
        $groupP = "PR.province_id";
        $province = query($strP,$groupP);//省级组合
      //  $this->_p($province);exit;
        
        $result =   array();
        //依照provinceCode 分组对应的city
        foreach($province as $k=>$v){
            $strC = "P.city_id cityCode ,C.city cityName";
            $mapC = "PR.province_id =".$v['provinceCode'];
            $groupC ="P.city_id";
            $province[$k]['mallCityList']=  query($strC,$groupC,$mapC);
        } 
        $this->_p($province);exit;
        foreach ($province as $key=> $value) {
               //依照cityCode 分组对应的port
            foreach ($value['mallCityList'] as $kk =>$vv){
                $str = "P.port_code portCode, P.port_name portName";
                $group ="P.port_code";
                $map ="P.city_id =".$vv['cityCode'];
                $province[$key]['mallCityList'][$kk]['mallPortList']= query($str,$group,$map);
                
            }
        }
        
//        $this->_p($province);exit;

        $js_port = json_encode($province,true);
        $js_port = 'var JS_PORT ='.$js_port;
        $filename ="./static/admin/js/port.js"; 
        if(file_exists($filename)){
            $handle = fopen($filename, "w");//写入文件
            fwrite($handle, $js_port);
            fclose($handle);
        }  
    }
    

    
    //航线详情删除
    public function  shiproute_del($shiproute)
    {   
        $res = Db::name('ship_route')->where('id','in',$shiproute)->delete();
        $response =  [];
        $res ?  $response['success'] = '删除ship_route表':$response['fail'] = '删除ship_route表';
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
        $mtime = date('Y-m-d H:i:s');
        
        $res = Db::name('ship_route')->where(['bothend_id'=>$bothend_id,'middle_id'=>$middle_id])->value('id');
        if(empty($res)){
        $insertData = ['bothend_id'=>$bothend_id,'middle_id'=>$middle_id,'mtime'=>$mtime];
        $res1 = Db::name('ship_route')->insert($insertData);
        $res1 ?  $response['success'] = '添加boat表':$response['fail'] = '添加boat表';
        }else{
            $response['success'] = '添加boat表已存在';
        }
        return $response ;
    }
    
        //查询航线是否存在 参数分别为 起始港口id, 目的港口id, 
    public function  bothEndLine($sl_start,$sl_end){
        $res =Db::name('sea_bothend')->where(['sl_start'=>$sl_start,'sl_end'=>$sl_end])->value('sealine_id');
        $mtime =  date('Y-m-d H:i:s');
        if(empty($res)){
            $sealine_id = Db::name('sea_bothend')->max('sealine_id')+1;
            $data = ['sl_start'=>$sl_start,'sl_end'=>$sl_end,'sealine_id'=>$sealine_id,'mtime'=>$mtime];
            $res2 =Db::name('sea_bothend')->insert($data);
        }else{
            $sealine_id = $res['sealine_id'] ;
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
            $mtime = date('Y-m-d H:i:s');
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
        $mtime = date('Y-m-d H:i:s');
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