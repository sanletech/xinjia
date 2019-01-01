<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * $msg 待提示的消息
 * $url 待跳转的链接
 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）
 * $time 弹出维持时间（单位秒）
 */
function alert_success($msg='',$url='',$time=3){ 
    $str='<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js?x86494"></script> <script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js?x86494"></script>';//加载jquery和layer
    $str.='<script>
        $(function(){
            layer.msg("'.$msg.'",{icon:"6",time:'.($time*1000).'});
            setTimeout(function(){
                   self.parent.location.href="'.$url.'"
            },2000)
        });
    </script>';//主要方法
    return $str;
}

/**
 * $msg 待提示的消息
 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）
 * $time 弹出维持时间（单位秒）
 */
function alert_error($msg='',$time=3){ 
    $str='<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js?x86494"></script> <script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js?x86494"></script>';//加载jquery和layer
    $str.='<script>
        $(function(){
            layer.msg("'.$msg.'",{icon:"5",time:'.($time*1000).'});
            setTimeout(function(){
                   window.history.go(-1);
            },2000)
        });
    </script>';//主要方法
    return $str;
}