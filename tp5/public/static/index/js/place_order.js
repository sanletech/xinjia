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
    if (gai == '修改' || gai == '') {
        $('.er_anniu .wt3').val('确认');
        $('.in').css('border', '1px solid #e6e6e6');
        $('.in').removeAttr('readonly');
        input_null();
    } else if (gai == '确认') {
        $('.er_anniu .wt3').val('修改');
        $('.in').css('border', '0');
        $('.in').attr('readonly', 'readonly');
        input_wu();
    }
}
//发票设置
$('#fk').change(function () {
    let fk = $(this).children('option:selected').val();
    if (fk == 'thirdPayment') {//付款方式（第三方）
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
$('.fapiao').hide();
$('.fp01').change(function () {
    let fk = $(this).children('option:selected').val();
    if (fk == 0) {
        $('.tx,.fapiao').hide();
    } else {
        $('.tx,.fapiao').show();
    }
});

//柜量
for (let i = 1; i < 31; i++) {
    if ($('#gl').html()) {
    if ($('#gl').html() == i) {
        $('.guil').append("<option value='" + i + "' selected>" + i + "</option>");
              continue;
        }
    }
    $('.guil').append("<option value='" + i + "'>" + i + "</option>");
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// 接受后台的联系人资料
function selectlink() {
    var member_code = 'kehu001';
    var data = { 'member_code': member_code };
    $.ajax({
        type: 'POST',
        url: selectlinkUrl,
        data: data,
        dataType: "json",
        success: function (status) {
            //接受数据 展示页面
             wt(status);
            status =JSON.parse(status)
            // console.log(status);
        }
    });
}
var arr = [];

//展示委托信息
function wt(data) {
    var dataArray = eval(data);
    arr = dataArray;
    $('.xin').html('');
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
            + '<div class="tiao_id" style="display: none;"> '+ dataArray[i].id + '</div>'
            + '<div class="tiao">' + dataArray[i].name + '</div>'
            + '<div class="tiao">' + dataArray[i].phone + '</div>'
            + '<div class="tiao">' + dataArray[i].company + '</div>'
            + '<div class="tiao wu">' + dataArray[i].address + '</div>'
            + '</div>'
            + '</div>'
            + '</li>'
        )
    }
}
//委托信息放input上
function nei(zj) {
    let id = $(zj).find('.tiao_id').html();//点击当前的ID
    let lei = $(zj).parents('.xin')[0];//判断是送货还是收货
    let input = $('.er .in');//获取委托信息的input
    let nei;//当前选中的内容
    for (let i in arr) {
        if (arr[i].id == id) {
            nei = arr[i];
        }
    }
    
    if ($(lei).hasClass('song')) {
        $(input[0]).val(nei.company);
        $(input[1]).val(nei.name);
        $(input[2]).val(nei.phone);
        $(input[3]).val(nei.address);
        $(input[4]).val(nei.id);
    } else {
        $(input[5]).val(nei.company);
        $(input[6]).val(nei.name);
        $(input[7]).val(nei.phone);
        $(input[8]).val(nei.address);
        $(input[9]).val(nei.id);
    }
    layer.close(layer.index);
    $('html',window.parent.document).css('overflow-y','auto');
}


//收货/发货人的表单提交 
function linkman_btn() {
    var data = $("#linkman_form").serialize();
    toajax(linkmanUrl , data);
}

//发票的信息提交 
function invoice() {
    var data = $("#invoice_form").serialize();
    toajax(invoiceUrl, data);
}



//订单信息的提交
function order_data(zj) {
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
    toajax(OrderUrl, obj);
}

function toajax(url, data) {   
    $.ajax({
        type: 'POST',
        url: url,
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
var mony_fs = 0;
//计算运费
function zong_sum(shu,zs) {     
    var money = $('#money').html();//纯运费
    var sum = $("#container_sum option:selected").val();//柜量
    var bxje = $('#bxje').val() * 6;//保险金额
    var fp = $(".fp01 option:selected").val();//发票
    var zong = money * sum + bxje;//总价格
    if (shu == 1) {//发票6%
        zong = zong * 1.04;
    }else if(shu == 2){//发票10%
        zong = zong * 1.07;
    }
    zong += zs;//装货服务费    
    zong -= mony_fs;//减去优惠价格
    zong = Math.round(zong*100)/100;//保留小数点后面两位    
    $('#price_sum').html(zong); 
}

$('#container_sum').change(function () {//监听柜量
    let shu = 0;//装货数量
    let fa = 0;//发货数量
    let container = $('#container_sum').find("option:selected").val();
    $('.bge .r_num').each(function () {
      shu += Number($(this).val());
    });

    $('.bge_song .s_num').each(function () {
      fa += Number($(this).val());
    });

    if (container < shu || container < fa) {
        alert('当前柜量小于装送货服柜量');
        if (shu >= fa) {
            $('#container_sum').val(shu);
        }else{
            $('#container_sum').val(fa);
        }
    }
    
    youhui();
})

$('.mony_fs').change(function(){ //监听结账方式
    youhui();
});
youhui();
function youhui(){ //优惠价格
    let mony = $(".mony_fs").find("option:selected").attr('title');
    let container = $('#container_sum').find("option:selected").val();
    mony_fs = mony * container;
    $('#discount').html(mony_fs);
    zong_sum(0,0);
}

$('#bxje').bind('input propertychange', function () {//监听保险金额
    zong_sum(0,0);   
    $('#bxje1').html($('#bxje').val() * 6);
});
$('.fp01').change(function () {//发票柜量
    let fp = $(this).children('option:selected').val();
    if (fp == 1) {//6%
        zong_sum(fp,0);    
    } else if (fp == 2) {//10%
        zong_sum(fp,0);
    }else{//0%
        zong_sum(0,0);
    }
});

//计算装货费用
var op = 0;//判断单价数量
var zs_sum = 0;
var zong_zhuang = 0;
var zong_song = 0;
function zhuanghuo(){    
    let dan = 0;
    let shu = 0;
    zong_zhuang = 0;
    $('.bge .r_price,.bge .r_num').each(function(){
        op++;
        if (op%2) {
            dan = $(this).val();
        }else{
            shu = $(this).val();
            zong_zhuang+=dan*shu;
            $('#zhuang').html(zong_zhuang);
        }   
    });    
    if ($('.bge .r_price').length == 0) {
        $('#zhuang').html(0);
        zong_zhuang = 0;
    }
    zs_sum = zong_zhuang + zong_song;
    zong_sum(0,zs_sum);
}

//计算送货费用
function songhuo(){
    let dan = 0;
    let shu = 0;
    zong_song = 0;
    $('.bge_song .s_price,.bge_song .s_num').each(function(){
        op++;
        if (op%2) {
            dan = $(this).val();
        }else{
            shu = $(this).val();
            zong_song+=dan*shu;
            $('#song').html(zong_song);
        }   
    });

    if ($('.bge_song .s_price').length == 0) {
        $('#song').html(0);
        zong_song = 0;
    }
    zs_sum = zong_zhuang + zong_song;
    zong_sum(0,zs_sum);
}


function st(){    
    if (loading) {//判断装货状态
        zhuanghuo();
    }else{
        $('#zhuang').html(0);
        zong_zhuang = 0;
        zs_sum = zong_zhuang + zong_song;        
        zong_sum(0,zs_sum);
    }

    if(delivery){//判断送货状态
        songhuo();
    }else{
        $('#song').html(0);
        zong_song = 0;
        zs_sum = zong_zhuang + zong_song;
        zong_sum(0,zs_sum);
    }
}