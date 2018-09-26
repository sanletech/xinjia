
layui.use('upload', function () {
    var upload = layui.upload;

    //订舱单
    var uploadInst = upload.render({
        elem: '.dcd' //绑定元素
        , url: '/upload/' //上传接口
        , accept: 'file' //普通文件
        , done: function (res) {
            //上传完毕回调
        }
        , error: function () {
            //请求异常回调
        }
    });
    //水运单
    var uploadInst = upload.render({
        elem: '.syd' //绑定元素
        , url: '/upload/' //上传接口
        , accept: 'file' //普通文件
        , done: function (res) {
            //上传完毕回调
        }
        , error: function () {
            //请求异常回调
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
