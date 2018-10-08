
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

if ($('.fukuan a').html() == '已付款') {
    $('.fukuan a').css('color','#00DB00');
}
