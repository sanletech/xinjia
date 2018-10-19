
document.write("<script language='javascript' src='/static/admin/js/area.js'><\/script>");
   //初始数据
        var areaData = Area;
        var $form;
        var form;
        var $;
        layui.use(['jquery', 'form'], function() {
            $ = layui.jquery;
            form = layui.form;
            $form = $('form');
            loadProvince();  //选择省会
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
                $form.find('select[name=town]').html('<option value="">请选择镇/街道<</option>').parent().hide();
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
                $form.find('select[name=town]').html('<option value="">请选择镇/街道<</option>').parent().hide();
                var value = data.value;
                var d = value.split('_');
                var code = d[0];
                var count = d[1];
                var index = d[2];
                if (count > 0) {
                    //将城市Id 传给港口loadPort()
                    //console.log(citys[index].cityCode);
                  //  loadPort(citys[index].cityCode);
                    loadArea(citys[index].mallAreaList);
                } else {
                    $form.find('select[name=area]').parent().hide();
                }
            });
        }
     
         //加载县/区数据
        function loadArea(areas) {
            var areaHtml = '';
            for (var i = 0; i < areas.length; i++) {
                areaHtml += '<option value="' + areas[i].areaCode + '_'+ areas[i].areaName + '">' + areas[i].areaName + '</option>';
            }
            $form.find('select[name=area]').html(areaHtml).parent().show();
            form.render();
            form.on('select(area)', function(data) {
                var value = data.value;
                var d = value.split('_')
                var code = d[0];
                var areaName = d[1];
                //console.log(data.value);
                
                var towns = toajax(code);
            });
        }
        
                
         //加载镇
        function loadtown(towns){
            if (towns=='') {
            $form.find('select[name=town]').parent().hide();
            } else {
                towns = JSON.parse(towns)
                towns =  eval('('+towns+')')
                var townHtml = '';
                for (let i in  towns){
                    townHtml += '<option value="' + i +'_'+ towns[i] + '">' + towns[i] + '</option>';
                }
                $form.find('select[name=town]').html(townHtml).parent().show();
                form.render();
                form.on('select(town)', function(data) {
                   $('#oldadd').remove();
                   
            });
            }
        }
        //ajax 获取对应的镇的信息
        function toajax(townCode) { 
            var townurl = addressURL +'?twoncode='+townCode;
            $.ajax( {
                    type:'POST',
                    url:townurl,
                    dataType:"json",
                    success:function(data){
                            loadtown(data);
                    },
                })
        }
     