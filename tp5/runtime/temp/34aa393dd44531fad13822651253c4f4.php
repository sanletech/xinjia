<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1525767529;s:81:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Order\order_list.html";i:1525770730;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台登录-X-admin2.0</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/static/admin/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/static/admin/css/font.css">
    <link rel="stylesheet" href="/static/admin/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/xadmin.css">
                   
        
    <script type="text/javascript" src="/static/admin/js/jquery-3.2.1.min.js"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
    <script type="text/javascript" src="/static/admin/js/area.js"></script>

</head>
  
  <body>
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">订单展示</a>
        <a>
          <cite>导航元素</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
        <form id="searchform" class="layui-form layui-col-md12 x-so" >
          <div>
              <input type="text" name="car_name"  placeholder="请输入创建时间" autocomplete="off" class="layui-input" id="start">
              <input type="text" name="port"  placeholder="请输入装货时间" autocomplete="off" class="layui-input" id="end">
              <span>7天</span>
              <input type="text" name="port"  placeholder="请输入船期" autocomplete="off" class="layui-input">
              <button class="layui-btn"  lay-submit="" lay-filter="sreach" onclick="search()"><i class="layui-icon">&#xe615;</i></button>
          </div>
          
        </form>
      </div>
    </div>

    <div class="biao">
      <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
        <ul class="layui-tab-title">
          <li class="layui-this">待确认</li>
          <li>待订舱</li>
          <li>待派车</li>
          <li>待装货</li>
          <li>待报柜号</li>
          <li>待配船</li>
          <li>待走船</li>
          <li>已走船</li>
          <li>已到港</li>
          <li>待收款</li>
          <li>待送货</li>
        </ul>
        <div class="layui-tab-content">

            <!-- 待确认 -->
          <div class="layui-tab-item layui-show">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 待订舱 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 待派车 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 待装货 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 待报柜号 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 待配船 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 待走船 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 已走船 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 已到港 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 待收款 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 待送货 -->
          <div class="layui-tab-item">
              <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','<?php echo url(""); ?>',600,400)"><i class="layui-icon"></i>添加</button>
                <span class="x-right" style="line-height:40px">总共有<{}>条记录</span>
              </xblock>
              <!-- 相对定位 -->
          <div  style="position: relative;">
              <!-- 宽度1600px 出现滚动条 -->
              <div  style="overflow: scroll;overflow-y: hidden;">
                <table class="layui-table layui-table-item layui-da" id="te" style="overflow: scroll;width: 1600px;">
                    <thead>
                      <tr>
                        <th>
                          <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        </th>
                        <th>ID</th>
                        <th>运单号</th>
                        <th>船名/航次</th>
                        <th>运输条款</th>
                        <th>货名</th>
                        <th>航线</th>
                        <th>箱型*箱量</th>
                        <th>创建时间</th>
                        <th>离港时间</th>
                        <th>联系人</th>
                        <th>联系人电话</th>
                        <th>收货人</th>
                        <td>不操作</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i class="layui-icon">&#xe605;</i></div></td>
                        <td>1</td>
                        <td>1782NJSD045</td>
                        <td>场景18/1782N</td>
                        <td>DO-CY</td>
                        <td>钢卷</td>
                        <td>北京-上海</td>
                        <td>20GP*1</td>
                        <td>2017-02-30</td>
                        <td>2017-03-24</td>
                        <td>小虎</td>
                        <td>13016542464</td>
                        <td>小猪</td>
                        <td>此内容作废内容无填充</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <!-- 绝对定位 -->
              <div class="" style="position: absolute;top: 0;right: 0;">
                <table class="layui-table layui-table-item">
                  <thead>
                    <tr>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px 15px;">
                          <span class="layui-btn layui-btn-normal layui-btn-mini">正常</span>
                      </td>
                      <td style="padding: 8px 15px;">
                        <a title="查看" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe649;</i>
                        </a>  
                        <a title="确认" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="编辑" class="order_a" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" class="order_a" onclick="" href="javascript:;">
                          <i class="layui-icon">&#xe640;</i>
                        </a>
                        <a title="跟踪物流" onclick="" href="javascript:;">
                            <i class="layui-icon">&#xe715;</i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <script>
      $('.order_a').css('margin-right','3px');
            /*执行搜索车队或者港口*/
      function search(){
          $.ajax({
                  type:'post',
                  url:"<?php echo url('admin/Contact/search'); ?>",     
                  data:$("#searchform").serialize(),
                  dataType:"json",
                  success:function(data){
                      alert("1111");
                    if(data.status==1){
                      return 1;
                    }else{
                        return 0 ;
                  }
                }
          })
      }
          
          
      layui.use('laydate', function () {
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });
        
    
      /*用户-删除*/
      function member_del(obj, did) {
        layer.confirm('确认要删除吗？', function (index) {
          //转成数组形式
          var dataA = new Array()
          dataA[0] = did;
          var dataArray = { id: dataA }
          toajax(dataArray);
          $(obj).parents("tr").remove();
          layer.msg('已删除!', { icon: 1, time: 1000 });
        });
      }

      function delAll(argument) {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？' + data, function (index) {
          //捉到所有被选中的，发异步进行删除
          var dataArray = { id: data };
          toajax(dataArray);
          layer.msg('删除成功', { icon: 1 });
          $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }


      function toajax(dataArray) {
        $.ajax({
          type: 'POST',
          url: "<?php echo url('admin/member/toDel'); ?>",
          data: dataArray,
          dataType: "json",
          success: function (data) {
            if (data.status == 1) {
              return 1;
            } else {
              return 0;
            }
          }
        })
      }
    </script>
 
  </body>

</html>