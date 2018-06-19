<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1529105623;s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\port\port_add.html";i:1528465243;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1527898250;}*/ ?>
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
        <form class="layui-form" id="portaddform" >
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>所属城市
                </label>
                <div class="layui-input-inline">
                    <select name="province" lay-filter="province">
                        <option value="">请选择省</option>
                    </select>
                </div>
                <div class="layui-input-inline" id='citydiv' style="display: none;">
                    <select name="city" lay-filter="city">
                        <option value="">请选择市</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>港口名称
                </label>
                <div class="layui-input-inline">
<!--                    <input type="text" class="layui-input" placeholder="请输入港口名称"  name="port_name" value="">-->
                    <textarea  required lay-verify="required" placeholder="请输入" class="layui-textarea" name="port_name" value=""></textarea>
                </div>
                <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span>用逗号或者Enter分开，最多一次添加二十个港口
                </div>
            </div>
      
            <div class="layui-col-md10">
                <label class="layui-form-label">
                </label>
                <input type="button" value="确 认" class="layui-btn" id="editbtn" onclick="toAjax()">
            </div>
        </form>
    </div>
    <script>  var addressURL = "<?php echo url('admin/address/town'); ?>"; </script>
    <script type="text/javascript" src="/static/admin/js/address.js"></script>
    <script>
        var url="<?php echo url('admin/port/port_toadd'); ?>";  
        function toAjax(){
            var loading = layer.load(1);
            post_adduser = true;    
                $.ajax({
                    type:'POST',
                    url:url,    
                    data: $("#portaddform").serialize(),
                    dataType:"json",
                    success:function(status){
                        if(status>0){
                            post_adduser = false;
                            layer.close(loading);
                            layer.msg("添加成功", { icon: 6, time: 1000 }, function () {
                            // 获得frame索引
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });
                        }else{
                            post_adduser = false;
                            layer.close(loading);
                            layer.msg("添加失败", { icon: 5 });
                            }
                            },
                        error: function () {
                                post_adduser = false; //AJAX失败也需要将标志标记为可提交状态
                                layer.close(loading);
                                layer.msg("添加失败", { icon: 5 });
                            }
                });
                return false;//只此一句
            }  
    </script>
</body>
</html>