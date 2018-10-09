
layui.use('upload', function () {
    var upload = layui.upload;
    var order_num = $(" input[ name='order_num' ] ").val();
    
    //订舱单
    var uploadInst = upload.render({
        elem: '.dcd' //绑定元素
        , url: url+'?order_num='+order_num+'&type=book_note' //上传接口
        , accept: 'file' //普通文件
        , done: function (res) {
            //上传完毕回调
            if(res.status>0){
                alert(res.mssage)
            }else{
                alert(res.mssage)
            }
        }
       
    });
    //水运单
    var uploadInst = upload.render({
        elem: '.syd' //绑定元素
        , url: url+'?order_num='+order_num+'&type=sea_waybill' //上传接口
        , accept: 'file' //普通文件
        , done: function (res) {
            //上传完毕回调
             if(res.status>0){
                alert(res.mssage)
            }else{
                alert(res.mssage)
            }
        }
       
    });


    //指定允许上传的文件类型
    upload.render({
        elem: '#test1'
        , url: '/upload/'
        , accept: 'file' //普通文件
        , done: function (res) {
            console.log(res)
        }
    });
});

//保存修改
function xiu_order(zj){    
    //提交后禁止 
    var data = $("#order_data_form").serializeArray();
    let obj = {};
    $.each(data,function(i,v){
        obj[v.name] = v.value;
    });
    obj['money'] = $('#money').html();//纯运费
    obj['premium'] = $('#bxje1').html();//保险费
    obj['portprice_r'] = $('#zhuang').html();//装货费用
    obj['portprice_s'] = $('#song').html();//送货费用
    obj['discount'] = $('#discount').html();//优惠价格
    obj['price_sum'] = $('#price_sum').html();//总运费
    xiu_ajax(obj);
}

function xiu_ajax(data) {  
    $.ajax({
        type: 'POST',
        url: xiu_url,
        data: data,
        dataType: "json",
        success: function (data) {            
            if (data.status == 1) {
                alert('提交表单成功');
            }
        }
    });
    //return false;//只此一
}


//确认订单
function que(order_num) {
    layer.confirm('是否确定订单，确定之后将不能修改', { icon: 3, title: '提示' }, function (index) {
        $.get(xiu_url, { 'order_num': order_num},
        function(data){
            
        });
        layer.close(index);//关闭提示框
    });
}


//增加装货服务
var p = $('.bge tr').length - 1;

function admin_bge() {
  p++;
  $('.bge').append('<tr>' +
    '<td><input name="r_container_code[' + p + ']" type="hidden" ><input class="r_price" name="r_car_price[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input class="r_num" name="r_num[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td>柜</td>' +
    '<td><input name="r_add[' + p + ']"  type="text" value="" style="width: 100%"></td>' +
    '<td><input name="r_link_man[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input name="shipper[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input name="r_load_time[' + p + ']" type="date" value="" style="width: 100%"></td>' +
    '<td><input name="r_link_phone[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input name="r_car[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input name="r_comment[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td class="dele"><i class="layui-icon" onclick="dele(this)">&#x1006;</i></td>' +
    '</tr>');
  input_a();
}

//增加送货服务
var o = $('.bge_song tr').length - 1;

function admin_song() {
  o++;
  $('.bge_song').append('<tr>' +
    '<td><input name="s_container_code[' + p + ']" type="hidden" ><input class="s_price" name="s_car_price[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input class="s_num" name="s_num[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td>柜</td>' +
    '<td><input name="s_add[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input name="s_car[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input name="s_comment[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td class="dele"><i class="layui-icon" onclick="dele(this)">&#x1006;</i></td>' +
    '</tr>');
  input_a();
}

if ($('.fukuan a').html() == '已付款') {
    $('.fukuan a').css('color','#00DB00');
}
