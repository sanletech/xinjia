<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526981949;s:87:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Financial\company_edit.html";i:1526980252;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
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
                <!-- 总柜量 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs12" style="margin-bottom: 5px;">总柜量：<?php 50 ?></div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入总柜量" class="layui-input" value="<{10}>">
                    </div>
                </div>
                <!-- 总交易 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs12" style="margin-bottom: 5px;">总交易：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入总交易" class="layui-input" value="30">
                    </div>
                </div>
                <!-- 各个船公司总柜量 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs12" style="margin-bottom: 5px;">各个船公司总柜量：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="船公司总柜量" class="layui-input" value="20">
                    </div>
                </div>
                <!-- 各个码头总柜量 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs12" style="margin-bottom: 5px;">各个码头总柜量：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="码头总柜量" class="layui-input" value="10">
                    </div>
                </div>
                <!-- 各个码头各个船公司的总柜量 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs12" style="margin-bottom: 5px;">各个码头各个船公司的总柜量：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="各个码头各个船公司的总柜量" class="layui-input" value="50">
                    </div>
                </div>
                <!-- 按钮 -->
                <div class="layui-form-item">
                    <div class="layui-input-block an">
                        <button class="layui-btn" lay-submit="" lay-filter="demo1">确认</button>
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