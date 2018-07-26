//模态窗口基本设置
$("#modal-default").iziModal({
    title: "航线详情",
    iconClass: 'icon-announcement',
    width: 700,
    padding: 20
});
//启动模态窗
$(document).on('click', '.trigger-default', function (event) {
    event.preventDefault();
    $('#modal-default').iziModal('open');
});

// 日期
layui.use(['form', 'layedit', 'laydate'], function () {
    var form = layui.form
        , layer = layui.layer
        , layedit = layui.layedit
        , laydate = layui.laydate;

    //日期
    laydate.render({
        elem: '#load_time'
    });
    laydate.render({
        elem: '#load_time1'
    });
});

//初始数据
var areaData = Area;//获取所有地区
var inp;//选择文本框并赋值区县
//单击省份
$('#address li').eq(0).click(function () {
    loadProvince();
    $('#address li').removeClass('lanse').eq(0).addClass('lanse');
});
//加载省数据
function loadProvince() {
    $('#dizhi').html('');
    for (let i in areaData) {
        $('#dizhi').append('<a href="javascript:void(0)" onclick="loadCity(' + areaData[i].provinceCode + ')">' + areaData[i].provinceName + '</a>');
    }
    $('#address li').removeClass('lanse').eq(0).addClass('lanse');
}

//加载城市市数据
function loadCity(citys_id) {
    $.each(Area, function (i, data) {
        if (data.provinceCode == citys_id) {
            let arry = data.mallCityList;
            $('#dizhi').html('');
            for (let i in arry) {
                $('#dizhi').append('<a href="javascript:void(0)" onclick="loadPort(' + arry[i].cityCode + ')">' + arry[i].cityName + '</a>');
            }
            if (arry == false) {
                $(inp).val(data.provinceName);
                $('#address').hide();
            }
            $('#address li').removeClass('lanse').eq(1).addClass('lanse');
        }
    })
}

// 加载区县
function loadPort(areas_id) {
    $.each(Area, function (j, data) {
        let arry = data.mallCityList;
        for (let i in arry) {
            if (arry[i].cityCode == areas_id) {
                $('#dizhi').html('');
                for (let h in arry[i].mallAreaList) {
                    $('#dizhi').append('<a href="javascript:void(0)" onclick="jie_dao(' + arry[i].mallAreaList[h].areaCode + ')">' + arry[i].mallAreaList[h].areaName + '</a>');
                }
                if (arry[i] == false) {
                    $(inp).val(arry[i].cityName);
                    $('#address').hide();
                }
                $('#address li').removeClass('lanse').eq(2).addClass('lanse');
            }
        }
    })
}

// 加载街道
function jie_dao(jie_id) {
    $.each(Area, function (j, data) {
        let arry = data.mallCityList;
        for (let i in arry) {           
            for (let h in arry[i].mallAreaList) {
                if (arry[i].mallAreaList[h].areaCode == jie_id) {
                    $('#dizhi').html('');
                    // for (let g in arry[i].mallAreaList[h]) {
                    //     console.log(arry[i].mallAreaList[h]);
                    // }
                    $(inp).val(arry[i].mallAreaList[h].areaName);
                    $('#address').hide();
                    // if (arry[i].mallAreaList[h] == false) {
                        
                    // }
                }
            }
            
            $('#address li').removeClass('lanse').eq(2).addClass('lanse');
        }
    })
}

$('#start_add').focus(function () {//选择地址
    inp = $('#start_add');
    $('#address').css('top', '80px');
    $('#address').show();
    loadProvince();  //默认展示省份
})
$('#end_add').focus(function () {//选择地址
    inp = $('#end_add');
    $('#address').css('top', '140px');
    $('#address').show();
    loadProvince();  //默认展示省份
})

var cli = true;//判断是否点击
$('#search_price').click(function () {
    cli = false;
})
$(document).click(function () {//判断是否点击地址元素 否则隐藏
    if (cli) {
        $('#address').hide();
    } else {
        cli = true;
    }
})