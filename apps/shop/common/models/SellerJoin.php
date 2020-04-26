<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;


/**
 * 经销商入驻明细
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年12月8日 14:07:33
 * @version v1.0
 */
class SellerJoin extends ShopModel
{
    /**
     * 入驻申请
     */
    public function reg($config)
    {
        $join = new static();
        $join->sTradeNo = $this->makeTradeNo();
        $join->MemberID = \Yii::$app->frontsession->MemberID;

        if (\Yii::$app->frontsession->urlSeller) {
            $join->RecommSellerID = \Yii::$app->frontsession->urlSeller->lID;
        } elseif (\Yii::$app->frontsession->member->FromMemberID) {
            $join->RecommSellerID = \Yii::$app->frontsession->member->FromMemberID;
        }

        $join->sName = $config['sName'];
        $join->sMobile = $config['sMobile'];
        $join->dNewDate = \Yii::$app->formatter->asDatetime(time());

        $sellType = SellerType::find()->where(['sName' => $config['sType']])->one();

        $join->TypeID = $sellType->lID;
        $join->fMoney = $sellType->fJoin;
        $join->save();

        return $join;
    }

    /**
     * 生成订单号，长格式的时间+随机码
     */
    public function makeTradeNo()
    {
        return str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time())) . rand(10000, 99999);
    }

    /**
     * 通过支付单号查询
     */
    public function findByNo($sNo)
    {
        return static::find()->where(['sTradeNo' => $sNo])->one();
    }

    /**
     * 入驻成功
     */
    public function joinSuccess()
    {
        $this->dPayDate = \Yii::$app->formatter->asDatetime(time());
        $this->dJoinDate = \Yii::$app->formatter->asDatetime(time());
        $this->PaymentID = "wx";
        $this->save();

        $event = new CommonEvent();
        $event->extraData = $this;//传值订单明细
        \Yii::$app->sellerjoin->trigger("success", $event);
    }
}