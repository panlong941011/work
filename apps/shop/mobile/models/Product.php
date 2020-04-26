<?php

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;
use myerm\common\components\simple_html_dom;
use myerm\shop\backend\models\ProductModifyLog;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\ProductRecommend;
use myerm\shop\common\models\ProductSample;
use myerm\shop\common\models\RecommendProduct;
use myerm\shop\common\models\Seller;

/**
 * 商品类
 */
class Product extends \myerm\shop\common\models\Product
{
    /**
     *  订单付款成功后的响应事件
     * @param CommonEvent $event
     */
    public static function orderPaySuccess(CommonEvent $event)
    {

    }

    /**
     * 自动关闭未付款的订单
     * @param CommonEvent $event
     */
    public static function orderAutoClose(CommonEvent $event)
    {
        $orderDetail = $event->extraData;
        //修改为自动关闭不吐回库存 by hcc on 2018/6/19
        static::getDb()->createCommand("UPDATE Product SET lSale=lSale-{$orderDetail->lQuantity} WHERE lID='{$orderDetail->ProductID}'")->execute();
    }

    /**
     *  订单成功后的响应事件
     *  这里要用SQL的方式来减库存，如果用PHP计算再去更新，在抢购的时候会出现延迟导致错误。
     * @param CommonEvent $event
     */
    public static function saveOrder(CommonEvent $event)
    {
        $orderDetail = $event->extraData;

        static::getDb()->createCommand("UPDATE Product SET lSale=lSale+{$orderDetail->lQuantity} WHERE lID='{$orderDetail->ProductID}'")->execute();
    }


    /**
     * 获取商品图片
     * @param array $params 字符串或者数组
     * @return array $result
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-7 15:20:58
     */
    public function getPics()
    {
        $arrPic = json_decode($this->sPic);
        foreach ($arrPic as $item) {
            $result[] = \Yii::$app->request->imgUrl . "/" . $item;
        }
        return $result;
    }

    /**
     * 商品主图
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-10 11:44:19
     */
    public function getMasterPic()
    {
        if (!$this->sMasterPic) {
            return '';
        }
        return \Yii::$app->request->imgUrl . "/" . $this->sMasterPic;
    }

    /**
     * 渠道商品供货价
     * @return string
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2018-8-8 16:20:51
     */
    public function getWholesaleCostPrice()
    {
        return number_format($this->fCostPrice + $this->fFreeShipCost, 2);
    }

    /**
     * 关联商品供应商
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-9 16:15:07
     */
    public function getSupplier()
    {
        return $this->hasOne(\Yii::$app->supplier::className(), ['lID' => 'SupplierID']);
    }

    /**
     * 获取供应商最新编辑的$lLimit件商品
     * @param $lLimit
     */
    public function getSupplierLastProduct($SupplierID, $lLimit = 4)
    {
        return static::find()->where([
            'SupplierID' => $SupplierID,
            'bDel' => '0',
            'bSale' => '1'
        ])->limit($lLimit)->orderBy("dEditDate DESC")->all();
    }

    /**
     * 通过$config获取数据，用于列表、搜索结果
     * @param $config
     * @author 陈鹭明
     */
    public function getByConfig($config)
    {
        //关键词
        $sKeyword = $config['sKeyword'];

        //分类
        $CatID = $config['CatID'];

        //供应商
        $SupplierID = $config['SupplierID'];

        //品牌
        $arrBrand = $config['arrBrand'];

        //标签
        $arrTag = $config['arrTag'];

        //排序
        $sSortBy = $config['sSortBy'];

        //排序方向
        $sAscDesc = $config['sAscDesc'];

        //页码
        $lPageNo = $config['lPageNo'];


        //组装SQL
        $andWhere = [];


        if ($sKeyword != "") {
            $andWhere[] = "sName LIKE '%" . addslashes($sKeyword) . "%'";
        }

        //分类要用PathID来查询，包含所有的下级
        if ($CatID) {
            $productCat = \Yii::$app->productcat->findByID($CatID);
            if ($productCat->PathID) {
                $andWhere[] = "PathID LIKE '{$productCat->PathID}%'";
            }
        }

        if ($SupplierID) {
            $andWhere[] = "SupplierID='$SupplierID'";
        }

        if ($arrBrand) {
            $andWhere[] = "ProductBrandID IN (" . implode(",", $arrBrand) . ")";
        }

        if ($arrTag) {
            $arrTagCond = [];
            foreach ($arrTag as $TagID) {
                $arrTagCond[] = "ProductTagID LIKE '%;$TagID;%'";
            }

            $andWhere[] = "(" . implode(" OR ", $arrTagCond) . ")";
        }

        $andWhere[] = "bSale='1'";
        $andWhere[] = "bDel='0'";

        if ($sSortBy == 'sale') {
            $sOrderBy = "lSale DESC";
        } elseif ($sSortBy == 'price') {
            $sOrderBy = "fPrice";
            if ($sAscDesc == "down") {
                $sOrderBy .= " DESC";
            }
        } else {
            $sOrderBy = "dEditDate DESC";
        }

        $lOffset = ($lPageNo - 1) * 10;

        $ret[] = static::find()->where(implode(" AND ",
            $andWhere))->offset($lOffset)->limit(10)->orderBy($sOrderBy)->all();

        if ($lPageNo == 1) {
            $ret[] = static::find()->where(implode(" AND ",
                $andWhere))->count();
        }
        return $ret;
    }

    /**
     * 获取热销商品
     */
    public function getArrHotSale()
    {
        $arrProduct = \Yii::$app->cache->getOrSet("hotsale", function () {
            $arrProductID = [];
            $arrHotSale = json_decode(MallConfig::getValueByKey("sHotSaleConfig"), true);
            foreach ($arrHotSale as $c) {
                $arrProductID[] = $c['ProductID'];
            }

            $arrProduct = [];
            $arrProductTemp = static::find()->where([
                'lID' => $arrProductID,
                'bDel' => 0,
                'bSale' => 1,

            ])->andWhere(['>', 'lStock', 0])->indexBy('lID')->all();
            foreach ($arrHotSale as $c) {
                if ($arrProductTemp[$c['ProductID']]) {
                    $arrProduct[] = $arrProductTemp[$c['ProductID']];
                }
            }

            return $arrProduct;
        });

        if (count($arrProduct) < 4) {

            $arrID = [-1];
            foreach ($arrProduct as $product) {
                $arrID[] = $product->lID;
            }

            $arrProductTemp = static::find()->where([
                'bDel' => 0,
                'bSale' => 1,
            ])->andWhere(['>', 'lStock', 0])->andWhere([
                'not in',
                'lID',
                $arrID
            ])->limit(4 - count($arrProduct))->orderBy("dEditDate DESC")->all();
            $arrProduct = array_merge($arrProduct, $arrProductTemp);
        }

        return $arrProduct;
    }


    /*
     * 查询渠道商品
     * add by zy on 2019年5月7日08:44:42
     */
    public function getWholesaleProduct($page = 1, $type = 0)
    {
        $data = [];
        $data['bDel'] = 0;
        if ($type == 1) {
            $data['bGroup'] = 1;
            //$data['SupplierID'] = 767;
        } elseif ($type == 2) {
            $data['lBulk'] = 1;
        }
        return self::find()
            ->where(['and', ['bSale' => 1], ['bCheck' => 1]])
            ->andWhere($data)
            ->orderBy('lClick desc,dEditDate desc')
            ->limit(10)
            ->offset(($page - 1) * 10)
            ->all();
    }


    public function getProductByCatID($CatID)
    {
        $arrCatID = \myerm\shop\common\models\ProductCat::getSubs($CatID);
        $arrCatID = array_column($arrCatID, 'lID');
        return self::find()
            ->where(['ProductCatID' => $arrCatID, 'bSale' => 1, 'bDel' => 0])
            ->all();
    }

    public function getProductByMonth($CatID)
    {

        return self::find()
            ->where(['and', ['or', ['like', 'sMature', ';' . $CatID . ';'], ['sMature' => '全年']], ['bWholesale' => 1], ['bDel' => 0]])
            ->all();
    }

    /*
     * 查询推荐商品
     * add by zy on 2019年5月7日08:44:42
     */
    public function getRecommendProduct($page = 1, $type = 0)
    {
        return self::find()
            ->alias('p')
            ->rightJoin('productrecommend', 'productrecommend.ProductID=p.lID')
            ->select('p.*')
            ->where(['p.bSale' => 1])
            ->orderBy('lClick desc,dEditDate desc')
            ->limit(10)
            ->offset(($page - 1) * 10)
            ->all();
    }

    /*
  * 查询免费商品
  * add by zy on 2019年5月7日08:44:42
  */
    public function getSampleProduct()
    {
        $date = \Yii::$app->formatter->asDatetime(time());
        return self::find()
            ->alias('p')
            ->rightJoin('productsample', 'productsample.ProductID=p.lID')
            ->select('p.*')
            ->where(['p.bSale' => 1])
            ->andWhere(['and', ['<', 'dStart', $date], ['>', 'dEnd', $date]])
            ->orderBy('lClick desc,dEditDate desc')
            ->all();
    }

    public function getFShowPrice($SupplierID)
    {
        return $this->fSupplierPrice;
    }

    public function getSample()
    {
        $date = \Yii::$app->formatter->asDatetime(time());
        $res = ProductSample::find()->select('lID')->where(['and', ['ProductID' => $this->lID], ['<', 'dStart', $date], ['>', 'dEnd', $date]])->one();
        if ($res) {
            $memberID = \Yii::$app->frontsession->MemberID;
            $ProductID = $this->lID;
            $sql = "SELECT count(1) cont from orderdetail d LEFT JOIN `order` o on d.OrderID=o.lID where o.StatusID in ('unpaid','paid', 'delivered', 'success') and o.MemberID=$memberID and ProductID=$ProductID ";

            $order = \Yii::$app->db->createCommand($sql)->queryOne();

            if (!$order || !$order['cont']) {
                return true;
            }

        }
        return false;

    }

    public function getFGroupPrice()
    {
        $fPrice = $this->fPrice;
        $recomProduct = RecommendProduct::findOne(9);
        $date = \Yii::$app->formatter->asDatetime(time());
        if ($recomProduct->dStrart > $date) {
            $fPrice = $this->fShowPrice;
        }
        return $fPrice;
    }
}