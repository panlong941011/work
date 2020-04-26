<?php

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;
use myerm\common\libs\NewID;
use myerm\shop\common\models\ShopModel;

/**
 * 购物车类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月4日 22:22:57
 * @version v2.0
 */
class Cart extends ShopModel
{
    /**
     * 加入到购物车事件
     */
    const EVENT_ADDTOCART = 'addtocart';

    /**
     * 立即购买事件
     */
    const EVENT_BUY = 'buy';


    public static function findByIDs($arrID)
    {
        return static::findAll(['ID' => $arrID]);
    }

    /**
     * 当用户登录之后，把购物车中以SessionID形式保存的商品，转成以MemberID的形式来保存。
     * 这样，用户登录之后可以确保购物车内的商品不变。
     */
    public static function saveSessionProductIntoMember(CommonEvent $event)
    {
        $member = $event->extraData;
        static::updateAll(['MemberID' => $member->lID], ['SessionID' => \Yii::$app->frontsession->ID]);
        static::updateAll(['SessionID' => \Yii::$app->frontsession->ID], ['MemberID' => $member->lID]);
    }

    /**
     * 加入到购物车
     * @param $data
     */
    public function addToCart($data)
    {
        $product = \Yii::$app->product->findByID($data['ProductID']);
         $arrCond = [];
        if (\Yii::$app->frontsession->bLogin) {
            $arrCond['MemberID'] = \Yii::$app->frontsession->MemberID;
        } else {
            $arrCond['SessionID'] = \Yii::$app->frontsession->ID;
        }

        $arrCond['ProductID'] = $data['ProductID'];

        $cart = static::findOne($arrCond);

        if (!$cart) {//之前没加入过购物车
            $cart = new static();
            $cart->ID = NewID::make();
            $cart->SessionID = \Yii::$app->frontsession->ID;
            $cart->ProductID = $data['ProductID'];
            $cart->bGroup = $data['bGroup'];
        }

        if (\Yii::$app->frontsession->MemberID) {
            $cart->MemberID = \Yii::$app->frontsession->MemberID;
        }

        $cart->lQuantity += $data['lQty'];
        $cart->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $cart->sPic = $product->pic;
        $cart->save();

        $event = new CommonEvent();
        $event->extraData = $cart;
        $this->trigger(self::EVENT_ADDTOCART, $event);

        return $cart->ID;
    }

    /**
     * 获取购物车内的商品数量
     */
    public function getLProductQty()
    {
        if (\Yii::$app->frontsession->bLogin) {
            return intval(static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])->sum("lQuantity"));
        }

        return intval(static::find()->where(['SessionID' => \Yii::$app->frontsession->ID])->sum("lQuantity"));
    }

    /**
     * 获取购物车中的商品
     */
    public function getCartProduct()
    {
        if (\Yii::$app->frontsession->bLogin) {
            $arrCartProduct = static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])->with("product")->orderBy("dNewDate DESC")->all();
        } else {
            $arrCartProduct = static::find()->where(['SessionID' => \Yii::$app->frontsession->ID])->with("product")->orderBy("dNewDate DESC")->all();
        }


        return $arrCartProduct;
    }

    /**
     * 关联商品
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(\Yii::$app->product::className(),
            ['lID' => 'ProductID'])->with('shiptemplate')->with('nodelivery')->with('supplier');
    }

    /**
     * 清空购物车中的指定的商品
     */
    public function clear($arrCartID)
    {
        if ($arrCartID) {
            if (\Yii::$app->frontsession->bLogin) {
                static::deleteAll(['ID' => $arrCartID, 'MemberID' => \Yii::$app->frontsession->MemberID]);
            } else {
                static::deleteAll(['ID' => $arrCartID, 'SessionID' => \Yii::$app->frontsession->ID]);
            }
        }

        return true;
    }

    /**
     * 更新购物车数量
     * @param $CartID
     * @param $lQty
     */
    public function updateQty($CartID, $lQty)
    {
        $cart = static::findByID($CartID);
        $product = $cart->product;
        if ($product->lStock < $lQty) {
            return false;
        }

        static::updateAll(['lQuantity' => $lQty], ['ID' => $CartID]);
        return true;
    }

    /**
     * 订单生成之后的处理
     * @param CommonEvent $event
     */
    public static function afterOrderSave(CommonEvent $event)
    {
        \Yii::trace("ttttt");
        $detail = $event->extraData;
        static::deleteAll(['ID' => $detail->CartID]);
    }

    /**
     * 获得购物车中商品的失效状态，如果返回空，表示有效商品
     */
    protected function getSInvalidStatus()
    {
        $invalid_state = "";

        $product = $this->product;
        $product->sSKU = $this->sSKU;

        if (!$product->bSale) {
            $invalid_state = Product::STATUS_OFFSALE;
        } elseif ($product->bDel) {
            $invalid_state = Product::STATUS_DEL;
        } elseif ($product->bSaleOut) {
            $invalid_state = Product::STATUS_SALEOUT;
        } elseif ($product->sku && $product->stock < $this->lQuantity) {
            $invalid_state = Product::STATUS_SPEC_LOW_STOCK;
        } elseif ($product->lStock < $this->lQuantity) {
            $invalid_state = Product::STATUS_LOW_STOCK;
        } elseif ($this->sSKU && !$product->sku) {
            $invalid_state = Product::STATUS_SPEC_NOEXISTS;
        } elseif (!$this->sSKU && $product->arrSku) {
            $invalid_state = Product::STATUS_SPEC_NOSELECTED;
        }

        return $invalid_state;
    }

    public function getFPrice()
    {
        return $this->product->fSalePrice;
    }

    public function getFTotal()
    {
        return $this->fPrice * $this->lQuantity;
    }


}