<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\index\controller;

/**
 * Description of orderToken
 *
 * @author Administrator
 */
class orderToken {
    //put your code here
    
    
    //创建TOKEN
    public function createToken() {
       $code = chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) .chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE));
       session('TOKEN', $this->authcode($code));
    }
    //判断TOKEN
    public function checkToken($token) {
        if ($token == session('TOKEN')) {
           session('TOKEN', NULL);
           return TRUE;
        } else {
          return FALSE;
        }
    }
    /* 加密TOKEN */
    public function authcode($str) {
        $key = "YOURKEY";
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }
}
