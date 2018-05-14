<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526301865;s:79:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Ship\ship_info.html";i:1526307118;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1526047940;}*/ ?>
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
      <table class="layui-table">
        <thead>
            <tr><th class="layui-bg-green"><?php echo $list['0']['port_name']; ?></th>
                <th class="layui-bg-blue"><?php echo $list['0']['ship_short_name']; ?></th>
            </tr>
            <tr>
            <th>职务</th>
            <th>姓名</th>
            <th>座机</th>
            <th>手机</th>
            <th>微信/QQ</th>
            <th>传真</th></tr>
        </thead>
    <tbody >
            <?php 
            foreach ($list as  $value) {
                echo '<tr><td class="tdata">'.$value['position'].'</td>';
                echo '<td class="tdata">'.$value['name'].'</td>';
                echo '<td class="tdata">'.$value['sn_tel'].'</td>';
                echo '<td class="tdata">'.$value['sn_mobile'].'</td>';
                echo '<td class="tdata">'.$value['sn_qq'].'</td>';
                echo '<td class="tdata">'.$value['sn_fax'].'</td></tr>';
               }
            ?>
        </tbody> 
      </table>
    </div>
  </body>
</html>
