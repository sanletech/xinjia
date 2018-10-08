
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

function xiu_order(zj){    
    $(zj).attr("onclick",'return false');//禁用提交按钮   
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
        $('.tjiao a').eq(0).attr("onclick","order_data(this)");//禁用提交按钮
        }
    });
    //return false;//只此一
}



if ($('.fukuan a').html() == '已付款') {
    $('.fukuan a').css('color','#00DB00');
}
