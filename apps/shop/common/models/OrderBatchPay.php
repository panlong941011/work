<?php

namespace myerm\shop\common\models;

/**
 * 订单批量付款
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明
 * @time 2017年10月23日 12:37:31
 * @version v1.0
 */
class OrderBatchPay extends ShopModel
{
    /**
     * 保存批量付款记录
     * @param $sTradeNo
     * @param $arrOrderID
     */
    public static function batchPaySave($sTradeNo, $arrOrderID)
    {
        $pay = new static();
        $pay->sTradeNo = $sTradeNo;
        $pay->sOrderID = ";".implode(";", $arrOrderID).";";
        $pay->SessionID = \Yii::$app->frontsession->ID;
        $pay->MemberID = \Yii::$app->frontsession->MemberID;
        $pay->save();

        return true;
    }

    public static function remove($sTradeNo)
    {
        static::deleteAll(['sTradeNo'=>$sTradeNo]);
    }
}