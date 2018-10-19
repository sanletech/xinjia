<?php

namespace app\index\controller;

/**
 * php 根据自增id创建唯一编号类
 * Date:    2016-11-27
 * Author:  fdipzone
 * Ver:     1.0
 *
 * Func
 * Public create 创建编号
 */ class IDCode{ 

    /**  创建编号
     * @param  Int    $id         自增id
     * @param  Int    $num_length 数字最大位数
     * @param  String $prefix     前缀
     * @return String
     */ 
    public static function create($id, $prefix,$num_length=3){ 
        // 基数
        $base = pow(10, $num_length); 
        // 生成字母部分
        $division = (int)($id/$base); 
        $word = '';
        while($division){ 
            $tmp = fmod($division, 26); // 只使用26个大写字母 
            $tmp = chr($tmp + 65); // 转为字母 
            $word .= $tmp; $division = floor($division/26); 
        } 
        if($word==''){
            $word = chr(65); 
        } 
        // 生成数字部分 
        $mod = $id % $base;
        $digital = str_pad($mod, $num_length, 0, STR_PAD_LEFT); 
        $code = sprintf('%s-%s-%s', $prefix, $word, $digital); 
        return $code; 
    } 
 
 }
 