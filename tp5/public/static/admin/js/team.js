$('.you').children('input').addClass('xian').attr('readonly', true);
$('.tij,.xin').hide();
$('.x-nav .an').click(function () {
    $('#new_team').append('<div class="layui-input-inline">' +
        '<input name="cutoff_date" class="layui-input" value="" type="text">' +
        '</div>');
    $('.tij,.xin').show();
});
var i = 0;
$('.bumen a').click(function () {
    if (i) {//取消编辑
        if ($(this).html() == '编辑') {
            $('.tij').hide();
            $('.you').children('input').addClass('xian').attr('readonly', true);
            i = 0;
        }
    } else {//编辑
        if ($(this).html() == '编辑') {
            $('.tij').show();
            $('.you').children('input').removeClass('xian').attr('readonly', false);
            i = 1;
            let id = $(this).attr('id');
        }
    }
});