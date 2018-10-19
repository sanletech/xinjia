<?php
namespace app\index\controller;
use think\Controller;
use CURLFile;
class Logistics extends Controller {

    
    public function  zhonggu($waybillNum='' ,$boxNum='') {
        $postUrl ="http://dingcang.xlhy.cn/waybill/web/boxNum.json";
        $curlPost =  json_encode(array('waybillNum'=>$waybillNum));
        //  $header[]="Content-Type:application/json;charset=UTF-8";
        $header = array('CLIENT-IP:125.210.188.36', 'X-FORWARDED-FOR:125.210.188.36','Content-Type:application/json;charset=UTF-8');
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
            return '中谷无数据';
        }
         $track_num =[];
         $track_num  =  array_column($container,'boxId','boxNum');
        //  var_dump($track_num);exit;
        $boxId= $track_num[$boxNum];
        $data_logistics = $this->zhonggu_track_num($waybillNum,$boxId,$boxNum);
         echo'<pre>';
         var_dump($data_logistics);
        echo '</pre>';
        exit; 
        return $data_logistics;
          
    }
 
    public function  zhonggu_track_num($waybillNum,$boxId,$boxNum){
//           $waybillNum='ZSS1816NSKRZ076';$boxId='25240830';
            $postUrl ="http://dingcang.xlhy.cn/waybill/web/dynamic.json";
            $curlPost =  json_encode(array('boxId'=>$boxId,'waybillNum'=>$waybillNum));
            //$header[]="Content-Type:application/json;charset=UTF-8";
            $header = array('CLIENT-IP:125.210.188.36', 'X-FORWARDED-FOR:125.210.188.36','Content-Type:application/json;charset=UTF-8');
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
            $data_logistics =[];
            $data_logistics = json_decode($data,true);
            $this->dataEntry($data_logistics,$waybillNum,$boxId,$boxNum);
            return $data_logistics;
           
    }
    
      public function  zhongyuan1($waybillNum='PASU5884178320' ,$boxNum='UETU2479126') {
        $waybillNum = substr($waybillNum,3);
      //  $msectime = $this->msectime();
        //var_dump($msectime);exit;
        $postUrl ='http://elines.coscoshipping.com/NewEBWeb/public/cargoTracking/cargoTracking.xhtml?MM_PG_MeetingSearch&flag=l&PortalLanguage=GB18030&uid=&language=zh#';
     	
         $curlPost =  json_encode(array('cargoTrackingPara'=>'5806863750','containerNumberAll'=>'SEGU1017162'));
       // $postUrl ='http://elines.coscoshipping.com/ebtracking/public/container/status/'.$boxNum.'?bookingNumber='.$waybillNum.'&timestamp='.$msectime;
       // $header[]="Content-Type:application/json;charset=UTF-8";
         $header = array('CLIENT-IP:125.210.188.36', 'X-FORWARDED-FOR:125.210.188.36','Content-Type:application/x-www-form-urlencoded');
     //   $header = array('CLIENT-IP:125.210.188.36', 'X-FORWARDED-FOR:125.210.188.36','Content-Type:application/json;charset=UTF-8');
        $ch = curl_init();//初始化curl

        $options = array(CURLOPT_URL => $postUrl, //抓取指定网页
                        CURLOPT_HTTPHEADER=>$header,
                        CURLOPT_HEADER =>0,         
                        CURLOPT_RETURNTRANSFER =>0, //要求结果为字符串且输出到屏幕上
                        CURLOPT_POST =>1,           //post提交方式  
                        CURLOPT_POSTFIELDS =>$curlPost //参数
                        );
        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        
        echo($data); echo '</br>';
        $data_logistics =[];
        $data_logistics = json_decode($data,true);
        if(empty($data_logistics['data']['content'])){
            return '无数据';
        }
        echo '<pre>';
        var_dump($data_logistics);
        echo '</pre>'; exit;
      
        return $data_logistics;
          
    }
    
    public function  zhongyuan($waybillNum='' ,$boxNum='') {
        $waybillNum = substr($waybillNum,3);
        $msectime = $this->msectime();
        //var_dump($msectime);exit;
        $postUrl ='http://elines.coscoshipping.com/ebtracking/public/container/status/'.$boxNum.'?bookingNumber='.$waybillNum.'&timestamp='.$msectime;
       // $header[]="Content-Type:application/json;charset=UTF-8";
        $header = array('CLIENT-IP:125.210.188.36', 'X-FORWARDED-FOR:125.210.188.36','Content-Type:application/json;charset=UTF-8');
        $ch = curl_init();//初始化curl

        $options = array(CURLOPT_URL => $postUrl, //抓取指定网页
                        CURLOPT_HTTPHEADER=>$header,
                        CURLOPT_HEADER =>0,         
                        CURLOPT_RETURNTRANSFER =>1, //要求结果为字符串且输出到屏幕上
            //CURLOPT_HTTPPROXYTUNNEL=>'TRUE',
          //  CURLOPT_PROXY=>'125.21.23.6:8080'
                        );
        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        
       // echo($data); echo '</br>';
        $data_logistics =[];
        $data_logistics = json_decode($data,true);
        if(empty($data_logistics['data']['content'])){
            return '无数据';
        }
        echo '<pre>';
        var_dump($data_logistics);
        echo '</pre>'; exit;
      
        return $data_logistics;
          
    }
 
    public function  antong($waybillNum='' ,$boxNum='') {
     //   $postUrl ='http://www.antong56.com/biz/searchTracking?bookingnumber=&billofladingnumber='.$waybillNum.'&boxnumber='.$boxNum;
        $postUrl ='http://www.antong56.com/biz/searchTracking?bookingnumber=&billofladingnumber=ANNSHIK180500621&boxnumber=BMOU1342289';
        $header[]="Content-Type:application/json;charset=UTF-8";
        $ch = curl_init();//初始化curl

        $options = array(CURLOPT_URL => $postUrl, //抓取指定网页
                        CURLOPT_HTTPHEADER=>$header,
                        CURLOPT_HEADER =>0,         
                        CURLOPT_RETURNTRANSFER =>1, //要求结果为字符串且输出到屏幕上
            //CURLOPT_HTTPPROXYTUNNEL=>'TRUE',
          //  CURLOPT_PROXY=>'125.21.23.6:8080'
                        );
        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        
        echo($data); echo '</br>';
        $data_logistics =[];
        $data_logistics = json_decode($data,true);
        if(array_key_exists('message',$data_logistics)){
            return '安通无数据';
        }
        echo '<pre>';;
        var_dump($data_logistics);
        echo '</pre>'; exit;
      
        return $data_logistics;
          
    }
    
    //返回当前的毫秒时间戳
    public function msectime() {
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }
    //中谷数据插入表中 时间从旧到新
    public function  dataEntry($data_logistics,$waybillNum,$boxId,$boxNum){
        $num =count($data_logistics);
        $sqlNum = Db::name('logistic')->where('boxNum',$data_logistics['boxNum'])->count();
        if($num>$sqlNum){
            $dataArray = array_slice($data_logistics,$sqlNum);
            $response=[];
            for($i=0;$i<count($dataArray);$i++){
                $data['describe'] = $dataArray[$i]['describe '];   
                $data['czsj']  = $dataArray[$i]['czsj'];
                $data['code'] = $dataArray[$i]['code'];
                $data['codeName'] = $dataArray[$i]['codeName'];
                $data['waybillNum'] = $dataArray[$i]['waybillNum'];
                $data['boxNum'] = $dataArray[$i]['boxNum'];
                $data['boxId'] = $boxId;
                $res =Db::name('logistic')->insert($data);
                $res ? $response['success'][]='插入code:'.$data['codeName'].'记录成功' :$response['fail'][]='插入code:'.$data['codeName'].'记录失败';
            }
        }
        return $response ;
        
    }
    
    //安通物流数据插入  时间从新到旧
    public function  dataAntong($data_logistics,$waybillNum,$boxId,$boxNum){
        
        $num =count($data_logistics);
        $rule ="/<a.*?>(.*)<\/a>/i";  //正则
        $sqlNum = Db::name('logistic')->where('boxNum',$data_logistics['boxNo'])->count();
        if($num>$sqlNum){
            if($sqlNum ==0){$sqlNum = -$num;}
            $dataArray = array_slice($data_logistics,0 ,(-$sqlNum));
            $response=[];
            for($i=0;$i<count($dataArray);$i++){
                $data['describe'] = $dataArray[$i]['details'];   
                $data['czsj']  = $dataArray[$i]['workTime'];
                $data['code'] = $i;
                $data['codeName'] = $dataArray[$i]['operationType'];
                $data['waybillNum'] = $dataArray[$i]['billNo'];
                $data['boxNum'] = $dataArray[$i]['boxNo'];
                preg_match($rule,$dataArray[$i]['shipName'],$matches);
                $data['shipName'] =$matches[0][0];  
                $data['voyage'] = $dataArray[$i]['voyage'];   //航次
                $data['operationType'] = $dataArray[$i]['operationType'];
                $data['currentPort'] = $dataArray[$i]['currentPort'];
                $data['nextPort'] = $dataArray[$i]['nextPort'];
                $data['currentPort'] = $dataArray[$i]['currentPort'];
                $res =Db::name('logistic_antong')->insert($data);
                $res ? $response['success'][]='插入code:'.$data['codeName'].'记录成功' :$response['fail'][]='插入code:'.$data['codeName'].'记录失败';
            }
        }
        return $response ;
    }
    
        //中远物流数据插入 时间从新到旧
    public function  dataZhongyuan($data_logistics,$waybillNum,$boxId,$boxNum){
        $num =count($data_logistics);
        $sqlNum = Db::name('logistic_zhongyuan')->where("boxNum=:boxNum and waybillNum=:waybillNum")
                ->bind(['boxNum'=>$boxNum,'waybillNum'=>$waybillNum])->count();
        if($num>$sqlNum){
            if($sqlNum ==0){$sqlNum = -$num;}
            $dataArray = array_slice($data_logistics,0 ,(-$sqlNum));
            $response=[];
            for($i=0;$i<count($dataArray);$i++){
                $data['describe'] = $dataArray[$i]['details'];   
                $data['czsj']  = $dataArray[$i]['workTime'];
                $data['code'] = $i;
                $data['codeName'] = $dataArray[$i]['operationType'];
                $data['waybillNum'] = $dataArray[$i]['billNo'];
                $data['boxNum'] = $dataArray[$i]['boxNo'];
                preg_match($rule,$dataArray[$i]['shipName'],$matches);
                $data['shipName'] =$matches[0][0];  
                $data['voyage'] = $dataArray[$i]['voyage'];   //航次
                $data['operationType'] = $dataArray[$i]['operationType'];
                $data['currentPort'] = $dataArray[$i]['currentPort'];
                $data['nextPort'] = $dataArray[$i]['nextPort'];
                $data['currentPort'] = $dataArray[$i]['currentPort'];
                $res =Db::name('logistic_antong')->insert($data);
                $res ? $response['success'][]='插入code:'.$data['codeName'].'记录成功' :$response['fail'][]='插入code:'.$data['codeName'].'记录失败';
            }
        }
        return $response ;
    }
    
   

    
}
