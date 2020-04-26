<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/8 0008
 * Time: 下午 3:42
 */

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;

/**
 * 商品SKU
 * Class ProductSKU
 * @package myerm\shop\mobile\models
 * @author oyyz <oyyz@3elephant.com>
 * @time 2017-10-8 15:42:47
 */
class ProductSKU extends \myerm\shop\common\models\ProductSKU
{
    /**
     *  订单付款成功后的响应事件
     * @param CommonEvent $event
     */
    public static function orderPaySuccess(CommonEvent $event)
    {

    }

    /**
     * 自动关闭未付款的订单
     * @param CommonEvent $event
     */
    public static function orderAutoClose(CommonEvent $event)
    {
        //修改为自动关闭不吐商品规格库存 by hcc on 2018/6/19
//        $orderDetail = $event->extraData;
//        if ($orderDetail->sSKU && !$orderDetail->SecKillID) {
//            $product = $orderDetail->product;
//            $product->sSKU = $orderDetail->sSKU;
//            static::getDb()->createCommand("UPDATE ProductSKU SET lStock=lStock+{$orderDetail->lQuantity} WHERE lID='{$product->sku->lID}'")->execute();
//        }
    }

    /**
     *  订单成功后的响应事件
     *  这里要用SQL的方式来减库存，如果用PHP计算再去更新，在抢购的时候会出现延迟导致错误。
     * @param CommonEvent $event
     */
    public static function saveOrder(CommonEvent $event)
    {
        $orderDetail = $event->extraData;
        if ($orderDetail->sSKU && !$orderDetail->SecKillID) {
            $product = $orderDetail->product;
            $product->sSKU = $orderDetail->sSKU;
            static::getDb()->createCommand("UPDATE ProductSKU SET lStock=lStock-{$orderDetail->lQuantity} WHERE lID='{$product->sku->lID}'")->execute();
        }
    }
}