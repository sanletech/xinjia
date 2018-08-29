var list; //选中的运单号集装箱
var j;
var st;
//$('.ydh li a').click(function(e){//点击运单号显示隐藏集装箱
//    $(this).next().toggle();
//});

////点击全选/全不选
//$('#yd li>div').click(function(){
//    if($(this).hasClass('layui-form-checked')){
//        $(this).siblings('dl').find('div').removeClass("layui-form-checked");
//    }else{
//        $(this).siblings('dl').find('div').addClass("layui-form-checked");
//    }
//})
$('.ydh li dl').show();
$('.ydh li dl dd').css('float','left');
//确认
$('#yd .yes').click(function(){
    list = [];
    j = 0;
    st = '';
    let nei = $(this).parent('div').siblings('.ydh').find('li dl dd>div');
    for (let i=0;i<nei.length; i++) {
        if($(nei[i]).hasClass('layui-form-checked')){
//            shu($(nei[i]).parents('dl').siblings('a').html(),$(nei[i]).find('span').html());
              list.push({'container_code':$(nei[i]).find('span').html()});
        }
    }
    let order_num = $(this).parent('div').parent('div').prev().find('#order_num');
    list.push({[order_num.attr('name')]:order_num.attr('value')});
    if(list.length == 0){
        alert("请选择运单号集装箱");
    }
    toajax(list);
});
function shu(a,b){  
    if(st == a){
        list[j][st].push(b);
    }else{
        if(st){
            j++;
        }
//        list[j] = {[a]:[b]};
          list[j] = {'container_code':[b]};
    }
    st = a;
}


