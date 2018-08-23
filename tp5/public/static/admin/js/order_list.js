var list; //选中的运单号集装箱
var j;
var st;
$('.ydh li a').click(function(e){//点击运单号显示隐藏集装箱
    $(this).next().toggle();
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
    list = [];
    j = 0;
    st = '';
    let nei = $(this).parent('div').siblings('.ydh').find('li dl dd>div');
    for (let i=0;i<nei.length; i++) {
        if($(nei[i]).hasClass('layui-form-checked')){
            shu($(nei[i]).parents('dl').siblings('a').html(),$(nei[i]).find('span').html());
        }
    }
    if(list.length == 0){
        alert("请选择运单号集装箱");
    }
    console.log(list);//获取选中的数据
});
function shu(a,b){  
    if(st == a){
        list[j][st].push(b);
    }else{
        if(st){
            j++;
        }
        list[j] = {[a]:[b]};
    }
    st = a;
}


