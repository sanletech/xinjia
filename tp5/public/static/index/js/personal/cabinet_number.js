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

if($('.fukuan').html() == '去付款'){
    $('.fukuan').html('<a  target="_blank" href="/order/harbor_order.html?sea_id=1&s_car_id=39&r_car_id=36&container_size=40HQ">去付款</a>');
}