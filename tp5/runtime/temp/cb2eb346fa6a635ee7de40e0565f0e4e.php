<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\trailer_add.html";i:1530277776;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1527898250;}*/ ?>
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
<!--    <link rel="stylesheet" href="/static/admin/css/trailer_add.css">-->
    <form class="layui-form" id="trailerform" >
      <div class="trailer layui-row">
        <!-- 选择港口 -->
        <div class="layui-form-item">
          <label class="layui-form-label">港口:</label>
              <div class="layui-input-inline">
                    <select name="province1" lay-filter="province1" >
                        <option value="">请选择省</option>
                    </select>
                </div>
                <div class="layui-input-inline" id ='citydiv' style="display: none;">
                    <select name="city1" lay-filter="city1" >
                        <option value="">请选择市</option>
                    </select>
                </div>
                <div class="layui-input-inline" style="display: none;">
                    <select name="port"  lay-filter="port">
                        <option value="">请选择港口</option>
                    </select>
                </div>
        </div>
        <!-- 下拉选择框地址 -->
            <div class="layui-form-item">
                <label class="layui-form-label">
                <span class="x-red">*</span>请选择地址
                </label>
                <div class="layui-input-inline ">
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
                        <option value="">请选择县/区</option>
                    </select>
                </div>
                <div class="layui-input-inline" style="display: none;">
                    <select name="town"  lay-filter="town">
                        <option value="">请选择镇/街道</option>
                    </select>
                </div>
            </div>

        <!-- 收货 -->
        <div class="layui-col-xs5">
          <div class="grid-demo grid-demo-bg1">
            <div class="layui-form-item">
              <label class="layui-form-label">装货20GP:</label>
              <div class="layui-input-block">
                <input type="text" name="load[price_20GP]" lay-verify="title" autocomplete="off" placeholder="请输入价格" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">装货40HQ:</label>
              <div class="layui-input-block">
                <input type="text" name="load[price_40HQ]" lay-verify="title" autocomplete="off" placeholder="请输入价格" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">装货车队名:</label>
                <div class="layui-input-inline" >
                <select name ="car_load"  lay-filter="car" lay-search>
                    <option value="">请选装货车队</option>
                </select>
                </div>
            </div>
          </div>
        </div>
        <!-- 中间线 -->
        <div class="layui-col-xs2 xian"></div>
        <!-- 送货 -->
        <div class="layui-col-xs5">
          <div class="grid-demo">
            <div class="grid-demo grid-demo-bg1">
              <div class="layui-form-item">
                <label class="layui-form-label">送货20GP:</label>
                <div class="layui-input-block">
                  <input type="text" name="send[price_20GP]" lay-verify="title" autocomplete="off" placeholder="请输入价格" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">送货40HQ:</label>
                <div class="layui-input-block">
                  <input type="text" name="send[price_40HQ]" lay-verify="title" autocomplete="off" placeholder="请输入价格" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">送货车队名:</label>
                <div class="layui-input-inline" >
                    <select name ="car_send"  lay-filter="car" lay-search>
                        <option value="">请选装货车队</option>
                    </select>
                </div>
              </div>
            </div>
          </div>
        </div>

       <div class="layui-form-item layui-col-xs12 niu">
          <div class="layui-col-md1 layui-col-md-offset4">
            <div class="grid-demo grid-demo-bg1">
              <button class="layui-btn" type="button"  onclick="trailerAjax()">添加</button>
            </div>
          </div>
          <div class="layui-col-md1 layui-col-md-offset2">
            <div class="grid-demo">
              <button class="layui-btn cancel">取消</button>
            </div>
          </div>
        </div>
    </form>
    <script>  var addressURL = "<?php echo url('admin/address/town'); ?>"; </script>
    <script type="text/javascript" src="/static/admin/js/address.js"></script>
    <script type="text/javascript" src="/static/admin/js/port.js"></script>
    <script type="text/javascript">
      $('.cancel').click(function () {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
      });
      
       //加载 所有的车公司名字简称和相应的id
        var js_car = '<?php echo $js_car; ?>';
            js_car=JSON.parse(js_car);    
        //加载 所有的港口名字和相应的城市code
        var js_port = JS_PORT;
         
        
        var url="<?php echo url('admin/price/trailer_toadd'); ?>";    
                
        layui.use(['jquery', 'form'], function() {
            $ = layui.jquery;
            form = layui.form;
            $form = $('form');
            loadProvince1();
            loadCar();
        });  
        
           //加载省数据
        function loadProvince1() {
            var proHtml = '';
            for (var i = 0; i < areaData.length; i++) {
                proHtml += '<option value="' + areaData[i].provinceCode + '_' + areaData[i].mallCityList.length + '_' + i + '">' + areaData[i].provinceName + '</option>';
            }
            //初始化省数据
            $form.find('select[name=province1]').append(proHtml);
            form.render();
            form.on('select(province1)', function(data) {
                $form.find('select[name=area1]').html('<option value="">请选择县/区</option>').parent().hide();
                var value = data.value;
                var d = value.split('_');
                var code = d[0];
                var count = d[1];
                var index = d[2];
                if (count > 0) {
                    loadCity1(areaData[index].mallCityList);
                } else {
                    $form.find('select[name=city1]').parent().hide();
                }
            });
        }
   
    //加载市数据
        function loadCity1(citys) {
            var cityHtml = '';
            for (var i = 0; i < citys.length; i++) {
                cityHtml += '<option value="' + citys[i].cityCode + '_' + citys[i].mallAreaList.length + '_' + i + '">' + citys[i].cityName + '</option>';
            }
            $form.find('select[name=city1]').html(cityHtml).parent().show();
            form.render();
            form.on('select(city1)', function(data) {
                var value = data.value;
                var d = value.split('_');
                var code = d[0];
                var count = d[1];
                var index = d[2];
                if (count > 0) {
                    //将城市Id 传给港口loadPort()
                    //console.log(citys[index].cityCode);
                    loadPort(citys[index].cityCode);
                  // loadArea(citys[index].mallAreaList);
                } else {
                    $form.find('select[name=port]').parent().hide();
                }
            });
        }
     
        //加载对应城市的港口，并显示已经选中了
        function loadPort(CityCode){
            var port_arr =  js_port[CityCode];
            var areaHtml = '';
            if(typeof(port_arr) !== 'undefined' && port_arr!=0){
                var port_length =port_arr.length;
                for(var i=0;i<port_length;i++){
                    areaHtml += '<option  value="' + port_arr[i].id +'_'+ port_arr[i].port_name + '">' + port_arr[i].port_name + '</option>';  
                }
            }else{
                    areaHtml = '<option  value="">此城市无港口</option>'; 
            }
            $form.find('select[name=port]').html(areaHtml).parent().show();
            form.render();
            
            form.on('select(port)', function(data) {
            } ) 
        }
      
        function loadCar(){
            //加载 所有的车队名字和相应的车队id
            var car_length =js_car.length;
            var carHtml = '';
            for(var i=0;i<car_length;i++){
                carHtml += '<option  value="' + js_car[i].id +'_'+ js_car[i].car_name + '">' + js_car[i].car_name + '</option>';  
            }
            $form.find('select[name=car_load]').append(carHtml);
            $form.find('select[name=car_send]').append(carHtml);
            form.render();
            form.on('select(car)', function(data) {
            } ) 
        }
        
        function  trailerAjax(){
            var loading = layer.load(1);
            post_adduser = true;    
                $.ajax({
                    type:'POST',
                    url:url,    
                    data: $("#trailerform").serialize(),
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