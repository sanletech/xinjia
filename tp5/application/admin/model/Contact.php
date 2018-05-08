<?php
namespace app\admin\model;
use think\Model;
use think\Db;
//车队通讯录模块
class Car extends Model
{
    protected $table = 'hl_cardata';
    // 定义分页数
    protected $page = 5;
    //展示车队的对应信息
    public function carlist($searchdata = array()) { 
        $sql="select CP.port_id,P.port_name ,CP.car_id, CS.ship_id ,SC.ship_short_name , * from hl_car_port CP "
                . "right join hl_car_city CC on CC.car_id = CP.car_id "
                . "right join hl_car_ship CS on CS.car_id = CP.car_id"
                . "left join hl_shipcompany SC on SC.id =  CS.id"     //查询船公司名字
                . "left join hl_port P on P.id = CP.port_id"          //查询对应的港口名字
                . "left join hl_cardata CD on CD.id = CP.car_id "
                . "where CP .id ='1'"  ;
        
        
        
        //$res = Db::name('car_port')->alias('CP');
        
        
        $pageParam  = ['query' =>[]];
       // var_dump($searchdata);
        //接受search条件，并做判断处理三种情况或 和且的条件处理
        if( array_key_exists('port', $searchdata)){
            $port_name ='  p.port_name like \'%'.$searchdata['port'].'%\'';
            $sql = "select c.car_data_id from hl_car_ship_port c left join hl_port p on c.port_id = p.id where".$port_name ;
            $carid=Db::query($sql);
            //取以car_data_id 为键名的二维数组转成一维 
            $carid=array_column($carid, 'car_data_id');
            $bz=1; //做标记
            // 设置分页的额外参数 可以看http://www.thinkphp.cn/topic/44624.html 解释
            $pageParam['query']['port'] = $searchdata['port'];
         // var_dump($carid);
        } else{ $bz = -1;}
            
       
        if(array_key_exists('car_name', $searchdata)){
            $car_name ='  car_name like \'%'.$searchdata['car_name'].'%\'';
            $sql2 = "select id from hl_cardata  where".$car_name;
            $carid2=Db::query($sql2);
            $carid2=array_column($carid2, 'id');
            $bz2=1;
             $pageParam['query']['car_name'] = $searchdata['car_name'];
          //var_dump($carid2);
        } else{ $bz2 = -1;}
        if($bz>0 && $bz2>0 ){
          $id=  array_intersect($carid,$carid2);
        }elseif($bz>0 && $bz2<0){
          $id= $carid;
        }elseif($bz<0 && $bz2>0) {
          $id= $carid2; 
        }
       
      //1.将车队的信息赋值分页展示
        //如果有搜索条件就执行上面的查询
        if($bz>0 || $bz2 >0){
           // $id= implode(',', $id);
            $pageParam['query']['id'] = $id;
           // var_dump($id);
            $res = Db::name('cardata')->field('id,car_name,address,company_name,symbiosis,status')
                    ->where('id','in',$id)
                    ->paginate($this->page,false,$pageParam);
            //判断分页传的数组是怎么样的
        } elseif (array_key_exists('id', $searchdata)) {
            $pageParam['query']['id'] = $searchdata['id'];
            $res = Db::name('cardata')->field('id,car_name,address,company_name,symbiosis,status')
                    ->where('id','in',$searchdata['id'])
                    ->paginate($this->page,false,$pageParam);
        }else {
                $res = Db::name('cardata')->field('id,car_name,address,company_name,symbiosis,status')
                       ->where('id','>',0)
                       ->paginate($this->page);
        }
//          $res = Contact::where('id','>',0)
//                  ->paginate($this->page);
      
      
    // 获取分页显示
    return $res;
    }
    
    public function toedit($car,$port,$ship){
     // 根据修改的信息数组来修改cardata的信息
        //添加修改时间为当前时间戳
        $car['mtime'] =  time();
        $res=  Contact::where('id',$car['id'])
               ->update($car);
         $id=Db::name('car_ship_port')->where('car_data_id',$car['id'])->column('id');
         $carid= implode(',',$id);
       
        //重写创建新的记录
       //将port&ship数组少的数据以空补全
        $num =count($port)-count($ship); 
            if($num>0){
                for($i=0;$i<$num;$i++){
                    $ship[$i]='';
                }
            }elseif ($num<0) {
                for($i=0;$i<$num;$i++){
                    $port[$i]='';
                }
            } 
          
            //重置键位
        $port = array_values($port);
        $ship = array_values($ship);
    
        for($i=0;$i<count($port);$i++){
               $v=$port[$i];    $v1=$ship[$i]; $id=$car['id'];
            $arr[$i]=array('port_id'=>$v,'ship_id'=>$v1,'car_data_id'=>$id);
          }
         
           if($res>0){
            $res3=Db::name('car_ship_port')->insertAll($arr);
            if ($res3>0){
                //删除car_ship_port对应车队原有的记录
                $sql="delete from hl_car_ship_port where id in ($carid)";
                $res2=  Db::execute($sql);
                    if($res2>0){
                        $status=1;
                    }else { $status=0;  } 
                }else { $status=-1;  } 
            }  else { $status=-2;  } 

       return $status;
       
    }
    
    public function toadd($car,$port,$ship){
     // 根据修改的信息数组来添加cardata的信息
        //添加修改时间为当前时间戳
        $car['mtime'] =  time();
        unset($car['id']);
      
        $res =  Db::name('cardata')->insert($car);
        $carID = Db::name('cardata')->getLastInsID();
      
        //添加car_ship_port新的记录
       //将port&ship数组少的数据以空补全
        $num =count($port)-count($ship); 
            if($num>0){
                for($i=0;$i<$num;$i++){
                    $ship[$i]='';
                }
            }elseif ($num<0) {
                for($i=0;$i<$num;$i++){
                    $port[$i]='';
                }
            } 
          
            //重置键位
        $port = array_values($port);
        $ship = array_values($ship);
     
        for($i=0;$i<count($port);$i++){
               $v=$port[$i];    $v1=$ship[$i];
            $arr[$i]=array('port_id'=>$v,'ship_id'=>$v1,'car_data_id'=>$carID);
             
          }
         
        $res3=Db::name('car_ship_port')->insertAll($arr);
        if($res &&  $res3 >0){
            $status=1;
        }  else {
            $status=0;   
        }
       return $status;
       
    }
    //执行删除
    public function todel($data) {
        $data = implode(',', $data);
        $sql="delete A,B from hl_cardata A left join hl_car_ship_port B  on A.id = B.car_data_id  where A.id in ($data) ";
        $res = Db::execute($sql);
        if($res >0){
            $status=1;
        }else{$status=0;}
        return $status;
    }
    
    //根据控制器传过来的值使用获取器 将港口id改成汉字数组传回去
    public function getPortIdAttr($value)
    {     
        $sql="select port_name,id from hl_port where id in ($value) order by id  ";
        $port_name=Db::query($sql);
        $arr_port= array_column($port_name, 'port_name','id');
        
        return $arr_port;
    }
    
   //根据控制器传过来的值使用获取器 将船公司id改成汉字数组传回去
    public function getShipcyIdAttr($value)
    {     
        $sql2="select name,id from hl_shipcompany where id in ($value) ";
        $shipcy_name=Db::query($sql2);
        $arr_shipcy= array_column($shipcy_name, 'name','id');
       
        return $arr_shipcy;
    }

//   public function getStatusAttr($value)
//    {
//    $status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
//     return $status[$value];
//
//    }

//     public function getSymbiosisAttr($value)
//    {
//    $status = [1=>'长期合作',2=>'临时合作',3=>'暂无合作',4=>'中止合作'];
//     return $status[$value];
//
//    }
    
    /*
     * 对车队的人员信息展示
     */
    public function carinfo ($id) {
        $sql = "select * from hl_carinfo where car_data_id = '$id' " ;
        $res = Db::query($sql);
        return $res;
    }
    
    
    
   /*
    * 对港口的关联查询
    */
      //belongsToMany(‘关联模型名’,’中间表名’,’外键名’,’当前模型关联键名’,[‘模型别名定义’]);
      public function port ()
    {
      return $this->belongsToMany('Port', 'car_ship_port','port_id','car_data_id');
    }
    
    /*
    * 对港口的关联查询
    */
      //belongsToMany(‘关联模型名’,’中间表名’,’外键名’,’当前模型关联键名’,[‘模型别名定义’]);
      public function ship ()
    {
      return $this->belongsToMany('Ship', 'car_ship_port','ship_id','car_data_id');
    }
    
    
  

}



?>