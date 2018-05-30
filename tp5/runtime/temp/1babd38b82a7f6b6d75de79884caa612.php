<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1527577827;s:76:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Car\car_add.html";i:1526436519;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
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
        <form class="layui-form" id="editform" method="post">
            <div class="layui-form-item">
                <label class="layui-form-label">
                <span class="x-red">*</span>所属港口
                </label>
                <div class="layui-input-inline">
                    <select name="province" lay-filter="province" >
                        <option value="">请选择省</option>
                    </select>
                </div>
                <div class="layui-input-inline" id ='citydiv' style="display: none;">
                    <select name="city" lay-filter="city" >
                        <option value="">请选择市</option>
                    </select>
                </div>
                <div class="layui-input-inline" style="display: none;">
                    <select name="area"  lay-filter="area">
                        <option value="">请选择港口</option>
                    </select>
                </div>
            </div>
        <!--  <button id="btn_tag" class="layui-btn layui-btn-normal"  style="display: none;"  onclick="del(this) ;return false">
               <input id ="input_tag" type="hidden"  name="name" value="id"><i id ="i_tag" class="layui-icon">&#xe640;</i> </button>-->
            <div class="layui-form-item" id ="search_port">
            </div> 
        
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>合作船公司
                </label>
                <div class="layui-input-inline">
                    <select name="ship" lay-filter="ship" lay-search>
                        <option value=""  >请选择船公司</option>
                    </select>  
                </div>
            </div>
            <!-- <button id="btn2_tag"  class="layui-btn layui-btn-normal"  style="display: none;"  onclick="del(this) ;return false">船公司名字
                 <input type="hidden"  name="name" value="id"><i id ="i2_tag" class="layui-icon">&#xe640;</i> </button>-->
            <div class="layui-form-item" id ="search_ship">
            </div>  
            
              <div class="layui-form-item">
                <label class="layui-form-label">
                <span class="x-red">*</span>优势路线
                </label>
                <div class="layui-input-inline">
                    <select  name="Twoprovince" lay-filter="Twoprovince" >
                        <option value="">请选择省</option>
                    </select>
                </div>
                <div class="layui-input-inline" style="display: none;">
                    <select  name="Twocity"  lay-filter="Twocity" >
                        <option value="">请选择市</option>
                    </select>
                </div>
            </div>
            <!--  <button id="btn_tag" class="layui-btn layui-btn-normal"  style="display: none;"  onclick="del(this) ;return false">
                <input id ="input_tag" type="hidden"  name="name" value="id"><i id ="i_tag" class="layui-icon">&#xe640;</i> </button>-->
            <div class="layui-form-item" id ="search_city">
            </div> 
            <input type="hidden"  name="id" class="layui-input" value="">
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>车队名字
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="car_name" name="car_name" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>车队地址
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="address" name="address" class="layui-input" value="">
                </div>
            </div> 
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>合作关系
                </label>
                <div class="layui-input-inline">
                    <select id="symbiosis" name="symbiosis">
                        <option value="1"  >中止合作</option>
                        <option value="2"  >暂无合作</option>
                        <option value="3"  >临时合作</option>
                        <option value="4"  >长期合作</option>
                    </select>  
                </div>
            </div> 
           <div class="layui-form-item">
                    <label class="layui-form-label">
                       <span class="x-red">*</span>使用状态
                    </label>
                    <div class="layui-input-inline">
                     <select id="status" name="status">
                        <option value='1'>禁止使用</option>
                        <option value='2'>正常使用</option>
                    </select>  
                    </div>
            </div>
                    <div class="layui-form-item">
                        <label  class="layui-form-label">
                        </label>
                        <input type="button" value="确 认" class="layui-btn" id="editbtn"  onclick="toajax()"> 
                    </div>
             </form>
    </div>
 <script type="text/javascript" src="/static/admin/js/area.js"></script>
 <script>

    //加载 所有的船公司名字简称和相应的id
        var js_ship = '<?php echo $js_ship; ?>';
        js_ship=JSON.parse(js_ship);
       //加载 所有的港口名字和相应的城市code
        var js_port = '<?php echo $js_port; ?>';
            js_port=JSON.parse(js_port);    
        //ajax url生成
       var url="<?php echo url('admin/Car/toAdd'); ?>";
      //修改的车队ID 港口车队cp_id
       var carID='';
       var cpID ='';
  //初始数据
        var areaData = Area;
        var $form;
        var form;
        var $;
        layui.use(['jquery', 'form'], function() {
            $ = layui.jquery;
            form = layui.form;
            $form = $('form');
            loadProvince();  //选择港口
            TwoloadProvince(); //选择优势路线的城市
            loadship();//选择船公司 
           // oldshipPort();//原有的船公司和港口
            
        });     
     
 </script>
 <script type="text/javascript" src="/static/admin/js/car_edit.js"></script>

</body>
</html>

