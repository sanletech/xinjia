<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526547553;s:82:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\price_route.html";i:1526040607;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
        <form class="layui-form layui-col-md12 x-so">
          <input type="text" name="username" placeholder="请输入港口" autocomplete="off" class="layui-input">
          <button class="layui-btn" lay-submit="" lay-filter="sreach">
            <i class="layui-icon">&#xe615;</i>
          </button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()">
          <i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="x_admin_show('添加','<?php echo url('Price/route_add'); ?>',700,500)" href="javascript:;">
          <i class="layui-icon"></i>添加</button>
        <!-- <span class="x-right" style="line-height:40px">总共有<{10*$page}>条记录</span>-->
      </xblock>

      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>航务公司</th>
            <th>港口航线</th>
            <th>20GP</th>
            <th>40HQ</th>
            <th>船期</th>
            <th>截单时间</th>
            <th>船名</th>
            <th>海上时效</th>          
            <th>预计到港时间</th>
            <th>预计送货日期</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody >
          <tr >
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='1'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td class="tdata">1</td>
            <td>中远海船务</td>
            <td>南沙-天津</td>
            <td>￥3000</td>
            <td>￥5000</td> 
            <td>2018-03-06</td>
            <td>2018-3-10</td>
            <td>海洋局/tud36</td>
            <td>5天</td>
            <td>2018-3-5</td>
            <td>2018-3-17</td>
            <td class="td-manage">
              <a title="编辑"  onclick="x_admin_show('修改','<?php echo url('Price/route_edit'); ?>',700,500)" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>
              <a title="删除" onclick="" href="javascript:;">
                <i class="layui-icon">&#xe640;</i>
              </a>
            </td>
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


      /*用户-删除*/
      function member_del(obj, did) {
        layer.confirm('确认要删除吗？', function (index) {
          //转成数组形式
          var dataA = new Array()
          dataA[0] = did;
          var dataArray = { id: dataA }
          toajax(dataArray);
          $(obj).parents("tr").remove();
          layer.msg('已删除!', { icon: 1, time: 1000 });
        });
      }

      function delAll(argument) {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？' + data, function (index) {
          //捉到所有被选中的，发异步进行删除
          var dataArray = { id: data };
          toajax(dataArray);
          layer.msg('删除成功', { icon: 1 });
          $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }


      function toajax(dataArray) {
        $.ajax({
          type: 'POST',
          url: "<?php echo url('admin/member/toDel'); ?>",
          data: dataArray,
          dataType: "json",
          success: function (data) {
            if (data.status == 1) {
              return 1;
            } else {
              return 0;
            }
          }
        })
      }
    </script>

  </body>

  </html>