<?php

namespace myerm\shop\mobile\models;

use myerm\shop\common\models\ShopModel;

/**
 * 订单付款记录
 */
class OrderPayLog extends ShopModel
{
    public function getOrder()
    {
        return Order::findOne($this->sOrderID);
    }
}