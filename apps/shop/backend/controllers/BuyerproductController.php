<?php

namespace myerm\shop\backend\controllers;

use myerm\shop\common\models\ProductBuyer;

/**
 *
 * Class BuyerproductController
 * @package myerm\shop\backend\controllers
 * @author zy
 * @time
 */
class BuyerproductController extends BackendController
{

    public function listDataConfig($sysList, $arrConfig)
    {
        $arrSelectedID=ProductBuyer::find()->select('ProductID')->where(['BuyerID'=>$this->BuyerID])->asArray()->all();
        $arrSelectedID=array_column($arrSelectedID,'ProductID');
        $arrConfig['arrFlt'][] = [
            'sField' => $this->sysObject->sIDField,
            'sOper' => 'in',
            'sValue' => "'" . implode("','", $arrSelectedID) . "'"
        ];
        return parent::listDataConfig($sysList, $arrConfig);
    }
}