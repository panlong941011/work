<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;


/**
 * 秒杀商品
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
class SecKillProduct extends ShopModel
{
    /**
     * 该商品选中了某个SKU，会设置该值
     * @var string
     */
    public $sSKU = "";

    /**
     * 自动关闭未付款的订单
     * @param CommonEvent $event
     */
    public static function orderAutoClose(CommonEvent $event)
    {
        $orderDetail = $event->extraData;
        if ($orderDetail->SecKillID) {
            SecKillProduct::getDb()->createCommand("UPDATE SecKillProduct SET 
                                                            SecKillProduct.lStock=(SELECT SUM(IFNULL(SecKillProductSKU.lStock, 0)) FROM SecKillProductSKU WHERE  SecKillProductSKU.SecKillProductID=SecKillProduct.lID),
                                                            SecKillProduct.lSale=(SELECT SUM(IFNULL(SecKillProductSKU.lSale, 0)) FROM SecKillProductSKU WHERE  SecKillProductSKU.SecKillProductID=SecKillProduct.lID)
                                                         WHERE SecKillProduct.SecKillID='{$orderDetail->SecKillID}'")->execute();
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
            SecKillProduct::getDb()->createCommand("UPDATE SecKillProduct SET 
                                                            SecKillProduct.lStock=(SELECT SUM(IFNULL(SecKillProductSKU.lStock, 0)) FROM SecKillProductSKU WHERE  SecKillProductSKU.SecKillProductID=SecKillProduct.lID),
                                                            SecKillProduct.lSale=(SELECT SUM(IFNULL(SecKillProductSKU.lSale, 0)) FROM SecKillProductSKU WHERE  SecKillProductSKU.SecKillProductID=SecKillProduct.lID)
                                                         WHERE SecKillProduct.SecKillID='{$orderDetail->SecKillID}'")->execute();
        }
    }

    /**
     * 是否已抢光
     */
    public function getBSaleOut()
    {
        return $this->lStock <= 0;
    }

    /**
     * 关联商品
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(\Yii::$app->product::className(), ['lID' => 'ProductID']);
    }

    public function getArrSku()
    {
        return $this->hasMany(SecKillProductSKU::className(),
            ['SecKillID' => 'SecKillID', 'SecKillProductID' => 'lID'])->indexBy('sName');
    }

    /**
     * 通过SKU的值，取该SKU的配置
     * @param $sVal
     * @return mixed
     */
    public function getSku()
    {
        return $this->arrSku[$this->sSKU];
    }


    public function getParent()
    {
        return $this->hasOne(SecKill::className(), ['lID' => 'SecKillID']);
    }

    /**
     * 判断是否购买超出了限购
     */
    public function bOverLimit($lBuyQty)
    {
        if ($this->lNumLimit) {

            if ($lBuyQty > $this->lNumLimit) {
                return true;
            }

            return $lBuyQty + \Yii::$app->orderdetail->countSecKillProduct($this->SecKillID,
                    $this->ProductID) > $this->lNumLimit;
        }

        return false;
    }

    /**
     * 秒杀商品的状态
     */
    public function getSStatus()
    {
        if (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dStartDate) < 0) {
            //未开始
            return "未开始";
        } elseif (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dStartDate) >= 0
            && \Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dEndDate) <= 0
        ) {
            if ($this->bSaleOut) {
                return "已抢光";
            }

            return "未抢光";
        } elseif (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dEndDate) < 300) {

            if ($this->bSaleOut) {
                return "已抢光";
            }

            return "已结束";
        } else {
            return "已结束";
        }
    }

    public function getArrSpec($sMasterPic = '')
    {
        $arrSku = SecKillProductSKU::find()
            ->where(['SecKillProductID' => $this->lID])
            ->andWhere(['<>', 'sName', '默认规格'])
            ->all();
        if (!$arrSku) {
            return [];
        }

        $arrProductSpec = \Yii::$app->productspec->getArrSpec($this->ProductID, $sMasterPic);

        $arrParamsID = [];//每个属性的ID
        $arrSecKillProductSpec = [];
        foreach ($arrSku as $sku) {
            $arr = explode(";", $sku->sName);
            foreach ($arr as $v) {
                $arr2 = explode(":", $v);
                $arrSecKillProductSpec[$arr2[0]][$arr2[1]] = $arrProductSpec['arrSpec'][$arr2[0]][$arr2[1]];
            }
        }

        foreach ($arrSecKillProductSpec as $sGroup => $arrValue) {
            $arrID = [];
            foreach ($arrValue as $v) {
                $arrID[] = $v['id'];
            }

            $arrParamsID[] = $arrID;
        }

        $arrJsonGroup = [];
        foreach ($arrSku as $sku) {
            $arr = explode(";", $sku->sName);
            $arrID = [];

            $sPic = $sMasterPic;
            foreach ($arr as $v) {
                $arr2 = explode(":", $v);
                $arrID[] = $arrSecKillProductSpec[$arr2[0]][$arr2[1]]['id'];

                if ($arrSecKillProductSpec[$arr2[0]][$arr2[1]]['image']) {
                    $sPic = $arrSecKillProductSpec[$arr2[0]][$arr2[1]]['image'];
                }
            }

            $arrJsonGroup[implode(";", $arrID)] = [
                'price' => $sku->fPrice,
                'count' => $sku->lStock,
                'image' => \Yii::$app->request->imgUrl . "/" . $sPic
            ];
        }

        $result = [
            'arrSpec' => $arrSecKillProductSpec,
            'arrSpecID' => json_encode($arrParamsID),
            'sJsonGroup' => json_encode($arrJsonGroup)
        ];


        return $result;
    }

    public function findProduct($SecKillID, $ProductID)
    {
        return static::findOne([
            'SecKillID' => $SecKillID,
            'ProductID' => $ProductID
        ]);
    }

    /**
     * 获取所有未开始、进行中的秒杀商品
     */
    public function findAllActive()
    {
        return static::find()->where("SecKillProduct.dStartDate>now() OR SecKillProduct.dEndDate>now()")->orderBy("SecKillProduct.dStartDate")->all();
    }

    /**
     * 获取售价
     */
    public function getFSalePrice()
    {
        //进行中的秒杀活动
        if (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dStartDate) >= 0
            && \Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dEndDate) <= 0
        ) {
            if ($this->sSKU && $this->sku) {
                return $this->sku->fPrice;
            }

            return $this->fPrice;
        } else {
            return $this->product->fPrice;
        }
    }

    /**
     * 获取市场价
     * @return string
     */
    public function getFMarketPrice()
    {
        return $this->product->fPrice;
    }


    /**
     * 获取显示的售价
     * @return string
     */
    public function getFShowSalePrice()
    {
        if (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dStartDate) < 0) {
            //未开始
            return $this->fPrice;
        } elseif (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dStartDate) >= 0
            && \Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dEndDate) <= 0
        ) {
            return $this->fPrice;
        } elseif (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($this->dEndDate) < 300) {
            return $this->fPrice;
        } else {
            return $this->product->fPrice;//恢复原价
        }
    }

}