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
var areaData = JS_PORT;//获取所有地区
var inp;//选择文本框并赋值区县
var boot;//判断地址是否是起点还是终点
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
            inp.val('');
            if (boot) {
                add.start_id = data.provinceCode;
                add.start_name = data.provinceName;
            } else {
                add.end_id = data.provinceCode;
                add.end_name = data.provinceName;
            }

            inp.val(data.provinceName);//当前选中的值
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
                if (boot) {
                    add.start_id = arry[i].cityCode;
                    add.start_name = arry[i].cityName;
                } else {
                    add.end_id = arry[i].cityCode;
                    add.end_name = arry[i].cityName;
                }
                inp.val(inp.val() + arry[i].cityName);//当前选中的值
                $('#address li').removeClass('lanse').eq(2).addClass('lanse');
            }
        }
    })
}

function jie_dao(dao_id,zj) {
    if (boot) {
        add.start_id = dao_id;
        add.start_name = $(zj).html();
    } else {
        add.end_id = dao_id;
        add.end_name = $(zj).html();
    }
    inp.val(inp.val() + $(zj).html());//当前选中的值
    $('#address').hide();
}


$('#start_add').focus(function () {//选择地址
    inp = $('#start_add');
    boot = true;
    $('#address').css('top', '80px');
    $('#address').show();
    loadProvince();  //默认展示省份
})
$('#end_add').focus(function () {//选择地址
    boot = false;
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