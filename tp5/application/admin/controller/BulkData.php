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
        $fileName = $expTitle.date('_Ymd');
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
        
        
//            ob_end_clean();//清除缓冲区,避免乱码    
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="航线批量操作.xlsx"');
//        header('Cache-Control: max-age=0');
//        $objWriter->save('php://output');

        /** 设置第一个工作表为活动工作表 */
        $objPHPExcel->setactivesheetindex(0);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();//清除缓冲区,避免乱码
        @header('pragma:public');
        @header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        @header("Content-Disposition:attachment;filename=$fileName.xlsx");//attachment新窗口打印inline本窗口打印
        @header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        unset($objPHPExcel);exit;

    }
    //海运价格导出excel
    public function seaprcie_outExcel () {
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
        //船公司id数组
        $ship_id_arr= array_filter(array_keys($sea_price_arr));
        
//        $this->_p($sea_price_arr);exit;
        array_shift($sea_price_arr);
        array_shift($sea_price_arr);

        //生成航运价格excel
        $expTitle ='海运价格明细'; 
        $expCellName = array_values($sea_price_arr);
        $expCellName = array_keys($expCellName['0']['0']);
        $tableHeader= $sea_price_head;
        $sheetName =Db::name('shipcompany')->where('id','in',$ship_id_arr)->order('id')->column('ship_short_name') ;
//          $this->_p($expTableData);   $this->_p($sheetName);exit;
        $this->exportExcel($expTitle, $expCellName, $sea_price_arr, $sheetName,$tableHeader)   ;  
 
        $boat_head =array('ID','船公司ID','船名','航次');
        $boat_arr=[];   //船舶分组
        $boat_lists= Db::name('boat')->field('id,boat_name,boat_code,ship_id')->where('status',1)->select();
        foreach ($boat_lists as $boat_list) {
            $key =$boat_list['ship_id'];
            $boat_arr[$key][] =$boat_list;
        }
        //船舶对应的船公司id数组
        $boat_ship_id_arr= array_filter(array_keys($boat_arr));
        $boat_expCellName =array('id','boat_name','boat_code','ship_id');
        $boat_expTableData = array_values($boat_arr);
        $boat_sheetName = Db::name('shipcompany')->where('id','in',$boat_ship_id_arr)->order('id')->column('ship_short_name') ;
//        $this->_p($boat_expTableData);$this->_p($boat_sheetName);exit;
        $this->exportExcel('船公司对应船舶明细', $boat_expCellName,$boat_expTableData,$boat_sheetName,$boat_head); 

    }
    //船舶导出excl
    public function boat_outExcel () {
        $boat_arr=[];   //船舶分组
        $boat_lists= Db::name('boat')->field('id,boat_name,boat_code,ship_id')->where('status',1)->select();
        foreach ($boat_lists as $boat_list) {
            $key =$boat_list['ship_id'];
            $boat_arr[$key][] =$boat_list;
        }
        //船舶对应的船公司id数组
        $boat_ship_id_arr= array_filter(array_keys($boat_arr));
        $boat_expCellName =array('id','boat_name','boat_code','ship_id');
        $boat_expTableData = array_values($boat_arr);//每行的键数组
        $boat_head =array('ID','船名','航次','船公司ID');//表头
        $boat_sheetName = Db::name('shipcompany')->where('id','in',$boat_ship_id_arr)->order('id')->column('ship_short_name') ;
//        $this->_p($boat_expTableData);$this->_p($boat_sheetName);exit;
        $this->exportExcel('船公司对应船舶明细', $boat_expCellName,$boat_expTableData,$boat_sheetName,$boat_head); 

    }
 }
