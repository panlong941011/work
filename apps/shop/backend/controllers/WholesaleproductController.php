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
use myerm\shop\common\models\Area;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\ProductBuyer;
use myerm\shop\common\models\ShipTemplate;
use myerm\shop\common\models\ShipTemplateDetail;
use myerm\shop\common\models\ShipTemplateNoDelivery;
use yii\base\UserException;

/**
 * 产品
 */
class WholesaleproductController extends BackendController
{
    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];

        $list = [];

        $list['ID'] = '1';
        $list['sName'] = '出售中';
        $list['sKey'] = 'Main.Shop.WholesaleProduct.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleProduct.List.All&sTabID=onsale&sExtra=onsale';
        $data['arrList'][] = $list;

        $list['ID'] = '2';
        $list['sName'] = '已售罄';
        $list['sKey'] = 'Main.Shop.WholesaleProduct.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleProduct.List.All&sTabID=saleout&sExtra=saleout';
        $data['arrList'][] = $list;

        $list['ID'] = '3';
        $list['sName'] = '待上架';
        $list['sKey'] = 'Main.Shop.WholesaleProduct.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleProduct.List.All&sTabID=offsale&sExtra=offsale';
        $data['arrList'][] = $list;

        $list['ID'] = '4';
        $list['sName'] = '所有';
        $list['sKey'] = 'Main.Shop.WholesaleProduct.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleProduct.List.All&sTabID=all&sExtra=all';
        $data['arrList'][] = $list;

        return $this->renderPartial('@app/common/views/hometabs', $data);
    }

    public function getListButton()
    {
        $data = [];
        $data['bWholesaler'] = $this->BuyerID;
        return $this->renderPartial('listbutton', $data);
    }

    public function getViewButton()
    {
        $data = [];
        return $this->renderPartial('viewbutton', $data);
    }

    public function listDataConfig($sysList, $arrConfig)
    {
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
        $productBuyer = new ProductBuyer();
        $productBuyer->BuyerID = $BuyerID;
        $productBuyer->ProductID = $productID;
        $productBuyer->dNewDate = date('Y-m-d H:i:s');
        $productBuyer->save();
        $sRespone = json_encode(['bSuccess' => true, 'sMsg' => '已添加到已选商品']);
        return "var ret = " . $sRespone;
    }


    /**
     * 对象的主页
     */
    public function actionHome()
    {
        if (\Yii::$app->backendsession->SysRoleID == 11 || \Yii::$app->backendsession->SysRoleID == 12) {
            echo '<div style="text-align: center;padding-top: 5rem;font-size: 24px;color: #333;">您暂无权限查看</div>';
        } else {
            return parent::actionHome();
        }
    }

    /**
     * 列表数据处理
     */
    public function formatListData($arrData)
    {
        foreach ($arrData as $key => $data) {
            $arrData[$key]['ShipTemplateID']['sName'] = $this->getDelivery($data['ShipTemplateID']['ID']);
        }
        return $arrData;
    }

    /**
     * 不发货地区
     */
    public function getNoDelivery($ID)
    {
        if (!$ID) {
            return false;
        }

        $data['arrNoDelivery'] = [];
        $arrNoDelivery = ShipTemplateNoDelivery::findAll(['ShipTemplateID' => $ID]);
        $arrID = [];
        foreach ($arrNoDelivery as $noDelivery) {
            $arrNoDeliveryID = explode(",", $noDelivery->sAreaID);
            foreach ($arrNoDeliveryID as $ID) {
                $arrID[substr($ID, 0, 2) . '0000'][] = '';
            }
        }
        $arrNoDeliveryID = [];
        foreach ($arrID as $key => $ID) {
            $arrNoDeliveryID[] = $key;
        }
        $arrNoDeliveryProvince = Area::findAll(['ID' => $arrNoDeliveryID]);
        $strListProvince = '';
        foreach ($arrNoDeliveryProvince as $key => $noDelivery) {
            $strListProvince .= $noDelivery->sName . '、';
        }
        return $strListProvince;
    }

    /**
     * 运费模板明细
     */
    public function getDelivery($ShipTemplateID)
    {
        $data['dataDetail'] = ShipTemplateDetail::find()
            ->where(['ShipTemplateID' => $ShipTemplateID])
            ->asArray()
            ->all();
        foreach ($data['dataDetail'] as $key => $value) {
            $data['dataDetail'][$key]['sShipMethodName'] = ShipTemplate::getShipMethodName($value['sShipMethod']);
            if ($value['sType'] == 'default') {
                $data['dataDetail'][$key]['sAreaName'] = '全国';
            } elseif ($value['sType'] == 'designatedArea') {
                //运送到
                $arrID=[];
                $arrDesignatedArea = explode(",", $value['sAreaID']);
                foreach ($arrDesignatedArea as $ID) {
                    $arrID[substr($ID, 0, 2) . '0000'][] = '';
                }
                $arrDeliveryID = [];
                foreach ($arrID as $k => $ID) {
                    $arrDeliveryID[] = $k;
                }
                $arrNoDeliveryProvince = Area::findAll(['ID' => $arrDeliveryID]);
                $strListProvince = '';
                foreach ($arrNoDeliveryProvince as  $delivery) {
                    $strListProvince .= $delivery->sName . '、';
                }
                $data['dataDetail'][$key]['sAreaName'] = $strListProvince;
                $data['dataDetail'][$key]['sk'] = $value['sAreaID'];
            }
        }

        $str = "<style> table th{padding-left: 5px;} </style><table>
                <tr>
                    <th>运送方式</th>
                    <th >运送到</th>
                    <th >首件</th>
                    <th >首费(元)</th>
                    <th >续件</th>
                    <th >续费(元)</th>
                </tr>";
        foreach ($data['dataDetail'] as $detail) {
            $sShipMethodName = $detail['sShipMethodName'];
            $sAreaName = $detail['sAreaName'];
            $lStart = intval($detail['lStart']);
            $fPostage = $detail['fPostage'];
            $lPlus = intval($detail['lPlus']);
            $fPostageplus = $detail['fPostageplus'];
            $sk = $detail['sk'];
            $str .= "<tr>
                        <td >$sShipMethodName</td>
                        <td sk='$sk'>$sAreaName</td>
                        <td>$lStart</td>
                        <td>$fPostage</td>
                        <td>$lPlus</td>
                        <td>$fPostageplus</td>
                    </tr>";
        }
        $strNoDelivery=$this->getNoDelivery($ShipTemplateID);
        if($strNoDelivery) {
            $str .= "<tr><td colspan='6'style='text-align: left;word-wrap:break-word '>不发货地区：" . $strNoDelivery . "</td></tr>";
        }
        $str .= "</table>";
        return $str;
    }
}


