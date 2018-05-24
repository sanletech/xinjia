<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1527060834;s:91:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\Financial\financial_select.html";i:1527150484;s:68:"E:\xampp\htdocs\xinjia\tp5\application\admin\view\public\header.html";i:1525660218;}*/ ?>
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
        <link rel="stylesheet" href="/static/admin/css/route_add.css">
        <link rel="stylesheet" href="/static/admin/css/all_order.css">
        <div>
            <table class="layui-table layui-table-item" id="te">
                <thead>
                    <tr>
                        <th>船名航次</th>
                        <th>开航日期</th>
                        <th>装货日期</th>
                        <th>箱量</th>
                        <th>箱型</th>
                        <th>柜号</th>
                        <th>发货方</th>
                        <th>装货地点</th>
                        <th>送货地址</th>
                        <th>发票</th>
                        <th>运费</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>新温州061Ｎ</td>
                        <td>2018-3-1</td>
                        <td>2018-3-1</td>
                        <td>12</td>
                        <td>20Gp</td>
                        <td>TEMU3046802</td>
                        <td>开平市美富达调味品食品有限公司</td>
                        <td>开平塘口镇水边村委会</td>
                        <td>河北省衡水市武强县食品城</td>
                        <td>6%</td>
                        <td>600</td>
                    </tr>
                    <tr>
                        <td>新温州061Ｎ</td>
                        <td>2018-3-1</td>
                        <td>2018-3-1</td>
                        <td>12</td>
                        <td>20Gp</td>
                        <td>TEMU3046802</td>
                        <td>开平市美富达调味品食品有限公司</td>
                        <td>开平塘口镇水边村委会</td>
                        <td>河北省衡水市武强县食品城</td>
                        <td>11%</td>
                        <td>1300</td>
                    </tr>
                </tbody>
            </table>
            <div class="xinxi">
                <div class="zong">
                    <strong class="zy">总运费：
                        <span>16100.00</span>
                    </strong>
                </div>
                <div class="yh">
                    <p>注：烦请在收到此单三个工作日内与我司核对并盖章回传020-22197970，否则我司将视为认可此笔费用。另汇款后请将水单传真至我司确认，谢谢合作！</p>
                    <p>工商银行,开户行：广州黄埔支行,账号6222 0836 0200 6218 504 户名：陈灿炎</p>
                    <p>交通银行,开户行：广州黄埔支行,账号6222 0836 0200 6218 504 户名：陈灿炎</p>
                    <p>建设银行,开户行：广州黄埔支行,账号6222 0836 0200 6218 504 户名：陈灿炎</p>
                    <p>农业银行,开户行：广州黄埔支行,账号6222 0836 0200 6218 504 户名：陈灿炎</p>
                    <div class="fapiao">
                        <h2>
                            <strong>发票金额：
                                <span>15699.12</span>
                            </strong>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $('.cancel').click(function () {
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            });


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