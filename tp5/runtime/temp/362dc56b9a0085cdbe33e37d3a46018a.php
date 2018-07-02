<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:74:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\top.html";i:1530532388;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1530532388;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\foot.html";i:1529718403;}*/ ?>
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
    <header>
      <div id="top">
        <div class="logo">
          <img src="/static/index/image/logo.jpg" alt="">
        </div>
        <ul> 
          <li class="hang">
            <a href="javascript:;">首页</a>
          </li>
          <li>
            <a href="javascript:;">公共查询</a>
          </li>
          <li>
            <a href="<?php echo url('Order/hyyj'); ?>">海运运价</a>
          </li>
          <li>
            <a href="javascript:;">集装箱出售</a>
          </li>
          <li>
            <a href="javascript:;">新闻中心</a>
          </li>
          <li>
            <a href="javascript:;">合伙人加盟</a>
          </li>
          <li>
            <a href="javascript:;">帮助与公告</a>
          </li>
          <li>
            <a href="javascript:;">个人中心</a>
          </li>
        </ul>
      </div>
    </header>

    <nav>
      <iframe src="<?php echo url('index/index1'); ?>" frameborder="0" scrolling="no" id="ifram" onload="this.height=100" style="min-height:600px;"></iframe>
    </nav>
    <footer>
      <div class="foot">
    <div class="lx">
        <p>联系我们</p>
        <p>地址：广东省广州市黄埔区港弯路59号中交港湾国际大厦2005室</p>
        <p>手机：13825001413</p>
        <p>电话：020-28211730</p>
        <p>邮箱：1572154495@qq.com</p>
    </div>
    <div class="rwm">
        <div class="r1">
            <img src="/static/index/image/rwm/chen.jpg" alt="">
            <p>加我好友</p>
        </div>
        <div class="r2">
            <img src="/static/index/image/rwm/chen.jpg" alt="">
            <p>关注海浪公众号</p>
        </div>
    </div>
</div>
<div class="bp">
    <div>@2017&nbsp;&nbsp;广州市海浪科技有限公司&nbsp;&nbsp;&nbsp;&nbsp;版权所有&nbsp;&nbsp;&nbsp;&nbsp;粤ＩＣＰ备14023066号-1</div>
</div>
    </footer>
    <script type="text/javascript">
      $('#top li').click(function (event) {
        $(this).addClass('hang').siblings('li').removeClass('hang');
        if($(this).index() == 0) { //首页
          $('nav iframe').attr('src','<?php echo url('index/index1'); ?>');
        }else if($(this).index() == 1) {//公共查询
          $('nav iframe').attr('src','<?php echo url('index/check'); ?>');
        }else if($(this).index() == 2) {//海运运价
          $('nav iframe').attr('src','<?php echo url('index/hyyj'); ?>');
        }else if($(this).index() == 3) {//集装箱出售
          $('nav iframe').attr('src','<?php echo url('index/container'); ?>');
        }else if($(this).index() == 4) {//新闻中心
          $('nav iframe').attr('src','<?php echo url('index/news'); ?>');
        }else if($(this).index() == 5) {//合伙人加盟
          $('nav iframe').attr('src','<?php echo url('index/join'); ?>');
        }else if($(this).index() == 6) {//帮助与公告
          $('nav iframe').attr('src','<?php echo url('index/help'); ?>');
        }else if($(this).index() == 7) {//个人中心
          $('nav iframe').attr('src','<?php echo url('index/personal'); ?>');
        }else if($(this).index() == 8) {
          $('nav iframe').attr('src','<?php echo url('index/index1'); ?>');
        }
      });

      function reinitIframe() {
        var iframe = document.getElementById("ifram");
        try {
          iframe.width = document.body.clientWidth;
          var bHeight = iframe.contentWindow.document.body.scrollHeight;
          var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
          var height = Math.max(bHeight, dHeight);
          iframe.height = height;
        } catch (ex) { }
      }
      window.setInterval("reinitIframe()", 200);

    </script>
  </body>

  </html>