<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;


/**
 * 经销商提现记录
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
class SellerWithdrawLog extends ShopModel
{
    /**
     * 计算冻结金额
     * @param $SellerID
     */
    public function computeFrozen($SellerID)
    {
        return static::find()->where(['SellerID' => $SellerID, 'TypeID' => [0, 1]])->sum('fMoney');
    }


    /**
     * 提现申请
     * @param $fMoney
     */
    public function withdraw($fMoney)
    {
        $seller = \Yii::$app->frontsession->seller;

        if ($fMoney < 1) {
            return ['status' => false, 'message' => "金额低于1元时不可提现"];
        }

        if ($fMoney > $seller->computeWithdraw) {
            return ['status' => false, 'message' => '可提现金额不足'];
        }

        $log = new static();
        $log->sName = "ACT" . $this->makeTradeNo();
        $log->SellerID = $seller->lID;
        $log->TypeID = 1;
        $log->fMoney = $fMoney;
        $log->sBank = $seller->sBank;
        $log->sBankAccount = $seller->sBankRealName;
        $log->sBankNo = $seller->sBankAccount;
        $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $log->sOpenID = \Yii::$app->frontsession->sOpenID;
        $log->save();
        //新增会员提现中金额
        $seller->fWithdrawed += $fMoney;
        $seller->save();
        //同步到后台提现申请
        $withdraw=new Withdraw();

        //获取供应商ID
        $withdraw -> sName = $log->sName;
        $withdraw -> CheckID = 0;
        $withdraw -> dNewDate = \Yii::$app->formatter->asDatetime(time());
        $withdraw -> NewUserID = $seller->lID;
        $withdraw -> fMoney = $fMoney;
        $withdraw -> save();
        return ['status' => true];
    }

    /**
     * 生成订单号，长格式的时间+随机码
     */
    public function makeTradeNo()
    {
        return str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time())) . rand(10000, 99999);
    }

    /**
     * 提现记录列表
     * @param $page
     */
    public function withdrawList($page, $TypeID)
    {
        $page = $page ? intval($page) : 1;

        if ($TypeID == 1) {
            return static::find()->where(['SellerID' => \Yii::$app->frontsession->seller->lID, 'TypeID' => [0, 1]])
                ->orderBy('dNewDate DESC')->limit(10)->offset(($page - 1) * 10)->all();
        } elseif ($TypeID == 2) {
            return static::find()->where(['SellerID' => \Yii::$app->frontsession->seller->lID, 'TypeID' => $TypeID])
                ->orderBy('dNewDate DESC')->limit(10)->offset(($page - 1) * 10)->all();
        } else {
            return static::find()->where(['SellerID' => \Yii::$app->frontsession->seller->lID])
                ->orderBy('dNewDate DESC')->limit(10)->offset(($page - 1) * 10)->all();
        }
    }

    /**
     * 获取状态
     */
    public function getSStatus()
    {
        if ($this->TypeID == 1) {
            return "提现中";
        } elseif ($this->TypeID == 2) {
            return "已提现";
        } else {
            return "提现失败";
        }
    }

    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['lID' => 'SellerID']);
    }

    /**
     * 提现
     */
    public function pay()
    {
        $arrLog = static::find()->where(['TypeID' => 1])->all();
        foreach ($arrLog as $log) {
            $arrHandle = json_decode($log->sLog, true);
            if (!$arrHandle) {
                $arrHandle = [];
            }
            $result = \Yii::$app->wechat->merchant_pay->send(
                [
                    'partner_trade_no' => $log->sName,
                    'openid' => $log->sOpenID,
                    'check_name' => 'NO_CHECK',
                    'amount' => $log->fMoney * 100,  //单位为分
                    'desc' => "提现申请",
                    'spbill_create_ip' => "127.0.0.1"
                ]
            );
            $arrReturn = json_decode($result, true);
            if ($arrReturn['return_code'] == 'SUCCESS' && $arrReturn['result_code'] == 'SUCCESS') {
                $log->dAcceptDate = \Yii::$app->formatter->asDatetime(time());
                $log->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
                $log->TypeID = 2;

                array_unshift($arrHandle, ['微信受理', $log->dAcceptDate]);
                array_unshift($arrHandle, ['确认到账', $log->dCompleteDate]);
                $log->sLog = json_encode($arrHandle);

                $log->save();

                $event = new CommonEvent();
                $event->extraData = $log;
                \Yii::$app->sellerwithdrawlog->trigger("paysuccess", $event);
            } else {
                $log->dAcceptDate = \Yii::$app->formatter->asDatetime(time());
                $log->TypeID = 0;
                $log->sFailReason = $arrReturn['err_code_des'];

                array_unshift($arrHandle, ['微信受理失败', $log->dAcceptDate]);
                $log->sLog = json_encode($arrHandle);

                $log->save();

                $event = new CommonEvent();
                $event->extraData = $log;
                \Yii::$app->sellerwithdrawlog->trigger("payfail", $event);
            }
        }
    }

    /**
     * 确认提现
     */
    public function confirm()
    {
        $this->dAcceptDate = \Yii::$app->formatter->asDatetime(time());
        $this->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $this->TypeID = 2;

        $arrHandle = json_decode($this->sLog, true);
        if (!$arrHandle) {
            $arrHandle = [];
        }
        array_unshift($arrHandle, ['人工受理', $this->dAcceptDate]);
        array_unshift($arrHandle, ['到账', $this->dAcceptDate]);
        $this->sLog = json_encode($arrHandle);

        $this->save();

        $event = new CommonEvent();
        $event->extraData = $this;
        \Yii::$app->sellerwithdrawlog->trigger("paysuccess", $event);
    }
}