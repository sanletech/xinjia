<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1527060834;s:80:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\route_add.html";i:1527060834;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
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
        <!-- 航务公司 -->
        <div class="layui-form-item">
          <label class="layui-form-label">船务公司</label>
          <div class="layui-input-block">
            <select name="modules" lay-verify="required" lay-search="">
              <option value="">选择船务公司</option>
              <option value="">中远海船务</option>
            </select>
          </div>
        </div>
        <!-- 船名 -->
        <div class="layui-form-item">
          <label class="layui-form-label">船名</label>
          <div class="layui-input-block">
            <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入船名" class="layui-input">
          </div>
        </div>
        <!-- 船期 -->
        <div class="layui-form-item">
          <label class="layui-form-label">船期</label>
          <div class="layui-input-block">
            <input type="text" name="date" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
          </div>
        </div>
        <!-- 截单时间 -->
        <div class="layui-form-item">
          <label class="layui-form-label">截单时间</label>
          <div class="layui-input-block">
            <input type="text" name="date1" id="date1" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
          </div>
        </div>
        <!-- 海上时效 -->
        <div class="layui-form-item">
          <label class="layui-form-label">海上时效</label>
          <div class="layui-input-block">
            <select name="modules" lay-verify="required" id="day" lay-search="">
              <option value="">选择天数</option>
            </select>
          </div>
        </div>
        <!-- 20GP -->
        <div class="layui-form-item">
          <label class="layui-form-label">20GP</label>
          <div class="layui-input-block">
            <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="20GP" class="layui-input">
          </div>
        </div>
        <!-- 40HQ -->
        <div class="layui-form-item">
          <label class="layui-form-label">40HQ</label>
          <div class="layui-input-block">
            <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="40HQ" class="layui-input">
          </div>
        </div>
        <!-- 航线详情 -->
        <div class="gkou">
          <div class="le layui-col-xs2">港口航线</div>
          <div class="rig">
            <div class="layui-col-xs10">
              <div class="layui-col-xs3 inpu">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="港口" class="layui-input">
              </div>
              <div class="layui-col-xs3 inpu">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="港口" class="layui-input">
              </div>
              <div class="layui-col-xs3 jia">
                <a onclick="hang();" href="javascript:;">
                  <i class="layui-icon">&#xe654;</i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- 是否推荐 -->
        <div class="layui-form-item">
          <label class="layui-form-label">推荐</label>
          <div class="layui-input-block">
            <input type="radio" name="sex" value="是" title="是">
            <input type="radio" name="sex" value="否" title="否" checked="">
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
      //取消关闭模态框
      $('.cancel').click(function () {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
      });

      //海上时效天数
      for (let i = 1; i < 31; i++) {
        $('#day').append('<option value="' + i + '">' + i + '天</option>');
      }

      //航线详情-添加
      function hang() {
        $('.jia').before(
          '<div class="layui-col-xs3 inpu">'
          +'<input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="港口" class="layui-input">'
          +'<a onclick="hang_co(this);" href="javascript:;"><i class="layui-icon">&#x1006;</i></a>'
          +'</div>');
      }
      //航线详情-删除
      function hang_co(zj){
        console.log($(zj).parent().remove());
      }

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