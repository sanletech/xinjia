<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526628628;s:81:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\route_edit.html";i:1526975624;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
    <link rel="stylesheet" href="/static/admin/css/route_add.css">
    <form class="layui-form" action="">
      <div class="route layui-row">
        <!-- 航务公司 -->
        <div class="layui-form-item">
          <label class="layui-form-label">船务公司</label>
          <div class="layui-input-block">
              <select name="ship" lay-filter="ship" lay-search>
              <option value="">选择船务公司</option>
              <option value="" selected ="selected">中远海船务</option>
            </select>
          </div>
        </div>
        <!-- 船名 -->
        <div class="layui-form-item">
          <label class="layui-form-label">船名</label>
          <div class="layui-input-block">
            <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入船名" class="layui-input" value="海洋局/tud36">
          </div>
        </div>
        <!-- 船期 -->
        <div class="layui-form-item">
          <label class="layui-form-label">船期</label>
          <div class="layui-input-block">
            <input type="text" name="date" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input" value="2018-07-03">
          </div>
        </div>
        <!-- 截单时间 -->
        <div class="layui-form-item">
          <label class="layui-form-label">截单时间</label>
          <div class="layui-input-block">
            <input type="text" name="date1" id="date1" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input" value="2018-03-06">
          </div>
        </div>
        <!-- 海上时效 -->
        <div class="layui-form-item">
          <label class="layui-form-label">海上时效</label>
          <div class="layui-input-block">
            <select name="modules" lay-verify="required" id="day" lay-search="">
              <option value="">选择天数</option>
            </select>
          </div>
        </div>
        <!-- 20GP -->
        <div class="layui-form-item">
          <label class="layui-form-label">20GP</label>
          <div class="layui-input-block">
            <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="20GP" class="layui-input" value="3000">
          </div>
        </div>
        <!-- 40HQ -->
        <div class="layui-form-item">
          <label class="layui-form-label">40HQ</label>
          <div class="layui-input-block">
            <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="40HQ" class="layui-input" value="5000">
          </div>
        </div>
        <!-- 航线详情 -->
        <div class="gkou">
          <div class="le layui-col-xs2">港口航线</div>
          <div class="rig">
            <div class="layui-col-xs10">
              <div class="layui-col-xs3 inpu">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="港口" class="layui-input" value="南沙">
              </div>
              <div class="layui-col-xs3 inpu">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="港口" class="layui-input" value="天津">
              </div>
              <div class="layui-col-xs3 jia">
                <a onclick="hang();" href="javascript:;">
                  <i class="layui-icon">&#xe654;</i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- 是否推荐 -->
        <div class="layui-form-item">
          <label class="layui-form-label">推荐</label>
          <div class="layui-input-block">
            <input type="radio" name="sex" value="是" title="是">
            <input type="radio" name="sex" value="否" title="否" checked="">
          </div>
        </div>
        <!-- 按钮 -->
        <div class="layui-form-item">
          <div class="layui-input-block an">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">添加</button>
            <button class="layui-btn cancel">取消</button>
          </div>
        </div>
      </div>

    </form>

    <script type="text/javascript">
      //加载 所有的船公司名字简称和相应的id
        var js_ship = '<?php echo $js_ship; ?>';
        js_ship=JSON.parse(js_ship);    
       //加载 所有的港口名字和相应的城市code
        var js_port = '<?php echo $js_port; ?>';
            js_port=JSON.parse(js_port);        
        
        
        
      //取消关闭模态框
      $('.cancel').click(function () {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
      });

      //海上时效天数
      for (let i = 1; i < 31; i++) {
        $('#day').append('<option value="' + i + '">' + i + '天</option>');
      }
      //默认第五天
      $("#day").val(5);

      //航线详情-添加
      function hang() {
        $('.jia').before(
          '<div class="layui-col-xs3 inpu">'
          +'<input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="港口" class="layui-input">'
          +'<a onclick="hang_co(this);" href="javascript:;"><i class="layui-icon">&#x1006;</i></a>'
          +'</div>');
      }
      //航线详情-删除
      function hang_co(zj){
        console.log($(zj).parent().remove());
      }

      layui.use(['form', 'layedit', 'laydate'], function () {
        var form = layui.form
          , layer = layui.layer
          , layedit = layui.layedit
          , laydate = layui.laydate;
         loadship();//选择船公司  
        //日期
        laydate.render({
          elem: '#date'
        });
        laydate.render({
          elem: '#date1'
        });

      });
      
             
        function loadship(){
            alert('aaaaa');
//             //加载 所有的船公司名字简称和相应的id
//            var js_ship = '<?php echo $js_ship; ?>';
//            js_ship=JSON.parse(js_ship);
           
            //  console.log(js_ship['1'].id );
            var ship_length =js_ship.length;
            var shipHtml = '';
            for(var i=0;i<ship_length;i++){
                
                shipHtml += '<option  value="' + js_ship[i].id  +'_'+ js_ship[i].ship_short_name +'">' + js_ship[i].ship_short_name + '</option>';  
            }
            $form.find('select[name=ship]').append(shipHtml);
            form.render();
            form.on('select(ship)', function(data) {
               
               if(data.value!==''){
                var ship =data.value.split('_');
                //  console.log(ship);   
                 var ship_id =ship['0'];
                 var ship_name=ship['1'];
                 //dataArray[ship_id] = ship_name;
                 var mark = document.getElementById('search_ship');
                  
                   if(!document.getElementById(ship_id+'_'+ship_name)){
                         selectPortShip(ship_id,ship_name,mark)
                    }
                }
            });
        }
      
    </script>

  </body>

  </html>