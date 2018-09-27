function cabinet() {
    layer.open({
        type: 1,
        title: '报柜号',
        content: $('#cabinet'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
        offset: '0px',
        area: ['700px'],
        skin: 'demo-class',
        btn: ['确认'],
        yes: function(index, layero){
            //按钮【按钮一】的回调
            let list = $('#cabinet form').serialize();
            console.log(list);
        }
    });
}

if($('.fukuan a').html() == '已付款'){
    $('.fukuan a').css('background-color','#00DB00').attr('href','javascript:void(0);');

}else{
    $('.fukuan a').attr('href',xiangqing);
}