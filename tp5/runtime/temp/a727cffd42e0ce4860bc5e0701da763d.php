<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\login\login.html";i:1531705747;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1531300153;}*/ ?>
<!-- 登陆 -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/index/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/index/css/font.css">
    <link rel="stylesheet" href="/static/index/css/index.css">
    <link rel="stylesheet" href="/static/index/css/top.css">
    <link rel="stylesheet" href="/static/index/css/foot.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="/static/index/js/jquery-1.9.0.min.js"></script>
    <script src="/static/index/layui/layui.js"></script>
    
</head>
  <body>
      <link rel="stylesheet" href="/static/index/css/login.css">
    <div class="tou">
      <div class="zuo">
        <a href="<?php echo url('../index/index'); ?>"><img src="/static/index/image/logo.jpg" alt=""></a>
      </div>
      <div class="you">
        <div class="zi">
          <i class="fa fa-phone-square fa-5x"></i>
        </div>
        <div class="kf">
          <p>客服热线（tel）</p>
          <h1>020-28211720</h1>
        </div>
      </div>
    </div>
    <div class="lo">
        <div class="login">
            <div class="login_deng">
            <h3><strong>帐号登陆</strong></h3>
            <form id="loginform">
                <div class="tb">
                    <i class="fa fa-user fa-2x"></i>
                  <input type="text" name="loginname" lay-verify="title"  placeholder="请输入帐号/手机号" class="layui-input">
                </div>
                <div class="tb">
                <i class="fa fa-unlock-alt fa-2x"></i>
                  <input type="password" name="password" lay-verify="title"  placeholder="请输入密码" class="layui-input">
                </div>
                <div class="pwd" id="container">
                    <div class="pwd_le"><input type="checkbox" class="input_check" id="check1" checked='true'><label for="check1"></label><span>记住密码</span></div>
                    <div class="pwd_rig"><a href="#">忘记登陆密码？</a></div>
                </div>
                <div class="sub layui-col-xs12">
                    <button type="button" class="layui-btn layui-btn-fluid" id="loginbtn">登陆</button>
              </div>
                <div class="zh">
            <span>没有帐号？<a href="<?php echo url('index/login/register'); ?>">立即注册</a></span>
          </div>
            </form>
            </div>
        </div>
    </div>
<script>
    $(function(){
        $("#loginbtn").on('click',function(){
            $.ajax({
                type:'POST',
                url:"<?php echo url('index/login/check_login'); ?>",
                data:$("#loginform").serialize(),
                dataType:"json",
                success:function(data){
            
                    if(data.status==1){
                        alert(data.message);
                        window.location.href="<?php echo url('index/Order/order_list'); ?>";
                    }else{
                        alert(data.message);
                        window.location.href="<?php echo url('index/login/login'); ?>";
                    }
                }
            })
        })
    })
    
</script>
  </body>
</html>
