<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:71:"E:\xampp\htdocs\tp5\public/../application/admin\view\public\middle.html";i:1523172899;s:74:"E:\xampp\htdocs\tp5\public/../application/admin\view\contact\car_list.html";i:1523268289;s:61:"E:\xampp\htdocs\tp5\application\admin\view\public\header.html";i:1523271793;}*/ ?>
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
        
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>

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
          <h5>船务公司对应的代码</h5>
          <ol >
              <li>1:中远海航 </li>  
              <li>1:中远海航 </li>
              <li>1:中远海航 </li>
              <li>1:中远海航 </li>
              <li>1:中远海航 </li>
              <li>1:中远海航 </li>
              <li>1:中远海航 </li>
          </ol>       
        <form id="searchform" class="layui-form layui-col-md12 x-so" >
          <input type="text" name="car_name"  placeholder="请输入车队名字" autocomplete="off" class="layui-input">
          <input type="text" name="port"  placeholder="请输入港口名字" autocomplete="off" class="layui-input">
          <button class="layui-btn"  lay-submit="" lay-filter="sreach" onclick="search()"><i class="layui-icon">&#xe615;</i></button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
     <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
     <span class="x-right" style="line-height:40px">总共有<?php echo $count; ?>条记录</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>负责港口</th>
            <th>协议船务</th>
            <th>车队名</th>
            <th>老板</th>
            <th>财务</th>
            <th>调度</th>
            <th>跟单</th>
            <th>公司地址</th>
            <th>公司名称</th>
            <th>状态</th>
            <th>合作关系</th>
            <th>操作</th></tr>
        </thead>
    <tbody >
      <?php if(is_array($carlist) || $carlist instanceof \think\Collection || $carlist instanceof \think\Paginator): $i = 0; $__LIST__ = $carlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;  //将status(1正常0禁用) 和 symbiosis(1长期合作2临时合作3无合作) 的状态转成汉字
                $vo['status']==1? $vo['status']='正常':$vo['status']='禁用';
                if($vo['symbiosis']==1){
                    $vo['symbiosis']='长期合作'; 
                }elseif($vo['symbiosis']==2)
                {
                    $vo['symbiosis']='临时合作'; 
                }else{
                    $vo['symbiosis']='暂无合作'; 
                }
        ?>
         <tr >
            <td>
             <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $vo['id']; ?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td class="tdata"><?php echo $vo['id']; ?></td>
            <td><?php echo $vo['port']; ?></td>
            <td><?php echo $vo['ship_id']; ?></td>
            <td><?php echo $vo['car_name']; ?></td>
            <td><?php echo $vo['boss']; ?></td>
            <td><?php echo $vo['finance']; ?></td>
            <td><?php echo $vo['car_plan']; ?></td>
            <td><?php echo $vo['order_follow']; ?></td>
            <td><?php echo $vo['address']; ?></td>
            <td><?php echo $vo['company_name']; ?></td>
             <td class="td-status">
              <span class="layui-btn layui-btn-normal layui-btn-mini"><?php echo $vo['status']; ?></span></td>
            <td><?php echo $vo['symbiosis']; ?></td>
            <td class="td-manage">
              <a title="编辑"  onclick="x_admin_show('编辑','<?php echo url('Contact/car_edit'); ?>?id=<?php echo $vo['id']; ?>',600,400)" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>
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
           <?php echo $carlist->render(); ?>
        </div>
      </div>

    </div>
    <script>
           /*执行搜索车队或者港口*/
    function search(){
         $.ajax({
                type:'post',
                url:"<?php echo url('admin/Contact/search'); ?>",     
                data:$("#searchform").serialize(),
                dataType:"json",
                success:function(data){
                    alert("1111");
                  if(data.status==1){
                    return 1;
                  }else{
                      return 0 ;
                 }
               }
        })
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
                url:"<?php echo url('admin/member/toDel'); ?>",    
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
        }
    </script>
 
  </body>

</html>