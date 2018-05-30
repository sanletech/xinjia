<?php
/*
 *  地址的镇级管理
 */

namespace app\admin\controller;
use think\Controller;

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
            fclose($handle);
            $contents = json_encode($contents);   
        }  else {
             $contents =''; 
        }
       
        return  $contents ;
    }
}