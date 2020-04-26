<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;


/**
 * 经销商类
 */
class Seller extends ShopModel
{
    const TOP = 3;//顶级代理 渠道商
    const JUNIOR = 1;//初级代理 VIP
    /**
     * 收支流水有变动，要重新统计可提现
     * @param CommonEvent $event
     */
    public static function flowChange(CommonEvent $event)
    {
        $flow = $event->extraData;

        $seller = static::findOne($flow->SellerID);
        if ($seller) {
            $seller->updateStatus();
        }
    }

    /**
     * 更新经销商各种金额的状态
     */
    public function updateStatus()
    {
        $this->fWithdraw = $this->computeWithdraw;
        $this->fUnsettlement = $this->computeUnsettlement;
        $this->fSettlement = $this->computeSettlement;
        $this->fFrozen = $this->computeFrozen;
        $this->fWithdrawed = $this->computeWithdrawed;
        $this->fSumIncome = $this->computeSumIncome;

        $this->save();
    }

    /**
     * 退款成功
     * @param CommonEvent $event
     */
    public static function refundSuccess(CommonEvent $event)
    {
        $refund = $event->extraData;
        $sellerOrder = \Yii::$app->sellerorder->findByID($refund->OrderID);
        if ($sellerOrder) {
            if ($sellerOrder->SellerID) {
                $seller = static::findOne($sellerOrder->SellerID);
                $seller->updateStatus();
            }

            if ($sellerOrder->UpSellerID) {
                $upSeller = static::findOne($sellerOrder->UpSellerID);
                $upSeller->updateStatus();
            }

            if ($sellerOrder->UpUpSellerID) {
                $upUpSeller = static::findOne($sellerOrder->UpUpSellerID);
                $upUpSeller->updateStatus();
            }
        }
    }

    /**
     * 订单付款成功事件
     * @param CommonEvent $event
     */
    public static function orderPaySuccess(CommonEvent $event)
    {
        $order = $event->extraData;
        $sellerOrder = \Yii::$app->sellerorder->findByID($order->lID);
        if ($sellerOrder) {
            if ($sellerOrder->SellerID) {
                $seller = static::findOne($sellerOrder->SellerID);
                $seller->updateStatus();
            }

            if ($sellerOrder->UpSellerID) {
                $upSeller = static::findOne($sellerOrder->UpSellerID);
                $upSeller->updateStatus();
            }

            if ($sellerOrder->UpUpSellerID) {
                $upUpSeller = static::findOne($sellerOrder->UpUpSellerID);
                $upUpSeller->updateStatus();
            }
        }
    }

    /**
     * 入驻成功
     */
    public static function joinSuccess(CommonEvent $event)
    {
        $sellerJoin = $event->extraData;
        $member = \Yii::$app->member->findByID($sellerJoin->MemberID);
        $upSeller = \Yii::$app->seller->findByID($sellerJoin->RecommSellerID);
        $seller = \Yii::$app->seller->findByID($sellerJoin->MemberID);

        if (!$member || $seller) {
            return false;
        }

        //创建经销商账户
        $seller = new static();
        $seller->lID = $member->lID;
        $seller->sName = $sellerJoin->sName;
        $seller->MemberID = $member->lID;

        if ($upSeller) {
            $seller->UpID = $upSeller->lID;
            if ($upSeller->UpID) {
                $seller->UpUpID = $upSeller->UpID;
            }
        }


        $seller->TypeID = $sellerJoin->TypeID;
        $seller->bActive = 1;
        $seller->sMobile = $sellerJoin->sMobile;
        $seller->dNewDate = $sellerJoin->dJoinDate;
        $seller->save();

        if ($upSeller) {
            $seller->PathID = $upSeller->PathID . $seller->lID . "/";
        } else {
            $seller->PathID = "/" . $seller->lID . "/";
        }
        $seller->save();

        if (!$member->sMobile) {
            $member->sMobile = $seller->sMobile;
            $member->save();
        }
    }

    /**
     * 提现成功
     * @param CommonEvent $event
     */
    public static function withdrawSuccess(CommonEvent $event)
    {
        $log = $event->extraData;
        $seller = static::findOne($log->SellerID);
        if ($seller) {
            $seller->updateStatus();
        }
    }

    /**
     * 提现付款成功
     * @param CommonEvent $event
     */
    public static function withdrawPaySuccess(CommonEvent $event)
    {
        $log = $event->extraData;
        $seller = static::findOne($log->SellerID);
        if ($seller) {
            $seller->updateStatus();
        }
    }

    /**
     * 后台手动升级
     */
    public function levelUp($SellerID, $RecommSellerID, $TypeID)
    {
        $member = \Yii::$app->member->findByID($SellerID);
        $upSeller = \Yii::$app->seller->findByID($RecommSellerID);

        //创建经销商账户
        $seller = new static();
        $seller->lID = $member->lID;
        $seller->sName = $member->sName;
        $seller->MemberID = $member->lID;

        if ($upSeller) {
            $seller->UpID = $upSeller->lID;
            if ($upSeller->UpID) {
                $seller->UpUpID = $upSeller->UpID;
            }
        }

        $seller->TypeID = $TypeID;
        $seller->bActive = 1;
        $seller->sMobile = $member->sMobile;
        $seller->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $seller->save();

        if ($upSeller) {
            $seller->PathID = $upSeller->PathID . $seller->lID . "/";
        } else {
            $seller->PathID = "/" . $seller->lID . "/";
        }
        $seller->save();

        $shop = new SellerShop();
        $shop->lID = $seller->lID;
        $shop->sName = $member->sName;
        $shop->SellerID = $seller->lID;
        $shop->sPhone = $seller->sMobile;
        $shop->dNewDate = $seller->dNewDate;
        $shop->save();
    }

    /**
     * 关联经销商类别
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(SellerType::className(), ['lID' => 'TypeID']);
    }

    /**
     * 关联店铺
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(SellerShop::className(), ['lID' => 'lID']);
    }

    /**
     * 推荐人
     * @return \yii\db\ActiveQuery
     */
    public function getUpSeller()
    {
        return $this->hasOne(static::className(), ['lID' => 'UpID']);
    }

    /**
     * 上上级
     * @return \yii\db\ActiveQuery
     */
    public function getUpUpSeller()
    {
        return $this->hasOne(static::className(), ['lID' => 'UpUpID']);
    }

    /**
     * 直接下级
     * @return \yii\db\ActiveQuery
     */
    public function getArrSubSeller()
    {
        return $this->hasMany(static::className(), ['UpID' => 'lID']);
    }

    /**
     * 实时计算可提现金额，可提现=已结算-冻结金额
     */
    public function getComputeWithdraw()
    {
        //可提现金额-提现中金额=现在可提现金额
        return $this->fSettlement-$this->fWithdrawed;
    }

    /**
     * 实时计算冻结金额
     */
    public function getComputeFrozen()
    {
        return \Yii::$app->sellerwithdrawlog->computeFrozen($this->lID);
    }

    /**
     * 实时计算已提现的金额
     */
    public function getComputeWithdrawed()
    {
        return \Yii::$app->sellerflow->computeWithdrawed($this->lID);
    }

    /**
     * 实时计算累计收入
     */
    public function getComputeSumIncome()
    {
        return $this->getComputeSettlement() + $this->getComputeUnsettlement();
    }

    /**
     * 实时计算已结算金额
     */
    public function getComputeSettlement()
    {
        return \Yii::$app->sellerorder->computeSttlement($this->lID);
    }

    /**
     * 实时计算未结算金额
     */
    public function getComputeUnsettlement()
    {
        return \Yii::$app->sellerorder->computeUnsettlement($this->lID);
    }

    /**
     * 绑定银行卡
     */
    public function bindBank($data)
    {
        static::updateAll($data, ['lID' => \Yii::$app->frontsession->MemberID]);
    }

    /**
     * 获取所有下级经销商
     * @param $UpID
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findSub($UpID)
    {
        return static::find()->where(['UpID' => $UpID])->all();
    }

    /**
     * 关联统计分析
     * @return \yii\db\ActiveQuery
     */
    public function getStats()
    {
        return $this->hasOne(SellerStats::className(), ['lID' => 'lID']);
    }

    /**
     * 关联会员
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['lID' => 'lID']);
    }

    public function getTopSeller()
    {
        if (\Yii::$app->frontsession->sShopUrl) {
            $shopID = str_replace("shop", "", \Yii::$app->frontsession->sShopUrl);
        } else {
            $shopID = \Yii::$app->frontsession->member->FromMemberID;
        }
        $seller = static::findOne($shopID);
        if ($seller->TypeID == 3) {
            return $shopID;
        } else {
            $upIDs = explode('/', $seller->PathID);
            $seller = static::find()->where(['and', ['TypeID' => 3], ['lID' => $upIDs]])->one();
            if ($seller) {
                return $seller->lID;
            } else {
                return false;
            }
        }

    }

    public function getSeller(){
        return self::findOne(\Yii::$app->frontsession->MemberID);
    }
}