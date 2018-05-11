<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1525846743;s:77:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\car\car_info.html";i:1525660218;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
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
          <tr>
            <th>职务</th>
            <th>姓名</th>
            <th>电话</th>
            <th>座机</th>
            <th>微信/QQ</th>
            <th>传真</th></tr>
        </thead>
    <tbody >
            <?php 
            foreach ($data as  $value) {
                echo '<tr><td class="tdata">'.$value['position'].'</td>';
                echo '<td class="tdata">'.$value['name'].'</td>';
                echo '<td class="tdata">'.$value['phone'].'</td>';
                echo '<td class="tdata">'.$value['tel'].'</td>';
                echo '<td class="tdata">'.$value['qq'].'</td>';
                echo '<td class="tdata">'.$value['fax'].'</td></tr>';
               }
            ?>
        </tbody> 
      </table>
    </div>
<script type="text/javascript">

var arr = new Array(3)
arr[0] = "George"
arr[1] = "John"
arr[2] = "Thomas"

console.log(arr )
console.log(arr.push("James"))
console.log(arr.shift())
console.log(arr)

</script>
    
 
  </body>

</html>