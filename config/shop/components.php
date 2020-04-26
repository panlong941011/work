<?php

$components = [];

//前台的会话类
$components['frontsession'] = [
    'class' => 'myerm\shop\common\models\\FrontSession',
    'events' => [
        [
            'start',
            function ($event) {
            }
        ],//会话开始事件
        [
            'end',
            function ($event) {
}
        ],//会话结束事件
    ]
];

$components['request'] = [
    'class' => 'myerm\shop\common\components\Request',
    'cookieValidationKey' => 'myerm',
];

$components['errorHandler'] = [
    'errorAction' => 'error',
];

$components['wechat'] = [
    'class' => 'myerm\shop\common\components\Wechat',
];

//全局类
$components['shoperm'] = [
    'class' => 'myerm\shop\common\models\\ShopModel',
];

//会员类
$components['member'] = [
    'class' => 'myerm\shop\common\models\\Member',
    'events' => [
        ['wx_login', ['myerm\shop\mobile\models\\Cart', 'saveSessionProductIntoMember']]
    ]
];

//微信用户类
$components['wxuser'] = [
    'class' => 'myerm\shop\mobile\models\\WXUser',
];

//购物车类
$components['cart'] = [
    'class' => 'myerm\shop\mobile\models\\Cart',
];

//订单确认页
$components['ordercheckout'] = [
    'class' => 'myerm\shop\mobile\models\\OrderCheckout',
];

//供应商类
$components['supplier'] = [
    'class' => 'myerm\shop\mobile\models\\Supplier',
];

//供应商提现账户类 add by hcc on 2018-05-03
$components['supplierbankaccount'] = [
    'class' => 'myerm\shop\common\models\\SupplierBankAccount',
];

//商城设置类 add by pl on 2018-05-15
$components['mallconfig'] = [
    'class' => 'myerm\shop\common\models\\MallConfig',
];

//采购商类 add by hcc on 2018-05-03
$components['buyer'] = [
    'class' => 'myerm\shop\common\models\\Buyer',
];

//交易记录类 add by hcc on 2018-05-04
$components['dealflow'] = [
    'class' => 'myerm\shop\common\models\\DealFlow',
];

//提现记录类 add by hcc on 2018-05-05
$components['withdraw'] = [
    'class' => 'myerm\shop\common\models\\Withdraw',
    'events' => [
        ['newdeal', ['\myerm\shop\common\models\\Withdraw', 'newDeal']],
    ]
];

//商品库存变动记录类 add by hcc on 2018-05-05
$components['productstockchange'] = [
    'class' => 'myerm\shop\common\models\\ProductStockChange',
];

//商品
$components['product'] = [
    'class' => 'myerm\shop\mobile\models\\Product',
];

//商品规格
$components['productspec'] = [
    'class' => 'myerm\shop\mobile\models\\ProductSpecification',
];

//商品SKU
$components['productsku'] = [
    'class' => 'myerm\shop\mobile\models\\ProductSKU',
];

//商品标签
$components['producttag'] = [
    'class' => 'myerm\shop\mobile\models\\ProductTag',
];

//商品品牌
$components['productbrand'] = [
    'class' => 'myerm\shop\mobile\models\\ProductBrand',
];

//商城首页商品配置
$components['homeproduct'] = [
    'class' => 'myerm\shop\mobile\models\\MallHomeProductConfig',
];

//商品分类的类
$components['productcat'] = [
    'class' => 'myerm\shop\mobile\models\\ProductCat',
];

//运费模板类
$components['shiptemplate'] = [
    'class' => 'myerm\shop\mobile\models\\ShipTemplate',
];

//运费模板明细类
$components['shiptemplatedetail'] = [
    'class' => 'myerm\shop\mobile\models\\ShipTemplateDetail',
];

//运费模板免邮类
$components['shiptemplatefree'] = [
    'class' => 'myerm\shop\mobile\models\\ShipTemplateFree',
];

//运费指定不发货地区类
$components['shiptemplatenodelivery'] = [
    'class' => 'myerm\shop\mobile\models\\ShipTemplateNoDelivery',
];

//区域类
$components['area'] = [
    'class' => 'myerm\shop\mobile\models\\Area',
];

$components['memberaddress'] = [
    'class' => 'myerm\shop\mobile\models\\MemberAddress',
];

//订单类
$components['order'] = [
    'class' => 'myerm\shop\mobile\models\\Order',
    'events' => [
        ['saveorder', ['\myerm\shop\common\models\\SellerOrder', 'saveOrder']],
        ['paysuccess', ['\myerm\shop\common\models\\Seller', 'orderPaySuccess']],
//        ['success', ['\myerm\shop\common\models\\SellerFlow', 'orderSuccess']],
    ]
];

$components['orderdetail'] = [
    'class' => 'myerm\shop\mobile\models\\OrderDetail',
    'events' => [
        ['saveorder', ['\myerm\shop\common\models\\SecKillProductSKU', 'saveOrder']],
        ['saveorder', ['\myerm\shop\common\models\\SecKillProduct', 'saveOrder']],
        ['saveorder', ['\myerm\shop\mobile\models\\Cart', 'afterOrderSave']],
        ['saveorder', ['\myerm\shop\mobile\models\\Product', 'saveOrder']],
        ['saveorder', ['\myerm\shop\mobile\models\\ProductSKU', 'saveOrder']],
        ['paysuccess', ['\myerm\shop\mobile\models\\Product', 'orderPaySuccess']],
        ['paysuccess', ['\myerm\shop\mobile\models\\ProductSKU', 'orderPaySuccess']],
        ['autoclose', ['\myerm\shop\common\models\\SecKillProductSKU', 'orderAutoClose']],
        ['autoclose', ['\myerm\shop\common\models\\SecKillProduct', 'orderAutoClose']],
        ['autoclose', ['\myerm\shop\mobile\models\\Product', 'orderAutoClose']],
        ['autoclose', ['\myerm\shop\mobile\models\\ProductSKU', 'orderAutoClose']],
    ]
];

//发货推送失败记录类
$components['failshippush'] = [
    'class' => 'myerm\shop\common\models\\FailShipPush',
];

//退货信息类
$components['returns'] = [
    'class' => 'myerm\shop\common\models\\Returns',
];

$components['refund'] = [
    'class' => 'myerm\shop\mobile\models\\Refund',
    'events' => [
        ['apply', ['\myerm\shop\mobile\models\\OrderDetail', 'refundApply']],
        ['apply', ['\myerm\shop\mobile\models\\Order', 'refundApply']],
        ['success', ['\myerm\shop\mobile\models\\OrderDetail', 'refundSuccess']],
        ['success', ['\myerm\shop\mobile\models\\Order', 'refundSuccess']],
        ['closed', ['\myerm\shop\mobile\models\\OrderDetail', 'refundClosed']],
        ['closed', ['\myerm\shop\mobile\models\\Order', 'refundClosed']],
//        ['agree', ['\myerm\shop\mobile\models\\Refund', 'agreeNotify']],
        ['denyapply', ['\myerm\shop\mobile\models\\Refund', 'denyApplyNotify']],
//        ['denyreceive', ['\myerm\shop\mobile\models\\Refund', 'denyReceiveNotify']],
    ]
];

$components['seckill'] = [
    'class' => 'myerm\shop\common\models\\SecKill',
];

$components['seckillproduct'] = [
    'class' => 'myerm\shop\common\models\\SecKillProduct',
];

$components['productparam'] = [
    'class' => 'myerm\shop\common\models\\ProductParam',
];

$components['productparamvalue'] = [
    'class' => 'myerm\shop\common\models\\ProductParamValue',
];

$components['seller'] = [
    'class' => 'myerm\shop\common\models\\Seller',
];

$components['sellerflow'] = [
    'class' => 'myerm\shop\common\models\\SellerFlow',
    'events' => [
        ['change', ['\myerm\shop\common\models\\Seller', 'flowChange']],
    ]
];

$components['sellerjoin'] = [
    'class' => 'myerm\shop\common\models\\SellerJoin',
    'events' => [
        ['success', ['\myerm\shop\common\models\\Seller', 'joinSuccess']],
        ['success', ['\myerm\shop\common\models\\SellerShop', 'joinSuccess']],
    ]
];

$components['sellerorder'] = [
    'class' => 'myerm\shop\common\models\\SellerOrder',
];

$components['sellershop'] = [
    'class' => 'myerm\shop\common\models\\SellerShop',
];

$components['sellertype'] = [
    'class' => 'myerm\shop\common\models\\SellerType',
];

$components['sellerwithdrawlog'] = [
    'class' => 'myerm\shop\common\models\\SellerWithdrawLog',
    'events' => [
        ['success', ['\myerm\shop\common\models\\Seller', 'withdrawSuccess']],
        ['paysuccess', ['\myerm\shop\common\models\\SellerFlow', 'withdrawPaySuccess']],
        ['paysuccess', ['\myerm\shop\common\models\\Seller', 'withdrawPaySuccess']],
    ]
];

$components['sellerstats'] = [
    'class' => 'myerm\shop\common\models\\SellerStats',
];

$components['homeseckill'] = [
    'class' => 'myerm\shop\common\models\\HomeSecKill',
];

$components['goldflow'] = [
    'class' => 'myerm\shop\common\models\\GoldFlow',
];

$components['goldrecharge'] = [
    'class' => 'myerm\shop\common\models\\GoldRecharge',
];

$components['expresstrace'] = [
    'class' => 'myerm\kuaidi100\models\ExpressTrace',
];

//来三斤同步到云端版本更新记录
$components['upgradeversionlog'] = [
	'class' => 'myerm\shop\common\models\\UpgradeVersionLog',
];

//渠道商
$components['wholesaler'] = [
	'class' => 'myerm\shop\common\models\\Wholesaler',
];

//待确认订单
$components['preorder'] = [
	'class' => 'myerm\shop\common\models\\PreOrder',
];

return $components;