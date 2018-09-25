
layui.use('upload', function () {
    var upload = layui.upload;

    //订舱单
    var uploadInst = upload.render({
        elem: '#dcd' //绑定元素
        , url: '/upload/' //上传接口
        , done: function (res) {
            //上传完毕回调
        }
        , error: function () {
            //请求异常回调
        }
    });
    //水运单
    var uploadInst = upload.render({
        elem: '#syd' //绑定元素
        , url: '/upload/' //上传接口
        , done: function (res) {
            //上传完毕回调
        }
        , error: function () {
            //请求异常回调
        }
    });
});