

//初始数据
var areaData = JS_PORT;//获取所有地区
var inp;//选择文本框并赋值区县
var boot;//判断地址是否是起点还是终点


$('#start_add').focus(function () {//选择地址
    inp = $('#start_add');
    boot = true;
    $('#address').css('left', '323px');
    $('#address').show();
    loadProvince();  //默认展示省份
})
$('#end_add').focus(function () {//选择地址
    boot = false;
    inp = $('#end_add');
    $('#address').css('left', '537px');
    $('#address').show();
    loadProvince();  //默认展示省份
})

var cli = true;//判断是否点击
$('.layui-form-item').click(function () {
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


//船公司
var shipData =JS_SHIP_BOAT;//船公司
var ship_id = $('.ship_id').html();//船公司ID
for (let i = 0; i < shipData.length; i++) {
if(i+1 == ship_id){
    $('#ship_company').append("<option value='" + shipData[i].ship_id + "' selected>" + shipData[i].ship_name + "</option>");
}else{
    $('#ship_company').append("<option value='" + shipData[i].ship_id + "'>" + shipData[i].ship_name + "</option>");
}
}