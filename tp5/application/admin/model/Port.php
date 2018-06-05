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
                ->field('P.id , P.port_name ,P.city_id ,C.city ,P.mtime')
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
        return $response ;
  
    }
        
    //港口修改页面获许原数据
    public function  port_edit($port_id)
    {   
        $sql = "select P.id , P.port_name ,P.city_id ,C.city  from hl_port P "
                . "left join hl_city C on C.city_id = P.city_id "
                . "where P.id = '$port_id'";
        $data = Db::query($sql);
        $this->port_js();
        return $data['0'];
    }
      //港口执行修改
    public function  port_toeidt($port_id)
    {   
    }
    
    //港口添加页面原始数据
    public function  port_add($port_id)
    {   
    }
    //港口添加
    public function  port_toadd($port_id)
    {   
    }
    
    //港口js文件管理 ,其他的方法进行港口的增删改,都需要执行这个函数
    public function port_js() {
        $sql = "select P.id , P.port_name ,P.city_id ,C.city  from hl_port P "
                . "left join hl_city C on C.city_id = P.city_id "
                . "where P.id > 0";
        $data = Db::query($sql);
        
        $js_port = json_encode($data);
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