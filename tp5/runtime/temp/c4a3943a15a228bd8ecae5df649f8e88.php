<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1527857159;s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\port\port_add.html";i:1527857159;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
        <div class="x-body">
            <form class="layui-form" id="editform" method="post">
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>业务港口
                    </label>
                    <div class="layui-input-inline">
                        <select name="province" lay-filter="province">
                            <option value="">请选择省</option>
                        </select>
                    </div>
                    <div class="layui-input-inline" id='citydiv' style="display: none;">
                        <select name="city" lay-filter="city">
                            <option value="">请选择市</option>
                        </select>
                    </div>
                    <div class="layui-input-inline" style="display: none;">
                        <select name="area" lay-filter="area">
                            <option value="">请选择县</option>
                        </select>
                    </div>
                    <div class="layui-input-inline" style="display: none;">
                        <select name="town" lay-filter="town">
                            <option value="">请选择镇</option>
                        </select>
                    </div>
                </div>
                <!--  <button id="btn_tag" class="layui-btn layui-btn-normal"  style="display: none;"  onclick="del(this) ;return false">
                       <input id ="input_tag" type="hidden"  name="name" value="id"><i id ="i_tag" class="layui-icon">&#xe640;</i> </button>-->
                <div class="layui-form-item" id="search_port">
                </div>


                <div class="layui-form-item">
                        <label class="layui-form-label">
                            <span class="x-red">*</span>所属城市
                        </label>
                        <div class="layui-input-inline">
                            <select name="province" lay-filter="province">
                                <option value="">请选择省</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" id='citydiv' style="display: none;">
                            <select name="city" lay-filter="city">
                                <option value="">请选择市</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="display: none;">
                            <select name="area" lay-filter="area">
                                <option value="">请选择县</option>
                            </select>
                        </div>
                    </div>
                    <!--  <button id="btn_tag" class="layui-btn layui-btn-normal"  style="display: none;"  onclick="del(this) ;return false">
                           <input id ="input_tag" type="hidden"  name="name" value="id"><i id ="i_tag" class="layui-icon">&#xe640;</i> </button>-->
                    <div class="layui-form-item" id="search_port">
                    </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">
                    </label>
                    <input type="button" value="确 认" class="layui-btn" id="editbtn" onclick="toajax()">
                </div>
            </form>
        </div>
        <script>  var addressURL = "<?php echo url('admin/address/town'); ?>"; </script>
        <script type="text/javascript" src="/static/admin/js/address.js"></script>
        <script>
            layui.use(['form', 'layer'], function () {
                $ = layui.jquery;
                var form = layui.form
                    , layer = layui.layer;

                //自定义验证规则
                form.verify({
                    nikename: function () {
                        var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
                        var value = document.getElementById('phone');
                        if (myreg.test(value)) {
                            return '手机号码正确';
                        } else { return '手机号不正确'; }
                    }
                    , pass: [/(.+){6,12}$/, '密码必须6到12位']
                    , repass: function (value) {
                        if ($('#L_pass').val() != $('#L_repass').val()) {
                            return '两次密码不一致';
                        }
                    }
                });

                //监听提交
                form.on('submit(add)', function () {
                    //发异步，把数据提交给php
                    toajax();
                    layer.alert("增加成功", { icon: 6 }, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                    return false;
                });


                function toajax(dataArray) {
                    var message = 0;
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo url('admin/member/toAdd'); ?>",
                        data: $("#editform").serialize(),
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                message = '2';
                            } else {
                                message = '0';
                            }
                        }
                    })

                };


            });
        </script>
    </body>

    </html>