// 日期
layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate;

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
    title: function(value){
      if(value.length < 5){
        return '标题至少得5个字符啊';
      }
    }
    ,pass: [/(.+){6,12}$/, '密码必须6到12位']
    ,content: function(value){
      layedit.sync(editIndex);
    }
  });

  //监听指定开关
  form.on('switch(switchTest)', function(data){
    layer.msg('开关checked：'+ (this.checked ? 'true' : 'false'), {
      offset: '6px'
    });
    layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF', data.othis)
  });

  //监听提交
  form.on('submit(demo1)', function(data){
    layer.alert(JSON.stringify(data.field), {
      title: '最终的提交信息'
    })
    return false;
  });
});

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



//提交模态窗口基本设置
$("#modal-default").iziModal({
    title: "信息确认",
    iconClass: 'icon-announcement',
    width: 400,
    padding: 20
});
//启动模态窗
$(document).on('click', '.trigger-default', function (event) {
    event.preventDefault();
    $('#modal-default').iziModal('open');
});

//选择模态窗口基本设置
$("#wt1").iziModal({
    title: "请选择",
    iconClass: 'icon-announcement',
    width: 1000,
    padding: 20
});
//启动模态窗
$(document).on('click', '.wt1', function (event) {
    event.preventDefault();
    $('#wt1').iziModal('open');
});

//添加模态窗口基本设置
$("#wt2").iziModal({
    title: "添加",
    iconClass: 'icon-announcement',
    width: 600,
    padding: 20
});
//启动模态窗
$(document).on('click', '.wt2', function (event) {
    event.preventDefault();
    $('#wt2').iziModal('open');
});

//获取文本value值
function input_wu() {
  $('.er .in').each(function(){
      if($(this).val() == ''){
        $(this).val('无');
      }
  })
}
function input_null(){
  $('.er .in').each(function(){
      if($(this).val() == '无'){
        $(this).val('');
      }
  })
}
//修改设置
input_wu();
$('.in').css('border', '0');
function xiu(){
  var gai = $('.er_anniu .wt3').text();
  if (gai == '修改') {
    $('.er_anniu .wt3').text('确认');
    $('.in').css('border', '1px solid #e6e6e6');
    $('.in').removeAttr('readonly');
    input_null();
  }else{
    $('.er_anniu .wt3').text('修改');
    $('.in').css('border', '0');
    $('.in').attr('readonly', 'readonly');
    input_wu();
  }
}
