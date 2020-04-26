<?php

namespace myerm\shop\common\models;

use myerm\shop\backend\models\ProductModifyLog;
use yii\base\UserException;


/**
 * 商品
 */

/**
 * Class Product
 * @property integer $lID 商品ID
 * @property string $sName 商品名称
 * @property integer $OwnerID
 * @property integer $NewUserID 新建人
 * @property integer $EditUserID 编辑人
 * @property string $dNewDate 新建时间
 * @property string $dEditDate 编辑时间
 * @property string $sRecomm 商品推荐词
 * @property string $sCode 商家编码
 * @property integer $ProductBrandID 品牌
 * @property integer $SupplierID 供应商
 * @property string $ProductTagID 标签
 * @property integer $ProductCatID 商品分类
 * @property string $PathID PathID
 * @property string $fPrice 售价
 * @property string $fShowPrice 市场价
 * @property string $fCostPrice 进货价
 * @property integer $lStock 总库存
 * @property integer $lSaleBase 已售基数
 * @property integer $lSaleShow 显示销量
 * @property integer $bSale 是否上架
 * @property integer $ShipTemplateID 运费设置
 * @property string $sContent 商品描述
 * @property integer $lSale 实际销量
 * @property string $sSpec 商品规格
 * @property string $sPic 商品图片
 * @property integer $bDel 是否删除
 * @property string $sMasterPic 商品主图
 */
class Product extends ShopModel
{
    /**
     * 已删除状态
     */
    const STATUS_DEL = '该商品已删除';

    /**
     * 下架状态
     */
    const STATUS_OFFSALE = '该商品已下架';

    /**
     * 售罄状态
     */
    const STATUS_SALEOUT = '该商品已售罄';

    /**
     * 在售状态
     */
    const STATUS_ONSALE = '该商品在售';

    /**
     * 不存在
     */
    const STATUS_NOTEXISTS = '该商品不存在';

    /**
     * 该地区不发货
     */
    const STATUS_AREA_NODELIVERY = '该地区不发货';

    const STATUS_LOW_STOCK = '该商品库存不足';

    const STATUS_SPEC_LOW_STOCK = '所选规格库存不足';

    const STATUS_SPEC_NOEXISTS = '所选规格不存在';

    const STATUS_SPEC_NOSELECTED = '未选择规格，请重新添加商品';

    /**
     * 该商品选中了某个SKU，会设置该值
     * @var string
     */
    public $sSKU = "";

    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    /**
     * 上架商品
     * @param $ProductID
     * @throws yii\base\UserException
     */
    public static function onSale($ProductID)
    {
        $product = static::findOne($ProductID);

        if ($product->bSale) {
            throw new UserException("所选商品中有已上架商品，不可重复上架。");
        }

        $product->bSale = 1;
        $product->save();

        return true;
    }

    /**
     * 下架商品
     * @param $ProductID
     * @throws yii\base\UserException
     */
    public static function offSale($ProductID)
    {
        $product = static::findOne($ProductID);

        if (!$product->bSale) {
            throw new UserException("所有选商品中有已下架商品，不可重复下架。");
        }

        $product->bSale = 0;
        $product->save();

        return true;
    }

    /**
     * 删除，这里不是物理删除，而是逻辑上标记删除
     * @param $ProductID
     * @return bool
     * @throws UserException
     */
    public static function del($ProductID)
    {
        $product = static::findOne($ProductID);

        if ($product->bDel) {
            throw new UserException("所有选商品中有已删除商品，不可重复删除。");
        }

        $product->bDel = 1;
        $product->save();

        ProductModifyLog::saveLog($ProductID, "删除", "删除商品");

        return true;
    }

    /**
     * 通过ID数组查找N个数据
     * @param $arrID
     */
    public static function findByIDs($arrID)
    {
        return static::find()->where([
            'lID' => $arrID,
            'bDel' => 0,
            'bSale' => 1
        ])->all();
    }

    /**
     * 获取对应的运费模板
     * @return \yii\db\ActiveQuery
     */
    public function getShiptemplate()
    {
        return $this->hasOne(ShipTemplate::className(), ['lID' => 'ShipTemplateID']);
    }

    /**
     * 判断是否指定的CityID，是不发货的地区
     * @param $CityID
     */
    public function getNodelivery()
    {
        return $this->hasOne(ShipTemplateNoDelivery::className(), ['ShipTemplateID' => 'ShipTemplateID']);
    }

    /**
     * 是否已售罄
     */
    public function getBSaleOut()
    {
        return !$this->bDel && $this->bSale && !$this->lStock;
    }


    /**
     * 是否在售
     */
    public function getBOnSale()
    {
        return !$this->bDel && $this->bSale && $this->lStock;
    }

    /**
     * 是否下架
     */
    public function getBOffSale()
    {
        return !$this->bDel && !$this->bSale;
    }

    public function getSecKillProductID()
    {
        return null;
    }

    /**
     * 返回所有的SKU
     * @return \yii\db\ActiveQuery
     */
    public function getArrSku()
    {
        return null;
    }


    /**
     * 通过SKU的值，取该SKU的配置
     * @param $sVal
     * @return mixed
     */
    public function getSku()
    {
        return null;
    }


    /**
     * 获取库存
     * @return int
     */
    public function getStock()
    {
        return $this->lStock;
    }


    /**
     * 获取商品的图片，如果有设置SKU，那么它的图片要去规格取，如果没有则返回商品的主图
     */
    public function getPic()
    {
        if ($this->sku) {
            $firstSpec = explode(";", $this->sku->sValue)[0];
            $sSpecName = explode(":", $firstSpec)[0];
            $sSpecVal = explode(":", $firstSpec)[1];

            $arrSpecPic = explode(";", $this->arrSpec[$sSpecName]->sPic);
            foreach (explode(";", $this->arrSpec[$sSpecName]->sValue) as $i => $sVal) {
                if ($sVal == $sSpecVal) {
                    if ($arrSpecPic[$i]) {
                        return $arrSpecPic[$i];
                    } else {
                        return $this->sMasterPic;
                    }
                }
            }

            return $this->sMasterPic;
        } else {
            return $this->sMasterPic;
        }
    }

    /**
     * 关联商品规格
     * @return \yii\db\ActiveQuery
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-9 16:26:05
     */
    public function getArrSpec()
    {
        return $this->hasMany(\Yii::$app->productspec->className(),
            ['ProductID' => 'lID'])->orderBy("lPos")->indexBy('sName');
    }

    /**
     * 关联秒杀活动
     */
    public function getSecKill()
    {
        return null;
    }


    /**
     * 显示的销售价格。注意它不是实际的交易价格。
     * 比如未开始的秒杀活动，这件商品也显示未开始活动的实际交易价格。
     */
    public function getFShowSalePrice()
    {
        return $this->fPrice;
    }


    /**
     * 根据不同身份获取售价
     * @return string|void
     *  panlong
     *  2019年9月9日16:30:07
     */
    public function getFSalePriceold()
    {
        $fPrice = $this->fPrice;

        //默认取设置售价，若用户为代理则特殊处理
        $MemberID = \Yii::$app->frontsession->MemberID;
        $seller = Seller::findOne(['MemberID' => $MemberID]);
        if ($seller) {
            if ($seller->TypeID == Seller::TOP) {
                //渠道商（顶级代理）售价为进货价
                $fPrice = $this->fSupplierPrice;
            } elseif ($seller->TypeID == Seller::JUNIOR) {
                //查询该代理该产品是否特殊设定价格
                $res = MemberProduct::find()->where(['MemberID' => $MemberID, 'ProductID' => $this->lID])->one();
                if ($res) {
                    $fPrice = $res->fPrice;
                } else {
                    //VIP（初级代理）价格=（售价-进货价）*0.2 + 进货价
                    $fPrice = ($this->fPrice - $this->fSupplierPrice) * 0.2 + $this->fSupplierPrice;
                }
            }
        }

        return $fPrice;
    }

    public function getFSalePrice()
    {
        $fPrice = $this->fPrice;

        //默认取设置售价，若用户为代理则特殊处理
        $MemberID = \Yii::$app->frontsession->MemberID;
        $seller = Seller::findOne(['MemberID' => $MemberID]);
        if ($seller) {
            //VIP（初级代理）价格=（售价-进货价）*0.6 + 进货价
            $fPrice = ($this->fPrice - $this->fSupplierPrice-$this->fSelfProfit) * 0.6 + $this->fSupplierPrice+$this->fSelfProfit;
        }

        return $fPrice;
    }

    /**
     * 获取市场价
     * @return string
     */
    public function getFMarketPrice()
    {
        //普通商品决定市场价
        return $this->fShowPrice;
    }

    /**
     * 获取进货价
     */
    public function getCostPrice()
    {
        if ($this->sku) {
            if ($this->sku->fCostPrice) {
                return $this->sku->fCostPrice;
            } else {
                return $this->fCostPrice;
            }
        } else {
            return $this->fCostPrice;
        }
    }

    /**
     * 计算利润
     */
    public function getFProfit()
    {
        if ($this->fSalePrice > $this->costPrice) {
            return $this->fSalePrice - $this->costPrice;
        } else {
            return 0;
        }
    }

    /**
     * 获取第一个标签
     */
    public function getFirstTag()
    {
        return $this->hasOne(\Yii::$app->producttag->className(), ['lID' => 'FirstTagID']);
    }

    /**
     * 获取icon小图标
     */
    public function getIcon()
    {
        return \Yii::$app->request->imgUrl . "/" . $this->sMasterPic;
//		if ($this->firstTag && $this->firstTag->sLogoPath) {
//			return \Yii::$app->request->imgUrl . "/" . $this->sMasterPic;
//		} else {
//			return '';
//		}
    }

    /**
     * 根据不同身份获取售价
     * @return string|void
     *  panlong
     *  2019年9月9日16:30:07
     */
    public function fSalePrice($MemberID)
    {
        $fPrice = $this->fPrice;
        //默认取设置售价，若用户为代理则特殊处理
        $seller = Seller::findOne(['MemberID' => $MemberID]);
        if ($seller) {
            if ($seller->TypeID == Seller::TOP) {
                //渠道商（顶级代理）售价为进货价
                $fPrice = $this->fSupplierPrice;
            } elseif ($seller->TypeID == Seller::JUNIOR) {
                //查询该代理该产品是否特殊设定价格
                $res = MemberProduct::find()->where(['MemberID' => $MemberID, 'ProductID' => $this->lID])->one();
                if ($res) {
                    $fPrice = $res->fPrice;
                } else {
                    //VIP（初级代理）价格=（售价-进货价）*0.2 + 进货价
                    $fPrice = ($this->fPrice - $this->fSupplierPrice) * 0.2 + $this->fSupplierPrice;
                }
            }
        }

        return $fPrice;
    }


    public function __get($name)
    {
        return parent::__get($name);
    }
}