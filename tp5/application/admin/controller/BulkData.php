<?php
/*
 *  航线运价批量导入导出
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Loader;
use think\Db;
use app\admin\model\ExportExcelDroplist;

class BulkData extends Base{
 
    //港到港批量导入页面
    public function price_route_excel(){
        return $this->view->fetch('excel/price_route_excel');
    }
    
    //港到港数据导入
    public function insert_excel()
    {
    	Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel5');
        $PHPExcel = new \PHPExcel();
        //获取表单上传文件
        $file = request()->file('excel');
 
        // echo function_exists($connect->validate);die;
        $info = $file->validate(['size'=>15678,'ext'=>'xlsx,xls,csv'])->move(ROOT_PATH . 'public' . DS . 'excel');
        if ($info) {
            $exclePath = $info->getSaveName();
              //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'excel' . DS . $exclePath;
               //上传文件的地址
            $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
            $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');
              //加载文件内容,编码utf-8
            $excel_array=$obj_PHPExcel->getsheet(0)->toArray();
            $this->_p($excel_array);die;
               //转换为数组格式
            array_shift($excel_array);
              //删除第一个数组(标题);
            $city = [];
            $error_infos = [];
            $login_info = [];
            $i=0;
            // $j=2;
            foreach($excel_array as $k=>$v) {
				
                $sn = Db::name('agent')->where('agent_sn',$v[0])->find();
                if ($sn) {
                	$j = $k+2;
                	$error_infos[$k] = "第{$j}条代理商编号已存在";
                	$i++;
                	continue;
                }
				$city[$k]['agent_sn'] 		= $v[0];
                $city[$k]['agent_name'] 	= $v[1];
                $city[$k]['agent_phone'] 	= $v[2];
                $city[$k]['agent_fuzearea'] = $v[3];                
                $city[$k]['root_id'] 		= $v[4];
                $city[$k]['agent_member'] 	= $v[5];
                $city[$k]['agent_event'] 	= $v[6];
                $city[$k]['agent_areainfo'] = $v[7];
                               
                $i++;
            }
            // print_r($city);die;
            $success = Db::name('agent')->insertAll($city);		
            $error=$i-$success;  
            $error_infos['msg'] = "总{$i}条，成功{$success}条，失败{$error}条。";
 
            if ($i>0 && $res2>0) {
            	return $this->ok(205,"xc",$error_infos);
            }else{
            	return $this->no(300,'导入失败');
            }
             //批量插入数据
        } else {
            echo $file->getError();
        }
    }
    
    
    //所有路线的到处
    public function aaaout_exce11111111111()
    {
        //导出
	//##########################################################################
        $lists =Db::name('seaprice')->alias('SP')
            ->join('hl_ship_route SR','SR.id=SP.route_id','left')
            ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
            ->join('hl_sea_middle SM','SR.middle_id=SM.sealine_id','left')
            ->join('hl_port P1','P1.port_code= SB.sl_start','left')
            ->join('hl_port P2','P2.port_code= SB.sl_end','left')
            ->join('hl_port P3','P3.port_code= SM.sl_middle','left')
            ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')
            ->join('hl_boat B','B.id=SP.boat_id','left')
            ->field("SP.id,SP.ship_id,SC.ship_short_name,SP.route_id,P1.port_name s_port,P2.port_name e_port,"
            . " group_concat(distinct P3.port_name order by SM.sequence separator '-') m_port,"
          . " SP.price_20GP,SP.price_40HQ,SP.shipping_date,SP.cutoff_date,SP.sea_limitation,SP.ETA,SP.EDD,SP.mtime,"
            . " SP.boat_id,B.boat_name,B.boat_code,SP.generalize,price_description")
             ->order('SP.ship_id,SP.mtime DESC')->group('SP.route_id,SP.ship_id')->select();
   

//        $this->_p($lists);exit;
	$file_name = date('Y-m-d_His').'.xlsx';  
        $path = dirname( ROOT_PATH . 'public' . DS . 'uploads/excel');       
        Loader::import('PHPExcel.Classes.PHPExcel'); //手动引入PHPExcel.php
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');//引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
      
        $PHPExcel = new \PHPExcel(); //实例化
//         print_r($PHPExcel);die;
        $PHPSheet = $PHPExcel->setActiveSheetIndex(0);
        $PHPSheet->getActiveSheet()->setTitle("航线运价");  //给当前活动sheet设置名称
        $PHPSheet->setCellValue("A1","ID");//表格数据
        $PHPSheet->setCellValue("B1","船公司ID");
        $PHPSheet->setCellValue("C1","船名");
        $PHPSheet->setCellValue("D1","路线id");
        $PHPSheet->setCellValue("E1","起运港口");
        $PHPSheet->setCellValue("F1","目的港口");
        $PHPSheet->setCellValue("G1","中间港口");
        $PHPSheet->setCellValue("H1","船舶ID");
        $PHPSheet->setCellValue("I1","船舶名字");
        $PHPSheet->setCellValue("J1","船舶航次");
        $PHPSheet->setCellValue("K1","是否推荐");
        $PHPSheet->setCellValue("L1","价格说明");
        $i = 2;
		foreach($lists as $key => $value){
        	$PHPSheet->setCellValue('A'.$i,''.$value['id']);
        	$PHPSheet->setCellValue('B'.$i,''.$value['ship_id']);
        	$PHPSheet->setCellValue('C'.$i,''.$value['ship_short_name']);
        	$PHPSheet->setCellValue('D'.$i,''.$value['route_id']);
        	$PHPSheet->setCellValue('E'.$i,''.$value['s_port']);
        	$PHPSheet->setCellValue('F'.$i,''.$value['e_port']);
        	$PHPSheet->setCellValue('G'.$i,''.$value['m_port']);
        	$PHPSheet->setCellValue('H'.$i,''.$value['boat_id']);
        	$PHPSheet->setCellValue('I'.$i,''.$value['boat_name']);
        	$PHPSheet->setCellValue('J'.$i,''.$value['boat_code']);
                $PHPSheet->setCellValue('K'.$i,''.$value['generalize']);
                $PHPSheet->setCellValue('L'.$i,''.$value['price_description']);
        	$i++;
    	}
//        $PHPExcel->getActiveSheet()->setTitle('航线运价');
//	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//	$PHPExcel->setActiveSheetIndex(0);

        $PHPExcel->createSheet();
	$PHPExcel->setActiveSheetIndex(1);
        $PHPExcel->getActiveSheet()->setTitle("航线运价1");  //给当前活动sheet设置名称
        $PHPSheet->setCellValue("A1","ID");//表格数据
        $PHPSheet->setCellValue("B1","船公司ID");
        $PHPSheet->setCellValue("C1","船名");
        $PHPSheet->setCellValue("D1","路线id");
        $PHPSheet->setCellValue("E1","起运港口");
        $PHPSheet->setCellValue("F1","目的港口");
        $PHPSheet->setCellValue("G1","中间港口");
        $PHPSheet->setCellValue("H1","船舶ID");
        $PHPSheet->setCellValue("I1","船舶名字");
        $PHPSheet->setCellValue("J1","船舶航次");
        $PHPSheet->setCellValue("K1","是否推荐");
        $PHPSheet->setCellValue("L1","价格说明");
   
//        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
//        header('Content-Disposition: attachment;filename='.$file_name);
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Cache-Control: max-age=0');
//        $PHPWriter->save('php://output');
        
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //创建生成的格式
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Disposition: attachment;filename='.$file_name); //下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //表示在$path路径下面生成demo.xlsx文件
        
        header('Cache-Control: max-age=0');
        $PHPWriter->save("php://output");exit;
     }
    
    
     
     
     public function lot_excel($sea_price_data,$ship_name,$boat_data,$boat_name){
         
        Loader::import('PHPExcel.Classes.PHPExcel'); //手动引入PHPExcel.php
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');//引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); //设置第一个工作表为活动工作表
        $objPHPExcel->getActiveSheet()->setTitle($ship_name); //设置工作表名称

        //方法②:二维数组
//        $arrHeader = array(['id', '名字', '技能', '创建时间']);
//        $arrAllCardInfo=array([1,'aa','bb','cc'],[1,'aa','bb','cc']);
////        $arrAllCardInfo = $this->admin_model->getAllCardInfo(); //二维数组
//        $arrExcelInfo = array_merge($arrHeader, $arrAllCardInfo);
       // $arrExcelInfo = eval('return ' . iconv('gbk', 'utf-8', var_export($arrExcelInfo, true)) . ';'); //将数组转换成utf-8
        $objPHPExcel->getActiveSheet()->fromArray(
                $ship_data, // 赋值的数组
                NULL, // 忽略的值,不会在excel中显示
                'A1' // 赋值的起始位置
        );

        //创建第二个工作表
        $msgWorkSheet = new \PHPExcel_Worksheet($objPHPExcel, $boat_name); //创建一个工作表
        $objPHPExcel->addSheet($msgWorkSheet); //插入工作表
        $objPHPExcel->setActiveSheetIndex(1); //切换到新创建的工作表
//        $arrHeader = array(['id', 'uid', '描述']);
//        $arrBody=array([1,'aa','bb','cc'],[1,'aa','bb','cc']);
////        $arrBody = $this->admin_model->getAllCardMsg();
//        $arrExcelInfo = array_merge($arrHeader, $arrBody);
        $objPHPExcel->getActiveSheet()->fromArray(
                $boat_data, // 赋值的数组
                NULL, // 忽略的值,不会在excel中显示
                'A1' // 赋值的起始位置
        );
         
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="航线批量操作.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');

        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);exit;
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
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);
        $fileName = $expTitle.date('_YmdHis');
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
            if($key !== 0) $objPHPExcel->createSheet();
            $objPHPExcel->setactivesheetindex($key);
            /** 设置工作表名称 */ 
            $objPHPExcel->getActiveSheet($key)->setTitle($sheetName[$key]);

            for($i = 0; $i < $cellNum; $i++)
            {
                /** 垂直居中 */
//                $objPHPExcel->setActiveSheetIndex($key)->getStyle($cellName[$i])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                /** 水平居中 */
//                $objPHPExcel->setActiveSheetIndex($key)->getStyle($cellName[$i])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                /** 设置默认宽度 */
                $objPHPExcel->setActiveSheetIndex($key)->getColumnDimension($cellName[$i])->setWidth($defaultWidth);
                $objPHPExcel->setActiveSheetIndex($key)->setCellValue($cellName[$i].'1', $expCellName[$i]);
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
        @header('pragma:public');
        @header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        @header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }
    public function out_excel () {
        $sea_price_lists =Db::name('seaprice')->alias('SP')
               ->join('hl_ship_route SR','SR.id=SP.route_id','left')
               ->join('hl_sea_bothend SB','SB.sealine_id =SR.bothend_id','left')
               ->join('hl_sea_middle SM','SR.middle_id=SM.sealine_id','left')
               ->join('hl_port P1','P1.port_code= SB.sl_start','left')
               ->join('hl_port P2','P2.port_code= SB.sl_end','left')
               ->join('hl_port P3','P3.port_code= SM.sl_middle','left')
               ->join('hl_shipcompany SC',"SC.id=SP.ship_id and SC.status='1'",'left')
               ->join('hl_boat B','B.id=SP.boat_id','left')
               ->field("SP.id,SP.ship_id,SC.ship_short_name,SP.route_id,P1.port_name s_port,P2.port_name e_port,"
               . " group_concat(distinct P3.port_name order by SM.sequence separator '-') m_port,"
             . " SP.price_20GP,SP.price_40HQ,SP.shipping_date,SP.cutoff_date,SP.sea_limitation,SP.ETA,SP.EDD,SP.mtime,"
               . " SP.boat_id,B.boat_name,B.boat_code,SP.generalize,price_description")
                ->order('SP.ship_id,SP.mtime DESC')->where('SP.status',1)->group('SP.route_id,SP.ship_id')->select();
        $sea_price_arr = []; //海运价格分组
        $sea_price_head =array('ID','船公司ID','船公司','航线ID','起运港','目的港',
            '中间港口','20GP海运费','40HQ海运费','船期','截单时间','海上时效',
            '预计到港时间','预计送货时间','创建时间','船舶ID','船名','航次',
            '是否推荐','价格说明');
        
        foreach ($sea_price_lists as  $sea_price_list) {
            $key = $sea_price_list['ship_id'];
            $sea_price_arr[$key][] = $sea_price_list;
           
        }
        array_shift($sea_price_arr);
        array_shift($sea_price_arr);
        //

         //生成航运价格excel
        $expTitle ='海运价格明细'; 
        $expCellName=  array_values($sea_price_arr);
        $expCellName=$expCellName['0']['0'];
        $expTableData= array_values($sea_price_arr);
        $sheetName =Db::name('shipcompany')->where('id','in',array_keys($sea_price_arr))->order('id')->column('ship_short_name') ;
     
        $this->exportExcel($expTitle, $expCellName, $expTableData, $sheetName)   ;  
 
//        $boat_head =array('ID','船公司ID','船名','航次');
//        $boat_arr=[];   //船舶分组
//        $boat_lists= Db::name('boat')->field('id,ship_id,boat_name,boat_code')->where('status',1)->select();
//        foreach ($boat_lists as $boat_list) {
//            $key =$boat_list['ship_id'];
//            $boat_arr[$key][] =$boat_list;
//        }
//        $expTitle ='海运价格明细'; 
//        $expCellName=  array_keys($sea_price_arr['0']['0']);
//        $expTableData=$sea_price_arr;
//        $this->exportExcel($expTitle, $expCellName, $expTableData, $sheetName)  
       
        
        

    }

 }
