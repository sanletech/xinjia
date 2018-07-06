<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\index.html";i:1530871061;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1530520898;}*/ ?>
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
    <link rel="stylesheet" href="/static/index/css/index.css">
    <div class="home">
      <!-- 搜索 -->
      <div class="home_top">
        <h1>全球集装箱跟踪&nbsp;&nbsp;&nbsp;&nbsp;尽在海浪互联平台</h1>
        <div class="home_sou">
          <ul id="jzx">
            <li>集装箱查询</li>
            <li>船舶GPS定位</li>
            <li>查询历史记录</li>
          </ul>
          <form class="ss" action="#" method="post">
            <input class="ding layui-col-xs5" type="text" placeholder="请输入运单号">
            <span>|</span>
            <input  class="yun layui-col-xs6" type="text" placeholder="请输入集装箱号">
            <button type="submit">搜索</button>
        </form>
        <form class="ss" action="#" method="post" >
          <input  class="ding layui-col-xs11" type="text" placeholder="请输入船名">
          <button type="submit">搜索</button>
        </form>
        </div>
      </div>
      <!-- 新闻咨询 -->
      <div class="home_center">

        <div class="home_le">

          <div class="tu"><img src="/static/index/image/index/xw.jpg" alt="图片" style="width:100%;"></div>
          <ul>
            <li>
                <div class="date">
                  <strong>05</strong>
                  <p>2018-03</p>
                </div>

                <div class="xw">
                  <h3>2018年，跨境电商们的机遇与挑战</h3>
                  <p>2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了</p>
                </div>

            </li>
            <li>
              <div class="date">
                <strong>05</strong>
                <p>2018-03</p>
              </div>
              <div class="xw">
                <h3>2018年，跨境电商们的机遇与挑战</h3>
                <p>2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了</p>
              </div>
            </li>
            <li>
              <div class="date">
                <strong>05</strong>
                <p>2018-03</p>
              </div>

              <div class="xw">
                <h3>2018年，跨境电商们的机遇与挑战</h3>
                <p>2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了</p>
              </div>
            </li>
          </ul>
        </div>

        <!-- 通知通告 -->
        <div class="home_rig">
          <div class="tu"><img src="/static/index/image/index/gg.jpg" alt="图片" style="width:100%;"></div>
          <div class="tong">
            <ul>
              <li>
                <h3>关于2018年端午节放假通知</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>关于2018年端午节放假通知</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>关于2018年端午节放假通知</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>关于2018年端午节放假通知</h3>
                <p>2018-03-02</p>
              </li>
              <li>
                <h3>关于2018年端午节放假通知</h3>
                <p>2018-03-02</p>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- 关于我们 -->
      <div class="foot_di">
        <div class="hh1">
          <h1><strong>关于我们/</strong>About us</h1>
          <div class="heng"></div>
        </div>
        <div class="di_nei">
          <div class="di_le">
            <div class="di_sp">
                              视频
              <video id="myvideo">
                <source src="#">视频</source>
              </video>
            </div>
            <div class="di_xw">新闻</div>
          </div>
          <div class="di_rig">
            <div class="di_sp">内容</div>
            <div class="di_xw">我的荣誉</div>
          </div>
        </div>
      </div>

      <!-- 底部 -->
    </div>
    
    <script type="text/javascript">
      $('#jzx li').eq(0).addClass('xuan');
      $('.home_top .ss').eq(0).show();
      $('#jzx li').click(function(event) {
        $(this).addClass('xuan').siblings().removeClass('xuan');
        $('.home_top .ss').eq($(this).index()).show().siblings('.ss').hide();
      });

    </script>
  </body>
</html>