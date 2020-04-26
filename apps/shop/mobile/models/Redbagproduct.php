<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 3:59
 */

namespace myerm\shop\mobile\models;

use myerm\shop\common\models\RecommendProduct;
use myerm\shop\common\models\RecommendProductDetail;
use myerm\shop\common\models\ShopModel;


/**
 * 地区类
 */
class Redbagproduct extends ShopModel
{
    public static function addRedbagproduct()
    {
        $res = 0;
        $recomend = RecommendProduct::findOne(9);
        $date = \Yii::$app->formatter->asDatetime(time());
        if($recomend->dEnd > $date) {
            $recomendDetail = RecommendProductDetail::findAll(['RecommendID' => 9]);
            foreach ($recomendDetail as $detail) {
                if ($detail->fRedBagMoney > 0) {
                    $red = new Redbagproduct();
                    $red->sName = $detail->sName;
                    $red->ProductID = $detail->ProductID;
                    $red->fChange = $detail->fRedBagMoney;
                    $red->dStart = $recomend->dStrart;
                    $red->dEnd = $recomend->dEnd;
                    $red->MemberID = \Yii::$app->frontsession->MemberID;
                    $red->lCount=2;
                    $red->save();
                    $res = 1;
                }
            }
        }
        return $res;
    }
}