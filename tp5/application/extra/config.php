<?php
//订单状态配置

return [
    //港到港的订单状态编码
    //5,6,7,8, 只更改订单和账单的对应字段money_status，container_buckle
    //4,修改order_bill，order_port的状态和对应字段container_status
    'order_status' =>[
        'stop'=>505,//中止
        'cancel'=>404,//取消
        'order_audit'=>2,//订单审核
        'booking_note'=>3, //上传订舱单和运单号
                    'send_car'=>4,  //派车信息录入
                    'loading'=>5, //装货信息录入
        'up_container_code'=>7,// 提交柜号
                    'load_port'=>8, //配船
                    'to_port'=>9, //到港
                    'unload_ship'=>10, //卸船船
            'payment_status'=>11,//更改付款状态,在线支付就不需要
            'container_appley'=>12, //发起申请放柜
            'container_lock'=>13,   //继续扣柜
            'container_unlock'=>14, //同意放柜
        'sea_waybill'=>15,//上传水运单
        'unloading'=>16,//送货派车信息录入
        'completion'=>17, //港到港订单完成
            'check_bill'=>18, //港到港对账单 单独使用
    ]
];