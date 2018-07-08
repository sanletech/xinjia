<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1528888058;s:76:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\index\index.html";i:1523153942;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\middle.html";i:1528888058;}*/ ?>
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
     <body>
    <!-- 左侧菜单开始 -->
    <div class="left-nav">
        <div id="side-nav">
            <ul id="nav">
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6b8;</i>
                        <cite>用户管理</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Member/member_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>用户列表</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Member/company_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>企业列表</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Member/disable_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>禁用账号</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Member/member_check'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>用户跟踪</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe723;</i>
                        <cite>订单管理</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Order/order_audit'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>审核订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Order/order_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>处理订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Order/order_waste'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>查看订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Order/order_edit'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>废弃订单</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe698;</i>
                        <cite>运价设置</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Price/price_route'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>航线运价</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Price/price_trailer'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>拖车运价</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe705;</i>
                        <cite>财务中心</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Financial/customer_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>客户订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Financial/financial_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>账单列表</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Financial/company_form'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>公司表报</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe726;</i>
                        <cite>权限分配</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('keeper/user_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>用户列表</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('keeper/admin_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>管理员列表</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="layui-icon">&#xe631;</i>
                        <cite>数据管理</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Port/port_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>港口管理</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Port/boat_name'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>船名管理</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Port/shiproute_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>航线详情</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe725;</i>
                        <cite>通讯录</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Car/car_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>车队通讯录</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('CarMan/man_List'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>车队人员资料</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Ship/ship_List'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>船公司通讯录</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('ShipMan/man_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>船公司人员资料</cite>
                            </a>
                        </li>
                       
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6ce;</i>
                        <cite>系统统计</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="echarts1.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>拆线图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts2.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>柱状图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts3.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>地图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts4.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>饼图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts5.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>雷达图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts6.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>k线图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts7.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>热力图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts8.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>仪表图</cite>
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
    </script>>
</body>

</html>
    <!--  <div class="left-nav">
      <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6b8;</i>
                    <cite>会员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('Member/mList'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>会员列表</cite>
                            
                        </a>
                    </li >
                    <li>
                        <a _href="">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>会员删除</cite>
                            
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe70b;</i>
                            <cite>会员管理</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="xxx.html">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>会员列表</cite>
                                    
                                </a>
                            </li >
                            <li>
                                <a _href="xx.html">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>会员删除</cite>
                                    
                                </a>
                            </li>
                            <li>
                                <a _href="xx.html">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>等级管理</cite>
                                    
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe723;</i>
                    <cite>订单管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="order-list.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单列表</cite>
                        </a>
                    </li >
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite>管理员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="admin-list.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>管理员列表</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="admin-role.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>角色管理</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="admin-cate.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>权限分类</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="admin-rule.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>权限管理</cite>
                        </a>
                    </li >
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6ce;</i>
                    <cite>系统统计</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="echarts1.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>拆线图</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="echarts2.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>柱状图</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="echarts3.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>地图</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="echarts4.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>饼图</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="echarts5.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>雷达图</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="echarts6.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>k线图</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="echarts7.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>热力图</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="echarts8.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>仪表图</cite>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
      </div>
    </div> -->
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
          <ul class="layui-tab-title">
            <li>我的桌面</li>
          </ul>
          <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src="<?php echo url('index/welcome'); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
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