<?php
namespace app\index\controller;
use think\Controller;
use CURLFile;
class Logistics extends Controller {

    
    public function  zhonggu($waybillNum ,$boxNum='') {
           $postUrl ="http://dingcang.xlhy.cn/waybill/web/boxNum.json";
           $curlPost =  json_encode(array('waybillNum'=>$waybillNum));
           $header[]="Content-Type:application/json;charset=UTF-8";
           $ch = curl_init();//初始化curl

           $options = array(CURLOPT_URL => $postUrl, //抓取指定网页
                           CURLOPT_HTTPHEADER=>$header,
                           CURLOPT_HEADER =>0,         
                           CURLOPT_RETURNTRANSFER =>1, //要求结果为字符串且输出到屏幕上
                           CURLOPT_POST =>1,           //post提交方式  
                           CURLOPT_POSTFIELDS =>$curlPost //参数
                           );
           curl_setopt_array($ch, $options);
   
           $data = curl_exec($ch);//运行curl
           curl_close($ch);
           $container =[];
           $container  = json_decode($data,true);
           if(is_null($container)){
               return '无数据';
           }
            $track_num =array($boxNum=>'');
            $track_num  =  array_column($container,'boxId','boxNum');
      //  var_dump($track_num);exit;
           $boxId= $track_num[$boxNum];
           $res = $this->zhonggu_track_num($waybillNum,$boxId);
           return $res;
          
    }
 
    public function  zhonggu_track_num($waybillNum='',$boxId=''){
           $waybillNum='ZSS1816NSKRZ076';$boxId='25240830';
            $postUrl ="http://dingcang.xlhy.cn/waybill/web/dynamic.json";
            $curlPost =  json_encode(array('boxId'=>$boxId,'waybillNum'=>$waybillNum));
            $header[]="Content-Type:application/json;charset=UTF-8";
            $ch = curl_init();//初始化curl
            $options = array(CURLOPT_URL => $postUrl, //抓取指定网页
                           CURLOPT_HTTPHEADER=>$header,
                           CURLOPT_HEADER =>0,         
                           CURLOPT_RETURNTRANSFER =>1, //要求结果为字符串且输出到屏幕上
                           CURLOPT_POST =>1,           //post提交方式  
                           CURLOPT_POSTFIELDS =>$curlPost //参数
                           );
            curl_setopt_array($ch, $options);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);
            $container =[];
            $container  = json_decode($data,true);
    //            echo'<pre>';
    //            var_dump($container);
    //           echo '</pre>';
              // exit; 
              return $container;
    }
    
    
    public function  zhongyuan($waybillNum ,$boxNum) {
           $postUrl ="elines.coscoshipping.com/NewEBWeb/public/cargoTracking/containerHistory.xhtml?currentContainerNumber=TEMU0002930&containerNumber=TEMU0002930,&containerNumberLocation=0&billNumber=5806863751";
       //    $curlPost =  json_encode(array('waybillNum'=>$waybillNum));
           $header[]="Content-Type:application/json;charset=UTF-8";
           $ch = curl_init();//初始化curl

           $options = array(CURLOPT_URL => $postUrl, //抓取指定网页
                           CURLOPT_HTTPHEADER=>$header,
                           CURLOPT_HEADER =>0,         
                           CURLOPT_RETURNTRANSFER =>1, //要求结果为字符串且输出到屏幕上
                           CURLOPT_POST =>1,           //post提交方式  
                           CURLOPT_POSTFIELDS =>$curlPost //参数
                           );
           curl_setopt_array($ch, $options);
   
           $data = curl_exec($ch);//运行curl
           curl_close($ch);
           $container =[];
           $container  = json_decode($data,true);
           if(is_null($container)){
               return '无数据';
           }
            $track_num =array($boxNum=>'');
            $track_num  =  array_column($container,'boxId','boxNum');
      //  var_dump($track_num);exit;
           $boxId= $track_num[$boxNum];
           $res = $this->zhonggu_track_num($waybillNum,$boxId);
           return $res;
          
    }
 
    public function  zhongyuan_track_num($waybillNum='',$boxId=''){
           $waybillNum='ZSS1816NSKRZ076';$boxId='25240830';
            $postUrl ="http://dingcang.xlhy.cn/waybill/web/dynamic.json";
            $curlPost =  json_encode(array('boxId'=>$boxId,'waybillNum'=>$waybillNum));
            $header[]="Content-Type:application/json;charset=UTF-8";
            $ch = curl_init();//初始化curl
            $options = array(CURLOPT_URL => $postUrl, //抓取指定网页
                           CURLOPT_HTTPHEADER=>$header,
                           CURLOPT_HEADER =>0,         
                           CURLOPT_RETURNTRANSFER =>1, //要求结果为字符串且输出到屏幕上
                           CURLOPT_POST =>1,           //post提交方式  
                           CURLOPT_POSTFIELDS =>$curlPost //参数
                           );
            curl_setopt_array($ch, $options);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);
            $container =[];
            $container  = json_decode($data,true);
    //            echo'<pre>';
    //            var_dump($container);
    //           echo '</pre>';
              // exit; 
              return $container;
    }

}
