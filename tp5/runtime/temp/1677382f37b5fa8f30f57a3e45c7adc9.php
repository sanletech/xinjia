<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1527898250;}*/ ?>
<body>
    <!-- 左侧菜单开始 -->
    <div class="left-nav">
        <div id="side-nav">
            <ul id="nav">
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6b8;</i>
                        <cite>用户管理</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Member/member_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>用户列表</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Member/company_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>企业列表</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Member/disable_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>禁用账号</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Member/member_check'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>用户跟踪</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe723;</i>
                        <cite>订单管理</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Order/order_audit'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>审核订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Order/order_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>处理订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Order/order_waste'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>查看订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Order/order_edit'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>废弃订单</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe698;</i>
                        <cite>运价设置</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Price/price_route'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>航线运价</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Price/price_trailer'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>拖车运价</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe705;</i>
                        <cite>财务中心</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Financial/customer_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>客户订单</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Financial/financial_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>账单列表</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Financial/company_form'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>公司表报</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe726;</i>
                        <cite>权限分配</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('keeper/user_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>用户列表</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('keeper/admin_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>管理员列表</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="layui-icon">&#xe631;</i>
                        <cite>数据管理</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Port/port_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>港口管理</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('CarMan/ship_name'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>船名管理</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('CarMan/sealine_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>航线详情</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe725;</i>
                        <cite>通讯录</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="<?php echo url('Car/car_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>车队通讯录</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('Ship/ship_List'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>船公司通讯录</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('ShipMan/man_list'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>船公司人员资料</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="<?php echo url('CarMan/man_List'); ?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>车队人员资料</cite>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6ce;</i>
                        <cite>系统统计</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="echarts1.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>拆线图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts2.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>柱状图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts3.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>地图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts4.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>饼图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts5.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>雷达图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts6.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>k线图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts7.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>热力图</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="echarts8.html">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>仪表图</cite>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <script>
        window.onload = function func() {
            $(document).click(function () { return true; });
        }
    </script>>
</body>

</html>