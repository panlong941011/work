<?php

namespace myerm\shop\mobile\controllers;

use myerm\shop\common\models\MallConfig;
use myerm\shop\mobile\models\Product;
use yii\web\HttpException;


/**
 * 秒杀
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年11月23日 09:26:18
 * @version v1.0
 */
class SeckillController extends ProductController
{
    public function actionDetail($id)
    {
        $secKill = \Yii::$app->seckillproduct->findByID($id);

        if (!$secKill) {
            throw new HttpException(404);
        }

        $product = $secKill->product;

        /* @var Product $product */
        $data['product'] = $product;

        $data['secKill'] = $secKill;

        //商品规格
        if ($secKill->sStatus == '未开始') {
            $data['arrSpec'] = \Yii::$app->productspec->getArrSpec($product->lID, $product->sMasterPic);
        } else {
            $data['arrSpec'] = $secKill->getArrSpec($product->sMasterPic);
        }

        //如果是云端商品，且带有规格，规格的库存要取云端的规格库存，Mars，2018年6月27日 14:16:11
        if ($product->bCloudProduct && $data['arrSpec']['sJsonGroup']) {
            $arrJson = json_decode($data['arrSpec']['sJsonGroup'], true);
            foreach ($arrJson as $ID => $json) {
                $arrJson[$ID]['count'] = min($arrJson[$ID]['count'], $secKill->arrCloudSku[$json['title']]);
            }
            $data['arrSpec']['sJsonGroup'] = json_encode($arrJson);
        }

        //去掉库存为0的规格，Mars，2018年6月27日 14:16:06
        $arrJson = json_decode($data['arrSpec']['sJsonGroup'], true);
        foreach ($arrJson as $ID => $json) {
            if (!$json['count']) {
                unset($arrJson[$ID]);
            }
        }
        $data['arrSpec']['sJsonGroup'] = $arrJson ? json_encode($arrJson) : json_encode([]);

        $data['lCartProductQty'] = \Yii::$app->cart->lProductQty;

        $this->getView()->title = $product->sName;

        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");

        //商品参数
        $data['arrParamValue'] = array_values(\Yii::$app->productparamvalue->getArrParamValue($product->lID));

        return $this->render('detail', $data);
    }


    /**
     * 是否可以购买商品
     * @author 陈鹭明
     * @time 2017年11月29日 10:43:22
     */
    public function actionCanbuy($id)
    {
        $secKill = \Yii::$app->seckillproduct->findByID($id);

        $product = $secKill->product;

        $data = [];

        if (!$product) {
            $data['status'] = false;
            $data['message'] = Product::STATUS_NOTEXISTS;
        } elseif ($product->bDel) {
            $data['status'] = false;
            $data['message'] = Product::STATUS_DEL;
        } elseif ($product->bOffSale) {
            $data['status'] = false;
            $data['message'] = Product::STATUS_OFFSALE;
        } elseif ($secKill->bSaleOut) {
            $data['status'] = false;
            $data['message'] = "已抢光";
        } else {
            $data['status'] = true;
        }

        if ($data['status']) {
            if ($_GET['province'] && $_GET['city']) {
                $city = \Yii::$app->area->getCityByName($_GET['province'], $_GET['city']);
                if (!$product->nodelivery || $city && !strstr($product->nodelivery->sAreaID, $city->ID)) {
                    $data['status'] = true;
                } elseif ($product->nodelivery && $city && strstr($product->nodelivery->sAreaID, $city->ID)) {
                    $data['status'] = false;
                    $data['message'] = Product::STATUS_AREA_NODELIVERY;
                }
            }
        }

        return $this->asJson($data);
    }
}