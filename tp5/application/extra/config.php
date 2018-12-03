<?php
//订单状态配置

return [
    //港到港的订单状态编码
    //booking_note  up_container_code 更改状态和字段
    //payment_status  container_appley container_lock container_unlock 更改字段 
    //check_bill 只对bill表作用
    'order_status' =>[
        'stop'=>505,//中止
        'cancel'=>404,//取消
        'order_audit'=>2,//订单审核
        'booking_note'=>3, //上传订舱单和运单号
                    'send_car'=>4,  //派车信息录入
                    'loading'=>5, //装货信息录入
        'up_container_code'=>7,// 提交柜号
                    'load_ship'=>8, //配船
//                    'arrive_port '=>9, //到港
//                    'unload_ship'=>10, //卸船
        'payment_status'=>11,//更改付款状态,在线支付就不需要
        'sea_waybill'=>12,//上传水运单
            'container_appley'=>13, //发起申请放柜
            'container_lock'=>14,   //继续扣柜
            'container_unlock'=>15, //同意放柜
       
        'unloading'=>16,//送货派车信息录入
        'completion'=>17, //港到港订单完成
             'check_bill'=>18, //港到港对账单 单独使用
    ]
];