<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1527679283;s:88:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\carshipman\sealine_list.html";i:1527582583;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
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
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">演示</a>
                <a>
                    <cite>导航元素</cite>
                </a>
            </span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);"
                title="刷新">
                <i class="layui-icon" style="line-height:30px">ဂ</i>
            </a>
        </div>
        <div class="x-body">
            <div class="layui-row">
                <form class="layui-form layui-col-md12 x-so">
                    <input type="text" name="username" placeholder="请输入账号名" autocomplete="off" class="layui-input">
                    <button class="layui-btn" lay-submit="" lay-filter="sreach">
                        <i class="layui-icon">&#xe615;</i>
                    </button>
                </form>
            </div>
            <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()">
                    <i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url("CarMan/sealine_add"); ?>',650,400)">
                    <i class="layui-icon"></i>添加</button>
                <!-- <span class="x-right" style="line-height:40px">总共有<{10*$page}>条记录</span>-->
            </xblock>
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>
                            <div class="layui-unselect header layui-form-checkbox" lay-skin="primary">
                                <i class="layui-icon">&#xe605;</i>
                            </div>
                        </th>
                        <th>航线/起-终</th>
                        <th>航线详情</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='1'>
                                <i class="layui-icon">&#xe605;</i>
                            </div>
                        </td>

                        <td class="tdata">天津港/北京港</td>
                        <td>嘻哈-阿斯蒂芬-发大水-阿道夫-阿斯蒂芬-速度sd-士大夫</td>
                        <td>2018-02-02</td>
                        <td class="td-manage">
                            <a title="编辑" onclick="x_admin_show('修改信息','<?php echo url("CarMan/ship_edit"); ?>',500,350)" href="javascript:;">
                                <i class="layui-icon">&#xe642;</i>
                            </a>
                            <a title="删除" onclick="" href="javascript:;">
                                <i class="layui-icon">&#xe640;</i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="page">
                <div>

                </div>
            </div>

        </div>
        <script>
            layui.use('laydate', function () {
                var laydate = layui.laydate;

                //执行一个laydate实例
                laydate.render({
                    elem: '#start' //指定元素
                });

                //执行一个laydate实例
                laydate.render({
                    elem: '#end' //指定元素
                });
            });

        </script>

    </body>

    </html>