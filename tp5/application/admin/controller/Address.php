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
    
    public function getSortList($array,$level = -1,&$list =[]) {
	 for ($i=0; $i < count($array); $i++) { 
	 if($level < 0){ 
	 //第一级直接输出对应数字 
	 //$list[] = $array[$i]; 
	 }else{
		 $values = $array[$i]; 
		 //根据等级组装前几位 
		 for ($k=0; $k < $level ; $k++) { 
			$values .= '+'.$array[$i+$k+1]; 
		 } 
		  print_r($values);
		 $j= 0;
		 //循环拼合最后一位
		 for ($n=($i+$level+1); $n < count($array); $n++) {
			 $j++; 
			 $first = $values; 
			 // 组合上几位 
			 $first .= '+'.$array[$n];
			// if(substr_count($first,'+')==1){
			  $list[] = $first; 
			 //}
			
			 } 
			 //到最后一位结束递归 
			 if($j == 1){ 
			 break; 
			 } 
		 } 
	} 
			 //长度大于等级+2停止递归 
			 if(($level+2) < count($array)){ 
                            $this->getSortList($array,$level+1,$list); 
			 } 
			 return $list;
	 }
         
         
    public function abc( ) {
        $data = Db::name('sea_bothend')->where('sl_start','440600017')->column('sl_end');
        $data[]= '440600017';
        $data_middle = Db::name('ship_route')->alias('SR') 
                ->join('hl_sea_bothend SB','SR.bothend_id =SB.id','left')
                ->join('hl_sea_middle SM','SR.middle_id =SB.sealine_id','left')
                ->where('SB.sl_start','440600017')->field('SB.sl_start,SB.sl_end,SR.middle_id')->select();

        $temp = [];
        $k = 0;
        foreach ($data as $key => $value) {
           for($i =0 ;$i<count($data);$i++){
               if($data[$i] == $value){
                   continue;
               }
                $temp[$k]['sl_start']= $value;
                $temp[$k]['sl_end'] = $data[$i];
                $temp[$k]['bothend_id']= $this->bothEndLine( $temp[$k]['sl_start'],$temp[$k]['sl_end']);
                ++$k;
           }
        }
        
        foreach ($data_middle as $k1 => $v1) {
            foreach ($temp as $keys =>$values){
                if($v1['sl_end'] == $values['sl_end']){
                    $temp[$keys]['middle_id']=$v1['middle_id'];
                }
            }
        }
   
        $mtime= date('Y-m-d H:i:s');
        $insert_data =[] ;
        foreach ($temp as $k2=>$v2){
           if(array_key_exists('middle_id', $v2)){
                $data_s = ['bothend_id'=>$v2['bothend_id'],'middle_id'=>$v2['middle_id']];
                $is_exits = Db::name('ship_route')->where($data_s)->where('status',1)->value('id');
                if(!$is_exits){
                    $data_s['mtime']= $mtime;
                    $insert_data[] =$data_s;
                }  else {
                    $m[]= $is_exits;
                }
           }
        }
        $res =  Db::name('ship_route')->insertAll($insert_data);
    


    } 
    
    
            //查询航线是否存在 参数分别为 起始港口id, 目的港口id, 
    public function  bothEndLine($sl_start,$sl_end){
        $sealine_id =Db::name('sea_bothend')->where(['sl_start'=>$sl_start,'sl_end'=>$sl_end])->value('sealine_id');
        $mtime =  date('Y-m-d H:i:s');
        if(empty($sealine_id)){
            $sealine_id = Db::name('sea_bothend')->max('sealine_id')+1;
            $data = ['sl_start'=>$sl_start,'sl_end'=>$sl_end,'sealine_id'=>$sealine_id,'mtime'=>$mtime];
            $res  =Db::name('sea_bothend')->insert($data);
            if(!$res){ return FALSE;}
        }
        
        return $sealine_id ;
    }
    
}