<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1523172899;s:81:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\contact\car_edit.html";i:1523357726;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1523271793;}*/ ?>
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
       <?php if(is_array($carinfo) || $carinfo instanceof \think\Collection || $carinfo instanceof \think\Paginator): $i = 0; $__LIST__ = $carinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$car): $mod = ($i % 2 );++$i;?>
        <form  id="editform" method="post">
            <input type="hidden"  name="id" class="layui-input" value="<?php echo $car['id']; ?>">
          <div class="layui-form-item">
              <label  class="layui-form-label">
                  <span class="x-red">*</span>所属港口
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="port" class="layui-input" value="<?php echo $car['port']; ?>">
              </div>
              
          </div>
          <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>合作船公司
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="ship_id" class="layui-input" value="<?php echo $car['ship_id']; ?>">
              </div>
          </div>
          <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队名字
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="car_name" class="layui-input" value="<?php echo $car['car_name']; ?>">
              </div>
          </div>
<!--          <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队老板
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="boss" class="layui-input" value="<?php echo $car['boss']; ?>">
              </div>
          </div>
           <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队财务
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="finance" class="layui-input" value="<?php echo $car['finance']; ?>">
              </div>
          </div>
            <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队调度
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="car_plan" class="layui-input" value="<?php echo $car['car_plan']; ?>">
              </div>
          </div> 
       
           <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队跟单
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="order_follow" class="layui-input" value="<?php echo $car['order_follow']; ?>">
              </div>
          </div>-->
            <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队地址
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="address" class="layui-input" value="<?php echo $car['address']; ?>">
              </div>
          </div> 
<!--            <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>车队公司
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="company_name" class="layui-input" value="<?php echo $car['company_name']; ?>">
              </div>
          </div> -->
             <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>合作关系
              </label>
              <div class="layui-input-inline">
                  
                  <input type="text"  name="symbiosis" class="layui-input" value="<?php echo $symbiosis; ?>">
              </div>
          </div> 
            <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>使用状态
            </label>
                <div class="layui-input-block" style="line-height: 42px" >
                    <?php switch($car['status']): case "1": ?>
                   <input type="radio" name="status" value="1"  checked>正常
                   <input type="radio" name="status" value="0"   >禁用
                    <?php break; case "0": ?>
                   <input type="radio" name="status" value="1" >正常
                   <input type="radio" name="status" value="0" checked  >禁用
                    <?php break; endswitch; ?>
                </div>
          </div>
          <div class="layui-form-item">
              <label  class="layui-form-label">
              </label>
              <input type="button" value="确 认" class="layui-btn" id="editbtn"  onclick="toajax()"> 
          </div>
      </form>
       <?php endforeach; endif; else: echo "" ;endif; ?>
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