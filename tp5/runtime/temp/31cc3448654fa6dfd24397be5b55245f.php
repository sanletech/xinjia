<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1528888058;s:84:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\port\shiproute_list.html";i:1528966012;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
                    <input type="text" name="sl_start" value="<?php echo !empty($sl_start)?$sl_start : ''; ?>"  placeholder="请输入起点港口" autocomplete="off" class="layui-input">
                    <input type="text" name="sl_end" value="<?php echo !empty($sl_end)?$sl_end : ''; ?>"  placeholder="请输入终点港口" autocomplete="off" class="layui-input">
                    <button class="layui-btn" lay-submit="" lay-filter="sreach">
                        <i class="layui-icon">&#xe615;</i>
                    </button>
                </form>
            </div>
            <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()">
                    <i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url("Port/shiproute_add"); ?>',850,400)">
                    <i class="layui-icon"></i>添加</button>
                <!-- <span class="x-right" style="line-height:40px">总共有<{10*$page}>条记录</span>-->
            </xblock>
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>
                            <div class="layui-unselect header layui-form-checkbox" lay-skin="primary">
                                <i class="layui-icon">&#xe605;</i>
                            </div>
                        </th>
                        <th>ID</th>
                        <th>航线/起-终</th>
                        <th>航线详情</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td>
                            <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $vo['id']; ?>'>
                                <i class="layui-icon">&#xe605;</i>
                            </div>
                        </td>
                        <td><?php echo $vo['id']; ?></td>
                        <td class="tdata"><?php echo $vo['s_port_name']; ?>/<?php echo $vo['e_port_name']; ?></td>
                        <td><?php echo strtr($vo['port_name'],',','>')?></td>
                        <td><?php echo date("Y-m-d",$vo['mtime']); ?></td>
                        <td class="td-manage">
                            <a title="删除" onclick="del(this,'<?php echo $vo['id']; ?>')" href="javascript:;">
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
<script>
         /*用户-删除*/
    function  del(obj,did){
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

    function delAll () {
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
                url:"<?php echo url('admin/Port/shiproute_del'); ?>",    
                data:dataArray,
                dataType:"json",
                success:function(data){
                    if(data.status==1){
                      return 1;
                    }else{
                        return 0 ;
                  }
                }
            })
        };
</script>
    </body>
    </html>