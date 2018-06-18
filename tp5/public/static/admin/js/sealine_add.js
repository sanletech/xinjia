//给航线详情添加样式
$('.bu>input,.bu>span>button').css('margin', '5px');

//当确认起点和终点时
$('.price').click(function () {
    var qi = $('select[name=price_start] option:selected');
    var zhong = $('select[name=price_end] option:selected');
    $('.bu>button').eq(0).attr('id', qi.val() + '_' + qi.text()).find('span').html(qi.text()).siblings('input').val(qi.val());
    $('.bu>button').eq(1).attr('id', zhong.val() + '_' + zhong.text()).find('span').html(zhong.text()).siblings('input').val(zhong.val());
});

function jj() {
    var list = [];
    $('#search_port input').each(function () {
        list.push($(this).val());
    });
    var shu = all(list);
    for (let i = 0; i < shu.length; i++) {
        console.log('起点'+shu[i]+'终点');
        
    }
}
//取消关闭模态框
$('.cancel').click(function () {
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
});

//列出所有详情的组合
function all(a) {
    var s = []; //深复制
    var answer = [];//最终结果
    var st = ''; //组合的结果
    //根据下标递归循环
    for (let i = 0; i < a.length; i++) {
        answer.push(a[i]);
        if (i == 0) {
            s = a.slice(0);  //深复制
        }
        st += s.splice(0, 1); //删除数组下标指定的元素长度为1     
        for (let j = 0; j < s.length; j++) {
            let t = s.slice(0);  //深复制
            let tt = st;
            yi(tt, t, j);
            function yi(tt, t, j) {
                if (t.length > j) {
                    tt += t.splice(j, 1); //删除下标元素并且跟删除的元素进行拼接                 
                    answer.push(tt);  //数组最后以为进行追加
                    yi(tt, t, j);
                }
            }
        }

        for (let j = 0; j < s.length; j++) {
            let t = s.slice(0);  //深复制
            let tt = t.splice(0, 1);
            tt = tt.toString();
            yi(tt, t, j);
            function yi(tt, t, j) {
                if (t.length > j) {
                    tt += t.splice(j, 1);
                    answer.push(tt);
                    yi(tt, t, j);
                }
            }
        }
    }
    //去除重复
    function qu(array) {
        return Array.from(new Set(array));
    }
    return qu(answer);
    // console.log(JSON.stringify(qu(answer)));
    // console.log(qu(answer));
}

