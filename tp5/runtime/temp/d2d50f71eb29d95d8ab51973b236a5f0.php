<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Order\load_time.html";i:1532432723;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1531300152;}*/ ?>
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
<body>
<link rel="stylesheet" href="/static/admin/css/route_add.css">
<form class="layui-form"  id ='loading_form'>
<div class="layui-form ">
    <table class="layui-table">
    <colgroup>
        <col width="6%">
        <col width='6%'>
    </colgroup>
    <thead>
        <tr>
            <th colspan="2">订单号<input type="text" readOnly="true" name='order_num' value="<?php echo $order_num; ?>" class="layui-input" ></th>
        </tr> 
        <tr>
            <th>柜号</th><th >装货时间</th>
        </tr> 
    </thead>
    <tbody>
        <?php foreach($data as $vo): ?>
        <tr>
            <td><input type="text" name="container_code[]" readOnly="true"  value="<?php echo $vo; ?>" class="layui-input"></td>
            <td><input type="date" name="loading_time[]" value="" class="layui-input"></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>
<div class="layui-form-item">
    <div class="layui-input-block an">
            <button type="button" class="layui-btn" onclick="toajax()">保存</button>
    </div>
</div>
</form>

<script type="text/javascript">
    layui.use(['form', 'layedit', 'laydate'], function () {
        var form = layui.form
            , layer = layui.layer
            , layedit = layui.layedit
            , laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#date'
        });
        laydate.render({
            elem: '#date1'
        });

    });
    function toajax() {
        $.ajax({
          type: 'post',
          url: "<?php echo url('admin/Order/toLoadTime'); ?>",
          data: $("#loading_form").serialize(),
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
      }
    
</script>
</body>
</html>