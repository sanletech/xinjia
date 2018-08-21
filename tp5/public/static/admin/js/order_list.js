var list = []; //选中的运单号集装箱
$('.ydh li a').click(function(e){//点击运单号显示隐藏集装箱
    $('#yd li').eq($(this).parent().index()).find('dl').toggle();
});

//点击全选/全不选
$('#yd li>div').click(function(){
    if($(this).hasClass('layui-form-checked')){
        $(this).siblings('dl').find('div').removeClass("layui-form-checked");
    }else{
        $(this).siblings('dl').find('div').addClass("layui-form-checked");
    }
})
//确认
$('#yd .yes').click(function(){
    var nei = $('#yd li dl dd>div');
    for (let i=0;i<nei.length; i++) {
        if($(nei[i]).hasClass('layui-form-checked')){
            shu($(nei[i]).parents('dl').siblings('a').html(),$(nei[i]).find('span').html())
        }
    }
    if(list.length == 0){
        alert("请选择运单号集装箱");
    }
});
var st = '';
var i = 0;
function shu(a,b){
    if(st == a){
        list[i][st].push(b);
    }else{
        if(st){
            i++;
        }
        list[i] = {[a]:[b]};
    }
    st = a;
}


