<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\listOrder\list_load.html";i:1532330490;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1531300152;}*/ ?>
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
<link rel="stylesheet" href="/static/admin/css/order_list.css">
<!-- 待装货 -->
<div class="layui-tab-item layui-show">
<xblock>
    <button class="layui-btn layui-btn-danger" onclick="delAll()">
    <i class="layui-icon"></i>批量删除</button>
    <span class="x-right" style="line-height:40px">总共有
    <?php echo $count_book; ?>条记录</span>
</xblock>
<!-- 内容 -->
<div class="order_list layui-row">
    <?php if(is_array($list_book) || $list_book instanceof \think\Collection || $list_book instanceof \think\Paginator): $i = 0; $__LIST__ = $list_book;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>  
    <div class="nei layui-col-md12">
        <div class="top">
            <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='1'>
                <i class="layui-icon">&#xe605;</i>
                <span>创建时间: <?php echo date("y-m-d",$vo['mtime']); ?></span>
            </div>
            <span class="top_ma">
                <span>业务员: <?php echo $vo['salesname']; ?></span>
                <span>船期: <?php echo date("y-m-d",$vo['shipping_date']); ?></span>
                <span>海上时效: <?php echo $vo['sea_limitation']; ?></span>
                <span>截单时间: <?php echo date("y-m-d",$vo['cutoff_date']); ?></span>
            </span>
        </div>
        <div class="cen">
            <div class="cen_le layui-col-md12">
            <div class="layui-col-md3">
                <p>订单号:<?php echo $vo['order_num']; ?></p>
                <p>船公司/船名/航次: <?php echo $vo['ship_short_name']; ?> <?php echo $vo['boat_name']; ?>/<?php echo $vo['boat_code']; ?></p>
            </div>
            <div class="layui-col-md3">
                <p>收货人: <?php echo $vo['company']; ?></p>
                <p>货名: <?php echo $vo['cargo']; ?></p>
            </div>
            <div class="layui-col-md3">
                <p>航线: <?php echo $vo['s_port_name']; ?>-<?php echo $vo['e_port_name']; ?></p>
                <p>箱型*箱量: <?php echo $vo['type']; ?>*<?php echo $vo['container_num']; ?></p>
            </div>
            <div class="layui-col-md2">
                <p class="se">状态：待装货</p>
                <p class="se">天数: <?php $timediff =time()-$vo['mtime']; $days = intval($timediff/86400); echo $days;?>天</p>
            </div>
            <div class="layui-col-md1">
                <p class="a_niu">
                    <a title="录入派车信息" onclick="x_admin_show('<?php echo $vo['order_num']; ?>录入装货时间','<?php echo url('admin/Order/addLoadTime'); ?>?order_num=<?php echo $vo['order_num']; ?>',800,600)" href="javascript:;">确认</a>
                </p>
                <p class="a_niu">
                    <a class="qu" href="">取消</a>
                </p>
            </div>
          </div>
        </div>
        <div class="fo">
            <a title="查看" onclick="x_admin_show('查看','<?php echo url('Price/route_edit'); ?>',700,500)" href="javascript:;">查看订单</a>
            <a title="修改" onclick="x_admin_show('修改','<?php echo url('Price/route_edit'); ?>',700,500)" href="javascript:;">修改订单</a>
            <a title="删除" onclick="" href="javascript:;">删除订单</a>
        </div>
    </div>
    <?php endforeach; endif; else: echo "" ;endif; ?>

</div>
<div class="page">
    <div>
        <?php echo $page_book; ?>
    </div>
</div>
</div>
<script>
    
</script>

</body>
</html>
