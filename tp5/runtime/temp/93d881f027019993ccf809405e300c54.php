<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:71:"E:\xampp\htdocs\tp5\public/../application/admin\view\public\middle.html";i:1523172899;s:80:"E:\xampp\htdocs\tp5\public/../application/admin\view\member\member_password.html";i:1522319362;s:61:"E:\xampp\htdocs\tp5\application\admin\view\public\header.html";i:1521513870;}*/ ?>
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
	<link rel="stylesheet" href="/static/admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>

</head>
  <body>
    <div class="x-body">
        <form class="layui-form" id="editform">
            <input type="hidden" name="id" value="<?php echo $member['id']; ?>" >
        <div class="layui-form-item">
              <label for="L_pass" class="layui-form-label">
                  <span class="x-red">*</span>密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="pwd1" name="password" required="" lay-verify="pass"
                  autocomplete="off" class="layui-input" value="">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  6到16个字符
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
                  <span class="x-red">*</span>确认密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="pwd2" name="password" required="" lay-verify="repass"
                  autocomplete="off" class="layui-input"  value=""><span id="tishi"></span>
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
            <input type="button" value="提　交" class="layui-btn" id="editbtn"  onclick="validate()"> 
          </div>
      </form>
    </div>
    <script>
   
      
      
        //自定义验证规则
       function validate(){
                  var pwd1 = document.getElementById("pwd1").value;
                  var pwd2 = document.getElementById("pwd2").value;
                   
    		//<!-- 对比两次输入的密码 -->
                  if(pwd1 !== pwd2) {
                      document.getElementById("tishi").innerHTML="<font color='red'>两次密码不相同请重新输入</font>";                
                  }
                  else {
                    document.getElementById("tishi").innerHTML="";
                       toajax();
                  }
              }
      function toajax (){   
            $.ajax({
                type:'POST',
                url:"<?php echo url('admin/member/mupdate'); ?>",    
                data:$("#editform").serialize(),
                dataType:"json",
                  success:function(data){
                    if(data.status==1){
                        alert(data.message,{icon:1,time:1000});
                     window.parent.location.reload();  
                     parent.layer.closeAll('iframe');  
                       
                    }else{
                        alert(data.message,{icon:1,time:1000});
                     
                  }
                }
            })
        }
       
            
        </script>
  
  </body>

</html>