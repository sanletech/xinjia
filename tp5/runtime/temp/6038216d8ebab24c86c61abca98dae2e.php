<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:83:"E:\xampp\htdocs\xinjia\tp5\public/../application/index\view\personal\all_order.html";i:1529651522;s:66:"E:\xampp\htdocs\xinjia\tp5\application\index\view\public\head.html";i:1529651522;}*/ ?>
<!-- 全部订单 -->
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
      <link rel="stylesheet" href="/static/index/css/all_order.css">
      <link rel="stylesheet" type="text/css" href="/static/index/css/iziModal.css">
    <div class="all_order">
      <form class="" action="index.html" method="post">
        <div class="layui-row">
          <!-- 日期 -->
          <div class="layui-col-xs6">
            <div class="grid-demo grid-demo-bg1">
              <div class="layui-form-item">
                <label class="layui-form-label">验证日期：</label>
                <div class="layui-input-inline">
                  <input type="text" name="date" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid">至</div>
                <div class="layui-input-inline">
                  <input type="text" name="date" id="date1" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                </div>
              </div>
            </div>
          </div>
            <!-- 收货人 -->
          <div class="layui-col-xs6">
            <div class="grid-demo">
              <div class="layui-form-item">
                <label class="layui-form-label">收货人：</label>
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
            <li class="layui-this">所有订单</li>
            <li>待处理订单</li>
            <li>正在执行订单</li>
            <li>已完成订单</li>
          </ul>
          <div class="layui-tab-content" style="height: 100px;">
            <div class="layui-tab-item layui-show">
              <table class="layui-table" id="te">
                <colgroup>
                  <col width="150">
                  <col width="200">
                  <col>
                </colgroup>
                <thead>
                  <tr>
                    <th>运单号</th>
                    <th>集装箱号</th>
                    <th>收货公司</th>
                    <th>收货人</th>
                    <th>下单时间</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs select" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                      <a class="layui-btn layui-btn-primary layui-btn-xs trigger-default" lay-event="detail">委托函</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div id="demo7" style="text-align: center;"></div>
            </div>
            <div class="layui-tab-item">
              <table class="layui-table" id="te">
                <colgroup>
                  <col width="150">
                  <col width="200">
                  <col>
                </colgroup>
                <thead>
                  <tr>
                    <th>运单号</th>
                    <th>集装箱号</th>
                    <th>收货公司</th>
                    <th>收货人</th>
                    <th>下单时间</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs select" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                      <a class="layui-btn layui-btn-primary layui-btn-xs trigger-default" lay-event="detail">委托函</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div id="demo7" style="text-align: center;"></div>
            </div>
            <div class="layui-tab-item">
              <table class="layui-table" id="te">
                <colgroup>
                  <col width="150">
                  <col width="200">
                  <col>
                </colgroup>
                <thead>
                  <tr>
                    <th>运单号</th>
                    <th>集装箱号</th>
                    <th>收货公司</th>
                    <th>收货人</th>
                    <th>下单时间</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">委托函</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div id="demo7" style="text-align: center;"></div>
            </div>
            <div class="layui-tab-item">
              <table class="layui-table" id="te">
                <colgroup>
                  <col width="150">
                  <col width="200">
                  <col>
                </colgroup>
                <thead>
                  <tr>
                    <th>运单号</th>
                    <th>集装箱号</th>
                    <th>收货公司</th>
                    <th>收货人</th>
                    <th>下单时间</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">委托函</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                  <tr>
                    <td>TB23565864563</td>
                    <td>20GP</td>
                    <td>广州三乐有限公司</td>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>
                      <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
                      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div id="demo7" style="text-align: center;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 模态窗口 -->
    <div id="modal-default" class="iziModal">
      <div class="wth layui-row">
        <h1 class="cen"><strong>广州市兴佳国际代运理有限公司</strong></h1>
        <div class="wt cen">
          <span>沿海内贸货物托运委托书</span>
          <div class="dd">订单号：h10000000000001</div>
        </div>

        <table class="layui-col-xs12">
          <tbody class="layui-col-xs12">

            <tr class="layui-col-xs6">
              <td class="yi1 rig">委托人全称：</td>
              <td class="er2">广州市信家国际运货代理有限公司</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">委托联系人：</td>
              <td class="er2">预谋</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">委托人电话：</td>
              <td class="er2">12345645646798</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">委托人地址：</td>
              <td class="er2">广州市信家国际运货代理有限公司</td>
            </tr>
            <tr class="layui-col-xs6">
              <td class="yi1 rig">船名：</td>
              <td class="er2">人家213</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">预配船期：</td>
              <td class="er2">2017-12-4</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">航次：</td>
              <td class="er2">1778</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">运输条款：</td>
              <td class="er2">门到们</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">箱内签回单：</td>
              <td class="yi1">否</td>
              <td class="layui-col-xs6 rig">等通知送货：</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1">否</td>
              <td class="layui-col-xs6 rig">装货时间：</td>
              <td class="yi1">2017-5-3</td>
            </tr>

            <tr class="layui-col-xs12">
              <td class="yi rig">备注:</td>
              <td class="er">125645643</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">发货人全称：</td>
              <td class="er2">广州市兴佳国际代运理有限公司</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">收货人全称：</td>
              <td class="er2">广州市信家国际运货代理有限公司</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">装货联系人：</td>
              <td class="er2">成管住</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">收货联系人：</td>
              <td class="er2">城管住</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">发货人电话：</td>
              <td class="er2">123456798</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">收货人电话：</td>
              <td class="er2">5646412313456</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">发货人门点：</td>
              <td class="er2">广东省－广州市－黄埔去</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">收货人门点：</td>
              <td class="er2">广东省－广州市－光弯路</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">装货地址：</td>
              <td class="er2">广州市兴佳国际代运理有限公司</td>
            </tr>

            <tr class="layui-col-xs6">
              <td class="yi1 rig">收货地址：</td>
              <td class="er2">广州市信家国际运货代理有限公司</td>
            </tr>

            <tr class="layui-col-xs12" style="height:200px;">
              <td class="yi cen" style="height:200px;padding-top:75px;">
                双边货物<br/>
                保险约定
              </td>
              <td class="er" style="height:100%;">
                <ol>
                  <li>受托人无偿为委托人购买基本保额的保险，单个集装箱的基本保额为： 5.00 万元人民币/箱，险种：综合险。</li>
                  <li>如委托人未如实声明货物价值，受托人将按人民币5万元/箱的保额进行投保。如由于委托人未如实声明货物价值导致不足额投保，则由此产生的损失概由委托人及收货人自行承担。</li>
                  <li>保险免赔额由委托人承担：一般货物免赔额为500元/柜；玻璃、板材类货物免赔额为800元/柜；水果（一般货柜装运）免赔额为1000元或损失金额的5%/柜，两者以高者为准。</li>
                </ol>
              </td>
            </tr>

            <tr class="layui-col-xs12" style="height:80px;">
              <td class="yi cen" style="height:100%;line-height:80px;">受托人声明</td>
              <td class="er" style="height:80px;padding-top:10px;">
                因不加固或加固不当引发的货损及箱体损坏不属于保险责任范畴。故不能理赔。但箱体受损需要委托人赔偿。受托方不承担一切责任。
              </td>
            </tr>

            <tr class="layui-col-xs12">
              <td class="yi rig">货名：</td>
              <td class="yi">陶瓷</td>
              <td class="yi rig">箱量及箱型：</td>
              <td class="yi" style="width:16.66666666%;">1*20GP</td>
              <td class="yi rig">单柜重量：</td>
              <td class="layui-col-xs1">28T</td>
              <td class="yi rig">货物包装：</td>
              <td class="yi">纸箱</td>
            </tr>

            <tr class="layui-col-xs12">
              <td class="yi rig">结算金额：</td>
              <td class="yi">4633</td>
              <td class="yi rig">大写：</td>
              <td class="yi" style="width:16.66666666%;">二千一百七以前</td>
              <td class="yi rig">支付方式：</td>
              <td class="layui-col-xs1">票结</td>
              <td class="yi rig">装货时间：</td>
              <td class="yi">2017-5-3</td>
            </tr>

            <tr class="layui-col-xs12">
              <td class="yi rig">备注:</td>
              <td class="er">125645643</td>
            </tr>
            <tr class="layui-col-xs12">
              <td class="">
                <h2><strong>声明条款：</strong></h2>
                <ol>
                  <li>委托人签署本委托书时已视受托人为其代理人，并委托受托人代办运单及代办沿海运输、公路、铁路运输、码头操作及费用结算等。</li>
                  <li>如货物未办理保险，则按照集装箱运输的惯例：在箱体完好、铅封一致的情况下交付集装箱视为已完好交付货物，受托人对货方声称的货损及货差不承担责任。</li>
                  <li>委托人应按约定时间支付全部运杂费，否则，应按所产生费用总额每天千分之五的标准向受托人支付违约金。</li>
                  <li>其他未列事项按照《国内水路货物运输规则》及交通部的有关规定。</li>
                  <li>请勿在箱内装入国家法律禁止运输的物品，如化学制品、液体、易燃易爆或其他危险品等，否则，后果自负。</li>
                  <li>装货时间限定：20GP/个 2-3小时；40G/40HQ/个 3-5小时；如超时产生压车费：100元/小时。压夜费：500/天。</li>
                  <li>货物抵达卸港后，委托人应安排收货人立即收货。因收货人不及时收货或拒绝收货而造成的包括但不限于滞箱费，堆存费，平移费等一切损失均由委托人承担。具体费用按码头和船公司的规定收取。</li>
                  <li>本委托可以以传真/网上下单的方式签订，传真件/传真件的复印件/网上下单打印本与正本具有同样的法律效力</li>
                  <li>委托人的代理人签字与委托人签章具有同等法律效力，并委托人的业务章与公章具同等效力。</li>
                  <li>委托人/代理人已充分阅读相关内容，且已将本声明内容告知收货人，并收货人同意受本声明项下内容的约束。</li>
                </ol>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="fot layui-col-xs12">
          <div class="layui-col-xs6">
            委托人（签章）：广州市兴佳国际货运代理有限公司
          </div>
          <div class="layui-col-xs6">
            受托人（签章）：广州市兴佳国际货运代理有限公司
          </div>

          <div class="shang layui-col-xs12">
            <div class="layui-col-xs6">
              日期：2017-6-21
            </div>
            <div class="layui-col-xs6">
              日期：2017-6-21
            </div>
          </div>
        </div>

        <div class="layui-col-xs12 dibu">
          地址：广州市黄埔区港湾路68号中交港湾国际大厦2003-2018&nbsp;&nbsp;电话：020-28211730&nbsp;&nbsp;传真：020-28211720&nbsp;&nbsp;邮编：510700
        </div>
      </div>
    </div>

    <div id="select" class="iziModal">
      查看
    </div>
    <script type="text/javascript" src="/static/index/js/personal/all_order.js"></script>
    <script src="/static/index/js/iziModal.min.js"></script>
    <script type="text/javascript">
    //模态窗口基本设置
    $("#modal-default").iziModal({
        title: "委托函",
        iconClass: 'icon-announcement',
        width: 1100,
        padding: 20
    });
    //启动模态窗
    $(document).on('click', '.trigger-default', function (event) {
        event.preventDefault();
        $('#modal-default').iziModal('open');
    });

    //查询
    $("#select").iziModal({
        title: "查看信息",
        iconClass: 'icon-announcement',
        width: 1100,
        padding: 20
    });
    //启动模态窗
    $(document).on('click', '.select', function (event) {
        event.preventDefault();
        $('#select').iziModal('open');
    });
    </script>
  </body>
</html>
