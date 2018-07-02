<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\hyyj.html";i:1530524280;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1530520898;}*/ ?>
<!-- 海运运价 -->
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
    <link rel="stylesheet" href="/static/index/css/hyyj.css">
    <link rel="stylesheet" type="text/css" href="/static/index/css/iziModal.css">
    <div class="banner">
      <div class="gnhy">
        <div class="gnhy_top">国内海运</div>
        <div class="gnhy_nei layui-row">
          <input type="text" name="qi" placeholder="请输入起始地" class="layui-col-xs12">
          <input type="text" name="mu" placeholder="请输入目的地" class="layui-col-xs12">

          <input type="text" name="date" placeholder="装货时间" id="date" class="layui-col-xs12">
        </div>
        <div class="gnhy_di">
          <div class="di_zuo">
            <a href="" style="margin-right:50px;">
              <i class="fa fa-ban on fa-lg"></i>&nbsp;不接货物</a>
            <a href="">
              <i class="fa fa-warning fa-lg c"></i>&nbsp;国家禁运品声明</a>
          </div>
          <div class="di_you">
            <button>查价</button>
          </div>
        </div>
      </div>
    </div>
    <div class="head_hy">
      <!-- 查询表单 -->

      <!-- 查询内容 -->
      <div class="cen_nei">
        <!-- 查询导航 -->
        <div class="nei_dao">
          <ul>
            <li>航公司</li>
            <li>截单时间</li>
            <li>预计开船日期</li>
            <li>航线</li>
            <li>预计到港日期</li>
            <li>预计送货日期&nbsp;
              <i class="icon iconfont icon-paixu"></i>
            </li>
            <li>20GP&nbsp;
              <i class="icon iconfont icon-paixu"></i>
            </li>
            <li>40HQ&nbsp;
              <i class="icon iconfont icon-paixu se"></i>
            </li>
            <li>操作</li>
          </ul>
        </div>
        <!-- 查询内容 -->
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="nei_nei">
            <?php if($vo['generalize']=1): ?>
            <div class="tj">
                <img src="/static/index/image/tuijian.jpg" alt="">
            </div>
            <?php endif; ?>
            
            <div class="hang_nei">
            <div class="nei_le">
                <ul>
                <li>
                    <?php echo $vo['ship_short_name']; ?>
                    <div class="dian">
                        <i class="icon iconfont icon-chuan se"></i>&nbsp;
                        <?php if($vo['middle_id']=0): ?><span class="hui">直航</span>
                        <?php else: ?><span class="hui">中转</span><?php endif; ?>
                    </div>
                 </li>
                <li><?php echo date("Y-m-d",$vo['cutoff_date']); ?></li>
                <li>
                    <?php echo date("Y-m-d",$vo['shipping_date']); ?>
                    <div class="dian">
                        <i class="icon iconfont icon-dingwei se">&nbsp;</i>
                        <span class="hui"><?php echo $vo['s_port_name']; ?></span>
                    </div>
                </li>
                <li class="zhi">
                    <div class="hui"><?php echo $vo['boat_name']; ?></div>
                        <div>
                            <div class="j"></div>
                        </div>
                    <div class="hui"><?php echo $vo['boat_code']; ?></div>
                </li>
                <li>
                    <?php echo date("Y-m-d",$vo['ETA']); ?>
                    <div class="dian">
                        <i class="icon iconfont icon-dingwei se">&nbsp;</i>
                        <span class="hui"><?php echo $vo['e_port_name']; ?></span>
                    </div>
                </li>
                <li><?php echo date("Y-m-d",$vo['EDD']); ?></li>
                <li>
                    ￥<?php echo $vo['price_20GP']; ?>
                    <div class="dian">
                        <a href="<?php echo url('Order/lrdd'); ?>?sea_id=<?php echo $vo['id']; ?>&s_car_id=<?php echo $vo['sid']; ?>$r_car_id=<?php echo $vo['rid']; ?>&container_size=1" class="gp">下单</a>
                    </div>
                </li>
                <li>
                    ￥<?php echo $vo['price_40HQ']; ?>
                    <div class="dian">
                        <a href="<?php echo url('Order/lrdd'); ?>?sea_id=<?php echo $vo['id']; ?>&s_car_id=<?php echo $vo['sid']; ?>$r_car_id=<?php echo $vo['rid']; ?>&container_size=2" class="gp">下单</a>
                    </div>
                </li>
              </ul>
            </div>
            
            <div class="nei_rig">
                <a href="#" class="trigger-default">航线详情</a>
            </div>
            
            <div class="sm">
                截单时间：2018-1-18&nbsp;&nbsp;&nbsp;海上走船：5天&nbsp;&nbsp;&nbsp; 说明时间：到货时间为估算时间，具体送货时间以实际开船和实际靠港为准&nbsp;&nbsp;&nbsp; 价格说明：已含们到门所有费用，不含开票。
            </div>
            </div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>  
        <div class="page">
            <div>
                <?php echo $page; ?>
            </div>
        </div>

        <div class="nei_nei">
          <div class="hang_nei">
            <div class="nei_le">
              <ul>
                <li>
                  中远海船务
                  <div class="dian">
                    <i class="icon iconfont icon-zhongzhuan se"></i>&nbsp;
                    <span class="hui">中转</span>
                  </div>
                </li>
                <li>3月6日</li>
                <li>
                  3月10日
                  <div class="dian">
                    <i class="icon iconfont icon-dingwei se"></i>&nbsp;
                    <span class="hui">南沙</span>
                  </div>
                </li>
                <li class="zhi">
                  <div class="hui">陆威海</div>
                  <div>
                    <div class="j"></div>
                  </div>
                  <div class="hui">IC9</div>
                </li>
                <li>
                  3月15日
                  <div class="dian">
                    <i class="icon iconfont icon-dingwei se"></i>&nbsp;
                    <span class="hui">天津</span>
                  </div>
                </li>
                <li>3月17日</li>
                <li>
                  ￥3000
                  <div class="dian">
                      <a href="<?php echo url('index/lrdd'); ?>" class="gp">下单</a>
                  </div>
                </li>
                <li>
                  ￥5000
                  <div class="dian">
                      <a href="<?php echo url('index/lrdd'); ?>" class="gp">下单</a>
                  </div>
                </li>
              </ul>
            </div>

            <div class="nei_rig">
              <a href="#" class="trigger-default">航线详情</a>
            </div>

            <div class="sm">
              截单时间：2018-1-18&nbsp;&nbsp;&nbsp;海上走船：5天&nbsp;&nbsp;&nbsp; 说明时间：到货时间为估算时间，具体送货时间以实际开船和实际靠港为准&nbsp;&nbsp;&nbsp; 价格说明：已含们到门所有费用，不含开票。
            </div>
          </div>
        </div>


      </div>
    </div>

    <!-- 模态窗口 -->
    <div id="modal-default" class="iziModal">
      <div class="mo_cen">
        <div class="xian"></div>
        <div class="mo_nei layui-row">
          <div class="layui-col-xs4 la">
            <!-- 进过地址加y类样式 -->
            <div class="guan">广州
              <br/>3018-03-14
              <div class="nn">
                <div class="yuan y"></div>
              </div>
            </div>
          </div>
          <div class="layui-col-xs4 la">
            <!-- 航船经过加chuan类样式 -->
            <div class="guan chuan">
              <div class="nn">
                <div class="yuan">船</div>
              </div>中转地
              <br/>3018-03-14 </div>
          </div>

          <div class="layui-col-xs4 la">
            <div class="guan">天津
              <br/>3018-03-14
              <div class="nn">
                <div class="yuan"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="/static/index/js/iziModal.min.js"></script>
    <script type="text/javascript">
      //模态窗口基本设置
      $("#modal-default").iziModal({
        title: "航线详情",
        iconClass: 'icon-announcement',
        width: 700,
        padding: 20
      });
      //启动模态窗
      $(document).on('click', '.trigger-default', function (event) {
        event.preventDefault();
        $('#modal-default').iziModal('open');
      });

      // 日期
      layui.use(['form', 'layedit', 'laydate'], function () {
        var form = layui.form
          , layer = layui.layer
          , layedit = layui.layedit
          , laydate = layui.laydate;

        //日期
        laydate.render({
          elem: '#date'
        });
        laydate.render({
          elem: '#date1'
        });
      });
    </script>
  </body>

  </html>