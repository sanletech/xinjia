<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\news.html";i:1529651522;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1530520898;}*/ ?>
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
      <link rel="stylesheet" href="/static/index/css/news.css">
    <div class="banner"></div>
    <div class="news">
      <div class=" news_top">
        <div class="hh1">
          <h1><strong>新闻中心 / </strong>News</h1>
          <div class="heng"></div>
        </div>
      </div>
      <div class="news_nei">
        <ul id="xinwen">
          <li>全部咨询</li>
          <li>行业资讯</li>
          <li>企业资讯</li>
          <li>产品资讯</li>
          <li>养生常识</li>
        </ul>
        <div class="xw">
          <div class="xw_tou layui-row">
            <div class="layui-col-xs6 xw_tu">
              <img src="" alt="图片">
            </div>
            <div class="layui-col-xs6">
              <div class="tou_ti">
                <h3>2018年，跨境电商们的机遇与挑战</h3>
                <p>2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了</p>
                <p>2018-01-06</p>
              </div>
            </div>
          </div>
          <div class="xw_nei layui-row">
            <ul>
              <li>
                <div class="layui-col-xs1">
                  <div class="date">
                    <strong>05</strong>
                    <p>2018-03</p>
                  </div>
                </div>

                <div class="xw_n layui-col-xs10">
                  <h3>2018年，跨境电商们的机遇与挑战</h3>
                  <p>2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了客了洒离开的分</p>
                </div>
              </li>
              <li>
                <div class="layui-col-xs1">
                  <div class="date">
                    <strong>05</strong>
                    <p>2018-03</p>
                  </div>
                </div>
                <div class="xw_n layui-col-xs10">
                  <h3>2018年，跨境电商们的机遇与挑战</h3>
                  <p>2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了</p>
                </div>
              </li>
              <li>
                <div class="layui-col-xs1">
                  <div class="date">
                    <strong>05</strong>
                    <p>2018-03</p>
                  </div>
                </div>

                <div class="xw_n layui-col-xs10">
                  <h3>2018年，跨境电商们的机遇与挑战</h3>
                  <p>2018已经到来随机的分了洒家啊了房间撒开了家房客了洒家房客了洒离开的分家啊洒开来房客了洒进店分啊开来随机的分了</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="xw">
          456
        </div>
        <div class="xw">
          789
        </div>
        <div class="xw">
          5
        </div>
        <div class="xw">
          6
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $('#xinwen li').eq(0).addClass('n');
      $('.news_nei .xw').eq(0).show();
      $('#xinwen li').click(function(event) {
        $(this).addClass('n').siblings().removeClass('n');
        $('.news_nei .xw').eq($(this).index()).show().siblings('.xw').hide();
      });
    </script>
</body>
</html>
