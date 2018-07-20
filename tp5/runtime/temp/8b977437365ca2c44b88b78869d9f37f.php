<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\Order\place_order.html";i:1532081657;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1531988465;}*/ ?>
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
    <link rel="stylesheet" type="text/css" href="/static/index/css/iziModal.css">
    <link rel="stylesheet" href="/static/index/css/lrdd.css">
    <link rel="stylesheet" href="/static/index/css/xiala.css">
    <link rel="stylesheet" href="/static/index/css/all_order.css">
    <div class="banner"></div>
    <form class="" id ='order_data_form'>
      <div class="xxtx">
        <!-- 流程 -->
        <div class="lc">
                <ul>
                <li><span>1</span> 运单查询</li>
                <li class="jt">></li>
                <li><span>2</span> 完善订单</li>
                <li class="jt">></li>
                <li><span>3</span> 确认并支付</li>
                <li class="jt">></li>
                <li><span>4</span> 完成</li>
                </ul>
        </div>
        <!-- 订单信息 -->A.sea_id, A.rid, A.sid
            <input type="hidden" name='sea_id' value="<?php echo $list['sea_id']; ?>">
            <input type="hidden" name='rid' value="<?php echo $list['rid']; ?>">
            <input type="hidden" name='sid' value="<?php echo $list['sid']; ?>">
        <div class="xxtx_dd">
          <div class="xx">
            <strong>订单信息</strong>
          </div>
          <div class="layui-col-xs4 dd">
            <div class="zhong">
              <div><?php echo $list['ship_short_name']; ?></div>
              <div><?php echo $list['boat_name']; ?>/<?php echo $list['boat_code']; ?></div>
            </div>
          </div>
          <div class="layui-col-xs4 dd">
            <div class="zhong">
              <div class="layui-col-xs5">
                <h5><?php echo $list['r_add']; ?></h5><h2>----<?php echo $list['s_port_name']; ?></h2>
                <span><?php echo date("Y-m-d",$list['shipping_date']); ?></span>
              </div>

              <div class="layui-col-xs2">
                <i class="fa fa-ship fa-2x"></i>
              </div>

              <div class="layui-col-xs5">
                <h5><?php echo $list['s_add']; ?></h5><h2>----<?php echo $list['e_port_name']; ?></h2>
                <span><?php echo date("Y-m-d",$list['ETA']); ?></span>
              </div>
            </div>
          </div>
          <div class="layui-col-xs4 dd">
            <div class="zhong">
            <span>
                <?php switch($list['container_size']): case "1": ?>20GP<?php break; case "2": ?>40HQ<?php break; default: ?>未知集装箱型号
                <?php endswitch; ?>
            </span>
              <p>￥
                <strong class="money"><?php echo $list['price']; ?></strong>
              </p>
            </div>
            <div class="you">价格说明：这是纯运费</div>
          </div>
        </div>

        <!-- 委托信息 -->
        <div class="xxtx_dd er" style="padding: 0 10px;">
          <div class="xx" style="margin-bottom:0px;">
            <strong>委托信息</strong>
            <div class="er_anniu">
              <input type="button" class="se wt1" onclick="selectlink('<?php echo url("index/order/selectlinkman"); ?>');" value="选择">
              <input type="button" class="se wt2" onclick="javascript:void(0);" value="添加">
              <input type="button" class="se wt3" onclick="xiu();" value="修改">
              <input type="button" class="se wt4" onclick="javascript:void(0);" value="设置为默认">
            </div>
          </div>
          <div class="layui-row" style="padding-bottom: 10px;">
            <div class="layui-col-xs6 er_le">
              <div class="grid-demo" style="margin-top: 10px;">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>装货公司名全称：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="r_company" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>装货联系人：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="r_name" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>装货联系人电话：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="r_phone" value="" readonly='readonly' class="in">
                </div>
              </div>

              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>装货详情地址：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="r_add" value="" readonly='readonly' class="in">
                </div>
              </div>
            </div>

            <div class="layui-col-xs6 er_rig">
              <div class="grid-demo" style="margin-top: 10px;">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>收货公司名全称：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="s_company" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>收货联系人：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="s_name" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>收货联系人电话：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="s_phone" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>收货详情地址：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="s_add" value="" readonly='readonly' class="in">
                </div>
              </div>
            </div>
              
          </div>
        </div>

        <!-- 货物 -->
        <div class="xxtx_dd">
          <div class="xx">
            <strong>货物</strong>
          </div>
          <div class="layui-col-xs4 er">
            <div class="grid-demo nei">
              <div class="layui-col-xs3">
                <span>*&nbsp;</span>货名：</div>
              <div class="layui-col-xs6">
                   <input type="text" name="cargo" value="" placeholder="">
<!--                <div class="select">
                  <select name ='cargo'>
                    <option value='0' selected>请选择</option>
                    <option  value='1'>柜子</option>
                    <option  value='2'>箱子</option>
                  </select>
                </div>-->
              </div>
            </div>
          </div>
          <div class="layui-col-xs4 er">
            <div class="grid-demo nei">
              <div class="layui-col-xs3">
                <span>*&nbsp;</span>包装：</div>
              <div class="layui-col-xs6">
                <div class="select">
                  <select name ='container_type'>
                    <option  value='1'>杂货集装箱</option>
                    <option  value='2'>散货集装箱</option>
                    <option  value='3'>液体货集装箱</option>
                  </select>
                </div>
 
              </div>
            </div>
          </div>
          <div class="layui-col-xs4 er">
            <div class="grid-demo nei">
              <div class="layui-col-xs3">
                <span>*&nbsp;</span>重量：</div>
              <div class="layui-col-xs6">
                  <input type="text" name="weight" value="" placeholder="">
              </div>
              <div class="layui-col-xs2" style="text-align: left;margin-left: 8px; ">
                <span>吨(T)</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 费用 -->
        <div class="xxtx_dd" style="margin-bottom: 0px;">
          <div class="xx">
            <strong>费用</strong>
          </div>
          <div class="layui-col-xs4 er">
            <div class="grid-demo" style="margin-top: 0px;">
              <div class="layui-col-xs4">
                <span>*&nbsp;</span>集装箱规格：</div>
              <div class="layui-col-xs6">
                <input type="text" name="container" value="<?php if($list['container_size'] ==1){
                       echo '20GP';  }else{echo '40HQ';} ?>" readonly="readonly">
                <input type="hidden" name="container_size" value="<?php echo $list['container_size']; ?>">
              </div>
            </div>
          </div>
          <div class="layui-col-xs4 er">
            <div class="grid-demo nei">
              <div class="layui-col-xs3">
                <span>*&nbsp;</span>柜量：</div>
              <div class="layui-col-xs6">
                <div class="select">
                  <select name='container_num' class="guil" id='container_num'>
                  </select>
                </div>
              </div>
            </div>

          </div>
          <div class="layui-col-xs4 er">
            <div class="grid-demo" style="margin-top: 0px;">
              <div class="layui-col-xs3">
                <span>*&nbsp;</span>保险金额：</div>
              <div class="layui-col-xs6">
                <input id="bxje" type="text" name="cargo_cost" value="" placeholder="">
              </div>

              <div class="layui-col-xs2" style="text-align: left;margin-left:8px;">
                <span>万</span>
              </div>
            </div>
          </div>
        </div>
        <!-- 发票 -->
        <div class="kpf">
          <div class="layui-col-xs12 bei">
            <div class="grid-demo">
              <div class="layui-col-xs1 you">
                备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：
              </div>
              <div class="layui-col-xs10 inp">
                <input type="text" name="comment" value="" placeholder="填写备注信息" class="layui-input">
              </div>
            </div>
          </div>
          <div class="fp layui-col-xs12">
            <!-- 是否开取发票 -->
            <div class="fp1 layui-col-xs2 layui-form" onclick="kfp(this)">
              <input type="checkbox" name="invoice_if" title="是否开取发票信息" lay-skin="primary" value="1">
            </div>
            <div class="layui-col-xs1">&nbsp;</div>
            <!-- 选择发票 -->
            <div class="layui-col-xs3">
              <div class="ze">
                <div class="layui-col-xs3 zep">
                  发票：
                </div>
                <div class="layui-col-xs6">
                  <div class="select">
                    <select name='tax_rate' disabled="ture" class="layui-disabled fp01">
                      <option  value='0' selected>请选择</option>
                      <option  value='1'>6%</option>
                      <option  value='2'>10%</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="layui-col-xs3 tx">
              <a class="trigger-default">填写发票信息</a>
            </div>
          </div>
        </div>
        <!-- 总运费 -->
        <div class="sun">
            <span>总运费：<strong id ='price_sum'>￥165970.00元</strong></span>
        </div>

        <!-- 付款方式 -->
        <div class="xxtx_dd">
          <div class="xx">
            <strong>付款方式</strong>
          </div>

          <div class="layui-col-xs7 er">
            <div class="grid-demo layui-col-xs6 ds" style="margin-top: 45px;">
              <div class="layui-col-xs4">
                <span>*&nbsp;</span>付款人：</div>
              <div class="layui-col-xs8">
                <div class="select">
                  <select name='payer' id="fk">
                    <option  value='0' selected="selected">请选择</option>
                    <option  value='1'>已方付款</option>
                    <option  value='2'>对方付款</option>
                    <option  value='3'>第三方付款</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="layui-col-xs6" class="ds">
              <div class="grid-demo">
                <div class="layui-col-xs4">
                  <span>*&nbsp;</span>姓名：</div>
                <div class="layui-col-xs8 jin">
                  <input type="text" name="payer_name" value="" placeholder="第三方输入" class="layui-disabled" disabled="disabled">
                </div>
              </div>

              <div class="grid-demo">
                <div class="layui-col-xs4">
                  <span>*&nbsp;</span>电话号码：</div>
                <div class="layui-col-xs8 jin">
                  <input type="text" name="payer_phone" value="" placeholder="第三方输入" class="layui-disabled" disabled="disabled">
                </div>
              </div>
            </div>
          </div>

          <div class="layui-col-xs5 er">
            <div class="grid-demo">
              <div class="layui-col-xs3">
                <span>*&nbsp;</span>结账方式：</div>
              <div class="layui-col-xs6">
                <div class="select">
                  <select name='payment_method'>
                    <option  value='0' selected>请选择</option>
                    <option  value='1'>到港付</option>
                    <option  value='2'>月结付</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="layui-col-xs9">
              <div class="zhong layui-form">
                <div class="chec">
                  <input type="checkbox" name="message_send" title="&nbsp;通 知 送 货" value='1' lay-skin="primary">
                </div>
                <div class="chec">
                  <input type="checkbox" name="sign_receipt" title="箱内签回单"  value='1' lay-skin="primary">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 条款声明 -->
        <div class="xxtx_dd er">
          <div class="xx">
            <strong>条款声明</strong>
          </div>
          <!-- 条款内容 -->
          <div class="hx">
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
            <p>sdfsdf</p>
          </div>
        </div>

        <div class="tjiao">
          <a href="#" onclick="order_data()">提交</a>
          <a class="shi" href="javascript:void(0);">取消</a>
        </div>
      </div>
    </form>
      <!-- 填写发票窗口 -->
      <div id="modal-default">
        <form class="layui-form"id ='invoice_form'>
            <input type="hidden" name='member_code' value="kehu001"> 
            <!--需要贮存客户session_id-->
          <div class="layui-form-item">
            <label class="layui-form-label">发票抬头</label>
            <div class="layui-input-block">
              <input type="text" name="title" lay-verify="invoice_title" autocomplete="off" placeholder="发票抬头" class="layui-input">
            </div>
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label">纳税人识别号</label>
            <div class="layui-input-block">
              <input type="text" name="taxpayer_id" lay-verify="title" autocomplete="off" placeholder="纳税人识别号" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">注册地址</label>
            <div class="layui-input-block">
              <input type="text" name="registered_address" lay-verify="title" autocomplete="off" placeholder="注册地址" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">注册电话</label>
            <div class="layui-input-block">
              <input type="text" name="registered_phone" lay-verify="title" autocomplete="off" placeholder="注册电话" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">开户银行</label>
            <div class="layui-input-block">
              <input type="text" name="deposit_bank" lay-verify="title" autocomplete="off" placeholder="开户银行" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">银行账户</label>
            <div class="layui-input-block">
              <input type="text" name="bank_account" lay-verify="title" autocomplete="off" placeholder="银行账户" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
              <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" onclick='invoice()' lay-filter="demo1">立即保存</button>
              </div>
            </div>
        </form>
      </div>

          <!-- 选择模态框 -->
    <div id="wt1">
      <div class="all_order">
        <form class="" action="index.html" method="post">
          <div class="layui-row">
              <!-- 收货人 -->
            <div class="layui-col-xs8">
              <div class="grid-demo">
                <div class="layui-form-item">
                  <label class="layui-form-label">姓名：</label>
                  <div class="layui-input-inline">
                    <input type="text" name="identity" lay-verify="identity" placeholder="" autocomplete="off" class="layui-input">
                  </div>

                  <div class="layui-input-inline" style="float:right;">
                    <button class="layui-btn btn_sou" lay-submit="" lay-filter="demo1">搜索</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>

        <!-- 表格 -->
        <div class="biao">
          <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
              <li class="layui-this">收货人</li>
              <li>发货人</li>
            </ul>
            <div class="layui-tab-content" style="height: 100px;">
              <div class="layui-tab-item layui-show">
                <ul class="xin song">
                      <!--送货人-->
                  <!-- ajax添加数据 -->
                </ul>
              </div>
                   <!--收货人-->
              <div class="layui-tab-item">
                <ul class="xin shou">
                  <!-- ajax添加数据 -->
                </ul>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      

      <!-- 添加模态框 -->
      <div id="wt2">
        <div class="layui-row">
          <form class="layui-form" id ="linkman_form">
            <div class="layui-form-item">
              <label class="layui-form-label">姓名：</label>
              <div class="layui-input-block">
                <input type="text" name="link_name" lay-verify="title" value="" autocomplete="off" placeholder="请输入姓名" class="layui-input">
              </div>
            </div>

            <div class="layui-form-item">
              <label class="layui-form-label">手机号码：</label>
              <div class="layui-input-block">
                <input type="text" name="phone" lay-verify="title" value="" autocomplete="off" placeholder="请输入手机" class="layui-input">
              </div>
            </div>

            <div class="layui-form-item">
              <label class="layui-form-label">公司名：</label>
              <div class="layui-input-block">
                <input type="text" name="company" lay-verify="title" value="" autocomplete="off" placeholder="请输入公司名" class="layui-input">
              </div>
            </div>

            <div class="layui-form-item">
              <label class="layui-form-label">收/发货地址:</label>
              <div class="layui-input-block">
                <input type="text" name="add" lay-verify="title" value="" autocomplete="off" value="" placeholder="请输入地址" class="layui-input">
              </div>
            </div>

            <div class="layui-form-item">
              <div class="layui-input-block">
                  <button class="layui-btn" lay-submit="" lay-filter="demo1" onclick="linkman_btn()">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
              </div>
            </div>
            </from>
        </div>
      </div>
    <script src="/static/index/js/iziModal.min.js"></script>
    <script src="/static/index/js/lrdd.js"></script>
    <script>
      
$('.shi').click(function(){
  var money = $('.money').text();//纯运费
  var sum = $("#container_num option:selected").val();//柜量
  var bxje = $('#bxje').val();//保险金额
  var fp = $(".fp01 option:selected").val();//发票
  var cun =money*sum;
  var bx = bxje*6;
  var zong = cun+bx;
  var zong6 = zong*1.038;
  var zong10 = zong*1.06;
  console.log('纯运费:'+money*sum);
  console.log('加保险费:'+bxje*6);
  console.log('总运费:'+zong);
  console.log('总运费6:'+zong6);
  console.log('总运费10:'+zong10);
});
    </script>
  </body>
  </html>
