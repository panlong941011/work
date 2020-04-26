<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;
use myerm\backend\system\models\SysUI;
use myerm\common\components\Image;
use myerm\common\components\simple_html_dom;
use myerm\shop\backend\models\ProductModifyLog;
use myerm\shop\backend\models\ProductSKU;
use myerm\shop\backend\models\ProductSpecification;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\ProductBuyer;
use yii\base\UserException;

class MonthproductController extends BackendController
{
    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];

        $list = [];

        $list['ID'] = '1';
        $list['sName'] = '出售中';
        $list['sKey'] = 'Main.Shop.MonthProduct.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.MonthProduct.List.All&sTabID=onsale&sExtra=onsale';
        $data['arrList'][] = $list;


        $list['ID'] = '2';
        $list['sName'] = '待上架';
        $list['sKey'] = 'Main.Shop.MonthProduct.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.MonthProduct.List.All&sTabID=offsale&sExtra=offsale';
        $data['arrList'][] = $list;


        $list['ID'] = '3';
        $list['sName'] = '所有';
        $list['sKey'] = 'Main.Shop.MonthProduct.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.MonthProduct.List.All&sTabID=all&sExtra=all';
        $data['arrList'][] = $list;

        return $this->renderPartial('@app/common/views/hometabs', $data);
    }
	public function getListButton()
	{
		$data = [];
		$data['bWholesaler'] = $this->supplier->BuyerID;
		return $this->renderPartial('listbutton', $data);
	}
	
	public function getViewButton()
	{
		$data = [];
		return $this->renderPartial('viewbutton', $data);
	}

    public function listDataConfig($sysList, $arrConfig)
    {

        $m = intval(date('m'));
        $arrConfig['arrFlt'][] = ['sField' => 'bWholesale', 'sOper' => 'equal', 'sValue' => '1'];
        $arrConfig['arrFlt'][] = [
            'sField' => 'sMature',
            'sOper' => 'equal',
            'sValue' => '全年',
            'sSQL' => "(sMature like '%;" .$m . ";%' or sMature='全年')"
        ];

        if ($_POST['sExtra'] == 'all') {

        } elseif ($_POST['sExtra'] == 'offsale') {
            $arrConfig['arrFlt'][] = ['sField' => 'bSale', 'sOper' => 'equal', 'sValue' => '0'];
        } elseif ($_POST['sExtra'] == 'saleout') {
            $arrConfig['arrFlt'][] = ['sField' => 'lStock', 'sOper' => 'equal', 'sValue' => '0'];
            $arrConfig['arrFlt'][] = ['sField' => 'bSale', 'sOper' => 'equal', 'sValue' => '1'];
        } elseif ($_POST['sExtra'] == 'onsale') {
            $arrConfig['arrFlt'][] = ['sField' => 'bSale', 'sOper' => 'equal', 'sValue' => '1'];
            $arrConfig['arrFlt'][] = ['sField' => 'lStock', 'sOper' => 'larger', 'sValue' => '0'];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }
    /**
     * 渠道商选择商品
     */
    public function actionProductbuyer()
    {
        $productID = $_REQUEST['sSelectedID'];
        $BuyerID = $this->BuyerID;
        $productBuyer=new ProductBuyer();
        $productBuyer->BuyerID=$BuyerID;
        $productBuyer->ProductID=$productID;
        $productBuyer->dNewDate=date('Y-m-d H:i:s');
        $productBuyer->save();
        $sRespone = json_encode(['bSuccess' => true, 'sMsg' => '已添加到已选商品']);
        return "var ret = " . $sRespone;
    }
}


