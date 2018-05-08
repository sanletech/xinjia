<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1525251612;s:79:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Ship\ship_info.html";i:1525241530;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
   <?php 
        for($i=0;$i<count($list);$i++){
           if($i>0 && $i<(count($list)-1)){
             if( $list[$i+1]['shipport_id'] ==$list[$i]['shipport_id']){
            
                echo "<thead><th  align='center' class='layui-bg-red'>".$list[$i]['port_name']."</th>";
                echo '<tr>
                <th>职务</th>
                <th>姓名</th>
                <th>电话</th>
                <th>座机</th>
                <th>微信/QQ</th>
                <th>负责路线</th></tr>
            </thead>';
             }else{echo'<th></th>';}
			 }else{
                echo '<thead>';
                echo "<th  align='center' class='layui-bg-red'>".$list[$i]['port_name']."</th>";
                echo '<tr>
                <th>职务</th>
                <th>姓名</th>
                <th>电话</th>
                <th>座机</th>
                <th>微信/QQ</th>
                <th>负责路线</th></tr>
            </thead>';
             } 
               
                echo '<tbody ><tr>';
                echo '<th>'.$list[$i]['position'].'</th>';
                echo '<th>'.$list[$i]['name'].'</th>';
                echo '<th>'.$list[$i]['sn_mobile'].'</th>';
                echo '<th>'.$list[$i]['sn_tel'].'</th>';
                echo '<th>'.$list[$i]['sn_qq'].'</th>';
                echo '<th>'.$list[$i]['duty_line'].'</th></tr>';
                echo '</tbody>';
        }
            ?>
    </div>

 
  </body>

</html>