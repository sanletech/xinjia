<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\help.html";i:1529105630;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1529718403;}*/ ?>
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
      <link rel="stylesheet" href="/static/index/css/help.css">
    <div class="banner"></div>
    <div class="help">
      <div class=" help_top">
        <div class="hh1">
          <h1><strong>帮助和公告 / </strong>Help and notice</h1>
          <div class="heng"></div>
        </div>
      </div>

      <div class="help_cen layui-row">
        <div class="layui-col-xs4">
          <div class="grid-demo">图片</div>
        </div>
        <div class="layui-col-xs8">
          <div class="bz">
            <ul id="bzhu">
              <li>帮助</li>
              <li>公告</li>
              <li>常见问题</li>
            </ul>
          </div>
          <div class="bz_nei">
            <ul>
              <li>
                <h3>如何填写运单号</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>如何查询快递价格</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>如何查询有那些物品不能收送</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>快递已经寄出，为何网上单查不到这个单号</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>关于2018年端午节放假通知</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>如何填写运单号</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>如何查询快递价格</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>如何查询有那些物品不能收送</h3>
                <p>2018-03-02</p>
              </li>
            </ul>
          </div>
          <div class="bz_nei">公告</div>
          <div class="bz_nei">常见问题</div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $('#bzhu li').eq(0).addClass('bb');
      $('.bz_nei').eq(0).show();
      $('#bzhu li').click(function(event) {
        $(this).addClass('bb').siblings().removeClass('bb');
        $('.bz_nei').eq($(this).index()).show().siblings('.bz_nei').hide();
      });
    </script>
  </body>
</html>
