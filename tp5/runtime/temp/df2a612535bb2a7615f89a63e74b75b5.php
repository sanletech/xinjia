<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\personal.html";i:1529068907;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1529068907;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/index/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/index/font/iconfont.css">
    <link rel="stylesheet" href="/static/index/css/index.css">
    <link rel="stylesheet" href="/static/index/css/top.css">
    <link rel="stylesheet" href="/static/index/css/foot.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="/static/index/js/jquery-1.9.0.min.js"></script>
</head>

  <body class="layui-layout-body">
    <link rel="stylesheet" href="/static/index/css/personal.css">
    <!-- 头部区域（可配合layui已有的水平导航） -->
      <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
          <div class="layui-logo">layui 后台布局</div>
          <!-- 头部区域（可配合layui已有的水平导航） -->
          <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item">
              <a href="">控制台</a>
            </li>
            <li class="layui-nav-item">
              <a href="">商品管理</a>
            </li>
            <li class="layui-nav-item">
              <a href="">用户</a>
            </li>
            <li class="layui-nav-item">
              <a href="javascript:;">其它系统</a>
              <dl class="layui-nav-child">
                <dd>
                  <a href="">邮件管理</a>
                </dd>
                <dd>
                  <a href="">消息管理</a>
                </dd>
                <dd>
                  <a href="">授权管理</a>
                </dd>
              </dl>
            </li>
          </ul>
          <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
              <a href="javascript:;">
                <img src="http://t.cn/RCzsdCq" class="layui-nav-img"> 贤心
              </a>
              <dl class="layui-nav-child">
                <dd>
                  <a href="">基本资料</a>
                </dd>
                <dd>
                  <a href="">安全设置</a>
                </dd>
              </dl>
            </li>
            <li class="layui-nav-item">
              <a href="">退了</a>
            </li>
          </ul>
        </div>

        <div class="layui-side layui-bg">
          <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-filter="test">
              <li class="layui-nav-item">
                <a href="<?php echo url('personal/steward'); ?>">物流管家</a>
              </li>
              <li class="layui-nav-item">
                <a class="" href="javascript:;">订单中心</a>
                <dl class="layui-nav-child">
                  <dd>
                    <a href="personal/all_order.html">所有订单</a>
                  </dd>
                  <dd>
                    <a href="personal/invalid.html">作废订单</a>
                  </dd>
                </dl>
              </li>
              <li class="layui-nav-item">
                <a href="javascript:;">账单中心</a>
                <dl class="layui-nav-child">
                  <dd>
                    <a href="personal/a_order.html">生成账单</a>
                  </dd>
                  <dd>
                    <a href="personal/form_order.html">账单报表</a>
                  </dd>
                </dl>
              </li>

              <li class="layui-nav-item">
                <a href="javascript:;">企业中心</a>
                <dl class="layui-nav-child">
                  <dd>
                    <a href="personal/info.html">个人信息</a>
                  </dd>
                  <dd>
                    <a href="personal/company.html">公司信息</a>
                  </dd>
                  <dd>
                    <a href="personal/common_info.html">常用信息</a>
                  </dd>
                </dl>
              </li>
            </ul>
          </div>
        </div>

        <div class="layui-body body_nei">
          <!-- 内容主体区域 -->
          <iframe src="<?php echo url('personal/steward'); ?>" width="100%" height="0" name="myFrame" frameborder="0" scrolling="auto" id="mainiframe"></iframe>
        </div>
      </div>
        <!-- 底部固定区域 -->
        <!-- <div class="layui-footer" style="z-index:999">
          © layui.com - 底部固定区域
        </div> -->


        <script>
          function changeFrameHeight() {
            var ifm = document.getElementById("mainiframe");
            ifm.height = document.documentElement.clientHeight - 220 + 'px';
            console.log(ifm.height);

          }
          $(function () { changeFrameHeight(); });
        </script>
    </body>

    </html>