// 日期
layui.use(['form', 'layedit', 'laydate'], function () {
  var form = layui.form
    , layer = layui.layer
    , layedit = layui.layedit
    , laydate = layui.laydate;

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

  //监听提交
  form.on('submit(demo1)', function (data) {
    layer.alert(JSON.stringify(data.field), {
      title: '最终的提交信息'
    })
    return false;
  });
});

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



//提交模态窗口基本设置
$("#modal-default").iziModal({
  title: "发票填写",
  iconClass: 'icon-announcement',
  width: 700,
  top: 30,
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
  $('.er .in').each(function () {
    if ($(this).val() == '') {
      $(this).val('无');
    }
  })
}
function input_null() {
  $('.er .in').each(function () {
    if ($(this).val() == '无') {
      $(this).val('');
    }
  })
}
//修改设置
input_wu();
$('.in').css('border', '0');
function xiu() {
  var gai = $('.er_anniu .wt3').val();
  console.log(gai);

  if (gai == '修改' || gai == '') {
    $('.er_anniu .wt3').val('确认');
    $('.in').css('border', '1px solid #e6e6e6');
    $('.in').removeAttr('readonly');
    input_null();
  } else if(gai == '确认'){

    $('.er_anniu .wt3').val('修改');
    $('.in').css('border', '0');
    $('.in').attr('readonly', 'readonly');
    input_wu();
  }
}
//发票设置
$('#fk').change(function () {
  let fk = $(this).children('option:selected').val();
  if (fk == 3) {
    $('.jin input').removeClass('layui-disabled').attr('disabled', false);
  } else {
    $('.jin input').addClass('layui-disabled').attr('disabled', true);
  }
});

//开取发票
function kfp(fp) {
  if ($(fp).find('input[type="checkbox"]').prop('checked')) {
    $('.fp01').removeClass('layui-disabled').attr('disabled', false);
  } else {
    $('.fp01').addClass('layui-disabled').attr('disabled', true);
  }
};
//设置发票增税值
$('.fp01').change(function () {
  let fk = $(this).children('option:selected').val();
  if (fk == 0) {
    $('.tx').hide();
  } else {
    $('.tx').show();
  }
});

//柜量
for (let i = 1; i < 31; i++) {
  $('.guil').append("<option value='" + i + "'>" + i + "</option>");
}


// 接受后台的联系人资料
function selectlink(url) {
  var member_code = 'kehu001';
  var data = { 'member_code': member_code };
  $.ajax({
    type: 'POST',
    url: url,
    data: data,
    dataType: "json",
    success: function (status) {
      //接受数据 展示页面
      wt(status);
    }
  });
}
var arr = [];

//展示委托信息
function wt(data) {
  var dataArray = eval(data); 
  arr = dataArray;
  for (let i in dataArray) {
    $('.xin').append('<li class="layui-col-xs6">'
      + '<div class="nei" onclick="nei(this)">'
      + ' <div class="le">'
      + '<div class="tiao">姓名：</div>'
      + '<div class="tiao">手机号：</div>'
      + '<div class="tiao">公司名：</div>'
      + '<div class="tiao">装货地址：</div>'
      + '</div>'
      + '<div class="rig">'
      + '<div class="tiao_id" style="display: none;">'+dataArray[i].id+'</div>'
      + '<div class="tiao">'+dataArray[i].name+'</div>'
      + '<div class="tiao">'+dataArray[i].phone+'</div>'
      + '<div class="tiao">'+dataArray[i].company+'</div>'
      + '<div class="tiao wu">'+dataArray[i].address+'</div>'
      + '</div>'
      + '</div>'
      + '</li>'
    )
  }
}
//委托信息放input上
function nei(zj) {
  let id = $(zj).find('.tiao_id').html();//获取当前选中ID
  let lei = $(zj).parents('.xin')[0];//判断是送货还是收货
  let input = $('.er .in');//获取委托信息的input
  let nei;//当前选中的内容
  for(let i in arr){
    if (arr[i].id = id) {
      nei = arr[i];
    }
  }
  
  if($(lei).hasClass('song')){
    $(input[0]).val(nei.company);
    $(input[1]).val(nei.name);
    $(input[2]).val(nei.phone);
    $(input[3]).val(nei.address);
  }else{
    $(input[4]).val(nei.company);
    $(input[5]).val(nei.name);
    $(input[6]).val(nei.phone);
    $(input[7]).val(nei.address);
  }
  $('#wt1').iziModal('close');
}

//收货/发货人的表单提交 
function linkman_btn() {
  var url = "<{:url('index/order/linkman')}>";
  var data = $("#linkman_form").serialize();
  toajax(url, data);
}

//发票的信息提交 
function invoice() {
  var url = "<{:url('index/order/invoice')}>";
  var data = $("#invoice_form").serialize();
  toajax(url, data);
}

//订单信息的提交
function order_data() {
  var url = "<{:url('index/order/order_data')}>";
  var data = $("#order_data_form").serialize();
  toajax(url, data);
}

function toajax(url, data) {
  $.ajax({
    type: 'POST',
    url: url,
    data: data,
    dataType: "json",
    success: function (status) {
      if (status == 1) {
        alert('提交表单成功');
        window.location.href = "<{:url('index/order/order_list')}>"
      }
    }
  });
  //return false;//只此一句
}