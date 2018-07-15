<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:83:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Order\list_booking.html";i:1531481520;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1527898250;}*/ ?>
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
        <form class="layui-form" action="" id="waybillNum_form" >
        <input type="hidden" name='order_num' value="<?php echo $order_num; ?>">
        <input type="hidden" name='container_num' value="<?php echo $container_num; ?>">
            <div class="route layui-row">
                <!-- 运单号 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">运单号：</label>
                    <div class="layui-input-block">
                        <input type="text" name="waybillNum" lay-verify="title" autocomplete="off" placeholder="请输入运单号" class="layui-input">
                    </div>
                </div>

                <!-- 按钮 -->
                <div class="layui-form-item">
                    <div class="layui-input-block an">
                        <button class="layui-btn"  type="button"  onclick ='toajax()'>保存</button>
                    </div>
                </div>
            </div>

        </form>

        <script type="text/javascript">
            $('.cancel').click(function () {
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            });
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
          url: "<?php echo url('admin/Order/waybillNum'); ?>",
          data: $("#waybillNum_form").serialize(),
          dataType: "json",
          success: function (data) {
            alert("提交成功");
            if (data.status == 1) {
              return 1;
            } else {
              return 0;
            }
          }
        })
      }
            
        </script>

    </body>

    </html>