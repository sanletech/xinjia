<?php
/*
 *  订单拆分查看完整订单控制器
 */
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;
use app\admin\model\OrderProcess as OrderPM;
use think\Validate;
use think\session;
class OrderProcess extends Base
{     

    
    public function Upload($order_num,$type,$file)
    {
        // 获取表单上传文件,订单号，上传文件的类别 
        // sea_waybill 水运单  book_note 订舱单
        $rename = $order_num.'_'.$type;
         // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'text,txt,pdf,docx,doc,docm,dotx,dotm'])
                 ->move(ROOT_PATH . 'public' . DS . 'uploads/files',$rename);
        if($info){
            $reFile = str_replace('.','_',$info->getSaveName());
            $res = Db::name('order_port')->where('order_num',$order_num)
                    ->update([$type=>$reFile]);    
            return  array('status'=>1,'mssage'=>'提交成功');
           // }
        }else{
            // 上传失败获取错误信息
            return array('status'=>0,'mssage'=>$file->getError());
        }


    }
    
    public function downs(){    
            $order_name = $this->request->param('order_num');    //下载文件名 
            $type = $this->request->param('type'); //文件类型
            $file = Db::name('order_port')->where('order_num',$order_name)->value($type);
            var_dump($file);
            //将后缀修改成.
            $file_Extension= strstr(strrev($file),'_',true);
            $file_name = substr($file,0,strrpos($file, '_')).'.'.$file_Extension;     
            $file_dir = ROOT_PATH . 'public' . DS . 'uploads/files';        //下载文件存放目录    
            //检查文件是否存在    
//            var_dump($file_dir .DS. $file_name);exit;
            if (! file_exists ($file_dir .DS. $file_name)) {    
                echo "文件找不到";    
                exit ();    
            } else {    
                //打开文件
                $file = fopen ($file_dir .DS. $file_name, "r" );    
                //输入文件标签     
                Header ( "Content-type: application/octet-stream" );    
                Header ( "Accept-Ranges: bytes" );    
                Header ( "Accept-Length: " . filesize ($file_dir .DS. $file_name) );    
                Header ( "Content-Disposition: attachment; filename=" . $file_name );    
                //输出文件内容     
                //读取文件内容并直接输出到浏览器    
                echo fread ( $file, filesize ($file_dir .DS. $file_name) );    
                fclose ( $file );    
                exit ();    
            }    

        }


    

    
}