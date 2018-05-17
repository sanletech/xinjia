<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526458698;s:82:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\trailer_add.html";i:1526040607;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
    <link rel="stylesheet" href="/static/admin/css/trailer_add.css">
    <form class="layui-form" action="">
      <div class="trailer layui-row">
        <!-- 下拉选择框 -->
        <div class="layui-form-item">
          <label class="layui-form-label">请选择地址:</label>
          <div class="layui-input-inline">
            <select name="quiz1">
              <option value="">请选择省</option>
              <option value="浙江" selected="">浙江省</option>
              <option value="你的工号">江西省</option>
              <option value="你最喜欢的老师">福建省</option>
            </select>
          </div>
          <div class="layui-input-inline">
            <select name="quiz2">
              <option value="">请选择市</option>
              <option value="杭州">杭州</option>
              <option value="宁波" disabled="">宁波</option>
              <option value="温州">温州</option>
              <option value="温州">台州</option>
              <option value="温州">绍兴</option>
            </select>
          </div>
          <div class="layui-input-inline">
            <select name="quiz3">
              <option value="">请选择县/区</option>
              <option value="西湖区">西湖区</option>
              <option value="余杭区">余杭区</option>
              <option value="拱墅区">临安市</option>
            </select>
          </div>
          <div class="layui-input-inline">
            <select name="quiz4">
              <option value="">请选择县/区</option>
              <option value="西湖区">西湖区</option>
              <option value="余杭区">余杭区</option>
              <option value="拱墅区">临安市</option>
            </select>
          </div>
        </div>

        <!-- 收货 -->
        <div class="layui-col-xs5">
          <div class="grid-demo grid-demo-bg1">
            <div class="layui-form-item">
              <label class="layui-form-label">收货20GP:</label>
              <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">收货40HQ:</label>
              <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
              </div>
            </div>
          </div>
        </div>
        <!-- 中间线 -->
        <div class="layui-col-xs2 xian"></div>
        <!-- 送货 -->
        <div class="layui-col-xs5">
          <div class="grid-demo">
            <div class="grid-demo grid-demo-bg1">
              <div class="layui-form-item">
                <label class="layui-form-label">送货20GP:</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">送货40HQ:</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="layui-form-item layui-col-xs12 niu">
          <div class="layui-col-md1 layui-col-md-offset4">
            <div class="grid-demo grid-demo-bg1">
              <button class="layui-btn" lay-submit="" lay-filter="demo1">添加</button>
            </div>
          </div>
          <div class="layui-col-md1 layui-col-md-offset2">
            <div class="grid-demo">
              <button class="layui-btn cancel">取消</button>
            </div>
          </div>
        </div>
      </div>
    </form>

    <script type="text/javascript">
      $('.cancel').click(function () {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
      });
    </script>

  </body>

  </html>