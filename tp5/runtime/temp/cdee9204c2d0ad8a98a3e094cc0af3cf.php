<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1528888058;s:81:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\route_edit.html";i:1529395116;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
     <form class="layui-form" id="routeaddform">
        <input type="hidden" name="id" value ="<?php echo $data['id']; ?>"/>
    <div class="route layui-row">
        <!-- 航务公司 -->
        <div class="layui-form-item">
            <label class="layui-form-label">船务公司</label>
            <div class="layui-input-inline">
                <select name="ship" lay-filter="ship" lay-search>
                    <option value="" >请选择船公司</option>
                </select>  
            </div>
        </div>
        <!-- 船名 -->
        <div class="layui-form-item">
            <label class="layui-form-label">船名</label>
            <div class="layui-input-inline">
                <select name="boat_code" lay-filter="boat" lay-search>
                    <option value="" >请选择船名</option>
                </select>  
            </div>
        </div>
        <!-- 船期 -->
        <div class="layui-form-item">
            <label class="layui-form-label">船期</label>
            <div class="layui-input-inline">
                <input type="text" name="shipping_date" id="date" value="<?php echo date("Y-m-d",$data['shipping_date']); ?>" lay-verify="date" placeholder="YY-MM-DD" autocomplete="off" class="layui-input">
            </div>
        </div>
        <!-- 截单时间 -->
        <div class="layui-form-item">
            <label class="layui-form-label">截单时间</label>
            <div class="layui-input-inline">
                <input type="text" name="cutoff_date" id="date1" value="<?php echo date("Y-m-d",$data['cutoff_date']); ?>" lay-verify="date" placeholder="YY-MM-DD" autocomplete="off" class="layui-input">
            </div>
        </div>
        <!-- 海上时效 -->
        <div class="layui-form-item">
            <label class="layui-form-label">海上时效</label>
            <div class="layui-input-inline">
                <select name="sea_limitation"  id="day" >
                    <option value="">选择天数</option>
                </select>
            </div>
        </div>
        <!-- 20GP -->
        <div class="layui-form-item">
            <label class="layui-form-label">20GP</label>
            <div class="layui-input-inline">
                <input type="text" name="price_20GP" value="<?php echo $data['price_20GP']; ?>" lay-verify="title" autocomplete="off" placeholder="20GP" class="layui-input">
            </div>
        </div>
        <!-- 40HQ -->
        <div class="layui-form-item">
            <label class="layui-form-label">40HQ</label>
            <div class="layui-input-inline">
                <input type="text" name="price_40HQ" value="<?php echo $data['price_40HQ']; ?>" lay-verify="title" autocomplete="off" placeholder="40HQ" class="layui-input">
            </div>
        </div>
        <!-- 航线详情 -->
        <div class="layui-form-item " id ="oldline">
            <label class="layui-form-label">港口航线</label>
            <div class="layui-input-inline" style="width: 450px">
                 <input type="text" name="route_id"  value="<?php echo $data['route_id']; ?>"   class="layui-input" >
                <input type="text" name="route_line"  value="起点:<?php echo $data['s_port_name']; ?>-<?php echo $data['port_name']; ?>- 终点:<?php echo $data['e_port_name']; ?>"   class="layui-input" >
            </div>
  
        </div>
        <!-- 港口航线 -->
        <div class="layui-form-item">
            <div class="layui-inline">
              <label class="layui-form-label" id="newline">重新选择</label>
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
                    <select name="port"  lay-filter="port">
                        <option value="">请选择起点/终点港口</option>
                    </select>
                </div>
              <div class="layui-form-item" id ="search_port">
              </div>
              <div class="layui-input-inline" style="width: 82px;">
                    <button type ="button" class="layui-btn" onclick="routeSelect()">确认</button>
              </div>
            </div></div>
         
        <!-- 航线详情 -->
        <div class="layui-form-item ">
            <label class="layui-form-label">航线详情</label>
            <div class="layui-input-inline" >
                <select name ="route"  lay-filter="route" lay-search>
                    <option value="">请选择航线</option>
                </select>
            </div>
        </div>
        

        <!-- 是否推荐 -->
        <div class="layui-form-item">
            <label class="layui-form-label">推荐</label>
            <div class="layui-input-block">
                <input type="radio" name="generalize" value="1" <?php if($data['generalize']==1){echo 'checked="checked"';};?> title="是">
                <input type="radio" name="generalize" value="0"  <?php if($data['generalize']==0){echo 'checked="checked"';};?> title="否" >
            </div>
        </div>
        <!-- 按钮 -->
        <div class="layui-form-item">
            <div class="layui-input-block an">
                <button  type ="button" class="layui-btn" onclick="toajax()">修改</button>
                <button class="layui-btn cancel">取消</button>
            </div>
        </div>
    </div>
    </form>
<script type="text/javascript" src="/static/admin/js/port.js"></script>
<script type="text/javascript" src="/static/admin/js/ship_port.js"></script>
<script type="text/javascript" src="/static/admin/js/ship_boat.js"></script>
    <script type="text/javascript">
        //取消关闭模态框
        $('.cancel').click(function () {
          var index = parent.layer.getFrameIndex(window.name);
          parent.layer.close(index);
        });

        //海上时效天数
        for (let i = 1; i < 31; i++) {
          $('#day').append('<option value="' + i + '">' + i + '天</option>');
        }
        
          var day = "<?php echo $data['sea_limitation']?>";
          var ship_id = "<?php echo $data['ship_id']?>";
        
        //默认第五天
      
        $("#day").val(day);

        layui.use(['form', 'layedit', 'laydate'], function () {
            var form = layui.form
            , layer = layui.layer
            , layedit = layui.layedit
            , laydate = layui.laydate;
            //日期
            laydate.render({
              elem: '#date'
            });
            laydate.render({
              elem: '#date1'
            });
          });
        
                
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
            loadship();//原有船公司的港口和城市
            
        });     
        
        //加载 所有的船公司名字简称和相应boat的id
        var js_ship_boat = JS_BOAT;
        //加载 所有的港口名字和相应的城市code
        var js_port = JS_PORT;
        var url="<?php echo url('admin/Price/route_toedit'); ?>";
        var ship_id ="<?php echo $data['ship_id']; ?>";
        var boat_code ="<?php echo $data['boat_code']; ?>";
        //加载船公司
        function loadship(){
//             //加载 所有的船公司名字简称和相应的id
            var ship_length =js_ship_boat.length;
            var shipHtml = ''; 
            for(var i=0;i<ship_length;i++){
                if(js_ship_boat[i].ship_id == ship_id){
                 
                shipHtml += '<option  value="' + js_ship_boat[i].ship_id  +'_'+ js_ship_boat[i].boat_list.length+'_' + i + '"  selected="selected">' + js_ship_boat[i].ship_name + '</option>';  
                }else{
                shipHtml += '<option  value="' + js_ship_boat[i].ship_id  +'_'+ js_ship_boat[i].boat_list.length+ '_' + i + '">' + js_ship_boat[i].ship_name + '</option>';  
            }}
            $form.find('select[name=ship]').append(shipHtml);
            form.render();
            form.on('select(ship)', function(data) {
                var value = data.value;
                var d = value.split('_');
                var code = d[0];
                var count = d[1];
                var index = d[2];
                if (count > 0) {
                    loadBoat(js_ship_boat[index].boat_list);
                }else {
                    $form.find('select[name=boat]').parent().hide();
                }
            });
        }
        //加载船公司对应boat
        function loadBoat(ship){
            var areaHtml = '';
            if(typeof(ship) !== 'undefined' && ship!=0){
                var ship_length =ship.length;
                for(var i=0;i<ship_length;i++){
                    if(ship[i].boat_code == boat_code){
                        areaHtml += '<option  value="' + ship[i].boat_code +'_'+ ship[i].id+ '" selected="selected">' + ship[i].boat_name + '</option>';    
                    }else{
                    areaHtml += '<option  value="' + ship[i].boat_code +'_'+ ship[i].id+ '">' + ship[i].boat_name + '</option>';  
                }}
            }else{
                areaHtml = '<option  value="">此船公司无对应船舶</option>'; 
            }
            $form.find('select[name=boat_code]').html(areaHtml).parent().show();
            form.render();
            form.on('select(boat)', function(data) {
            } ) 
        }
    
        
   //加载省数据
        function loadProvince() {
            var proHtml = '';
            for (var i = 0; i < areaData.length; i++) {
                proHtml += '<option value="' + areaData[i].provinceCode + '_' + areaData[i].mallCityList.length + '_' + i + '">' + areaData[i].provinceName + '</option>';
            }
            //初始化省数据
            $form.find('select[name=province]').append(proHtml);
            form.render();
            form.on('select(province)', function(data) {
                $form.find('select[name=area]').html('<option value="">请选择县/区</option>').parent().hide();
                var value = data.value;
                var d = value.split('_');
                var code = d[0];
                var count = d[1];
                var index = d[2];
                if (count > 0) {
                    loadCity(areaData[index].mallCityList);
                } else {
                    $form.find('select[name=city]').parent().hide();
                }
            });
        }
   
    //加载市数据
        function loadCity(citys) {
            var cityHtml = '';
            for (var i = 0; i < citys.length; i++) {
                cityHtml += '<option value="' + citys[i].cityCode + '_' + citys[i].mallAreaList.length + '_' + i + '">' + citys[i].cityName + '</option>';
            }
            $form.find('select[name=city]').html(cityHtml).parent().show();
            form.render();
            form.on('select(city)', function(data) {
                var value = data.value;
                var d = value.split('_');
                var code = d[0];
                var count = d[1];
                var index = d[2];
                if (count > 0) {
                    //将城市Id 传给港口loadPort()
                    loadPort(citys[index].cityCode);
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
                 var port =data.value.split('_');
                 var port_id =port['0'];
                 var port_name=port['1'];
                var mark = document.getElementById('search_port');
                 //已经存的港口就不执行
                if(mark.childNodes.length<3){
                    if(! document.getElementById(port_id+'_'+port_name)  ){
                            selectPortShip(port_id,port_name,mark);
                    } 
                }
            } ) 
        }
        //加载对应起点 港口和终点港口的航线
        function loadShipRoute(route_arr){
            
            var areaHtml = '';
            if(route_arr.length!=0){
                var j=0;
                for(var i in route_arr){
                    j++;
                    areaHtml += '<option  value="' +i+'">路线'+j+'\-'+ route_arr[i]+ '</option>';  
                }
            }else{
                    areaHtml = '<option  value="">无此航线</option>'; 
            }
            $form.find('select[name=route]').html(areaHtml).parent().show();
            form.render();
            form.on('select(port)', function(data) {
            } ) 
        
        }
        
        function  selectPortShip(port_id,port_name,mark){
            var btn =document.createElement('button');
                btn.className="layui-btn layui-btn-normal";
                btn.style="display: inline\;" ;
                btn.innerHTML=port_name;
                btn.setAttribute('id',(port_id+'_'+port_name))
                btn.addEventListener("click",function() {
                    del(this);
                    return false;
                });
            var ipt= document.createElement('input');
                ipt.type="hidden";
                ipt.name='port_code[]';
                ipt.value=port_id;
            var  i_tag=document.createElement('i');
                 i_tag.className="layui-icon";
                 i_tag.innerHTML="&#xe640\;";
            btn.appendChild(ipt);   
            btn.appendChild(i_tag);
            mark.appendChild(btn);
         }
         
        function del(obj){
            obj.setAttribute('style','display: none;');
           // 删除button的子节点
            var childs = obj.childNodes; 
                for(var i = childs.length - 1; i >= 0; i--) { 
                    obj.removeChild(childs[i]); 
                }
                //删除本身button
                obj.parentNode.removeChild(obj);
         }
        function routeSelect(){
            //删除旧的数据 同时改名字
           var obj = document.getElementById("oldline");
           del(obj);
           $("#newline").html('港口航线');
            var sl_start = $("input[name='port_code[]']:eq(0)").val();
            var sl_end  = $("input[name='port_code[]']:eq(1)").val();
             var data ={'sl_start':sl_start,'sl_end':sl_end};
                    $.ajax({
                    type:'POST',
                    url:"<?php echo url('admin/Price/route_select'); ?>",
                    data: data,
                    dataType:"json",
                    success:function(data){
                        loadShipRoute(data); 
                    }
                });
       
        }
         
         
        function toajax (){
                var loading = layer.load(1);
                post_adduser = true;    
                $.ajax({
                    type:'POST',
                    url:url,
                    data:$("#routeaddform").serialize(),
                    dataType:"json",
                    success:function(status){
                        if(status>0){
                            post_adduser = false;
                            layer.close(loading);
                            layer.msg("修改成功", { icon: 6, time: 1000 }, function () {
                            // 获得frame索引
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });
                        }else{
                            post_adduser = false;
                            layer.close(loading);
                            layer.msg("修改失败", { icon: 5 });
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