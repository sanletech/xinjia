<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\container.html";i:1529651522;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1530520898;}*/ ?>
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
      <link rel="stylesheet" href="/static/index/css/container.css">
    <div class="banner"></div>
    <div class="sale">
      <div class=" sale_top">
        <div class="hh1">
          <h1><strong>集装箱出售 / </strong>Container sale</h1>
          <div class="heng"></div>
        </div>
      </div>
      <div class="layui-row">
        <ul id="ghuo">
          <li>全新干货集装箱</li>
          <li>全新干货集装箱</li>
          <li>全新干货集装箱</li>
          <li>全新干货集装箱</li>
          <li>全新干货集装箱</li>
        </ul>

        <ul class="jzxcs">
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
          <li>
            <div class="tu">图片</div>
            <div class="qx">
              <v>全新干货集装箱</v>
              <span>￥229/个</span>
            </div>
          </li>
        </ul>
        <ul class="jzxcs">
          <li>1</li>
          <li>2</li>
          <li>3</li>
          <li>4</li>
          <li>5</li>
          <li>6</li>
          <li>7</li>
          <li>8</li>
          <li>9</li>
        </ul>
        <ul class="jzxcs">
          <li>1</li>
          <li>2</li>
          <li>3</li>
          <li>4</li>
          <li>5</li>
          <li>6</li>
          <li>7</li>
          <li>8</li>
          <li>9</li>
        </ul>
        <ul class="jzxcs">
          <li>1</li>
          <li>2</li>
          <li>3</li>
          <li>4</li>
          <li>5</li>
          <li>6</li>
          <li>7</li>
          <li>8</li>
          <li>9</li>
        </ul>
        <ul class="jzxcs">
          <li>1</li>
          <li>2</li>
          <li>3</li>
          <li>4</li>
          <li>5</li>
          <li>6</li>
          <li>7</li>
          <li>8</li>
          <li>9</li>
        </ul>
      </div>
    </div>

    <script type="text/javascript">
      $('#ghuo li').eq(0).addClass('ys');
      $('.jzxcs').eq(0).show();
      $('#ghuo li').click(function(event) {
        $(this).addClass('ys').siblings().removeClass('ys');
        $('.jzxcs').eq($(this).index()).show().siblings('.jzxcs').hide();
      });
    </script>
  </body>
</html>
