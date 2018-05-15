<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526356196;s:88:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\carshipman\shipman_list.html";i:1526371206;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
        <a href="">车队</a>
        <a>
          <cite>导航元素</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
        <form id="searchform" class="layui-form 
              layui-col-md12 x-so" >
            <input type="text" name="ship_name"  value="<?php echo !empty($searchship)?$searchship : '';; ?>"  placeholder="请输入船公司名字" autocomplete="off" class="layui-input">
          <input type="text" name="port_name"    value="<?php echo !empty($searchport)?$searchport : '';; ?>"   placeholder="请输入港口名字" autocomplete="off" class="layui-input">
          <button class="layui-btn"  lay-submit="" lay-filter="sreach" onclick="search()"><i class="layui-icon">&#xe615;</i></button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
     <button class="layui-btn" onclick="x_admin_show('添加车队','<?php echo url('ShipMan/man_add'); ?>',800,726)"><i class="layui-icon"></i>添加</button>
     <span class="x-right" style="line-height:40px"> 本页有<?php echo $count; ?>条记录</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>船公司名</th>
            <th>所属港口</th>
            <th>姓名</th>
            <th>职位</th>
            <th>负责路线</th>
            <th>电话</th>
            <th>手机</th>
            <th>QQ/微信</th>
            <th>传真</th>
            <th>操作</th></tr>
        </thead>
    <tbody >
       

      <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
   
         <tr >
            <td>
             <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $vo['id']; ?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td class="tdata"><?php echo $vo['id']; ?></td>
            <td><?php echo $vo['ship_short_name']; ?></td>
            <td><?php echo $vo['port_name']; ?></td>
            <td><?php echo $vo['name']; ?></td>
            <td><?php echo $vo['position']; ?></td>
            <td><?php echo $vo['duty_line']; ?></td>
            <td><?php echo $vo['sn_tel']; ?></td>
            <td><?php echo $vo['sn_mobile']; ?></td>
            <td><?php echo $vo['sn_qq']; ?></td>
            <td><?php echo $vo['sn_fax']; ?></td>
            <td class="td-manage">
              <a title="删除" onclick="member_del(this,'<?php echo $vo['id']; ?>') " href="javascript:;">
                <i class="layui-icon">&#xe640;</i> 
              </a>
            </td>
          </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>          
        </tbody> 
      </table>
      <div class="page">
        <div>
           <?php echo $page; ?>
        </div>
      </div>

    </div>
      <div>
          <div>
    <script>
           /*执行搜索车队或者港口*/
           
    function search(){
         $.ajax({
                type:'get',
                url:"<?php echo url('admin/ShipMan/man_list'); ?>",     
                data: $("#searchform").serialize(),
              //  data:{"success":true,"id":"1"} ,
                dataType:"json",
                async:false,
                success:function(data){
                  if(data.status==1){
                    return 1;
                  }else{
                      return 0 ;
                 }
                         
               },   error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log(XMLHttpRequest.status);
                    console.log(XMLHttpRequest.readyState);
                    console.log(textStatus);
                },

        });
        return 1;
    }
        
        
      layui.use('laydate', function(){
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
    function member_del(obj,did){
        layer.confirm('确认要删除吗？',function(index){
            //转成数组形式
            var dataA=new Array()
            dataA[0]=did ;
            var dataArray={id:dataA}
            toajax(dataArray);
            $(obj).parents("tr").remove();
            layer.msg('已删除!',{icon:1,time:1000});
         });
      }

   function delAll (argument) {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            var dataArray={id:data};
            toajax(dataArray);
             layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }


       function toajax (dataArray){
            $.ajax({
                type:'POST',
                url:"<?php echo url('admin/ShipMan/man_del'); ?>",    
                data:dataArray,
                dataType:"json",
                success:function(data){
                    if(data>0){
                     layer.msg('删除成功', {icon: 1});
                     $(".layui-form-checked").not('.header').parents('tr').remove();
                    }else{
                        return 0 ;
                  }
                }
            })
        }
    </script>
 
  </body>

</html>