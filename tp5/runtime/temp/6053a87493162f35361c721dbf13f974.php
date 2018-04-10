<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:71:"E:\xampp\htdocs\tp5\public/../application/admin\view\public\middle.html";i:1523172899;s:74:"E:\xampp\htdocs\tp5\public/../application/admin\view\contact\car_edit.html";i:1523328756;s:61:"E:\xampp\htdocs\tp5\application\admin\view\public\header.html";i:1523271793;}*/ ?>
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
        
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>

</head>
  
  <body>
    <div class="x-body">
       
        <form  id="editform" method="post">
            <input type="hidden"  name="id" class="layui-input" value="<?php echo $carinfo['id']; ?>">
          <div class="layui-form-item">
              <label  class="layui-form-label">
                  <span class="x-red">*</span>港口
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="username" class="layui-input" value="<?php echo $carinfo['port']; ?>">
              </div>
              
          </div>
          <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>合作船公司
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="phone" class="layui-input" value="<?php echo $carinfo['ship_id']; ?>">
              </div>
          </div>
          <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队名字
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="email" class="layui-input" value="<?php echo $carinfo['car_name']; ?>">
              </div>
          </div>
          <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队老板
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="email" class="layui-input" value="<?php echo $carinfo['car_name']; ?>">
              </div>
          </div>
           <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队财务
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="email" class="layui-input" value="<?php echo $carinfo['car_name']; ?>">
              </div>
          </div>
            <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队调度
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="email" class="layui-input" value="<?php echo $carinfo['car_name']; ?>">
              </div>
          </div> 
       
           <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队跟单
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="email" class="layui-input" value="<?php echo $carinfo['car_name']; ?>">
              </div>
          </div>
            <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队地址
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="email" class="layui-input" value="<?php echo $carinfo['car_name']; ?>">
              </div>
          </div> 
            <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队公司
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="email" class="layui-input" value="<?php echo $carinfo['car_name']; ?>">
              </div>
          </div> 
             <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>合作关系
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="email" class="layui-input" value="<?php echo $carinfo['car_name']; ?>">
              </div>
          </div> 
            <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>状态
            </label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="正常" 
                       <?php var_dump($carinfo);exit;  if($carinfo['status']==1)
                       {echo 'checked';}else{echo'';} ?>  >
                <input type="radio" name="sex" value="0" title="禁用" 
                         <?php if($carinfo['status']==0)
                       {echo 'checked';}else{echo'';} ?>     >
            </div>
          </div>
       
          <div class="layui-form-item">
              <label  class="layui-form-label">
              </label>
              <input type="button" value="确 认" class="layui-btn" id="editbtn"  onclick="toajax()"> 
          </div>
      </form>
    </div>
  <script>
  
      
       function toajax (){   
                $.ajax({
                    type:'POST',
                    url:"<?php echo url('admin/member/mupdate'); ?>",    
                    data:$("#editform").serialize(),
                    dataType:"json",
                      success:function(data){
                        if(data.status==1){
                             alert(data.message,{icon:1,time:1000});
                        }else{
                            alert(data.message,{icon:1,time:1000});
                            }
                        }
                     })
                 }
      
      
     
</script>
  </body>

</html>