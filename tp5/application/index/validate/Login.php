<?php
namespace app\index\validate;

use think\Validate;

class Login extends Validate
{
    // 验证规则
    protected $rule = [
        ['membername', 'require|max:5|chs', '姓名必须|姓名不能长于4个字符|姓名只能是汉字'],
        ['password', 'require|min:5|alphaDash', '密码错误'],
        ['member_code' , 'unique:member,member_code','账户已经存在'],
        ['repassword','require|confirm:password','两次密码不对'],
       // ['password','require|confirm'],
    ];
}