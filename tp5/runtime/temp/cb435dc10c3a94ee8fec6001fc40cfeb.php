<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\login\login.html";i:1531988465;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1531988465;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台登录-X-admin2.0</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/static/admin/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/static/admin/css/font.css">
    <link rel="stylesheet" href="/static/admin/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/xadmin.css">
                   
        
    <script type="text/javascript" src="/static/admin/js/jquery-3.2.1.min.js"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
    <script type="text/javascript" src="/static/admin/js/area.js"></script>

</head>
<body class="login-bg">
    
    <div class="login">
        <div class="message">x-admin2.0-管理登录</div>
        <div id="darkbannerwrap"></div>
        
        <form  class="layui-form" id="loginform" >
            <input name="loginname" placeholder="用户名"  type="text" lay-verify="required" class="layui-input"  value="">
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input" value="">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="button" id="loginbtn">
            <hr class="hr20" >
        </form>
    </div>

 <!-- <script>
        $(function  () {
            layui.use('form', function(){
              var form = layui.form;
              // layer.msg('玩命卖萌中', function(){
              //   //关闭后的操作
              //   });
              //监听提交
              form.on('submit(login)', function(data){
                // alert(888)
                layer.msg(JSON.stringify(data.field),function(){
                    location.href='index.html'
                });
                return false;
              });
            });
        })

        
    </script>-->
    
<script>
    $(function(){
        $("#loginbtn").on('click',function(){
          
            $.ajax({
                type:'POST',
                url:"<?php echo url('admin/login/check'); ?>",
                    
                data:$("#loginform").serialize(),
                dataType:"json",
                success:function(data){
            
                    if(data.status==1){
                        alert(data.message);
                        window.location.href="<?php echo url('index/index'); ?>";
                    }else{
                        alert(data.message);
                        window.location.href="<?php echo url('login/index'); ?>";
                    }
                }
            })
        })
    })
    
</script>

    
    <!-- 底部结束 -->

</body>
</html>