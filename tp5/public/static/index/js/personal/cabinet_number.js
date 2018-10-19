function cabinet(od_num,gui) { 
    $('#gui').val(gui);
    $('#order_number').val(od_num);
    $.get(track_data, { order_num: od_num },//获取柜量
    function(data){
        $('.ggh').html('');
        for (let i = 0; i < data.length; i++) {
            $('.ggh').append('<div class="layui-form-item">'+
            '<div class="guinei"><input name="container_num[]" type="hidden" value="'+data[i]+'">'+
            '<input name="container_code[]" placeholder="" autocomplete="off" class="layui-input" type="text" value=""></div>'+
            '<div class="layui-form-mid">-</div>'+
            '<div class="guinei"><input name="seal[]" placeholder="" autocomplete="off" class="layui-input" type="text" value=""></div>'+
        '</div>');
        };
        order_num();
    });
}

//弹出报柜号
function order_num(){
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
            $.post(track_num, $('#cabinet form').serialize());
        }
    });
}

if($('.fukuan a').html() == '已付款'){
    $('.fukuan a').css('background-color','#00DB00').attr('href','javascript:void(0);');
}else{
    $('.fukuan a').attr('href',xiangqing);
}

if ($('.ddh a').html()) {
    $('.ddh a').css('background-color','#00DB00');
}