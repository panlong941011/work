<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;


/**
 * 秒杀商品SKU
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年11月27日 22:53:47
 * @version v1.0
 */
class SecKillProductSKU extends ShopModel
{
    /**
     * 自动关闭未付款的订单
     * @param CommonEvent $event
     */
    public static function orderAutoClose(CommonEvent $event)
    {
        $orderDetail = $event->extraData;
        if ($orderDetail->SecKillID) {
            //有带规格
            if ($orderDetail->sSKU) {
                static::getDb()->createCommand("UPDATE SecKillProductSKU SET lStock=lStock+{$orderDetail->lQuantity}, lSale=IFNULL(lSale, 0)-{$orderDetail->lQuantity}
                                                    WHERE SecKillProductID='{$orderDetail->secKill->lID}' AND sName='{$orderDetail->sSKU}'")->execute();
            } else {
                static::getDb()->createCommand("UPDATE SecKillProductSKU SET lStock=lStock+{$orderDetail->lQuantity}, lSale=IFNULL(lSale, 0)-{$orderDetail->lQuantity}
                                                    WHERE SecKillProductID='{$orderDetail->secKill->lID}' AND sName='默认规格'")->execute();
            }
        }
    }


    /**
     * 处理下单后的事件
     * @param CommonEvent $event
     */
    public static function saveOrder(CommonEvent $event)
    {
        $orderDetail = $event->extraData;
        if ($orderDetail->SecKillID) {
            //如果这订单明细有关联到秒杀活动

            //有带规格
            if ($orderDetail->sSKU) {
                static::getDb()->createCommand("UPDATE SecKillProductSKU SET lStock=lStock-{$orderDetail->lQuantity}, lSale=IFNULL(lSale, 0)+{$orderDetail->lQuantity}
                                                    WHERE SecKillProductID='{$orderDetail->secKill->lID}' AND sName='{$orderDetail->sSKU}'")->execute();
            } else {
                static::getDb()->createCommand("UPDATE SecKillProductSKU SET lStock=lStock-{$orderDetail->lQuantity}, lSale=IFNULL(lSale, 0)+{$orderDetail->lQuantity}
                                                    WHERE SecKillProductID='{$orderDetail->secKill->lID}' AND sName='默认规格'")->execute();
            }
        }
    }

    public function getSku()
    {
        return $this->hasOne(ProductSKU::className(), ['lID' => 'ProductSkuID']);
    }

    public function getFCostPrice()
    {
        if ($this->ProductSkuID) {
            return $this->sku->fCostPrice;
        }
    }
}