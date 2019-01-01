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
        $datas = $this->getSortList($data);
        $temp = [];
        foreach ($datas as $key => $value) {
                $temp[$key]= ['sl_start'=>$value];
           for($i =0 ;$i<count($datas);$i++){
               if($datas[$i] == $value){
                   continue;
               }
                $temp[$key]=['sl_end'=>$datas[$i]];
           }
        }
        
         echo'<pre>';
 print_r($temp);
echo '</pre>';
    } 
    
}