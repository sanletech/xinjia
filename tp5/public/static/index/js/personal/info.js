//表单切换
layui.use('element', function(){
var $ = layui.jquery
,element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块

//触发事件
  var active = {
    tabAdd: function(){
      //新增一个Tab项
      element.tabAdd('demo', {
        title: '新选项'+ (Math.random()*1000|0) //用于演示
        ,content: '内容'+ (Math.random()*1000|0)
        ,id: new Date().getTime() //实际使用一般是规定好的id，这里以时间戳模拟下
      })
    }
    ,tabDelete: function(othis){
      //删除指定Tab项
      element.tabDelete('demo', '44'); //删除：“商品管理”


      othis.addClass('layui-btn-disabled');
    }
    ,tabChange: function(){
      //切换到指定Tab项
      element.tabChange('demo', '22'); //切换到：用户管理
    }
  };
});

layui.use('upload', function(){
var $ = layui.jquery
,upload = layui.upload;

//普通图片上传
var uploadInst = upload.render({
elem: '#test1'
,url: '/upload/'
,before: function(obj){
  //预读本地文件示例，不支持ie8
  obj.preview(function(index, file, result){
    $('#demo1').attr('src', result); //图片链接（base64）
  });
}
,done: function(res){
  //如果上传失败
  if(res.code > 0){
    return layer.msg('上传失败');
  }
  //上传成功
}
,error: function(){
  //演示失败状态，并实现重传
  var demoText = $('#demoText');
  demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
  demoText.find('.demo-reload').on('click', function(){
    uploadInst.upload();
  });
}
});

//多图片上传
upload.render({
elem: '#test2'
,url: '/upload/'
,multiple: true
,before: function(obj){
  //预读本地文件示例，不支持ie8
  obj.preview(function(index, file, result){
    $('#demo2').append('<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">')
  });
}
,done: function(res){
  //上传完毕
}
});

//指定允许上传的文件类型
upload.render({
elem: '#test3'
,url: '/upload/'
,accept: 'file' //普通文件
,done: function(res){
  console.log(res)
}
});
upload.render({ //允许上传的文件后缀
elem: '#test4'
,url: '/upload/'
,accept: 'file' //普通文件
,exts: 'zip|rar|7z' //只允许上传压缩文件
,done: function(res){
  console.log(res)
}
});
upload.render({
elem: '#test5'
,url: '/upload/'
,accept: 'video' //视频
,done: function(res){
  console.log(res)
}
});
upload.render({
elem: '#test6'
,url: '/upload/'
,accept: 'audio' //音频
,done: function(res){
  console.log(res)
}
});

//设定文件大小限制
upload.render({
elem: '#test7'
,url: '/upload/'
,size: 60 //限制文件大小，单位 KB
,done: function(res){
  console.log(res)
}
});

//同时绑定多个元素，并将属性设定在元素上
upload.render({
elem: '.demoMore'
,before: function(){
  layer.tips('接口地址：'+ this.url, this.item, {tips: 1});
}
,done: function(res, index, upload){
  var item = this.item;
  console.log(item); //获取当前触发上传的元素，layui 2.1.0 新增
}
})

//选完文件后不自动上传
upload.render({
elem: '#test8'
,url: '/upload/'
,auto: false
//,multiple: true
,bindAction: '#test9'
,done: function(res){
  console.log(res)
}
});

//拖拽上传
upload.render({
elem: '#test10'
,url: '/upload/'
,done: function(res){
  console.log(res)
}
});

//多文件列表示例
var demoListView = $('#demoList')
,uploadListIns = upload.render({
elem: '#testList'
,url: '/upload/'
,accept: 'file'
,multiple: true
,auto: false
,bindAction: '#testListAction'
,choose: function(obj){
  var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
  //读取本地文件
  obj.preview(function(index, file, result){
    var tr = $(['<tr id="upload-'+ index +'">'
      ,'<td>'+ file.name +'</td>'
      ,'<td>'+ (file.size/1014).toFixed(1) +'kb</td>'
      ,'<td>等待上传</td>'
      ,'<td>'
        ,'<button class="layui-btn layui-btn-mini demo-reload layui-hide">重传</button>'
        ,'<button class="layui-btn layui-btn-mini layui-btn-danger demo-delete">删除</button>'
      ,'</td>'
    ,'</tr>'].join(''));

    //单个重传
    tr.find('.demo-reload').on('click', function(){
      obj.upload(index, file);
    });

    //删除
    tr.find('.demo-delete').on('click', function(){
      delete files[index]; //删除对应的文件
      tr.remove();
      uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
    });

    demoListView.append(tr);
  });
}
,done: function(res, index, upload){
  if(res.code == 0){ //上传成功
    var tr = demoListView.find('tr#upload-'+ index)
    ,tds = tr.children();
    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
    tds.eq(3).html(''); //清空操作
    return delete this.files[index]; //删除文件队列已经上传成功的文件
  }
  this.error(index, upload);
}
,error: function(index, upload){
  var tr = demoListView.find('tr#upload-'+ index)
  ,tds = tr.children();
  tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
  tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
}
});

//绑定原始文件域
upload.render({
elem: '#test20'
,url: '/upload/'
,done: function(res){
  console.log(res)
}
});
});
