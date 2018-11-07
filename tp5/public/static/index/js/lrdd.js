// 日期
layui.use(['form', 'layedit', 'laydate', 'element'], function () {
  var form = layui.form
    , layer = layui.layer
    , layedit = layui.layedit
    , laydate = layui.laydate;

  form.on('checkbox(zhuang)', function (data) {
    if (data.elem.checked) {
      $('#zeng,.zhuanghuo').show();
      loading = true;
      $('.bge input').attr('readOnly', false);
    } else {
      $('#zeng,.zhuanghuo').hide();
      loading = false;
      $('.bge input').attr('readOnly', true);
    }
    st();
  })

  form.on('checkbox(song)', function (data) {
    if (data.elem.checked) {
      $('#zeng_song,.songhuo').show();
      delivery = true;
      $('.bge_song input').attr('readOnly', false);
    } else {
      $('#zeng_song,.songhuo').hide();
      delivery = false;
      $('.bge_song input').attr('readOnly', true);
    }
    st();
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


function gun() {
  //监听滚动条
  $('html', window.parent.document).css('overflow-y', 'hidden');
  $(".demo-class").css('top', $(parent.window).scrollTop() - 100);

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

//选择模态框调用数据
var arr = [];
function tiao() {
  var member_code = 'kehu001';
  var data = { 'member_code': member_code };
  $.ajax({
    type: 'POST',
    url: selectlinkUrl,
    data: data,
    dataType: "json",
    success: function (status) {
      //接受数据 展示页面
      weituo(status);
      status = JSON.parse(status)
      // console.log(status);
    }
  });

  //展示委托信息
  function weituo(data) {
    var dataArray = eval(data);
    arr = dataArray;
    $('.xin').html('');
    for (let i in dataArray) {
      shuzu(dataArray[i]);
    }
  }

  $('.btn_sou').click(function(){//单击搜索
    let name = $('.xing').val();
    $('.xin').html('');
    for (let i in arr) {
      if (name) {
        if (arr[i].name.indexOf(name) >= 0 ) {
         shuzu(arr[i]);
        }
      }else{
        boot = true;
        shuzu(arr[i]);
      }
    }
  });


  function shuzu(arr){
    $('.xin').append('<li class="layui-col-xs6">'
    + '<div class="nei" onclick="xzwt(this)">'
    + ' <div class="le">'
    + '<div class="tiao">公司名：</div>'
    + '<div class="tiao">姓名：</div>'
    + '<div class="tiao">手机号：</div>'
    + '</div>'
    + '<div class="rig">'
    + '<div class="tiao_id" style="display: none;"> ' + arr.id + '</div>'
    + '<div class="tiao">' + arr.company + '</div>'
    + '<div class="tiao">' + arr.name + '</div>'
    + '<div class="tiao">' + arr.phone + '</div>'
    + '</div>'
    + '</div>'
    + '</li>'
  )
  }
}



//选中委托信息
var lei;//判断是送货还是收货
var id;//点击当前的ID 
var nei;//当前选中的内容
function xzwt(zj){
  $(zj).addClass('nei_a').parent().siblings().find('.nei').removeClass('nei_a');
  id = $(zj).find('.tiao_id').html();//点击当前的ID
  lei = $(zj).parents('.xin')[0];//判断是送货还是收货
  for (let i in arr) {
    if (arr[i].id == id) {
      nei = arr[i];
    }
  }
}

//双击确认
$('.biao .xin').on('dblclick','li',function(){
  wtxx();
})

//委托信息放input上
function wtxx() {
  let input = $('.er .in');//获取委托信息的input
  for (let i in arr) {
    if (arr[i].id == id) {
      nei = arr[i];
    }
  }
  if ($(lei).hasClass('song')) {
    $(input[0]).val(nei.id);
    $(input[1]).val(nei.company);
    $(input[2]).val(nei.name);
    $(input[3]).val(nei.phone);
  } else {
    $(input[4]).val(nei.id);
    $(input[5]).val(nei.company);
    $(input[6]).val(nei.name);
    $(input[7]).val(nei.phone);
  }
  layer.close(layer.index);
}


//选择模态框
$('.wt1').click(function () {
  layer.open({
    type: 1,
    title: '通讯录',
    offset: 'auto',
    area: ['1000px', '500px'],
    content: $('#wt1'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
    skin: 'demo-class',
    shadeClose: true,
    success: function () {
      // 模态框成功调用
      
      tiao();//调用数据
    },
    //关闭窗口时
    cancel: function () {

    }
  });
})

//添加模态框
$('.wt_zeng').click(function () {
  layer.close(layer.index);
  layer.open({
    type: 1,
    title: '增加信息',
    area: ['600px'],
    content: $('#wt2'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
    skin: 'demo-class',
    fixed: false,
    success: function () {
      // 模态框成功调用
    }
  });
});

//增加委托信息
function zeng_wt(){
  let data = $('#linkman_form').serialize();//增加委托信息表单数据
  let inpu = $('#linkman_form input');
  let boot = true;
  inpu.each(function(){
    if (!$(this).val()) {
      boot = false;
      alert('请完善您的内容！')
      return false;
    }
  })
  if (boot) { 
  $.ajax({
    type: 'POST',
    url: linkmanAddURL,
    data: data,
    dataType: "json",
    success: function (data) {
      if(data.status){
        layer.close(layer.index);//关闭添加窗口
        $('.wt1').click();//重新查询并打开窗口
      }else{
        alert('修改联系人失败');
      }      
    }
  });  
} 
}

//修改委托信息
$('.wt_xiu').click(function () {
  if (!nei.id) {
    boot = false;
    alert('请选择您要修改的信息');
  }else{
    layer.close(layer.index);
    layer.open({
      type: 1,
      title: '修改信息',
      area: ['600px'],
      content: $('#wt3'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
      skin: 'demo-class',
      fixed: false,
      success: function (data) {
        // 模态框成功调用            
        $('#wt3 input').eq(0).val(nei.id);      
        $('#wt3 input').eq(1).val(nei.company);
        $('#wt3 input').eq(2).val(nei.name);
        $('#wt3 input').eq(3).val(nei.phone);
      }
    });
  }
});

//修改委托信息
function xiu_wt(){
  let data = {};//增加委托信息表单数据
  data.id = $('#wt3 input').eq(0).val();
  data.company = $('#wt3 input').eq(1).val();
  data.link_name = $('#wt3 input').eq(2).val();
  data.phone = $('#wt3 input').eq(3).val();
  if (data.company && data.link_name && data.phone) {
    $.ajax({
      type: 'POST',
      url: linkmanUpdateURL,
      data: data,
      dataType: "json",
      success: function (data) {
        if(data.status){
          layer.close(layer.index);//关闭添加窗口
          $('.wt1').click();//重新查询并打开窗口
        }else{
          alert('修改联系人失败');
        }   
      }
    });
  }else{
    alert('请完善您的内容！')
  }
}

//删除选中的委托信息
$('.wt_del').click(function(){
  $.get(linkmanDelURL,{'id':id},function(data){
    if(data.status){
      $('.nei_a').parent().remove();//删除当前选中的节点
      alert('成功删除联系人');
    }else{
      alert('删除联系人失败');
    }   
  });
});

//点击默认
$('.wt_default').click(function(){
  if ($(lei).hasClass('song')) {//判断是不是收货
    $.get(linkman_default,{'id':id,type:'r'},function(data){
      if(data.status){
        alert('成功设置默认信息')
      }
    });
  }else{//发货
    $.get(linkman_default,{'id':id,type:'s'},function(data){
      if(data.status){
        alert('成功设置默认信息')
      }
    });
  }
})

//发票模态框
$('.trigger-default').click(function () {
  layer.open({
    type: 1,
    title: '路线详情',
    area: ['600px'],
    content: $('#modal-default'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
    skin: 'demo-class',
    scrollbar: false,
    fixed: false,
    success: function () {
      // 模态框成功调用
      gun();
    },
    //关闭窗口时
    cancel: function () {
      $('html', window.parent.document).css('overflow-y', 'auto');
    }
  });
})

//propertychange监听input里面的字符变化,属性改变事件
$('.bge input').each(function () {
  $(this).css("width", parseInt($(this).val().length) * 12 + 'px');
});
$('.bge_song input').each(function () {
  $(this).css("width", parseInt($(this).val().length) * 12 + 'px');
});
input_a();
function input_a() {
  var i = 0;
  $('.biaoge input').bind('input propertychange', function () {//监听发货表格
    var $this = $(this);    
    var text_length = $this.val().length;//获取当前文本框的长度
    var current_width = parseInt(text_length) * 11;//该16是改变前的宽度除以当前字符串的长度,算出每个字符的长度
    $this.css("width", current_width + "px");
    let shu = 0;//装货数量
    let fa = 0;//发货数量
    let container = $('#container_sum').find("option:selected").val();
    $('.bge .r_num').each(function () {
      shu += Number($(this).val());
      if (shu > container) {
        i++;
        $(this).val('');
        return false;
      }
    });

    $('.bge_song .s_num').each(function () {
      fa += Number($(this).val());
      if (fa > container) {
        i++;
        $(this).val('');
        return false;
      }
    });
    st();//重新计算价格
    if (i == 1) {//避免重复跳出提示框
      alert('当前数量大于柜量,请重新输入！');
      i = 0;
      return false;
    }
  });
}

//增加装货服务
var p = $('.bge tr').length - 1;

function zeng_bge() {
  p++;
  $('.bge').append('<tr>' +
    '<td><input class="r_price" name="r_car_price[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input class="r_num" name="r_num[' + p + ']" type="text" value="" style="width: 100%"></td>' +
    '<td>柜</td>' +
    '<td><input name="r_add[' + p + ']" type="text" value="" style="width: 100%"></td>' +
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

function zeng_song() {
  o++;
  $('.bge_song').append('<tr>' +
    '<td><input class="s_price" name="s_car_price[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input class="s_num" name="s_num[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td>柜</td>' +
    '<td><input name="s_add[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input name="s_car[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td><input name="s_comment[' + o + ']" type="text" value="" style="width: 100%"></td>' +
    '<td class="dele"><i class="layui-icon" onclick="dele(this)">&#x1006;</i></td>' +
    '</tr>');
  input_a();
}

function dele(zj) {//删除当前装货或者送货
  $(zj).parents('tr').remove();
  st();//重新计算价格 方法在plce_order.js
}

//第一次 下单
$('.tjiao').eq(0).find('.shi').click(function(){
  $('.tjiao').eq(0).hide();
  $('.lc,.wt1,.dd_nei .layui-form').hide();
  $('.tjiao').eq(1).show();
  $('.lche,.fuwu').show();
  $('input').css('border','0').attr('readonly',true);
  $('.er .layui-form-checkbox[lay-skin=primary] i').hide();
  $('.select').css('border','0');
//  $("select").each(function () {
//    $(this).attr("disabled","disabled");
//  });
  // $("html,body").animate({scrollTop:100}, 500);
  // console.log(window.location.port);
  layer.open({
    type:1
    ,title: '确认信息'
    ,content: $('#order_data_form')
    ,offset: 't'
    ,area:['80%','100%']
    ,shadeClose:true
    ,end: function(index, layero){
      layer.close(index);
      $('.tjiao').eq(1).find('.qu').click();
    }
  });
  
})

//返回修改
$('.tjiao').eq(1).find('.qu').click(function(){
  layer.close(layer.index);
  $('.tjiao').eq(1).hide();
  $('.lc,.wt1,.dd_nei .layui-form').show();
  $('.tjiao').eq(0).show();
  $('.lche,.fuwu').hide();
  $('input').css('border','1px solid #e5e5e5').attr('readonly',false);
  $('#bxje').css('border','0').attr('readonly',true);
  $('.inp input,.bge input,.bge_song input').css({'border':'0','border-bottom':'1px solid #000'});
  $('.er .layui-form-checkbox[lay-skin=primary] i').show();
  $('.select').css('border','1px solid #aaa');
//  $("select").each(function () {
//    $(this).removeAttr("disabled");
//  });
})
