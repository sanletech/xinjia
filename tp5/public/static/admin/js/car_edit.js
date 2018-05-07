
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
                
                //港口的手游子节点，如果超过两个就删除第一个
                var portarr=document.getElementById('search_port').children; 
                if(portarr.length>0){
                    del(portarr[0]);
                }
                var mark = document.getElementById('search_port');
                 //已经存的港口就不执行
                if(! document.getElementById(port_id+'_'+port_name)  ){
                        selectPortShip(port_id,port_name,mark);
                        var portobj=port_id+'_'+port_name;
                        var obj=document.getElementById(portobj);
                        obj.setAttribute("disabled","disabled"); 
                } 
            } ) 
        
        }
        
        
          function loadship(){
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
        //展示原有的船公司和港口和优势路线
         function oldshipPort(){
            //console.log(shipPort); 
            var portMark = document.getElementById('search_port');  
            var shipMark = document.getElementById('search_ship');
            var cityMark = document.getElementById('search_city');
            //加载原有的优势路线城市
           if(shipPort['city_code']['0']!==''){
            for(var i=0;i<shipPort['city_code'].length;i++){
               var cCode = shipPort['city_code'][i];
               var cName = shipPort['city_name'][i]
               selectPortShip(cCode,cName,cityMark)
            }  }
            //加载原有的船公司
            if(shipPort['ship_id']['0']!==''){
             for(var i=0;i<shipPort['ship_id'].length;i++){
               var sId = shipPort['ship_id'][i];
               var sName = shipPort['ship_short_name'][i]
               selectPortShip(sId,sName,shipMark)
            }}
            //加载原有的港口
             var pId=shipPort['port_id'];
             var pName=shipPort['port_name'];
            selectPortShip(pId,pName,portMark)

        }
        
//         <button id="btn_tag" class="layui-btn layui-btn-normal"  style="display: none;"  onclick="del(this) ;return false">
//                <input id ="input_tag" type="hidden"  name="name" value="id"><i id ="i_tag" class="layui-icon">&#xe640;</i> </button>
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
                ipt.name=port_name;
                ipt.value=port_id;
            var  i_tag=document.createElement('i');
                 i_tag.className="layui-icon";
                 i_tag.innerHTML="&#xe640\;";
            btn.appendChild(ipt);   
            btn.appendChild(i_tag);
            mark.appendChild(btn);
//             var nextbtn = document.getElementById("btn_tag").cloneNode(true);
//             var nextI=document.getElementById("i_tag").cloneNode(true);
//             var nextInput=document.getElementById("input_tag").cloneNode(true);
//             nextbtn.setAttribute('style','display: inline;') 
//             nextbtn.innerHTML= port_name;
//             nextInput.setAttribute('name',port_name)
//             nextInput.setAttribute('value',port_id)
//             nextbtn.setAttribute('id',(port_id+'_'+port_name))
//              nextbtn.appendChild(nextInput);
//             nextbtn.appendChild(nextI);
//             mark.appendChild(nextbtn);
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
            
             // 获取选择好的港口和船公司
           var ship_arr = arr('search_ship');
           var city_arr = arr('search_city');
           var port_arr = arr('search_port');
           var car_id= carID;
           //将车队港口的ID添加到port_arr
           var cp_id = cpID;
           port_arr['cp_id']=car_id;
           var status = document.getElementById("status").selectedIndex+1;
           var symbiosis = document.getElementById("symbiosis").selectedIndex+1;
           var car_name = document.getElementById("car_name").value;
           var address = document.getElementById("address").value;
           var dataArray= {};
           dataArray['ship']=ship_arr;
           dataArray['port']=port_arr;
           dataArray['city']=city_arr;
           dataArray[0]={};
           dataArray[0]['car_id']= car_id;
           dataArray[0]['status']=status;
           dataArray[0]['symbiosis']=symbiosis;
           dataArray[0]['car_name']=car_name;
           dataArray[0]['address']=address;
            //表单需要提交的json数组
         //  console.log((dataArray));
            return dataArray;
        }

        
        function  selectPortShip1(port_id,port_name,mark){
             var nextbtn = document.getElementById("btn_tag").cloneNode(true)
             var nextI=document.getElementById("i_tag").cloneNode(true)
             nextbtn.setAttribute('style','display: inline;') 
             nextbtn.innerHTML= port_name;
             nextbtn.setAttribute('name',port_id)
              nextbtn.setAttribute('value',port_name)
             nextbtn.setAttribute('id',(port_id+'_'+port_name))
             nextbtn.appendChild(nextI);
             mark.appendChild(nextbtn);
         }    
