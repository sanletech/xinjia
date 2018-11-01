<?php
namespace app\admin\controller;
use think\Loader;
use think\Controller;
use think\Db;
 
class Excel
{
    public function excel()
     {
        //导出
	//##########################################################################
	$list = Db::name("agent")->field("agent_id,agent_name,agent_phone,agent_sn,agent_fuzearea,root_id,agent_member,agent_event,agent_areainfo")->limit(50)->select();
	$file_name = date('Y-m-d_His').'.xls';
        $path = dirname(__FILE__);
        Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
      
        $PHPExcel = new \PHPExcel();
        // print_r($PHPExcel);die;
        $PHPSheet = $PHPExcel->getActiveSheet();
        $PHPSheet->setTitle("代理商");
        $PHPSheet->setCellValue("A1","ID");
        $PHPSheet->setCellValue("B1","名字");
        $PHPSheet->setCellValue("C1","电话");
        $PHPSheet->setCellValue("D1","编号");
        $PHPSheet->setCellValue("E1","负责区域");
        $PHPSheet->setCellValue("F1","代理商编号");
        $PHPSheet->setCellValue("G1","管理员id");
        $PHPSheet->setCellValue("H1","联系人");
        $PHPSheet->setCellValue("I1","代理商级别");
        $PHPSheet->setCellValue("J1","代理商所在地址");
 
 
        $i = 2;
		foreach($list as $key => $value){
        	$PHPSheet->setCellValue('A'.$i,''.$value['agent_id']);
        	$PHPSheet->setCellValue('B'.$i,''.$value['agent_name']);
        	$PHPSheet->setCellValue('C'.$i,''.$value['agent_phone']);
        	$PHPSheet->setCellValue('D'.$i,''.$value['agent_sn']);
        	$PHPSheet->setCellValue('E'.$i,''.$value['agent_fuzearea']);
        	$PHPSheet->setCellValue('F'.$i,''.$value['agent_sn']);
        	$PHPSheet->setCellValue('G'.$i,''.$value['root_id']);
        	$PHPSheet->setCellValue('H'.$i,''.$value['agent_member']);
        	$PHPSheet->setCellValue('I'.$i,''.$value['agent_event']);
        	$PHPSheet->setCellValue('J'.$i,''.$value['agent_areainfo']);
        	$i++;
    	}
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007");
        header('Content-Disposition: attachment;filename='.$file_name);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output");
     }
 
    //导入
    //#################################
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
            // print_r($excel_array);die;
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
}