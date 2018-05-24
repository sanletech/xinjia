<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526981949;s:83:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Member\member_edit.html";i:1526898373;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
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
                <!-- 账号 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">账号：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入账号" class="layui-input" disabled="true"
                            value="18365486284">
                    </div>
                </div>
                <!-- 密码 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">密码：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入密码" class="layui-input" value="13055493654">
                    </div>
                </div>
                <!-- 推荐人 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">推荐人：</div>
                    <div class="layui-col-xs12">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入推荐人" class="layui-input" value="吴先生">
                    </div>
                </div>
                <!-- 航程 -->
                <!-- 船公司一 -->
                <div class="layui-form-item">
                    <div style="margin-bottom: 10px;margin-top: 20px;">选择航线：</div>
                    <div class="layui-col-xs3">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入航线" class="layui-input" disabled="true"
                            value="船公司一">
                    </div>
                    <div class="layui-col-xs3" style="margin:0px 65px;">
                        <select name="quiz3" lay-filter="test">
                            <option value="">请选择价格</option>
                            <option value="600" selected="">600</option>
                            <option value="200">200</option>
                            <option value="0">自定义</option>
                        </select>
                    </div>
                    <div class="layui-col-xs3 zdy">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="价格-500至2000" class="layui-input layui-disabled"
                            disabled="true">
                    </div>
                </div>
                <!-- 船公司二 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs3">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入航线" class="layui-input" disabled="true"
                            value="船公司二">
                    </div>
                    <div class="layui-col-xs3" style="margin:0px 65px;">
                        <select name="quiz3" lay-filter="test">
                            <option value="">请选择价格</option>
                            <option value="600" selected="">600</option>
                            <option value="200">200</option>
                            <option value="0">自定义</option>
                        </select>
                    </div>
                    <div class="layui-col-xs3 zdy">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="价格-500至2000" class="layui-input layui-disabled"
                            disabled="true">
                    </div>
                </div>
                <!-- 船公司三 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs3">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入航线" class="layui-input" disabled="true"
                            value="船公司三">
                    </div>
                    <div class="layui-col-xs3" style="margin:0px 65px;">
                        <select name="quiz3" lay-filter="test">
                            <option value="">请选择价格</option>
                            <option value="600" selected="">600</option>
                            <option value="200">200</option>
                            <option value="0">自定义</option>
                        </select>
                    </div>
                    <div class="layui-col-xs3 zdy">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="价格-500至2000" class="layui-input layui-disabled"
                            disabled="true">
                    </div>
                </div>
                <!-- 船公司四 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs3">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入航线" class="layui-input" disabled="true"
                            value="船公司四">
                    </div>
                    <div class="layui-col-xs3" style="margin:0px 65px;">
                        <select name="quiz3" lay-filter="test">
                            <option value="">请选择价格</option>
                            <option value="600" selected="">600</option>
                            <option value="200">200</option>
                            <option value="0">自定义</option>
                        </select>
                    </div>
                    <div class="layui-col-xs3 zdy">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="价格-500至2000" class="layui-input layui-disabled"
                            disabled="true">
                    </div>
                </div>
                <!-- 船公司五 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs3">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入航线" class="layui-input" disabled="true"
                            value="船公司五">
                    </div>
                    <div class="layui-col-xs3" style="margin:0px 65px;">
                        <select name="quiz3" lay-filter="test">
                            <option value="">请选择价格</option>
                            <option value="600" selected="">600</option>
                            <option value="200">200</option>
                            <option value="0">自定义</option>
                        </select>
                    </div>
                    <div class="layui-col-xs3 zdy">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="价格-500至2000" class="layui-input layui-disabled"
                            disabled="true">
                    </div>
                </div>
                <!-- 船公司六 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs3">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入航线" class="layui-input" disabled="true"
                            value="船公司六">
                    </div>
                    <div class="layui-col-xs3" style="margin:0px 65px;">
                        <select name="quiz3" lay-filter="test">
                            <option value="">请选择价格</option>
                            <option value="600" selected="">600</option>
                            <option value="200">200</option>
                            <option value="0">自定义</option>
                        </select>
                    </div>
                    <div class="layui-col-xs3 zdy">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="价格-500至2000" class="layui-input layui-disabled"
                            disabled="true">
                    </div>
                </div>
                <!-- 船公司七 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs3">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入航线" class="layui-input" disabled="true"
                            value="船公司七">
                    </div>
                    <div class="layui-col-xs3" style="margin:0px 65px;">
                        <select name="quiz3" lay-filter="test">
                            <option value="">请选择价格</option>
                            <option value="600" selected="">600</option>
                            <option value="200">200</option>
                            <option value="0">自定义</option>
                        </select>
                    </div>
                    <div class="layui-col-xs3 zdy">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="价格-500至2000" class="layui-input layui-disabled"
                            disabled="true">
                    </div>
                </div>
                <!-- 船公司八 -->
                <div class="layui-form-item">
                    <div class="layui-col-xs3">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入航线" class="layui-input" disabled="true"
                            value="船公司八">
                    </div>
                    <div class="layui-col-xs3" style="margin:0px 65px;">
                        <select name="quiz3" lay-filter="test">
                            <option value="">请选择价格</option>
                            <option value="600" selected="">600</option>
                            <option value="200">200</option>
                            <option value="0">自定义</option>
                        </select>
                    </div>
                    <div class="layui-col-xs3 zdy">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="价格-500至2000" class="layui-input layui-disabled"
                            disabled="true">
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

                form.on('select(test)', function (data) {
                    //获取当前下自定义的input
                    var zj = $(this).parent().parent().parent().siblings('.zdy').find('input');
                    if (data.value == 0) {
                        zj.removeClass('layui-disabled').removeAttr('disabled');
                    } else {
                        zj.addClass('layui-disabled').attr('disabled', false);
                    }
                });
            });
        </script>

    </body>

    </html>