<?php
namespace app\admin\model;
use think\Model;
class Category extends Model
{
    public static function  getCate($pid=0,&$result=[],$blank=0)
    { 
        //1.分类查询
        $res = self::all(['pid'=>$pid]);
        //2.自定义分类名称前面的提示信息
         $blank += 2 ;
        //3.遍历分类表
        foreach ($res as $key =>$value) {
             //3-1 自定义分类名称的显示格式
            $cate_name = '|--'.$value->cate_name;
            $value->cate_name = str_repeat('&nbsp;', $blank).$cate_name;
            //3-2将查询到的当前记录保存到结果$result中
            $result[] = $value;
            //3-3关键：将当前的ID 作为下一级的分类的父ID
            self::getCate($value->id,$result,$blank);
        }
        //4.返回查询结果,调用结果集类make 方法打包当前结果
        return \Collator::make($result)->toArray();
    }
    
}



?>