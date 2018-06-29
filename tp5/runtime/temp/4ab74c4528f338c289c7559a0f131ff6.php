<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\personal\info.html";i:1529718403;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1529718403;}*/ ?>
<!-- 个人信息 -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/index/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/index/css/font.css">
    <link rel="stylesheet" href="/static/index/css/index.css">
    <link rel="stylesheet" href="/static/index/css/top.css">
    <link rel="stylesheet" href="/static/index/css/foot.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="/static/index/js/jquery-1.9.0.min.js"></script>
    <script src="/static/index/layui/layui.js"></script>
</head>
  <body>
      <link rel="stylesheet" href="/static/index/css/info.css">
      <script src="/static/index/js/personal/info.js"></script>
    <div class="info">
      <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
        <ul class="layui-tab-title">
          <li class="layui-this">个人信息</li>
          <li>修改密码</li>
        </ul>

        <!-- 个人信息修改 -->
        <div class="layui-tab-content layui-row" style="height: 100px;">
          <div class="layui-tab-item layui-show">
            <!-- 上传图片 -->
            <div class="layui-upload">
              <img class="layui-upload-img" id="demo1">
              <p id="demoText"></p>
              <button type="button" class="layui-btn yin" id="test1">上传图片</button>
            </div>

            <form class="layui-form layui-col-xs6" action="">

              <div class="layui-col-xs10">
                <label class="layui-form-label">姓名:</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入姓名" class="layui-input in" value="123" readonly='readonly'>
                </div>
              </div>

              <div class="layui-col-xs10">
                <label class="layui-form-label">企业名:</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入企业名" class="layui-input in" value="123" readonly='readonly'>
                </div>
              </div>

              <div class="layui-col-xs10">
                <label class="layui-form-label">推荐人:</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入推荐人" class="layui-input in" value="123" readonly='readonly'>
                </div>
              </div>

              <div class="layui-col-xs10">
                <label class="layui-form-label">绑定手机号:</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="绑定手机号" class="layui-input in" value="123" readonly='readonly'>
                </div>
              </div>

              <div class="layui-col-xs10 yin">
                <div class="layui-inline layui-col-xs9">
                  <label class="layui-form-label">验证码:</label>
                  <div class="layui-input-block">
                    <input type="tel" name="title" lay-verify="title" autocomplete="off" placeholder="验证码" class="layui-input" value="">
                  </div>
                </div>
                <button class="layui-btn layui-btn-normal you">获取验证码</button>
              </div>

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn layui-btn-warm xiu">修改</button>
                  <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                </div>
              </div>
            </form>
          </div>

          <!-- 密码修改 -->
          <div class="layui-tab-item">
            <form class="layui-form layui-col-xs6" action="">
              <div class="layui-col-xs10">
                <label class="layui-form-label">绑定手机号:</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="绑定手机号" class="layui-input " value="123" readonly='readonly' style="border:0">
                </div>
              </div>

              <div class="layui-col-xs10">
                <div class="layui-inline layui-col-xs8" style="margin:0">
                  <label class="layui-form-label">验证码:</label>
                  <div class="layui-input-block">
                    <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="验证码" class="layui-input" value="">
                  </div>
                </div>
                <button class="layui-btn layui-btn-normal you">获取验证码</button>
              </div>

              <div class="layui-col-xs10">
                <label class="layui-form-label">新密码：</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="新密码" class="layui-input">
                </div>
              </div>

              <div class="layui-col-xs10">
                <label class="layui-form-label">确认密码：</label>
                <div class="layui-input-block">
                  <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="确认密码" class="layui-input">
                </div>
              </div>

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit="" lay-filter="demo1">确认修改</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
    $('.in').css('border', '0');
    $('.yin').hide();
      $('.xiu').toggle(function() {
        $('.in').css('border', '1px solid #e6e6e6');
        $('.in').removeAttr('readonly');
        $('.yin').show();

      }, function() {
        $('.yin').hide();
        $('.in').css('border', '0');
        $('.in').attr('readonly', 'readonly');
      });
    </script>
  </body>
</html>
