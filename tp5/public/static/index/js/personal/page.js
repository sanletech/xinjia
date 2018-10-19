layui.use('table', function(){
  var table = layui.table;

  //第一个实例
  table.render({
  });
});

//分页
layui.use(['laypage', 'layer'], function(){
  var laypage = layui.laypage
  ,layer = layui.layer;

  laypage.render({
            elem: 'pages',
            limit: limit,
            limits: [5, 10, 15],
            count: count,
            layout: ['count', 'prev', 'page', 'next', 'limit', 'skip'],
            curr: page,
            theme: '#c00',
            jump: function (obj, first) {
              //obj包含了当前分页的所有参数，比如：
              //console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
              //console.log(obj.limit); //得到每页显示的条数
              //首次不执行
              if (!first) {
                //do something
               
                window.location.href = url+"?page=" + obj.curr + '&limit=' + obj.limit;
              }
        }
    });
  });

layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate;

  //日期
  laydate.render({
    elem: '#date'
  });
  laydate.render({
    elem: '#date1'
  });
});
