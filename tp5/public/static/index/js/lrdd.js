// 日期
layui.use(['form', 'layedit', 'laydate','element'], function () {
  var form = layui.form
    , layer = layui.layer
    , layedit = layui.layedit
    , laydate = layui.laydate;

    form.on('checkbox(zhuang)',function(data){
      if(data.elem.checked){
        $('#zeng,.zhuanghuo').show();
      }else{
        $('#zeng,.zhuanghuo').hide();
      }
    })

    form.on('checkbox(song)',function(data){
      if(data.elem.checked){
        $('#zeng_song,.songhuo').show();
      }else{
        $('#zeng_song,.songhuo').hide();
      }
    })

  //日期
  laydate.render({
    elem: '#date'
  });
  laydate.render({
    elem: '#date1'
  });

  //创建一个编辑器
  var editIndex = layedit.build('LAY_demo_editor');

  //自定义验证规则
  form.verify({
    title: function (value) {
      if (value.length < 5) {
        return '标题至少得5个字符啊';
      }
    }
    , pass: [/(.+){6,12}$/, '密码必须6到12位']
    , content: function (value) {
      layedit.sync(editIndex);
    }
  });

  //监听指定开关
  form.on('switch(switchTest)', function (data) {
    layer.msg('开关checked：' + (this.checked ? 'true' : 'false'), {
      offset: '6px'
    });
    layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF', data.othis)
  });
});


function gun(){
  //监听滚动条
  $('html',window.parent.document).css('overflow-y','hidden');
  $(".demo-class").css('top',$(parent.window).scrollTop()-100);
  
}
//表单切换
layui.use('element', function () {
  var $ = layui.jquery
    , element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块

  //触发事件
  var active = {
    tabAdd: function () {
      //新增一个Tab项
      element.tabAdd('demo', {
        title: '新选项' + (Math.random() * 1000 | 0) //用于演示
        , content: '内容' + (Math.random() * 1000 | 0)
        , id: new Date().getTime() //实际使用一般是规定好的id，这里以时间戳模拟下
      })
    }
    , tabDelete: function (othis) {
      //删除指定Tab项
      element.tabDelete('demo', '44'); //删除：“商品管理”

      othis.addClass('layui-btn-disabled');
    }
    , tabChange: function () {
      //切换到指定Tab项
      element.tabChange('demo', '22'); //切换到：用户管理
    }
  };
});

//选择模态框
$('.wt1').click(function(){
  layer.open({
    type: 1,
    title:'路线详情',
    area:['1000px','500px'],
    content: $('#wt1'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
    skin: 'demo-class',
    scrollbar: false,
    fixed : false,
    success: function(){
      // 模态框成功调用
      gun();
    },
    //关闭窗口时
    cancel:function(){
      $('html',window.parent.document).css('overflow-y','auto');
    }
  });
})
//添加模态框
$('.wt2').click(function(){
  layer.open({
    type: 1,
    title:'路线详情',
    area:['600px'],
    content: $('#wt2'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
    skin: 'demo-class',
    scrollbar: false,
    fixed : false,
    success: function(){
      // 模态框成功调用
      gun();
    },
    //关闭窗口时
    cancel:function(){
      $('html',window.parent.document).css('overflow-y','auto');
    }
  });
})
//发票模态框
$('.trigger-default').click(function(){
  layer.open({
    type: 1,
    title:'路线详情',
    area:['600px'],
    content: $('#modal-default'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
    skin: 'demo-class',
    scrollbar: false,
    fixed : false,
    success: function(){
      // 模态框成功调用
      gun();
    },
    //关闭窗口时
    cancel:function(){
      $('html',window.parent.document).css('overflow-y','auto');
    }
  });
})

//propertychange监听input里面的字符变化,属性改变事件
$('.bge input').each(function(){
  $(this).css("width",parseInt($(this).val().length) *12+'px');
});
$('.bge_song input').each(function(){
  $(this).css("width",parseInt($(this).val().length) *12+'px');
});
input_a();
function input_a(){
  $('.biaoge input').bind('input propertychange', function() {
    var $this = $(this);
    var text_length = $this.val().length;//获取当前文本框的长度
    var current_width = parseInt(text_length) *11;//该16是改变前的宽度除以当前字符串的长度,算出每个字符的长度
    $this.css("width",current_width+"px");
});
}

//增加装货服务
function zeng_bge(){
  $('.bge').append('<tr>'+
  '<td><input name="r_car_price[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="r_num[]" type="text" value="" style="width: 100%"></td>'+
  '<td>柜</td>'+
  '<td><input name="r_add[]" type="r_num[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="r_link_man[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="shipper[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="r_load_time[]" type="date" value="" style="width: 100%"></td>'+
  '<td><input name="r_link_phone[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="r_car[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="r_comment[]" type="text" value="" style="width: 100%"></td>'+
  '<td class="dele"><i class="layui-icon" onclick="dele(this)">&#x1006;</i></td>'+
'</tr>');
input_a();
}

//增加送货服务
function zeng_song(){
  $('.bge_song').append('<tr>'+
  '<td><input name="s_car_price[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="s_num[]" type="text" value="" style="width: 100%"></td>'+
  '<td>柜</td>'+
  '<td><input name="s_add[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="s_car[]" type="text" value="" style="width: 100%"></td>'+
  '<td><input name="s_comment[]" type="text" value="" style="width: 100%"></td>'+
  '<td class="dele"><i class="layui-icon" onclick="dele(this)">&#x1006;</i></td>'+
'</tr>');
input_a();
}

function dele(zj){
  $(zj).parents('tr').remove();
}