<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019/9/19
 * Time: 10:25
 */

namespace myerm\shop\mobile\controllers;


use myerm\shop\common\models\RecommendProductDetail;
use myerm\shop\mobile\models\Product;
use yii\helpers\Url;

class ProductportController extends \yii\web\Controller
{
    /*
     * 商品列表
     */
    public function actionItem($index, $type = 0)
    {
        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['isMore'] = false;
        $data['data'] = [];
        $arrRecommed = RecommendProductDetail::find()->where(['RecommendID' => 2])->with('product')->orderBy('lSort desc')->all();
        if ($arrRecommed) {
            $data['data']['commodity'] = [];
            foreach ($arrRecommed as $recommend) {
                $product = $recommend->product;
                $price = '促销价：¥' . number_format($product->fSalePrice, 2);//促销价
                $market_price = '市场价：¥' . number_format($product->fMarketPrice, 2);//市场价
                $fSupplierPrice = '供货价：¥' . number_format($product->fSupplierPrice, 2);//市场价
                $link = Url::toRoute(["/product/detail",
                    'id' => $product->lID
                ], true);

                $title = $product->sName;
                //规格
                if ($product->sStandard) {
                    $title = $title . ' ' . $product->sStandard;
                }
                //口味
                if ($product->sTaste) {
                    $title = $title . ' ' . $product->sTaste;
                }
                $data['data']['commodity'][] = [
                    'lID' => $product->lID,
                    'title' => $title,
                    'fSupplierPrice' => $fSupplierPrice, //供货价
                    'price' => $price,//促销价
                    'market_price' => $market_price,//促销价 . $product->fMarketPrice : '',//市场价
                    'image' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                    'saleout' => $product->bSaleOut ? true : false,
                    'link' => $link,
                    'sGroupKeyword' => $product->sGroupKeyword
                ];
            }
            $count = count($arrRecommed);
            if ($count && $count % 10 == 0) {
                $data['isMore'] = true;
            }

        } else {
            $data['isMore'] = false;
        }

        return json_encode($data);
    }

    /*
     * 商品详情
     */
    public function actionDetail($ProductID, $MemberID)
    {
        $product = Product::findOne($ProductID);
        $data = [];
        $data['sPic'] = json_decode($product['sPic']);//轮播图
        $data['imgDetail'] = json_decode($product['PathID']);//商品详情图
        $data['fSalePrice'] = number_format($product->fSalePrice($MemberID), 2);//当前销售价格，跟会员有关
        $data['fSupplierPrice'] = number_format($product->fSupplierPrice, 2); //供货价
        $data['price'] = number_format($product->fPrice, 2);//促销价
        $data['market_price'] = number_format($product->fMarketPrice, 2);//市场价
        $data['sName'] = $product->sName;//商品名称
        return json_encode($data);
    }
}