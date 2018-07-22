<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Order\sendCarInfo.html";i:1532090493;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1527898250;}*/ ?>
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
<form class="layui-form"  id ='sendCar_form'>
<div class="layui-form ">
    <table class="layui-table">
    <colgroup>
        <col width="6%">
        <?php 
        for($i=0;$i<$container_num;$i++){
          echo "<col width='8%'>";
        }
        ?>
        <col>
    </colgroup>
    <thead>
        <tr>
            <th>栏目\序号<?php echo $container_num; ?></th>
            <?php 
            for($i=1;$i<=$container_num;$i++){
              echo "<th>司机$i</th>";
            }
            ?>
        </tr> 
    </thead>
    <input type="hidden" name='order_num' value="<?php echo $order_num; ?>">
    <input type="hidden" name='container_num' value="<?php echo $container_num; ?>">
    <tbody>
        <tr>
            <td>车队名字</td> <?php 
            for($i=0;$i<$container_num;$i++){
                echo '<td> <input type="text" name="carName[]" lay-verify="title" autocomplete="off" placeholder="请输入车队名字" class="layui-input"></td>';
            } ?>
        </tr>
        <tr>
            <td>司机姓名</td><?php 
            for($i=0;$i<$container_num;$i++){
                echo '<td><input type="text" name="driverName[]" lay-verify="title" autocomplete="off" placeholder="司机姓名" class="layui-input"></td>';
            } ?>
        </tr>
        <tr>
            <td>车牌号</td><?php 
            for($i=0;$i<$container_num;$i++){
                echo '<td><input type="text" name="carCode[]" lay-verify="title" autocomplete="off" placeholder="请输入车牌号" class="layui-input"></td>';
            } ?>
        </tr>
        <tr>
            <td>身份证</td><?php 
            for($i=0;$i<$container_num;$i++){
                echo '<td><input type="text" name="identity[]" lay-verify="title" autocomplete="off" placeholder="身份证" class="layui-input"></td>';
            } ?>
        </tr>
        <tr>
            <td>手机号</td><?php 
              for($i=0;$i<$container_num;$i++){
                echo '<td><input type="text" name="mobile[]" lay-verify="title" autocomplete="off" placeholder="手机号" class="layui-input"></td>';
            } ?>
        </tr>
        <tr>
            <td>柜号</td><?php 
            for($i=0;$i<$container_num;$i++){
                echo '<td><input type="text" name="container[]" lay-verify="title" autocomplete="off" placeholder="手机号" class="layui-input"></td>';
            } ?>
        </tr>
        <tr>
            <td>封条号</td><?php 
            for($i=0;$i<$container_num;$i++){
                echo '<td><input type="text" name="seal_id[]" lay-verify="title" autocomplete="off" placeholder="封条号" class="layui-input"></td>';
            } ?>
        </tr>
        <tr>
            <td>收货人</td> <?php 
            for($i=0;$i<$container_num;$i++){
                echo '<td><input type="text" name="consignee[]" lay-verify="title" autocomplete="off" placeholder="收货人" class="layui-input"></td>';
            } ?>
        </tr>
        <tr>
            <td>装货时间</td><?php 
            for($i=0;$i<$container_num;$i++){
                echo '<td><input type="date" name="consigneeTime[]" lay-verify="title" autocomplete="off" placeholder="装货时间" class="layui-input"></td>';
            } ?>
        </tr>
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
          url: "<?php echo url('admin/Order/tosendCar'); ?>",
          data: $("#sendCar_form").serialize(),
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