<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;
use myerm\common\components\Func;


/**
 * 经销商收支明细
 */
class SellerFlow extends ShopModel
{
    const EVENT_CHANGE = 'change';
    const TYPE_UNSET = 1;//类型 待结算
    const TYPE_CASH = 2;//类型 可提现

    /**
     * 订单交易成功
     * @param CommonEvent $event
     */
    public static function orderSuccess(CommonEvent $event)
    {
        $order = $event->extraData;

        $sellOrder = \Yii::$app->sellerorder->findByID($order->lID);
        if ($sellOrder) {
            if ($sellOrder->SellerID) {
                $flow = new static();
                $flow->sName = "订单【" . $order->sName . "】交易成功，获得销售提成";
                $flow->SellerID = $sellOrder->SellerID;
                $flow->TypeID = 1;
                $flow->fChange = $sellOrder->fSellerCommission;
                $flow->fChangeBefore = $sellOrder->seller->computeWithdraw;
                $flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
                $flow->fWithdraw = $flow->fChangeAfter;
                $flow->dNewDate = $order->dReceiveDate;
                $flow->OrderID = $order->lID;
                $flow->sCommissionType = "销售提成";
                $flow->save();

                $event = new CommonEvent();
                $event->extraData = $flow;//传值订单明细
                \Yii::$app->sellerflow->trigger(static::EVENT_CHANGE, $event);
            }

            if ($sellOrder->UpSellerID) {
                $flow = new static();
                $flow->sName = "订单【" . $order->sName . "】交易成功，获得一级团队提成";
                $flow->SellerID = $sellOrder->UpSellerID;
                $flow->TypeID = 1;
                $flow->fChange = $sellOrder->fUpSellerCommission;
                $flow->fChangeBefore = $sellOrder->upSeller->computeWithdraw;
                $flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
                $flow->fWithdraw = $flow->fChangeAfter;
                $flow->dNewDate = $order->dReceiveDate;
                $flow->OrderID = $order->lID;
                $flow->sCommissionType = "一级团队提成";
                $flow->save();

                $event = new CommonEvent();
                $event->extraData = $flow;//传值订单明细
                \Yii::$app->sellerflow->trigger(static::EVENT_CHANGE, $event);
            }

            if ($sellOrder->UpUpSellerID) {
                $flow = new static();
                $flow->sName = "订单【" . $order->sName . "】交易成功，获得二级团队提成";
                $flow->SellerID = $sellOrder->UpUpSellerID;
                $flow->TypeID = 1;
                $flow->fChange = $sellOrder->fUpUpSellerCommission;
                $flow->fChangeBefore = $sellOrder->upUpSeller->computeWithdraw;
                $flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
                $flow->fWithdraw = $flow->fChangeAfter;
                $flow->dNewDate = $order->dReceiveDate;
                $flow->OrderID = $order->lID;
                $flow->sCommissionType = "二级团队提成";
                $flow->save();

                $event = new CommonEvent();
                $event->extraData = $flow;//传值订单明细
                \Yii::$app->sellerflow->trigger(static::EVENT_CHANGE, $event);
            }
        }
    }

    /**
     * 提现付款成功
     * @param CommonEvent $event
     */
    public static function withdrawPaySuccess(CommonEvent $event)
    {
        $log = $event->extraData;

        $flow = new static();
        $flow->sName = "申请提现";
        $flow->SellerID = $log->SellerID;
        $flow->TypeID = 2;

        $flow->fChange = -$log->fMoney;
        $flow->fChangeBefore = $log->seller->computeWithdraw;
        $flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
        $flow->fWithdraw = $flow->fChangeAfter;
        $flow->dNewDate = $log->dCompleteDate;
        $flow->WithdrawID = $log->lID;
        $flow->save();
    }

    /**
     * 实时统计$SellerID的可提现余额
     * @param $SellerID
     * @return mixed
     */
    public function computeWithdraw($SellerID)
    {
        return static::find()->where(['SellerID' => $SellerID])->sum("fChange");
    }

    /**
     * 实时统计$SellerID的已提现金额
     * @param $SellerID
     * @return mixed
     */
    public function computeWithdrawed($SellerID)
    {
        return abs(static::find()->where(['SellerID' => $SellerID, 'TypeID' => 2])->sum("fChange"));
    }


    /**
     * 实时统计$SellerID的累计收入
     * @param $SellerID
     * @return mixed
     */
    public function computeSumIncome($SellerID)
    {
        return static::find()->where(['SellerID' => $SellerID, 'TypeID' => 1])->sum("fChange");
    }

    /**
     * 流水列表
     */
    public function flowList($config)
    {
        $page = $config['page'] ? intval($config['page']) : 1;

        $flowSearch = static::find()->where(['SellerID' => \Yii::$app->frontsession->seller->lID]);

        if ($config['type']) {
            $flowSearch->andWhere(['TypeID' => $config['type']]);
        }

        if ($config['date']) {
            $flowSearch->andWhere(['>=', 'dNewDate', $config['date'] . "-01 00:00:00"]);
            $flowSearch->andWhere(['<=', 'dNewDate', $config['date'] . "-31 23:59:59"]);
        }

        return $flowSearch->orderBy("dNewDate DESC")->limit(10)->offset(($page - 1) * 10)
            ->with('arrOrderDetail')
            ->all();;
    }

    /**
     * 关联订单
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['lID' => 'OrderID'])->with('member');
    }

    /**
     * 关联订单
     */
    public function getSellerOrder()
    {
        return $this->hasOne(SellerOrder::className(), ['OrderID' => 'OrderID'])->with('seller');
    }

    public function getArrOrderDetail()
    {
        return $this->hasMany(OrderDetail::className(), ['OrderID' => 'OrderID']);
    }

    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['lID' => 'SellerID']);
    }

    public function getWithdrawlog()
    {
        return $this->hasOne(SellerWithdrawLog::className(), ['lID' => 'WithdrawID']);
    }

    /**
     * 发放订单提成待结算流水
     * @param $No string 微信支付交易号
     * @author panlong
     * @time 2019年9月16日14:13:43
     */
    public static function setWithdrawFlow($arrData)
    {
        $fMoney = $arrData['fMoney'];
        $order = $arrData['order'];
        $seller = $arrData['seller'];

        $flow = new self();
        $flow->sName = "订单【" . $order->sName . "】支付成功，获得销售提成";
        $flow->SellerID = $seller->lID;
        $flow->TypeID = self::TYPE_UNSET;
        $flow->fChange = $fMoney;
        $flow->fChangeBefore = $seller->fUnsettlement;
        $flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
        $flow->dNewDate = Func::getDate();
        $flow->OrderID = $order->lID;
        $flow->sCommissionType = "销售提成";
        $flow->save();
        $seller->lOrderNum += 1;
        $seller->fSumOrder += $order->fSumOrder;
        $seller->fUnsettlement += $fMoney;
        $seller->save();
    }

    /**
     * 生成交易成功扣除待结算流水
     * @param $No string 微信支付交易号
     * @author panlong
     * @time 2019年9月17日14:13:52
     */
    public static function reduceWithdrawFlow($arrData)
    {
        $fMoney = $arrData['fMoney'];
        $order = $arrData['order'];
        $seller = $arrData['seller'];

        $flow = new self();
        $flow->sName = "订单【" . $order->sName . "】交易成功，扣除待结算";
        $flow->SellerID = $seller->lID;
        $flow->TypeID = self::TYPE_UNSET;
        $flow->fChange = $fMoney;
        $flow->fChangeBefore = $seller->fUnsettlement;
        $flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
        $flow->dNewDate = Func::getDate();
        $flow->OrderID = $order->lID;
        $flow->sCommissionType = "销售提成";
        $flow->save();

        $seller->fUnsettlement += $fMoney;
        $seller->save();
    }

    /**
     * 生成交易成功扣除待结算流水
     * @param $No string 微信支付交易号
     * @author panlong
     * @time 2019年9月17日14:13:52
     */
    public static function setCashFlow($arrData)
    {
        $fMoney = $arrData['fMoney'];
        $order = $arrData['order'];
        $seller = $arrData['seller'];

        $flow = new self();
        $flow->sName = "订单【" . $order->sName . "】交易成功，增加收益金额";
        $flow->SellerID = $seller->lID;
        $flow->TypeID = self::TYPE_CASH;
        $flow->fChange = $fMoney;
        $flow->fChangeBefore = $seller->fWithdraw;
        $flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
        $flow->dNewDate = Func::getDate();
        $flow->OrderID = $order->lID;
        $flow->sCommissionType = "销售提成";
        $flow->save();

        $seller->fSettlement += $fMoney;
        $seller->fUnsettlement -= $fMoney;
        $seller->save();
    }

    /**
     * 订单退款成功扣除 待结算
     * @param $No string 微信支付交易号
     * @author panlong
     * @time 2019年9月17日14:13:52
     */
    public static function refundWithdrawFlow($arrData)
    {
        $fMoney = $arrData['fMoney'];
        $seller = $arrData['seller'];

        $flow = new self();
        $flow->sName = "订单【" .$arrData['OrderName'] . "】退款成功，扣除待结算";
        $flow->SellerID = $seller->lID;
        $flow->TypeID = self::TYPE_UNSET;
        $flow->fChange = -$fMoney;
        $flow->fChangeBefore = $seller->fUnsettlement;
        $flow->fChangeAfter = $flow->fChangeBefore -$fMoney;
        $flow->dNewDate = Func::getDate();
        $flow->OrderID = $arrData['OrderID'];
        $flow->sCommissionType = "销售提成";
        $flow->save();

        $seller->fUnsettlement -= $fMoney;
        $seller->save();
    }

}