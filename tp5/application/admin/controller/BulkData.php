<?php
/*
 *  航线运价批量导入导出
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Loader;
use think\Db;
use think\Validate;
use think\Debug;
use app\admin\model\ExportExcelDroplist;

class BulkData extends Base{
 
    protected function _initialize() {
      parent::_initialize();
      $this->overdue();  //自动处理过期的
      
    }
    
//    public function aaa(){
//        var_dump($this->request->param());exit;
//    }

    //港到港批量导入页面
    public function priceRouteExcel(){
//        $data = $this->request->param();
//        $this->seaprcie_outExcel($data);
//        $this->view->assign('data', $data);
        return $this->view->fetch('excel/price_route_excel');
    }

    //查看日志页面
    public function logs () {
        $dir =  ROOT_PATH . 'public' . DS . 'uploads'. DS . 'logs'. DS ;  //要获取的目录
        echo "********** 获取目录下所有文件和文件夹 ***********<hr/>";
        //先判断指定的路径是不是一个文件夹
        if (is_dir($dir)){
            if ($dh = opendir($dir)){
                while (($file = readdir($dh))!= false){
                    //文件名的全路径 包含文件名
                    $filePath = $dir.$file;
                    //获取文件修改时间
                    $fmt = filemtime($filePath);
                    echo "<span style='color:#666'>(".date("Y-m-d H:i:s",$fmt).")</span><a href='".url('admin/BulkData/logsDown',"log=$file")."'> ".$file."</a><br/>";
                }
                closedir($dh);
            }
        }

    }
    public function logsDown() {
            $logs_name = $this->request->param('log');    //下载文件名 
            $file_dir = ROOT_PATH . 'public' . DS . 'uploads/logs';        //下载文件存放目录    
            //检查文件是否存在    
//            var_dump($file_dir .DS. $file_name);exit;
            if (! file_exists ($file_dir .DS. $logs_name)) {    
                echo "文件找不到";    
                exit ();    
            } else {    
                //打开文件
//                var_dump($file_dir .DS. $file_name);
                $file = fopen ($file_dir .DS. $logs_name, "r" );    
                //输入文件标签     
                Header ( "Content-type: application/octet-stream" );    
                Header ( "Accept-Ranges: bytes" );    
                Header ( "Accept-Length: " . filesize ($file_dir .DS. $logs_name) );    
                Header ( "Content-Disposition: attachment; filename=" . $logs_name );    
                //输出文件内容     
                //读取文件内容并直接输出到浏览器    
                echo fread ( $file, filesize ($file_dir .DS. $logs_name) );    
                fclose ( $file );    
                exit ();    
            }      
    }
    
    //批量导入
    function importExcel(){
        $file = request()->file('excel');
        $info = $file->validate(['size'=>15678,'ext'=>'xlsx,xls'])
               ->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'excel');
        if(!$info){
            return '上传错误'.$file->getError();
        }

        $type =$info->getExtension(); 
        /** 实例化 */
        Loader::import('PHPExcel.Classes.PHPExcel'); //手动引入PHPExcel.php
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');//引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
        Loader::import('PHPExcel.Classes.PHPExcel.PHPExcel_Style_Alignment');//引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
        
        if ($type=='xlsx') { 
            $type='Excel2007'; 
        }elseif($type=='xls') { 
            $type = 'Excel5'; 
        } 
        ini_set('max_execution_time', '0');
//        Vendor('PHPExcel.PHPExcel');
        $objReader = \PHPExcel_IOFactory::createReader($type);//判断使用哪种格式
        Debug::remark('begin');
        $objReader ->setReadDataOnly(true); //只读取数据,会智能忽略所有空白行,这点很重要！！！设置为true 会快很多
        $file_path= ROOT_PATH . 'public' . DS . 'uploads'. DS .'excel'. DS .$info->getSaveName();
        $objPHPExcel = $objReader->load($file_path); //加载Excel文件
        $sheetCount = $objPHPExcel->getSheetCount();//获取sheet工作表总个数
        $rowData = array();
        $RowNum = 0;
        /*读取表格数据*/
        for($i =0;$i <= $sheetCount-1;$i++){//循环sheet工作表的总个数
            $sheet = $objPHPExcel->getSheet($i);
            $highestRow = $sheet->getHighestRow();
            $RowNum += $highestRow-1;//计算所有sheet的总行数
            $highestColumn = $sheet->getHighestColumn();//总列数
            //从第$i个sheet的第1行开始获取数据
            for($row = 1;$row <= $highestRow;$row++){
                //把每个sheet作为一个新的数组元素 键名以sheet的索引命名 利于后期数组的提取
            $rowData[$i][] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL, TRUE, FALSE);
          
            }
        }
       
        /*删除每行表头数据 整理成二维数组*/
        $temp=[];
        foreach($rowData as $k=>$v){
            $temp[$k] =array_column($v,0);
            array_shift($temp[$k]);
        }
        
     //  $this->_p($temp);//打印结果
        $this->seaprice_excel($temp);
         

    }

    //将运价设置处理数据过期
    protected  function  overdue(){
        $nowtime = date('Y-m-d H:i:s');
        $list =Db::name('seaprice')
            ->where('status',1)
            ->where('shipping_date','<=',$nowtime)
            ->whereOr('cutoff_date',['<=',$nowtime],['>=','2018-01-01 00:00:00'],'and')
                ->fetchSql(FALSE)->column('id');

        $res = Db::name('seaprice')->where('id','in',$list)
                ->update(['status'=>3]);
                
    }




    //处理excel的数据到数据里
    public function seaprice_excel($rowData) {
        $tmp = [];
        $mtime = date('y-m-d H:i:s');//当前时间
        //前后三天
        $before_mtime= date('y-m-d H:i:s',  strtotime($mtime.'-1day'));
        $after_mtime= date('y-m-d H:i:s',  strtotime($mtime.'+1day'));      
        $response=[];
        $sheet_title =Db::name('shipcompany')->where('status',1)->order('id')->column('ship_short_name');
        //设置数据验证
       $sqlLogs =[];//日志记录
        foreach ($rowData as $key=>$sheets) {
            //循环每个sheet
            foreach ($sheets as $k=> $rows) {
                //新的航线运价需要1=>船公司id,3=>航线id, 
                //7=>20GP,40HQ海运费,船期,截单时间,海上时效,预计到港时间,13=>预计送货时间,
                //15=>船舶ID,18=>是否推荐,19=>价格说明
                $tmp['ship_id']=$rows['1'];
                $tmp['route_id']=$rows['3'];
                $tmp['boat_id']=$rows['15'];
                $tmp['price_20GP']=$rows['7'];
                $tmp['price_40HQ']=$rows['8'];
                $tmp['shipping_date'] = date('Y-m-d',(($rows['9']-25569)* 24*60*60) );
                $tmp['cutoff_date']= date('Y-m-d', (($rows['10']-25569)* 24*60*60) );
                $tmp['sea_limitation']=$rows['11'];
                $tmp['generalize']=$rows['18'];
                $tmp['price_description']=$rows['18'];
                $tmp['mtime']=$mtime;
                $tmp['ETA'] = date('Y-m-d H:i:s',strtotime($tmp['shipping_date'].'+ '.$tmp['sea_limitation'].'day'));
                $tmp['EDD'] = date('Y-m-d H:i:s',strtotime("+3day",strtotime($tmp['ETA'])));
//                $this->_p($tmp);
               //验证数据的类型是否符合
                $result = $this->validate($tmp,'BulkData');
                if(true !== $result){
                    // 验证失败 输出错误信息
                   $sqlLogs[]=['status'=>3,'message'=>$result,'row'=>$sheet_title[$key].'sheet'.($k+1).'行','seaprice_id'=>''];
                }  else {
                    //先查询是否有重复再数据库里
                    $map= array_slice($tmp,0,3);
                    $select_res = Db::name('seaprice')->where($map)
                        ->whereTime('mtime','between',[$before_mtime,$after_mtime])
                        ->limit(1)->value('id');
                    if(!$select_res){
                        $tmp['status']='2';
                        $insert_res = Db::name('seaprice')->insert($tmp);
                        $insert_res ?$sqlLogs[] =['status'=>0,'message'=>'添加成功','row'=>$sheet_title[$key].'sheet'.($k+1).'行','seaprice_id'=>'']:$sqlLogs[] =['status'=>2,'message'=>'添加失败','row'=>$sheet_title[$key].'sheet'.($k+1).'行','seaprice_id'=>''];
                    }  else {
                        //记录那个sheet 那一行报错(表头+1)
                        $sqlLogs[] =['status'=>1,'message'=>'存在重复航线','row'=>$sheet_title[$key].'sheet'.($k+1).'行','seaprice_id'=>$select_res];
                    }
                }
            }
        }
//        $this->_p($sqlLogs);
        //写进文件生成日志记录
        $file_dir = ROOT_PATH . 'public' . DS . 'uploads'. DS . 'logs';        //文件存放目录
        $filename =  date("YmdH").".txt";
        if(!file_exists($file_dir.'DS'.$filename)){
            array_unshift($sqlLogs,array('状态','错误信息','错误行数','数据库重复ID')); 
        }  
        $file_data = '';
         for ($i=0;$i<count($sqlLogs);$i++) {
            $file_data .= implode('---- ', $sqlLogs[$i])."\r\n";
        }

        $handle = fopen($file_dir.DS .$filename ,"a+");//写入文件
        if(flock($handle,LOCK_EX)){
            fwrite($handle, $file_data);
            flock($handle,LOCK_UN);
        }  else {
           echo'不能同时批量录入数据';exit;    
        }
        fclose($handle);
        echo'批量录入完成,具体情况请查看日志';
        
    }
    
    

    /**
     * @creator Jimmy
     * @data 2018/1/05
     * @desc 数据导出到excel(csv文件)
     * @param $filename 导出的csv文件名称 如date("Y年m月j日").'-test.csv'
     * @param array $tileArray 所有列名称
     * @param array $dataArray 所有列数据
     */
    function exportExcel($expTitle,$expCellName,$expTableData,$sheetName,$tableHeader='',$defaultWidth=15)
    {
        /**文件名称*/
      
//        $fileName = $expTitle.date('_Ymd');
         $fileName = $expTitle.'_'.date('YmdHis');
        $cellNum = count($expCellName);

        /** 实例化 */
        Loader::import('PHPExcel.Classes.PHPExcel'); //手动引入PHPExcel.php
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');//引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
        Loader::import('PHPExcel.Classes.PHPExcel.PHPExcel_Style_Alignment');//引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        /** 缺省情况下,PHPExcel会自动创建第一个SHEET，其索引SheetIndex=0 */
        /** 设置 当前处于活动状态的SHEET 为PHPExcel自动创建的第一个SHEET */
        foreach($expTableData as $key => $item) {
            if($key !== 0) {$objPHPExcel->createSheet();}
            $objPHPExcel->setactivesheetindex($key);
            /** 设置工作表名称 */ 
            $objPHPExcel->getActiveSheet($key)->setTitle($sheetName[$key]);

            for($i = 0; $i < $cellNum; $i++)
            {
                /** 垂直居中 */
                $objPHPExcel->setActiveSheetIndex($key)->getStyle($cellName[$i])->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                /** 水平居中 */
                $objPHPExcel->setActiveSheetIndex($key)->getStyle($cellName[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                /** 设置默认宽度 */
                $objPHPExcel->setActiveSheetIndex($key)->getColumnDimension($cellName[$i])->setWidth($defaultWidth);
                $objPHPExcel->setActiveSheetIndex($key)->setCellValue($cellName[$i].'1', $tableHeader[$i]);
            }

            /** 写入多行数据 */
            for($i = 0; $i < count($item); $i++)
            {
                for($j = 0; $j < $cellNum; $j++)
                {
                  $objPHPExcel->getActiveSheet($key)->setCellValue($cellName[$j].($i+2), $item[$i][$expCellName[$j]]);
                }
            }
        }

        /** 设置第一个工作表为活动工作表 */
        $objPHPExcel->setactivesheetindex(0);
      
        $file_dir = ROOT_PATH . 'public' . DS . 'uploads'. DS . 'excel';   //文件存放目录  
        $filePath = $file_dir. DS . $fileName.'.xlsx';
      //  ob_end_clean();//清除缓冲区,避免乱码
        $fileDir =  '/uploads/excel/'.$fileName.'.xlsx';
    
      //  @header('pragma:public');
        @header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
        @header('Content-Disposition:attachment;filename='.$fileName.'.xlsx');//attachment新窗口打印inline本窗口打印
        @header('Cache-Control: max-age=0'); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel , 'Excel2007');
        $objWriter->save($filePath);
   
        return $response = array('success' => true,'url' =>$fileDir);

    }
   

   
    //海运价格导出excel
    public function seaprcie_outExcel () {
        $map =[];
        
        if($this->request->isAjax()){
            $data = $this->request->param();
            $this->view->assign('data', $data);
            if(array_filter($data)){
                $data['start_id']? $map['SB.sl_start'] = $data['start_id'] :'';//起始港口
                $data['end_id']? $map['SB.sl_end'] = $data['end_id']:''; //终点港口
                $data['date_start']? $map['SP.shipping_date'] =['>=',$data['date_start']]:'' ; //起始时间
                $data['date_end']? $map['SP.shipping_date'] =['<=',$data['date_end']]:'' ;//截至时间
            }
        }

        $sea_price_lists =Db::name('seaprice')->alias('SP')
               ->join('hl_ship_route SR','SR.id=SP.route_id and SR.status=1','left')
               ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
               ->join('hl_sea_middle SM','SR.middle_id=SM.sealine_id','left')
               ->join('hl_port P1','P1.port_code= SB.sl_start and P1.status=1','left')
               ->join('hl_port P2','P2.port_code= SB.sl_end and P2.status=1','left')
               ->join('hl_port P3','P3.port_code= SM.sl_middle and P3.status=1','left')
               ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')
               ->join('hl_boat B','B.id=SP.boat_id and B.status=1','left')
               ->field("SP.id,SP.ship_id,SC.ship_short_name,SP.route_id,P1.port_name s_port,P2.port_name e_port,"
               . " group_concat(distinct P3.port_name order by SM.sequence separator '-') m_port,"
             . " SP.price_20GP,SP.price_40HQ,SP.shipping_date,SP.cutoff_date,SP.sea_limitation,SP.ETA,SP.EDD,SP.mtime,"
               . " SP.boat_id,B.boat_name,B.boat_code,SP.generalize,price_description")
                ->order('SP.ship_id,SP.shipping_date ASC')->where($map)->group('SP.id')->fetchSql(FALSE)->select();
//var_dump($sea_price_lists);exit;
        $sea_price_arr = []; //海运价格分组
        $sea_price_head =array('ID','船公司ID','船公司','航线ID','起运港','目的港',
            '中间港口','20GP海运费','40HQ海运费','船期','截单时间','海上时效',
            '预计到港时间','预计送货时间','创建时间','船舶ID','船名','航次',
            '是否推荐','价格说明');
        
        foreach ($sea_price_lists as  $sea_price_list) {
            $key = $sea_price_list['ship_id'];
            $sea_price_arr[$key][] = $sea_price_list;
           
        }
        //船公司id数组
        $ship_id_arr= array_filter(array_keys($sea_price_arr));
        //生成航运价格excel
        $expTitle ='Shipping_list'; 
        $expTableData = array_values($sea_price_arr);
        if(empty($expTableData)){
            return json(array('success'=>false,'message'=>'无数据'));
        }
        $expCellName = $expTableData ? array_keys($expTableData['0']['0']):['0'=>'无数据'];
        $tableHeader= $sea_price_head;
        $sheetName =Db::name('shipcompany')->where('id','in',$ship_id_arr)->order('id')->column('ship_short_name') ;
        $expTableData = array_values($sea_price_arr);
//        $this->_p($expTitle); $this->_p($expCellName);
//        $this->_p($expTableData);
//        $this->_p($sheetName);
//        $this->_p($tableHeader);exit;
//        var_dump($is_return);
        $res = $this->exportExcel($expTitle, $expCellName, $expTableData, $sheetName,$tableHeader) ;

        return json($res) ;
    }
    
    //船舶导出excl
    public function boat_outExcel () {
        $boat_arr=[];   //船舶分组
        $boat_lists= Db::name('boat')->field('id,boat_name,boat_code,ship_id')->order('ship_id,id')->where('status',1)->select();
        foreach ($boat_lists as $boat_list) {
            $key =$boat_list['ship_id'];
            if($key==0){
                continue;
            }
            $boat_arr[$key][] =$boat_list;
        }
        //船舶对应的船公司id数组
        $boat_ship_id_arr= array_filter(array_keys($boat_arr));
        $boat_expCellName =array('id','boat_name','boat_code','ship_id');
        $boat_expTableData = array_values($boat_arr);//每行的键数组
        $boat_head =array('ID','船名','航次','船公司ID');//表头
        $boat_sheetName = Db::name('shipcompany')->where('id','in',$boat_ship_id_arr)->where('status',1)->order('id')->column('ship_short_name') ;
        
//        $this->_p($boat_expTableData);$this->_p($boat_sheetName);exit;
        $res = $this->exportExcel('boat_list', $boat_expCellName,$boat_expTableData,$boat_sheetName,$boat_head,$is_return=false); 
//        return json($res) ;
    }
 }
