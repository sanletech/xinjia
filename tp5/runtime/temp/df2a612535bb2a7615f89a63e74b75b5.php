<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\personal.html";i:1529651522;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1529651522;}*/ ?>
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
    <link rel="stylesheet" href="/static/index/css/xadmin.css">
    <script type="text/javascript" src="/static/index/js/xadmin.js"></script>
    <!-- 顶部开始 -->
    <div class="container">
        <div class="logo"><a href="./index.html">X-admin v2.0</a></div>
        <div class="left_open">
            <i title="展开左侧栏" class="iconfont">&#xe699;</i>
        </div>
        <ul class="layui-nav left fast-add" lay-filter="">
          <li class="layui-nav-item">
            <a href="javascript:;">+新增</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
              <dd><a onclick="x_admin_show('资讯','http://www.baidu.com')"><i class="iconfont">&#xe6a2;</i>资讯</a></dd>
              <dd><a onclick="x_admin_show('图片','http://www.baidu.com')"><i class="iconfont">&#xe6a8;</i>图片</a></dd>
               <dd><a onclick="x_admin_show('用户','http://www.baidu.com')"><i class="iconfont">&#xe6b8;</i>用户</a></dd>
            </dl>
          </li>
        </ul>
        <ul class="layui-nav right" lay-filter="">
          <li class="layui-nav-item">
            <a href="javascript:;">admin</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
              <dd><a onclick="x_admin_show('个人信息','http://www.baidu.com')">个人信息</a></dd>
              <dd><a onclick="x_admin_show('切换帐号','http://www.baidu.com')">切换帐号</a></dd>
              <dd><a href="<?php echo url('login/logout'); ?>">退出</a></dd>
            </dl>
          </li>
          <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
        </ul>
        
    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
     <!-- 左侧菜单开始 -->
     <div class="left-nav">
        <div id="side-nav">
            <ul id="nav">
                <li>
                    <a _href="<?php echo url('personal/steward'); ?>">
                        <i class="layui-icon">&#xe667;</i>
                        <cite>物流管家</cite>
                    </a>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe723;</i>
                        <cite>订单中心</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('personal/all_order'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>所有订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('personal/invalid'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>废弃订单</cite>
                            </a>
                        </li>
                    </ul>
                </li>
               
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe705;</i>
                        <cite>账单中心</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('personal/a_order'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>生成账单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('personal/form_order'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>账单报表</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe726;</i>
                        <cite>企业中心</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('personal/info'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>个人信息</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('personal/company'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>公司信息</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('personal/common_info'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>常用信息</cite>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <script>
        window.onload = function func() {
            $(document).click(function () { return true; });
        }
    </script>
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
          <ul class="layui-tab-title">
            <li>我的桌面</li>
          </ul>
          <div class="layui-tab-content" style="padding: 10px 0 0 10px;">
            <div class="layui-tab-item layui-show">
                <iframe src="<?php echo url('personal/steward'); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
          </div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <!-- 右侧主体结束 -->
    <!-- 中部结束 -->
    <!-- 底部开始 -->
    <div class="footer">
        <div class="copyright">Copyright ©2017 x-admin v2.3 All Rights Reserved</div>  
    </div>
    <!-- 底部结束 -->
    
</body>
</html>