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
}
?>