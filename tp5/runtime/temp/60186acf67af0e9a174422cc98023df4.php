<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1525947884;s:83:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Order\list_peiship.html";i:1526368181;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
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
        <form class="layui-form" action="">
            <div class="route layui-row">
                <!-- 航程 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">航程：</div>
                    <div class="layui-col-xs12">
                        <select name="interest" lay-filter="aihao">
                            <option value=""></option>
                            <option value="1">中转一程</option>
                            <option value="2">中转二程</option>
                            <option value="3">大船直航</option>
                            <option value="4">自定义</option>
                        </select>
                    </div>
                </div>
                <!-- 开船时间 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">实际开船时间：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="date" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <!-- 截单时间 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">预计开船时间：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="date1" id="date1" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <!-- 船名 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">船名：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入船名" class="layui-input">
                    </div>
                </div>
                <!-- 航线港口 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">港口航线：</div>
                    <div class="layui-input-inline gang" style="width: 245px;">
                        <input type="text" name="price_min" placeholder="起运港口" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid">—</div>
                    <div class="layui-input-inline gang" style="width: 245px;">
                        <input type="text" name="price_max" placeholder="目的港口" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <!-- 按钮 -->
                <div class="layui-form-item">
                    <div class="layui-input-block an">
                        <button class="layui-btn" lay-submit="" lay-filter="demo1">添加</button>
                        <button class="layui-btn cancel">取消</button>
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
        </script>

    </body>

    </html>