<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\index\lrdd.html";i:1530266800;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1529651522;}*/ ?>
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
    <form class="" action="">
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
        <!-- 订单信息 -->
        <div class="xxtx_dd">
          <div class="xx">
            <strong>订单信息</strong>
          </div>
          <div class="layui-col-xs4 dd">
            <div class="zhong">
              <div>上海中谷物流股份有限公司</div>
              <div>中谷福建/星东28N航次</div>
            </div>
          </div>
          <div class="layui-col-xs4 dd">
            <div class="zhong">
              <div class="layui-col-xs5">
                <h2>虎门港</h2>
                <span>2018-06-23</span>
              </div>

              <div class="layui-col-xs2">
                <i class="fa fa-ship fa-2x"></i>
              </div>

              <div class="layui-col-xs5">
                <h2>虎门港</h2>
                <span>2018-06-23</span>
              </div>
            </div>
          </div>
          <div class="layui-col-xs4 dd">
            <div class="zhong">
              <span>20GP</span>
              <p>￥
                <strong>1240.00</strong>
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
              <input type="button" class="se wt1" onclick="javascript:void(0);" value="选择">
              <input type="button" class="se wt2" onclick="javascript:void(0);" value="添加">
              <input type="button" class="se wt3" onclick="xiu();" value="修改">
              <input type="button" class="se wt4" onclick="javascript:void(0);" value="设置为默认">
              <!-- <button class="se wt1">选择</button>
              <button class="se wt2">添加</button>
              <button class="se wt3" onclick="xiu()">修改</button>
              <button class="se wt4">设置为默认</button> -->
            </div>
          </div>
          <div class="layui-row" style="padding-bottom: 10px;">
            <div class="layui-col-xs6 er_le">
              <div class="grid-demo" style="margin-top: 10px;">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>装货公司名全称：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>装货联系人：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>装货联系人电话：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">装货门店：</div>
                <div class="layui-col-xs9">
                  <d>广东省</d>
                  <d>广州市</d>
                  <d>天河区</d>
                  <d>长福路</d>
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>装货详情地址：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="" value="" readonly='readonly' class="in">
                </div>
              </div>
            </div>


            <div class="layui-col-xs6 er_rig">
              <div class="grid-demo" style="margin-top: 10px;">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>收货公司名全称：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>收货联系人：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>收货联系人电话：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="" value="" readonly='readonly' class="in">
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">收货门店：</div>
                <div class="layui-col-xs9">
                  <d>广东省</d>
                  <d>广州市</d>
                  <d>天河区</d>
                  <d>长福路</d>
                </div>
              </div>
              <div class="grid-demo">
                <div class="layui-col-xs3">
                  <span>*&nbsp;</span>收货详情地址：</div>
                <div class="layui-col-xs9">
                  <input type="text" name="" value="" readonly='readonly' class="in">
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
                <div class="select">
                  <select name='make'>
                    <option value='0' selected>请选择</option>
                    <option value='1'>柜子</option>
                    <option value='2'>箱子</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="layui-col-xs4 er">
            <div class="grid-demo nei">
              <div class="layui-col-xs3">
                <span>*&nbsp;</span>包装：</div>
              <div class="layui-col-xs6">
                <div class="select">
                  <select name='make'>
                    <option value='0' selected>请选择</option>
                    <option value='1'>铁桶</option>
                    <option value='2'>铁箱</option>
                    <option value='3'>纸箱</option>
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
                <input type="text" name="" value="" placeholder="">
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
                <input type="text" name="" value="20GP" placeholder="" disabled>
              </div>
            </div>
          </div>
          <div class="layui-col-xs4 er">
            <div class="grid-demo nei">
              <div class="layui-col-xs3">
                <span>*&nbsp;</span>柜量：</div>
              <div class="layui-col-xs6">
                <div class="select">
                  <select name='make' class="guil">
                    <option value='0'>请选择</option>
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
                <input type="text" name="" value="" placeholder="">
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
                <input type="text" name="" value="" placeholder="填写备注信息" class="layui-input">
              </div>
            </div>
          </div>
          <div class="fp layui-col-xs12">
            <!-- 是否开取发票 -->
            <div class="fp1 layui-col-xs2 layui-form" onclick="kfp(this)">
              <input type="checkbox" name="" title="是否开取发票信息" lay-skin="primary" value="1">
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
                    <select name='make' disabled="ture" class="layui-disabled fp01">
                      <option value='0' selected>请选择</option>
                      <option value='1'>6%</option>
                      <option value='2'>11%</option>
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
            <span>总运费：<strong>￥165970.00元</strong></span>
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
                  <select name='make' id="fk">
                    <option value='0' selected="selected">请选择</option>
                    <option value='1'>已方付款</option>
                    <option value='2'>对方付款</option>
                    <option value='3'>第三方付款</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="layui-col-xs6" class="ds">
              <div class="grid-demo">
                <div class="layui-col-xs4">
                  <span>*&nbsp;</span>姓名：</div>
                <div class="layui-col-xs8 jin">
                  <input type="text" name="" value="" placeholder="第三方输入" class="layui-disabled" disabled="disabled">
                </div>
              </div>

              <div class="grid-demo">
                <div class="layui-col-xs4">
                  <span>*&nbsp;</span>电话号码：</div>
                <div class="layui-col-xs8 jin">
                  <input type="text" name="" value="" placeholder="第三方输入" class="layui-disabled" disabled="disabled">
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
                  <select name='make'>
                    <option value='0' selected>请选择</option>
                    <option value='1'>到港付</option>
                    <option value='2'>月结付</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="layui-col-xs9">
              <div class="zhong layui-form">
                <div class="chec">
                  <input type="checkbox" name="" title="&nbsp;通 知 送 货" lay-skin="primary">
                </div>
                <div class="chec">
                  <input type="checkbox" name="" title="箱内签回单" lay-skin="primary">
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
          <a href="#">提交</a>
          <a>取消</a>
        </div>
      </div>

      <!-- 填写发票窗口 -->
      <div id="modal-default">
        <form class="layui-form" action="">
          <div class="layui-form-item">
            <label class="layui-form-label">发票抬头</label>
            <div class="layui-input-block">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="发票抬头" class="layui-input">
            </div>
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label">纳税人识别号</label>
            <div class="layui-input-block">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="纳税人识别号" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">注册地址</label>
            <div class="layui-input-block">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="注册地址" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">注册电话</label>
            <div class="layui-input-block">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="注册电话" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">开户银行</label>
            <div class="layui-input-block">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="开户银行" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">银行账户</label>
            <div class="layui-input-block">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="银行账户" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
              <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即保存</button>
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
                  <ul class="xin">
                    <li class='layui-col-xs6'>
                      <div class="nei">
                        <div class="le">
                          <div class="tiao">姓名：</div>
                          <div class="tiao">手机号：</div>
                          <div class="tiao">公司名：</div>
                          <div class="tiao">装货地址：</div>
                        </div>
                        <div class="rig">
                          <div class="tiao">刘某</div>
                          <div class="tiao">21346498797</div>
                          <div class="tiao">广州三乐可以有限公司</div>
                          <div class="tiao wu">啊撒娇发拉萨解撒旦发装货地址</div>
                        </div>
                      </div>
                    </li>

                    <li class='layui-col-xs6'>
                      <div class="nei">
                        <div class="le">
                          <div class="tiao">姓名：</div>
                          <div class="tiao">手机号：</div>
                          <div class="tiao">公司名：</div>
                          <div class="tiao">装货地址：</div>
                        </div>
                        <div class="rig">
                          <div class="tiao">刘某</div>
                          <div class="tiao">21346498797</div>
                          <div class="tiao">广州三乐可以有限公司</div>
                          <div class="tiao wu">啊撒娇发拉萨解撒旦发装货地址</div>
                        </div>
                      </div>
                    </li>
                  </ul>

                </div>

                <div class="layui-tab-item">
                  <ul class="xin">
                    <li class='layui-col-xs6'>
                      <div class="nei">
                        <div class="le">
                          <div class="tiao">姓名：</div>
                          <div class="tiao">手机号：</div>
                          <div class="tiao">公司名：</div>
                          <div class="tiao">装货地址：</div>
                        </div>
                        <div class="rig">
                          <div class="tiao">刘某</div>
                          <div class="tiao">21346498797</div>
                          <div class="tiao">广州三乐可以有限公司</div>
                          <div class="tiao wu">啊撒娇发拉萨解撒旦发装货地址</div>
                        </div>
                      </div>
                    </li>
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
          <form class="layui-form" action="">
            <div class="layui-form-item">
              <label class="layui-form-label">姓名：</label>
              <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入姓名" class="layui-input">
              </div>
            </div>

            <div class="layui-form-item">
              <label class="layui-form-label">手机号码：</label>
              <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入手机" class="layui-input">
              </div>
            </div>

            <div class="layui-form-item">
              <label class="layui-form-label">公司名：</label>
              <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入公司名" class="layui-input">
              </div>
            </div>

            <div class="layui-form-item">
              <label class="layui-form-label">收货地址：</label>
              <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入地址" class="layui-input">
              </div>
            </div>

            <div class="layui-form-item">
              <label class="layui-form-label">选择人物：</label>
              <div class="layui-input-block">
                <input type="radio" name="sex" value="" title="发货人" checked="">
                <input type="radio" name="sex" value="" title="收货人">
              </div>
            </div>

            <div class="layui-form-item">
              <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
              </div>
            </div>
            </from>
        </div>
      </div>
      </form>

      <script src="/static/index/js/iziModal.min.js"></script>
      <script src="/static/index/js/lrdd.js"></script>
  </body>

  </html>