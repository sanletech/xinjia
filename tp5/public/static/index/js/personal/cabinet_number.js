function cabinet(od_num,gui,zj) { 
    $('#gui').val(gui);
    $('#order_number').val(od_num);
    $.get(track_data, { order_num: od_num },//获取柜量
    function(data){
        $('.ggh').html('');
        for (let i = 0; i < data.length; i++) {
            data[i].container_code =data[i].container_code ? data[i].container_code:'';
            data[i].seal = data[i].seal ? data[i].seal : '';
            $('.ggh').append('<div class="layui-form-item">'+
            '<div class="guinei"><input name="id[]" type="hidden" value="'+data[i].id+'">'+
            '<input name="container_code[]" placeholder="" autocomplete="off" class="layui-input" type="text" value="'+data[i].container_code+'"></div>'+
            '<div class="layui-form-mid">-</div>'+
            '<div class="guinei"><input name="seal[]" placeholder="" autocomplete="off" class="layui-input" type="text" value="'+data[i].seal+'"></div>'+
        '</div>');
        };
        order_num(zj);
    });
}

//弹出报柜号
function order_num(zj){
    let stast = $(zj).parent().siblings('.caozuo').find('a').html();       
    if (stast == '进行中') {
    layer.open({
        type: 1,
        title: '报柜号',
        content: $('#cabinet'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
        offset: '0px',
        area: ['700px'],
        shadeClose: true,
        skin: 'demo-class',
        btn: ['确认'],
        yes: function(index, layero){
            //按钮【按钮一】的回调
            $.post(track_num, $('#cabinet form').serialize(),function(res){
                if (res.status == 1) {
                    layer.msg('提交成功', {icon: 6,time: 1000},function(){
                        layer.close(index);
                    });
                }else if(res.status == 2){
                    $(zj).parent().html('已补料');
                    layer.msg('已完成柜号', {icon: 6,time: 3000},function(){
                        layer.close(index);
                    });
                }else{
                    layer.msg('柜号封号一对提交', {icon: 5,time: 1000});
                }
            });
        }
    });           
  }else{
    layer.msg(stast, {icon: 5,time: 1000});
  }
}

if ($('.fukuan').html() == '未付款') {
    $('.fukuan').css('color','red');
}else{
    $('.fukuan').css('color','#00DB00');
}
var huo = $('.goods a');//货物状态
var st = $('.caozuo a');//订单状态
var tj = $('.tj_gh');//提交柜号

for (let i = 0; i < huo.length; i++) {
    if(huo.eq(i).html() == 'lock'){
        huo.eq(i).html('扣货');
        if (st.eq(i).html() == '进行中') {
            huo.eq(i).css('background-color','#FF2400');
        }
    }else if(huo.eq(i).html() == 'unlock'){
        huo.eq(i).html('放货');
        if (st.eq(i).html() == '进行中') {
            huo.eq(i).css('background-color','#00DB00');
        }
    }else if(huo.eq(i).html() == 'apply'){
        huo.eq(i).html('申请中');
        // huo.eq(i).css('background-color','#C9C9C9');
    }
    
    if (st.eq(i).html() == '进行中') {        
        tj.eq(i).find('a').css('background-color','#FF2400');        
    }else if(st.eq(i).html() == '已取消'){
        st.eq(i).parent('td').siblings('.fukuan').css('color','#C9C9C9');
        st.eq(i).parent('td').siblings('.ddh').find('a').css('color','#C9C9C9')        
    }
}

$('.goods a').click(function(){
    let order_num = $(this).parent().siblings('.ddh').find('a').html();
    let stast = $(this).parent().siblings('.caozuo').find('a').html();
    if ($(this).html() == '扣货' && stast == '进行中') {
        layer.open({
            type:1
            ,title: '货物状态'
            ,shadeClose :true
            ,btn:['申请放货','取消']
            ,content: $('#state_goods')
            ,yes:function (index, layero) {
                $.get(apply_cargo_url,{'order_num':order_num},function(res){
                    console.log(res);
                    
                    if(res.status){
                        // alert('申请成功');
                        layui.layer.msg('申请成功',{icon: 2,time: 500},function(){
                            layer.close(index);
                        });
                        location.reload();
                    }else{
                        // alert('申请失败');
                        layui.layer.msg('申请失败',{icon: 2,time: 1000});
                    }
                    
                });
                
            },function (index, layero) {
                layer.close(index);
            }
        });     
    }
});




//初始数据
var areaData = JS_PORT;//获取所有地区
var inp;//选择文本框并赋值区县
var boot;//判断地址是否是起点还是终点


$('#start_add').focus(function () {//选择地址
    inp = $('#start_add');
    boot = true;
    $('#address').css('left', '200px');
    $('#address').show();
    loadProvince();  //默认展示省份
})
$('#end_add').focus(function () {//选择地址
    boot = false;
    inp = $('#end_add');
    $('#address').css('left', '400px');
    $('#address').show();
    loadProvince();  //默认展示省份
})

var cli = true;//判断是否点击
$('.sousuo').click(function () {
    cli = false;
})
$(document).click(function () {//判断是否点击地址元素 否则隐藏
    if (cli) {
        $('#address').hide();
    } else {
        cli = true;
    }
})

//单击省份
$('#address li').eq(0).click(function () {
    loadProvince();
    $('#address li').removeClass('lanse').eq(0).addClass('lanse');
});


//加载省数据
function loadProvince() {
    $('#dizhi').html('');
    for (let i in areaData) {
        $('#dizhi').append('<a onclick=loadCity(' + areaData[i].provinceCode + ')>' + areaData[i].provinceName + '</a>');
    }
    $('#address li').removeClass('lanse').eq(0).addClass('lanse');
}

//加载港口
function loadCity(citys_id) {
    $.each(areaData, function (i, data) {
        if (data.provinceCode == citys_id) {
            let arry = data.mallCityList;
            $('#dizhi').html('');
            for (let i in arry) {
                $('#dizhi').append('<a href="javascript:void(0)" onclick="loadPort(' + arry[i].cityCode + ')">' + arry[i].cityName + '</a>');
            }
            if (arry == false) {
                $('#address').hide();
            }
            $('#address li').removeClass('lanse').eq(1).addClass('lanse');//选中城市
        }
    })
}

// 加载港口
function loadPort(areas_id) {
    $.each(areaData, function (j, data) {
        let arry = data.mallCityList;        
        for (let i in arry) {
            if (arry[i].cityCode == areas_id) {
                $('#dizhi').html('');
                for (let h in arry[i].mallPortList) {
                    $('#dizhi').append('<a href="javascript:void(0)" onclick="jie_dao(' + arry[i].mallPortList[h].portCode + ',this)">' + arry[i].mallPortList[h].portName + '</a>');
                }
                if (arry[i] == false) {//当后面没有数据的时候
                    $('#address').hide();
                }
                $('#address li').removeClass('lanse').eq(2).addClass('lanse');
            }
        }
    })
}

function jie_dao(dao_id,zj) {
    if (boot) {
        $('#start_id').val(dao_id);//赋值港口ID
    } else {
        $('#end_id').val(dao_id);//赋值港口ID
    }
    inp.val($(zj).html());//当前选中的值
    $('#address').hide();
}