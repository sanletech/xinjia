<<<<<<< HEAD
<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526628628;s:81:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\route_edit.html";i:1527141454;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
=======
<<<<<<< HEAD
<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526981949;s:81:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\route_edit.html";i:1526974668;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
=======
<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1526628628;s:81:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Price\route_edit.html";i:1526975624;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
>>>>>>> 5e6c00c694aaca5ced28534dfd89ea694e91c94b
>>>>>>> 1d45d2d9541146d6179c90d1932d29a1d83c6d7a
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
        <input type="hidden" name="sl_id" value ="<?php echo $data['sl_id']; ?>"/>
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
                <input type="text" id="car_name" name="boat_name" class="layui-input" value="<?php echo $data['boat_name']; ?>">
            </div>
        </div>
        <!-- 船期 -->
        <div class="layui-form-item">
            <label class="layui-form-label">船期</label>
            <div class="layui-input-inline">
                <input type="text" name="shipping_date" id="date" value="<?php echo date("y-m-d",$data['shipping_date']); ?>" lay-verify="date" placeholder="YY-MM-DD" autocomplete="off" class="layui-input">
            </div>
        </div>
        <!-- 截单时间 -->
        <div class="layui-form-item">
            <label class="layui-form-label">截单时间</label>
            <div class="layui-input-inline">
                <input type="text" name="cutoff_date" id="date1" value="<?php echo date("y-m-d",$data['cutoff_date']); ?>" lay-verify="date" placeholder="YY-MM-DD" autocomplete="off" class="layui-input">
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
        <div class="layui-form-item ">
            <label class="layui-form-label">港口航线</label>
            <div class="layui-input-inline" >
                <select name ="port"  lay-filter="port" lay-search>
                    <option value="">请选择港口</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item" id ="search_port">
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
                <button  type ="button" class="layui-btn" onclick="toajax()">添加</button>
                <button class="layui-btn cancel">取消</button>
            </div>
        </div>
      </div>

    </form>

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
        
        layui.use(['jquery', 'form'], function() {
            $ = layui.jquery;
            form = layui.form;
            $form = $('form');
            loadship();//选择船公司
            loadPort();//选择港口航线
            loadoldPort();//展示原有的港口航线
        });     
        
        //加载 所有的船公司名字简称和相应的id
        var js_ship = '<?php echo $js_ship; ?>';
        js_ship=JSON.parse(js_ship);    
        //加载 所有的港口名字和相应的城市code
        var js_port = '<?php echo $js_port; ?>';
            js_port=JSON.parse(js_port);        
        
        var url="<?php echo url('admin/Price/route_toedit'); ?>";
        
        function loadship(){
//             //加载 所有的船公司名字简称和相应的id
//            var js_ship = '<?php echo $js_ship; ?>';
//            js_ship=JSON.parse(js_ship);
            //  console.log(js_ship['1'].id );
            var ship_length =js_ship.length;
            var shipHtml = '';
            for(var i=0;i<ship_length;i++){
               if(js_ship[i].id == ship_id){
                shipHtml += '<option  value="' + js_ship[i].id  +'_'+ js_ship[i].ship_short_name +'" selected="selected">' + js_ship[i].ship_short_name + '</option>';  
                }else{
                shipHtml += '<option  value="' + js_ship[i].id  +'_'+ js_ship[i].ship_short_name +'">' + js_ship[i].ship_short_name + '</option>';  
            }  }
            $form.find('select[name=ship]').append(shipHtml);
            form.render();
            form.on('select(ship)', function(data) {
            });
        }
        
        function loadPort(){
//            //加载 所有的港口名字和相应的城市code
//        var js_port = '<?php echo $js_port; ?>';
//            js_port=JSON.parse(js_port);
              // console.log(js_port);
            var port_length =js_port.length;
            var portHtml = '';
            for(var i=0;i<port_length;i++){
                portHtml += '<option  value="' + js_port[i].id +'_'+ js_port[i].port_name + '">' + js_port[i].port_name + '</option>';  
            }
            $form.find('select[name=port]').append(portHtml);
            form.render();
            form.on('select(port)', function(data) {
                 var port =data.value.split('_');
                 var port_id =port['0'];
                 var port_name=port['1'];
                
                var mark = document.getElementById('search_port');
                 //已经存的港口就不执行
                if(! document.getElementById(port_id+'_'+port_name)  ){
                        selectPortShip(port_id,port_name,mark);
                } 
            } ) 
        }
        
        function loadoldPort(){
            var mark = document.getElementById('search_port')
            var middle_port ='<?php echo json_encode($middle_data); ?>'
                middle_port = JSON.parse(middle_port); 
            var start_port = "<?php echo $data['start_port'] ?>";
            var over_port = "<?php echo $data['over_port'] ?>";
            var sl_start = "<?php echo $data['sl_start'] ?>";
            var sl_over = "<?php echo $data['sl_over'] ?>";
            selectPortShip(sl_start,start_port,mark);
          //  console.log(middle_port); 
            for(var i =0;i<middle_port.length;i++){
                selectPortShip(middle_port[i]['middle_port'],middle_port[i]['port_name'],mark);
            }
            selectPortShip(sl_over,over_port,mark);
        }
        
        //根据不同的参数来创建对应port ship button块
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
                ipt.name='port_name[]';
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
        
        function toajax (){
            var loading = layer.load(1);
            post_adduser = true;    
                $.ajax({
                    type:'POST',
                    url:url,    
                    data: $("#routeaddform").serialize(),
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