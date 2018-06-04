<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1527857159;s:87:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Financial\company_form.html";i:1527161014;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">演示</a>
        <a>
          <cite>导航元素</cite>
        </a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);"
        title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i>
      </a>
    </div>
    <div class="x-body">
      <div class="layui-row">
        <form class="layui-form layui-col-md5">
          <div class="layui-form-item">
            <label class="layui-form-label">选择月份：</label>
            <div class="layui-input-block">
              <select name="time">
                <option value="0">请选择月份</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <!-- 公司报表信息 -->
      <table class="layui-table">
        <tbody>
          <tr>
            <td>总金额</td>
            <td>800</td>
          </tr>
          <tr>
            <td>总柜量</td>
            <td>300</td>
          </tr>
          <tr>
            <td>船公司一总柜量</td>
            <td>150</td>
          </tr>
          <tr>
            <td>船公司二总柜量</td>
            <td>63</td>
          </tr>
          <tr>
            <td>船公司三总柜量</td>
            <td>56</td>
          </tr>
          <tr>
            <td>船公司四总柜量</td>
            <td>106</td>
          </tr>
          <tr>
            <td>船公司五总柜量</td>
            <td>130</td>
          </tr>
          <tr>
            <td>船公司六总柜量</td>
            <td>20</td>
          </tr>
          <tr>
            <td>船公司七总柜量</td>
            <td>260</td>
          </tr>
          <tr>
            <td>船公司八总柜量</td>
            <td>310</td>
          </tr>
        </tbody>

      </table>
      <div class="page">
        <div>

        </div>
      </div>

    </div>
    <script>
      layui.use('laydate', function () {
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });
      //表格宽度
      $('td').css('width', '50%');
      //选择日期
      var arry = ['2018年5月','2018年6月'];
      console.log($('select[name=time]')[0]);
      for (let i = 0; i < arry.length; i++) {
        $('select[name=time]').append('<option value="'+arry[i]+'">'+arry[i]+'</option>');
      }
    </script>

  
  </body>

  </html>