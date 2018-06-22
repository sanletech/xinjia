<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\xxtx.html";i:1529651522;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1529651522;}*/ ?>
<!-- 下单 -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/index/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/index/css/font.css">
    <link rel="stylesheet" href="/static/index/css/index.css">
    <link rel="stylesheet" href="/static/index/css/top.css">
    <link rel="stylesheet" href="/static/index/css/foot.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="/static/index/js/jquery-1.9.0.min.js"></script>
    <script src="/static/index/layui/layui.js"></script>
</head>
  <body>
      <link rel="stylesheet" href="/static/index/css/xxtx.css">
      <link rel="stylesheet" href="/static/index/css/xiala.css">
    <div class="banner"></div>
    <div class="xxtx">
      <div class="xxtx_dd">
        <div class="xx">
          <strong>订单信息</strong>
        </div>

        <div class="dd layui-row layui-col-space10">
          <div class="layui-col-xs4 tu">
            <div class="grid-demo grid-demo-bg1">图片</div>
          </div>

          <!-- 航线 -->
          <div class="layui-col-xs8 dd_nei">
            <div class="grid-demo">
              <div class="dd_yu layui-col-xs6">
                <div class="dd_lu">
                  <div class="layui-col-xs4 a">
                    <strong>广州市</strong>
                    <i class="fa fa-long-arrow-right fa-lg"></i>
                  </div>
                  <div class="layui-col-xs4 c">
                    <i class="fa fa-ship fa-2x"></i>
                  </div>

                  <div class="layui-col-xs4 b">
                    <i class="fa fa-long-arrow-right fa-lg"></i>
                    <strong>阿木特但</strong>
                  </div>
                </div>
                <!-- 预计日期 -->
                <div class="yj">
                  <div class="yj_start">
                    <span>截单时间</span><span>2018-03-10&nbsp;8：00</span></div>
                  <div class="yj_end">
                    <span>船期</span><span>2018-03-10&nbsp;8：00</span>
                  </div>
                </div>

              </div>

              <div class="dd_yu bian layui-col-xs6">
                <div class="dd_lu">
                  <span>下单日期</span><span>2018-03-10</span>
                </div>
                <!-- 预计日期 -->
                <div class="yj">
                  <div class="yj_start">
                    <span>海上时效</span><span>2018-03-10&nbsp;8：00</span>
                  </div>
                  <div class="yj_end">
                    <span>装货时间</span><span>2018-03-10&nbsp;8：00</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="layui-col-xs8 dd_ming">
            <div class="layui-col-xs4">船名：<span>长通</span></div>
            <div class="layui-col-xs4">航次：<span>IC10</span></div>
            <div class="layui-col-xs4">运输条款：<span>门到们</span></div>
            </div>
          </div>

        </div>

        <!-- 货物信息填写 -->

          <div class="xxtx_dd er">
            <div class="xx">
              <strong>货物信息填写</strong>
            </div>
            <div class="layui-row">
            <div class="layui-col-xs3 er_zhuang">
              <span>*</span><d>集装箱规格：</d>
              <div class="select">
                <select name='make'>
                  <option value='0' selected>请选择</option>
                  <option value='20GP'>20GP</option>
                  <option value='30GP'>40HQ</option>
                </select>
              </div>
            </div>
            <div class="layui-col-xs3 er_zhuang">
              <span>*</span>集装箱柜量：<input type="text" value="" placeholder="请输入柜量">
            </div>

            <div class="layui-col-xs3 er_zhuang">
              <span>*</span>保险：<input type="text" value="" placeholder="请输入保险金额">
            </div>
            <div class="layui-col-xs3 er_zhuang">
              <span>*</span>发票：
              <div class="select">
                <select name='make'>
                  <option value='0' selected>无发票</option>
                  <option value='20GP'>6%增值税</option>
                  <option value='30GP'>11%增值税</option>
                </select>
              </div>
            </div>
          </div>
          <!-- 总运费 -->
          <div class="money">
            <strong>总运费：￥12325</strong>
          </div>
          <div class="er_an">
            <div class="an_cen">
              <button>确认</button>
              <a href="<?php echo url('index/lrdd'); ?>">下一步</a>
            </div>
          </div>
        </div>

        <!-- 航线介绍 -->
        <div class="xxtx_dd er">
          <div class="xx">
            <strong>航线介绍</strong>
          </div>
          <div class="hx">
            费用
          </div>
        </div>
    </div>
</body>
</html>
