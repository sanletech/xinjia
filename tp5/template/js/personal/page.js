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
      elem: 'demo7'
      ,count: 100
      ,theme: '#2e8de5'
      ,layout: ['count', 'prev', 'page', 'next', 'limit', 'skip']
      ,jump: function(obj){
        console.log(obj)
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
