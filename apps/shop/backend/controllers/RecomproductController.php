<?php

namespace myerm\shop\backend\controllers;
use myerm\shop\common\models\ProductRecommend;

/**
 * 产品
 */
class RecomproductController extends BackendController
{

    public function listDataConfig($sysList, $arrConfig)
    {
        $arrSelectedID=ProductRecommend::find()->select('ProductID')->where(['or',['BuyerID'=>$this->BuyerID],['BuyerID'=>0]])->asArray()->all();
        $arrSelectedID=array_column($arrSelectedID,'ProductID');
        $arrConfig['arrFlt'][] = [
            'sField' => $this->sysObject->sIDField,
            'sOper' => 'in',
            'sValue' => "'" . implode("','", $arrSelectedID) . "'"
        ];
        return parent::listDataConfig($sysList, $arrConfig);
    }
}


