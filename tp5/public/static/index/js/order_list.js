
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
        $('#dizhi').append('<a href="javascript:void(0)" onclick=loadCity(' + areaData[i].provinceCode + ')>' + areaData[i].provinceName + '</a>');
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

// 加载区县
function loadPort(areas_id) {
    $.each(Area, function (j, data) {
        let arry = data.mallCityList;
        for (let i in arry) {
            if (arry[i].cityCode == areas_id) {
                $('#dizhi').html('');
                for (let h in arry[i].mallAreaList) {
                    let st = arry[i].mallAreaList[h].areaCode + ",'" + arry[i].mallAreaList[h].areaName + "'";
                    $('#dizhi').append('<a href="javascript:void(0)" onclick="jie_dao(' + st + ')">' + arry[i].mallAreaList[h].areaName + '</a>');
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

// 加载街道
function jie_dao(jie_id,jie_name) {
    inp.val(inp.val() + jie_name);//当前选中的值
    if (boot) {
        add.start_id = jie_id;
        add.start_name = jie_name;
    } else {
        add.end_id = jie_id;
        add.end_name = jie_name;
    }
    var townurl = addressURL + '?twoncode=' + jie_id;
    $.ajax({//通过AJAX去数据库获取街道值
        type: 'POST',
        url: townurl,
        dataType: "json",
        success: function (data) {
            if (data) {
                let arry = data;
                arry = JSON.parse(arry)
                arry = eval('(' + arry + ')')
                $('#dizhi').html('');
                for (let i in arry) {
                    $('#dizhi').append('<a href="javascript:void(0)" onclick="add_food(' + i + ',this)">' + arry[i] + '</a>');
                }
                $('#address li').removeClass('lanse').eq(3).addClass('lanse');
            } else {
                $('#address').hide();
            }

        },
    })
}

//街道之后
function add_food(dao_id, zj) {
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
    $('#address,#select_add').css('left', '0px');
    if ($(this).val()) {
        $('#address').hide();
        $('#select_add').show();
    }else{
        $('#address').show();
        $('#select_add').hide();
    }
    
    loadProvince();  //默认展示省份
})
$('#end_add').focus(function () {//选择地址
    boot = false;
    inp = $('#end_add');
    $('#address,#select_add').css('left', '25%');
    if ($(this).val()) {
        $('#address').hide();
        $('#select_add').show();
    }else{
        $('#address').show();
        $('#select_add').hide();
    }
    loadProvince();  //默认展示省份
})

var cli = true;//判断是否点击
$('#search_price').click(function () {
    cli = false;
})
$(document).click(function () {//判断是否点击地址元素 否则隐藏
    if (cli) {
        $('#address').hide();
        $('#select_add').hide();        
    } else {
        cli = true;
    }
});

var add_di = []; 
for (let i = 0; i < addres.length; i++) {//省    
    for (let j = 0; j < addres[i].children.length; j++) {//市
        let arry =  addres[i].children[j].children; 
        for (let g = 0; g < arry.length; g++) {//区
            for (let h = 0; h < arry[g].children.length; h++) {//街道
                add_di.push({id:arry[g].code,name:addres[i].name+ addres[i].children[j].name+arry[g].name+arry[g].children[h].name}); 
            }            
        }
    }
}
$('#start_add,#end_add').on(" input propertychange",function(){
    var p = 0;
    if ($(this).val()) {
        $('#address').hide();
        $('#select_add').show();
        $('#select_add ul').html('');//清空地址栏
        for (let i = 0; i < add_di.length; i++) {
            if (add_di[i].name.indexOf($(this).val()) != -1) {//查找相关信息
                if (p < 30) {
                    p++;//最多查询30条
                    $('#select_add ul').append('<li name='+add_di[i].id+'>'+add_di[i].name+'</li>');//地址展现
                }else{
                    return false;
                }
            }
        }
    }else{
        $('#address').show();
        $('#select_add').hide();
    }
});

$('#select_add').on('click','li',function(){
    inp.val($(this).html());
    if (inp.attr('id') == 'start_add') {
        add.start_id = $(this).attr('name');
    }else{
        add.end_id = $(this).attr('name');;
    }
    $('#select_add').hide();
})