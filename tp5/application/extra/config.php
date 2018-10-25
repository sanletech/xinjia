<?php
//订单状态配置

return [
    //港到港的订单状态编码
    //505中止404取消2待审核3录入运单号和上传文件4客户提交柜号5根据收款状态6扣柜7上传水运单文件8通知车队
    'order_status' =>[
        'stop'=>505,//中止
        'cancel'=>404,//取消
        'order_audit'=>2,//订单审核
        'booking_note'=>3, //上传订舱单和运单号
        'up_container_code'=>4,//客户提交柜号
        'payment_status'=>5,//更改付款状态,在线支付就不需要
        'container_appley'=>6, //客户发起申请放柜
        'container_lock'=>7, //继续扣柜
        'container_unlock'=>8, //同意放柜
        'sea_waybill'=>9,//上传水运单
        'completion'=>10, //港到港订单完成
    ]
];