<?php
/*
 *  地址的镇级管理
 */

namespace app\admin\controller;
use think\Controller;
use think\Db;

class Address extends Controller
{
        public function town() 
    {
        $data = $this->request->param('twoncode');
        $filename ="./static/town/".$data.'.json'; 
        if(file_exists($filename)){
            $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
              //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
            $contents = fread($handle, filesize ($filename));
            fclose
            ($handle);
            $contents = json_encode($contents);   
        }  else {
             $contents =''; 
        }
       
        return  $contents ;
    }

         
//    public function abc( ) {
//        $leping = Db::name('port')->where('status',1)->where('port_name','like','%乐平%')->value('port_code');
//        $nansha = Db::name('port')->where('status',1)->where('port_name','like','%南沙%')->value('port_code');
//        $huangpu = Db::name('port')->where('status',1)->where('port_name','like','%黄埔老港%')->value('port_code');
//        
////        $data = Db::name('sea_bothend')->where('sl_start','440600017')->column('sl_end');
////        $data[]= '440600017';
//        
//        //筛选出广东省内的港口
//        $guangdong_port = Db::name('port')->alias('P')
//                ->join('hl_city C','C.city_id = P.city_id and P.status =1','left')
//                ->where('C.father','440000')
//                ->order('P.port_code')->fetchSql(false)
//                ->column('P.port_code,P.port_name');
//       
//        $guangdong_port = array_unique($guangdong_port);
//        unset($guangdong_port[$leping],$guangdong_port[$nansha],$guangdong_port[$huangpu]);
//   
//      $guangdong_port = array_keys($guangdong_port);
//        $data_middle = Db::name('ship_route')->alias('SR') 
//                ->join('hl_sea_bothend SB','SR.bothend_id=SB.sealine_id ','left')
//                ->join('hl_sea_middle SM','SR.middle_id=SM.sealine_id','left')
//                ->where('SB.sl_start','440600017')->where('SR.status','1')
//                ->group('SR.id')->field('SB.sl_start,SB.sl_end,SR.middle_id,group_concat(distinct SM.sl_middle order by SM.sequence) as middle_port')
//                ->order('SB.sl_end,SR.middle_id')->select();
//
//        $temp = [];
//
//        $hehe =[];
//        $guangdong_port = array_values($guangdong_port);
//        foreach ($guangdong_port as $k1 => $v1) {
//               $k = 0;
//            foreach ($data_middle as $keys =>$values){
//                if(!($v1 == $values['sl_end'] or $v1 == $values['sl_start'])){
//                    $aa = explode(',', $values['middle_port']);
//                     if(in_array($v1 , $aa)){
//                        $hehe[]=  $keys;
//                     }
//                    if(!in_array($v1 , $aa)){
//                        $temp[$k1][$k]=$values;
//                        $temp[$k1][$k]['sl_start']=$v1;
//                        $temp[$k1][$k]['middle_port']=$aa;
//                        ++$k;
//                    }
//                
//                }
//            }
//        }
//
//        $ccc =[];
//        foreach ($temp as $k2 => $v2) {
//             foreach ($v2 as $k3 => $v3){
//                 $bothend_id = $this->bothEndLine( $v3['sl_start'],$v3['sl_end']);
//                 if($bothend_id){
//                    $temp[$k2][$k3]['bothend_id']= $bothend_id;
//                 }  else {
//                    $ccc['id'][]= $bothend_id;
//                    $ccc['k'][]= $v2;
//                 
//                 }
//               
//            }
//        }
//
//        $mtime= date('Y-m-d H:i:s');
//        $insert_data =[] ;
//        foreach ($temp as $k4=>$v4){
//             
//            foreach ($v4 as $k5=>$v5){
//               if(!($v5['sl_start'] == $v5['sl_end'])){
//                    if(!in_array($v5['sl_end'] , $v5['middle_port'])){
//                if(array_key_exists('middle_id', $v5)){
//                     $data_s = ['bothend_id'=>$v5['bothend_id'],'middle_id'=>$v5['middle_id']];
//                     $is_exits = Db::name('ship_route')->where($data_s)->where('status',1)->value('id');
//                     if(!$is_exits){
//                         $data_s['mtime']= $mtime;
//                         $insert_data[] =$data_s;
//                     }  else {
//                         $m[]= $is_exits;
//                     }
//                  }
//            }
//            }
//             
//            }
//        }
////        $this->_p($insert_data);
//        $insert_data = array_unique($insert_data,SORT_REGULAR);
//         $this->_p($insert_data);
//        $res =  Db::name('ship_route')->insertAll($insert_data);
//    
//
//
//    } 
//    
//    
//            //查询航线是否存在 参数分别为 起始港口id, 目的港口id, 
//    public function  bothEndLine($sl_start,$sl_end){
//        $sealine_id =Db::name('sea_bothend')->where(['sl_start'=>$sl_start,'sl_end'=>$sl_end])->value('sealine_id');
//        $mtime =  date('Y-m-d H:i:s');
//        if(empty($sealine_id)){
//            $sealine_id = Db::name('sea_bothend')->max('sealine_id')+1;
//            $data = ['sl_start'=>$sl_start,'sl_end'=>$sl_end,'sealine_id'=>$sealine_id,'mtime'=>$mtime];
//            $res  =Db::name('sea_bothend')->insert($data);
//            if(empty($res)){
//                echo Db::getLastSql();
//                return false;
//                
//            }
//        }
//        
//        return $sealine_id ;
//    }
//    
}