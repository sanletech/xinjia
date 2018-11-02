<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Loader;
/**
 * 导出Excel数据
 */
class ExportExcelDroplist extends Model {

    public function initialize() {
        //引入phpexcel文件，这里要修改成你文件的路径
    	Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel5');
    }

    /**
     * 设置架构sheet
     * @param \PHPExcel $objPHPExcel
     * @param array $dataSource 格式如下：
     * [
     *      [   'name' => '设计部',
     *          'children' => ['设计师', '设计主管']
     *      ],
     *      [   'name' => '业务部',
     *          'children' => ['业务员', '业务主管']
     *      ]
     * ]
     * @return bool
     */
    public function setArchitecture(\PHPExcel $objPHPExcel, $dataSource = [])
    {
   
        if(empty($objPHPExcel)) {
            return false;
        }
        $msgWorkSheet = new \PHPExcel_Worksheet($objPHPExcel, 'architecture'); //创建一个工作表
        $objPHPExcel->addSheet($msgWorkSheet); //插入工作表
        //列，若children数据多，需扩展
        $col = ['B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        foreach ($dataSource as $key => $value) {
            $num = $key + 1;
            $objPHPExcel->getSheetByName('architecture')->SetCellValue("A".$num, $value['name']);
            $pCount = count($value['children']) - 1;
            if($pCount >= 0) {
                foreach ($value['children'] as $pKey => $pValue) {
                    $objPHPExcel->getSheetByName('architecture')->SetCellValue($col[$pKey].$num, $pValue);
                }
                $objPHPExcel->addNamedRange(
                    new \PHPExcel_NamedRange(
                        $value['name'],
                        $objPHPExcel->getSheetByName('architecture'),
                        'B'.$num.':'.$col[$pCount].$num
                    )
                );
            }

        }
        $objPHPExcel->addNamedRange(
            new \PHPExcel_NamedRange(
                'department',
                $objPHPExcel->getSheetByName('architecture'),
                'A1:A'.count($dataSource)
            )
        );
        return true;
    }

    /**
     * 设置联动下拉菜单的选项
     * @param \PHPExcel $objPHPExcel
     * @param int $row 行
     * @param string $col1 下拉框1的列标识
     * @param string $col2 下拉框2的列标识
     */
    public function setRelationDropList(\PHPExcel $objPHPExcel, $row = 1, $col1 = 'A', $col2 = 'B')
    {
  
        $objValidation = $objPHPExcel->getActiveSheet()->getCell($col1.$row)->getDataValidation();
        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST );
        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setErrorTitle('输入错误');
        $objValidation->setError('不在列表中的值');
        $objValidation->setPromptTitle('请选择');
        $objValidation->setPrompt('请从列表中选择一个值.');
        $objValidation->setFormula1("=设计部");

        $objValidation = $objPHPExcel->getActiveSheet()->getCell($col2.$row)->getDataValidation();
        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST );
        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setErrorTitle('输入错误');
        $objValidation->setError('不在列表中的值');
        $objValidation->setPromptTitle('请选择');
        $objValidation->setPrompt('请从列表中选择一个值.');
        $objValidation->setFormula1('=INDIRECT($'.$col1.'$'.$row.')');
    }

    /*
     * Excel导出demo带联动下拉框[部门和职位]
     * @param string $subject 表格主题
     * @param array $data 导出数据
     * @param array $title 表格的字段名
     * @param array $dataSource 格式如下：
     * [
     *      [   'name' => '设计部',
     *          'children' => ['设计师', '设计主管']
     *      ],
     *      [   'name' => '业务部',
     *          'children' => ['业务员', '业务主管']
     *      ]
     * ]
     * @param string $col1 下拉框1的列标识
     * @param string $col2 下拉框2的列标识
     */
    function export($subject, $title, $dataSource = [], $col1 = 'A', $col2 = 'B'){
    
        
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("ctos")
            ->setLastModifiedBy("ctos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        //设置标题样式
        $titleRow = array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1','O1','P1','Q1','R1','S1','T1','U1','V1','W1','X1','Y1','Z1','AA1','AB1','AC1','AD1');
        $objPHPExcel->getActiveSheet()->getStyle($titleRow[0].':'.$titleRow[count($title)-1])->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle($titleRow[0].':'.$titleRow[count($title)-1])->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        for($a = 0; $a < count($title); $a++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($titleRow[$a], $title[$a]);
        }
        //设置架构sheet
        $this->setArchitecture($objPHPExcel, $dataSource);
        //设置联动下拉框
        for($i = 2; $i < 1000; $i++){
            $this->setRelationDropList($objPHPExcel, $i, $col1, $col2);
        }
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        // Redirect output to a client's web browser (Excel2007)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$subject.''.date('Ymd').'.xlsx');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }




    
    
    }