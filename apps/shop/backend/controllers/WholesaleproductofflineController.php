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

/**
 * 产品
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @authProductparamtemplateController.phpor 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月26日 10:34:59
 * @version v1.0
 */
class WholesaleproductofflineController extends BackendController
{
    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];

        $list = [];

        $list['ID'] = '1';
        $list['sName'] = '出售中';
        $list['sKey'] = 'Main.Shop.WholesaleProductoffline.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleProductoffline.List.All&sTabID=onsale&sExtra=onsale';
        $data['arrList'][] = $list;

        $list['ID'] = '2';
        $list['sName'] = '已售罄';
        $list['sKey'] = 'Main.Shop.WholesaleProductoffline.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleProductoffline.List.All&sTabID=saleout&sExtra=saleout';
        $data['arrList'][] = $list;

        $list['ID'] = '3';
        $list['sName'] = '待上架';
        $list['sKey'] = 'Main.Shop.WholesaleProductoffline.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleProductoffline.List.All&sTabID=offsale&sExtra=offsale';
        $data['arrList'][] = $list;


        $list['ID'] = '4';
        $list['sName'] = '所有';
        $list['sKey'] = 'Main.Shop.WholesaleProductoffline.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleProductoffline.List.All&sTabID=all&sExtra=all';
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
        $arrConfig['arrFlt'][] = ['sField' => 'lBulk', 'sOper' => 'equal', 'sValue' => '1'];
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
    /**
     * 对象的主页
     */
    public function actionHome()
    {
        if (\Yii::$app->backendsession->SysRoleID == 11||\Yii::$app->backendsession->SysRoleID == 12) {
            echo '<div style="text-align: center;padding-top: 5rem;font-size: 24px;color: #333;">您暂无权限查看</div>';
        } else {
            return parent::actionHome();
        }
    }
}


