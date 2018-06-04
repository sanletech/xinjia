<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1527857159;s:79:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Ship\ship_edit.html";i:1526634010;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1524122628;}*/ ?>
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
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <input type="hidden" name="id" class="layui-input" value="<?php echo $vo['id']; ?>">
            <input type="hidden" name="ship_id" class="layui-input" value="<?php echo $vo['ship_id']; ?>">
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>船公司名缩写
                </label>
                <div class="layui-input-inline">
                    <input type="text" id ="ship_short_name"  name="ship_short_name" class="layui-input" value="<?php echo $vo['ship_short_name']; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>船公司名全称
                </label>
                <div class="layui-input-inline">
                    <input type="text"id ="ship_name" name="ship_name" class="layui-input" value="<?php echo $vo['ship_name']; ?>">
                </div>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            
            <div class="layui-form-item">
                <label class="layui-form-label">
                <span class="x-red">*</span>业务港口
                </label>
                <div class="layui-input-inline">
                    <select name="province" lay-filter="province" >
                        <option value="">请选择港口</option>
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
                <span class="x-red">*</span>所属城市
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
            
                    <div class="layui-form-item">
                        <label  class="layui-form-label">
                        </label>
                        <input type="button" value="确 认" class="layui-btn" id="editbtn"  onclick="toajax()"> 
                    </div>
             </form>
        
    </div>
    <?php $port_arr =$list['0']['port'];
          $ship_arr =$list['0']['city'];    
    ?>
 <script type="text/javascript" src="/static/admin/js/area.js"></script>
 <script>
      
    // //展示原有的船公司的  城市和港口
        var port= '<?php echo json_encode($port_arr); ?>';
        port_arr = JSON.parse(port); 
        
        var city= '<?php echo json_encode($ship_arr); ?>';
        city_arr= JSON.parse(city); 
    
        var SPC_ID ='<?php echo $vo["id"];  ?>';
        var ship_ID ='<?php echo $vo["ship_id"];  ?>'
      
//       //加载 所有的港口名字和相应的城市code
        var js_port = '<?php echo $js_port; ?>';
            js_port=JSON.parse(js_port);    
        //ajax url生成
       var url='<?php echo url('admin/ship/to_edit'); ?>';
       //修改的ship_port_city ID 船队ship_id
//       var SPC_id=
//       var ship_id =
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
            oldshipPort();//原有船公司的港口和城市
            
        });   
        
  
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
                    //console.log(citys[index].cityCode);
                    loadPort(citys[index].cityCode);
                  // loadArea(citys[index].mallAreaList);
                } else {
                    $form.find('select[name=area]').parent().hide();
                }
            });
        }
     
     
         //加载优势路线的省数据
       function TwoloadProvince() {
            var proHtml = '';
            for (var i = 0; i < areaData.length; i++) {
                proHtml += '<option value="' + areaData[i].provinceCode + '_' + areaData[i].mallCityList.length + '_' + i + '">' + areaData[i].provinceName + '</option>';
            }
               //初始化省数据
            $form.find('select[name=Twoprovince]').append(proHtml);
            form.render();
            form.on('select(Twoprovince)', function(data) {
            // document.getElementById('citydiv').setAttribute('style','display: none;'); 
                var value = data.value;
                var d = value.split('_');
                var code = d[0];
                var count = d[1];
                var index = d[2];
                if (count > 0) {
                    TwoloadCity(areaData[index].mallCityList);
                } else {
                    $form.find('select[name=Twocity]').parent().hide();
                }
            } );
      } 
        
        //加载优势路线的城市数据
        function TwoloadCity(citys) {
            var cityHtml = '';
            for (var i = 0; i < citys.length; i++) {
                cityHtml += '<option value="' + citys[i].cityCode + '_' + citys[i].mallAreaList.length + '_' + i + '_'+citys[i].cityName+'">' + citys[i].cityName + '</option>';
            }
            $form.find('select[name=Twocity]').html(cityHtml).parent().show();
            form.render();
            form.on('select(Twocity)', function(data) {
              //  console.log(data);
                var value = data.value;
                var d = value.split('_');
                var code = d[0];
                var count = d[1];
                var index = d[2];
                var cityName=d[3];
                var mark = document.getElementById('search_city');
                if (count > 0) {
                      if( !document.getElementById(code+'_'+cityName) ){
                           selectPortShip(code,cityName,mark)
                        }
                
                } else {
                        $form.find('select[name=areaTwo]').parent().hide();
                }
            });
        }
        
         //加载县/区数据
//        function loadArea(areas) {
//            var areaHtml = '';
//            for (var i = 0; i < areas.length; i++) {
//                areaHtml += '<option value="' + areas[i].areaCode + '">' + areas[i].areaName + '</option>';
//            }
//            $form.find('select[name=area]').html(areaHtml).parent().show();
//            form.render();
//            form.on('select(area)', function(data) {
//                //console.log(data);
//            });
//        }
         
        //加载对应城市的港口，并显示已经选中了
        function loadPort(CityCode){
//            //加载 所有的港口名字和相应的城市code
//        var js_port = '<?php echo $js_port; ?>';
//            js_port=JSON.parse(js_port);
              // console.log(js_port);
            var port_length =js_port.length;
            var areaHtml = '';
            for(var i=0;i<port_length;i++){
                if(CityCode == js_port[i].city_id ){
                 areaHtml += '<option  value="' + js_port[i].id +'_'+ js_port[i].port_name + '">' + js_port[i].port_name + '</option>';  
                }
            }
            $form.find('select[name=area]').html(areaHtml).parent().show();
            form.render();
            
            form.on('select(area)', function(data) {
                 var port =data.value.split('_');
                 var port_id =port['0'];
                 var port_name=port['1'];
                
//                //港口的手游子节点，如果超过两个就删除第一个
//                var portarr=document.getElementById('search_port').children; 
//                if(portarr.length>0){
//                    del(portarr[0]);
//                }
                var mark = document.getElementById('search_port');
                 //已经存的港口就不执行
                if(! document.getElementById(port_id+'_'+port_name)  ){
                        selectPortShip(port_id,port_name,mark);
//                        var portobj=port_id+'_'+port_name;
//                        var obj=document.getElementById(portobj);
//                        obj.setAttribute("disabled","disabled"); 
                } 
            } ) 
        
        }
        
        //展示原有的船公司和港口和优势路线
         function oldshipPort(){
            //console.log(shipPort); 
            var portMark = document.getElementById('search_port');  
            var cityMark = document.getElementById('search_city');
            //加载船公司原有的优势路线城市
            if(city_arr.length !== 7 && port_arr.length !==7){  
            for (var cCode in city_arr) {
                 var cName = city_arr[cCode]; //得到键对应的值
               
                selectPortShip(cCode,cName,cityMark)
             }
         
            //加载船公司原有的港口
            for (var sId in port_arr) {
                var sName = port_arr[sId]; //得到键对应的值
                selectPortShip(sId,sName,portMark)
            }
        }

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
                ipt.name=port_name;
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
            var dataform=ajaxdata();
            // console.log(typeof(dataform));
            var loading = layer.load(1);
            post_adduser = true;    
                $.ajax({
                    type:'POST',
                    url:url,    
                    data: dataform,
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
         
         
        function ajaxdata(){
            //处理ship和port的值变成数组
            function  arr(search_name){
                var shipMark =document.getElementById(search_name).getElementsByTagName('input');
                var shipArray= {};
                for(var i=0;i<shipMark.length;i++){
                    var name= shipMark[i].name;
                    var value= shipMark[i].value;
                    shipArray[name]=value;
                }
               // console.log(JSON.stringify(shipArray));
                return shipArray;
            }
            
             // 获取选择好的港口和城市
           var city_arr = arr('search_city');
           var port_arr = arr('search_port');
           //将船队的ID添加到port_arr
           var ship_name = document.getElementById("ship_name").value;
           var ship_short_name = document.getElementById("ship_short_name").value;
         //  var SPC_ID ='<?php echo $vo["id"];  ?>';
         //  var ship_ID ='<?php echo $vo["ship_id"];  ?>'
           var dataArray= {};
           dataArray['port']=port_arr;
           dataArray['city']=city_arr;
           dataArray[0]={};
           dataArray[0]['SPC_ID']= SPC_ID;
            dataArray[0]['ship_ID']= ship_ID ;
           dataArray[0]['ship_name']=ship_name ;
           dataArray[0]['ship_short_name']=ship_short_name;
            //表单需要提交的json数组
           console.log((dataArray));
            return dataArray;
            
        }

     
 </script>

</body>
</html>

