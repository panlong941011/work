<?php

namespace myerm\shop\common\models;


/**
 * 金币充值记录
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2018年1月24日 10:49:28
 * @version v1.5
 */
class GoldRecharge extends ShopModel
{

    /**
     * 充值
     * @param $data
     */
    public function recharge($data)
    {
        $recharge = new static();

        if ($data['sName']) {
            $recharge->sName = $data['sName'];
        } else {
            $recharge->sName = static::makeCode();
        }
        
        $recharge->fPaid = $data['fPaid'];
        $recharge->fGive = $data['fGive'];
        $recharge->fChange = $recharge->fPaid + $recharge->fGive;
        $recharge->MemberID = $data['MemberID'];
        $recharge->NewUserID = $data['NewUserID'];
        $recharge->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $recharge->PaymentID = $data['PaymentID'];
        $recharge->sNote = $data['sNote'];

        if ($data['dPayDate']) {
            $recharge->dPayDate = $data['dPayDate'];
        }
        $recharge->save();

    }

    public static function makeCode()
    {
        return "GR" . str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time())) . rand(10000, 99999);
    }
}