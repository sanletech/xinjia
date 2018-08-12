<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Order\cargoPlan.html";i:1533649520;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1527898250;}*/ ?>
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
<style>
    .layui-table td, .layui-table th{
        padding:4.5px 7px;
       
    }
</style>
<body>
<blockquote class="layui-elem-quote layui-text">
 实时船期
</blockquote>
              
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>船期录入</legend>
</fieldset>
 
<form class="layui-form"  id="cargoPlanForm">
<table class="layui-table">
  <colgroup>
    <col width="150">
     <col width="150">
     <col width="150">
     <col width="150">
     <col width="150">
     <col width="150">
     <col width="150">
     <col width="150">
  </colgroup>
  <thead>
    <tr>
      <th>船名</th>
      <th>航线/航次</th>
      <th>装货港</th>
      <th>实际装船时间</th>
      <th>离港时间</th>
      <th>卸货港</th>
       <th>到港时间</th>
       <th>卸船时间</th>
    </tr> 
  </thead>
  <tbody>
    <?php $__FOR_START_15902__=0;$__FOR_END_15902__=$num;for($i=$__FOR_START_15902__;$i < $__FOR_END_15902__;$i+=1){ ?> 
    <tr>
      <td><input type="text" name="boatName[]" required  lay-verify="required" placeholder="请输入船名" autocomplete="off" class="layui-input"/></td>
      <td><div class="layui-input-inline ">
            <input   type="text" name="routes[]" required lay-verify="required" placeholder="请输入航线" autocomplete="off" class="layui-input"/>
            <input   type="text" name="voyage[]" required lay-verify="required" placeholder="请输入航次" autocomplete="off" class="layui-input"/>
          </div></td>
          <td><input type="hidden" name="loadPortCodeArr[]" value="<{portCodeArr[$i]}>" autocomplete="off" class="layui-input"/>
              <input type="text" name="loadPort[]"  readOnly="true" required  lay-verify="required" placeholder="请输入装货港口名" value="<?php echo $portArr[$i]; ?>" autocomplete="off" class="layui-input"/></td>
       <td><div class="layui-input-inline">
            <input class="layui-input time" name="shipment_time[]"  placeholder="yyyy-MM-dd HH:mm:ss" type="text">
        </div></td>
        <td><div class="layui-input-inline"> 
            <input class="layui-input time" name="dispatch_time[]" placeholder="yyyy-MM-dd HH:mm:ss" type="text">
        </div</td>
         <td><input type="hidden" name="departurePort[]" value="<{portCodeArr[$i+1]}>" autocomplete="off" class="layui-input"/>
             <input type="text" name="departurePort[]" readOnly="true" required  lay-verify="required" placeholder="请输入卸货港口名" value="<?php echo $portArr[$i+1]; ?>" autocomplete="off" class="layui-input"/></td>
         <td><div class="layui-input-inline">
            <input class="layui-input time" name="arrival_time[]" placeholder="yyyy-MM-dd HH:mm:ss" type="text">
        </div</td>
        <td><div class="layui-input-inline">
            <input class="layui-input time" name="discharge_time[]" placeholder="yyyy-MM-dd HH:mm:ss" type="text">
        </div</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
  <div class="layui-form-item">
    <div class="layui-input-block">
        <button type="button" class="layui-btn" id="login" >立即提交</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>

</form>
<script>
layui.use('laydate', function(){
  var laydate = layui.laydate;
  
  //同时绑定多个
  lay('.time').each(function(){
    laydate.render({
        elem: this
      ,type: 'datetime'
      ,trigger: 'click'
    });
  });
 
});

$(function(){
        $("#login").on('click',function (event) {
        $.ajax({
          type: 'post',
          url: "<?php echo url('admin/Order/toCargoPlan'); ?>",
          data: $("#cargoPlanForm").serialize(),
          dataType: "json",
          success: function (data) {
            if (data.status == 1) {
                alert("提交成功");
                parent.location.reload()
            } else {
              alert(data.msg+"提交失败");
            }
          }
        })
      })
  })

</script>
</body>
</html>