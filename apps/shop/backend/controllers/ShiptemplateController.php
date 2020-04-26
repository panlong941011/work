<?php

namespace myerm\shop\backend\controllers;


use myerm\backend\common\controllers\ObjectController;
use myerm\backend\system\models\SysUI;
use myerm\shop\common\models\Area;
use myerm\shop\common\models\ShipTemplate;
use myerm\shop\common\models\ShipTemplateDetail;
use myerm\shop\common\models\ShipTemplateFree;
use myerm\shop\common\models\ShipTemplateNoDelivery;


/**
 * 运费模板
 */
class ShiptemplateController extends ObjectController
{

    public function beforeDel($arrData)
    {
        foreach ($arrData as $data) {
            if (ShipTemplate::findByID($data[$this->sysObject->sIDField])->lProductUse > 0) {
                throw new \yii\base\UserException('已有商品使用了该模板，不可删除。');
            }
        }
    }

    public function listDataConfig($sysList, $arrConfig)
    {
        if (\Yii::$app->backendsession->SysRoleID != 1) {
            $ownerID = \Yii::$app->backendsession->SysUserID;
            $arrConfig['arrFlt'][] = [
                'sField' => 'OwnerID',
                'sOper' => 'equal',
                'sValue' => $ownerID,
                'sSQL' => "(OwnerID =$ownerID)"
            ];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }

    public function actionNew()
    {
        $data = [];

        if (\Yii::$app->request->isPost) {
            $result = ShipTemplate::newSave();
            return $this->redirect(\Yii::$app->homeUrl . "/shop/shiptemplate/view?ID=" . $result['ID']);
        }

        $data['arrNoDelivery'] = [];

        return $this->render('new', $data);
    }

    public function actionEdit()
    {
        //获取界面
        $ui = SysUI::getUI($this->sObjectName, "edit");
        if (!$ui) {//找不到界面，要报错
            //throw new UserException("找不到编辑的界面，请联系管理员。");
        }

        $bCheck = $this->beforeEdit($_GET['ID']);
        if ($bCheck !== true) {
            throw new UserException($bCheck);
        }

        $data['ShipTemplate'] = ShipTemplate::find()
            ->where(['lID' => $_GET['ID']])
            ->asArray()
            ->one();

        if (\Yii::$app->request->isPost) {
            $result = ShipTemplate::editSave();
            return $this->redirect(\Yii::$app->homeUrl . "/shop/shiptemplate/view?ID=" . $result['ID']);
        }

        //获取计价方式
        switch ($data['ShipTemplate']['sValuation']) {
            case "Number":
                $data['ShipTemplate']['valuationValue'] = '0';
                $data['ShipTemplate']['valuationName'] = '按件数';
                break;
            case "Weight":
                $data['ShipTemplate']['valuationValue'] = '1';
                $data['ShipTemplate']['valuationName'] = '按重量';
                break;
            case "Volume":
                $data['ShipTemplate']['valuationValue'] = '2';
                $data['ShipTemplate']['valuationName'] = '按体积';
                break;
        }


        $data['arrNoDelivery'] = [];
        $arrNoDelivery = ShipTemplateNoDelivery::findAll(['ShipTemplateID' => $_GET['ID']]);
        foreach ($arrNoDelivery as $i => $noDelivery) {
            $arrArea = Area::find()->where(['ID' => explode(",", $noDelivery->sAreaID)])->all();
            foreach ($arrArea as $area) {
                $data['arrNoDelivery'][$i][] = ['area' => $area->sName, 'id' => $area->ID];
            }

        }

        return $this->render('edit', $data);
    }

    public function getViewButton()
    {
        $data = [];
        return $this->renderPartial('viewbutton', $data);
    }

    public function actionDetail()
    {
        $data = [];

        $data['arrDetail'] = ShipTemplateDetail::findAll(['ShipTemplateID' => $_GET['id'], 'sType' => 'default']);
        $ShipTemplate = ShipTemplate::findByID($data['arrDetail'][0]['ShipTemplateID']);
        switch ($ShipTemplate->sValuation) {
            case 'Number':
                $data['sValuation'] = '件';
                break;
            case 'Weight':
                $data['sValuation'] = '千克';
                break;
        }

        return $this->renderPartial('detail', $data);
    }

    public function actionView()
    {
        $data = [];



        $data['data'] = ShipTemplate::find()
            ->where(['lID' => $_GET['ID']])
            ->asArray()
            ->one();

        if (!$data['data']) {
            throw new UserException("你查看的对象数据不存在。");
        }

        //组装宝贝地址
        $DeliveryAddress = '';
        $DeliveryAddress .= Area::findOne($data['data']['CountryID'])['sName'] . " ";
        $DeliveryAddress .= Area::findOne($data['data']['ProvinceID'])['sName'] . " ";
        $DeliveryAddress .= Area::findOne($data['data']['CityID'])['sName'] . " ";
        $DeliveryAddress .= Area::findOne($data['data']['AreaID'])['sName'] . " ";
        $data['data']['DeliveryAddress'] = $DeliveryAddress;

        //组装运送地址
        $dataShipMethod = explode(",", $data['data']['sShipMethod']);
        $sShipMethodName = [];
        foreach ($dataShipMethod as $value) {
            $sShipMethodName[] = ShipTemplate::getShipMethodName($value) . " ";
        }
        $data['data']['sShipMethodName'] = implode(",", $sShipMethodName);

        switch ($data['data']['sValuation']) {
            case 'Number':
                $data['table']['Start'] = '首件(个)';
                $data['table']['Plus'] = '续件(个)';
                $data['data']['sValuationName'] = "按件数";
                break;
            case 'Weight':
                $data['table']['Start'] = '首重(kg)';
                $data['table']['Plus'] = '续重(kg)';
                $data['data']['sValuationName'] = "按重量";
                break;
            case 'Volume':
                $data['table']['Start'] = '首体积(m³)';
                $data['table']['Plus'] = '续体积(m³)';
                $data['data']['sValuationName'] = "按体积";
                break;
            default:
                $data['table']['Start'] = '首件(个)';
                $data['table']['Plus'] = '续件(个)';
                $data['data']['sValuationName'] = "按件数";
                break;
        }
        $data['table']['Postage'] = '首费(元)';
        $data['table']['Postageplus'] = '续费(元)';

        //运费模板明细
        $data['dataDetail'] = ShipTemplateDetail::find()
            ->where(['ShipTemplateID' => $_GET['ID']])
            ->asArray()
            ->all();
        foreach ($data['dataDetail'] as $key => $value) {
            $data['dataDetail'][$key]['sShipMethodName'] = ShipTemplate::getShipMethodName($value['sShipMethod']);

            if ($value['sType'] == 'default') {
                $data['dataDetail'][$key]['sAreaName'] = '全国';
            } elseif ($value['sType'] == 'designatedArea') {
                //运送到
                $arrDesignatedArea = explode(",", $value['sAreaID']);
                $sAreaName = [];
                foreach ($arrDesignatedArea as $DAkey => $DAvalue) {
                    if (intval($DAvalue)) {
                        $city = Area::findOne($DAvalue);
                        if ($city) {
                            $sAreaName[$city['UpID']] .= $city['sName'] . ", ";
                        }
                    }
                }


                if ($sAreaName) {
                    $sAreaNameNew = '';
                    foreach ($sAreaName as $ANKey => $ANValue) {
                        $province = Area::findOne($ANKey);
                        $pNewKey = $province['sName'];
                        $sAreaName[$pNewKey] .= $ANValue;
                        unset($sAreaName[$ANKey]);
                        $sAreaNameNew .= $province['sName'] . "：" . $ANValue . "<br>";
                    }
                }

                $data['dataDetail'][$key]['sAreaName'] = $sAreaNameNew;
            }
        }

        //运费模板指定包邮明细
        $data['dataFreeDetail'] = ShipTemplateFree::find()
            ->where(['ShipTemplateID' => $_GET['ID']])
            ->asArray()
            ->all();
        foreach ($data['dataFreeDetail'] as $key => $value) {
            //运送方式
            switch ($value['sShipMethod']) {
                case 'EXPRESS':
                    $data['dataFreeDetail'][$key]['sShipMethodName'] = '快递';
                    break;
                case 'EMS':
                    $data['dataFreeDetail'][$key]['sShipMethodName'] = 'EMS';
                    break;
                case 'POSTAGE':
                    $data['dataFreeDetail'][$key]['sShipMethodName'] = '平邮';
                    break;
                default:
                    $data['dataFreeDetail'][$key]['sShipMethodName'] = '快递';
                    break;
            }

            $arrFreeArea = explode(",", $value['sFreeAreaID']);

            //运送到
            $sFreeAreaName = [];
            foreach ($arrFreeArea as $fAkey => $dAvalue) {
                if (intval($dAvalue)) {
                    $freeCity = Area::findOne($dAvalue);
                    if ($freeCity) {
                        $sFreeAreaName[$freeCity['UpID']] .= $freeCity['sName'] . ", ";
                    }
                }
            }

            if ($sFreeAreaName) {
                $sAreaNameNew = '';
                foreach ($sFreeAreaName as $FNKey => $FNValue) {
                    $province = Area::findOne($FNKey);
                    $sAreaNameNew .= $province['sName'] . "：" . $FNValue . "<br>";
                }
            }

            $data['dataFreeDetail'][$key]['sAreaName'] = $sAreaNameNew;
            if ($data['data']['sValuation'] == "Number") {
                $value['fFreeNumber'] = intval($value['fFreeNumber']);
            }

            switch ($value['lFreeType']) {
                case 0:
                    if ($data['data']['sValuation'] == 'Weight') {
                        $value['FreeType'] = "在" . $value['fFreeNumber'] . "千克内包邮";
                    } else {
                        $value['FreeType'] = "满" . $value['fFreeNumber'] . "件包邮";
                    }
                    break;
                case 1:
                    $value['FreeType'] = "满" . $value['fFreeMoney'] . "元包邮";
                    break;
                case 2:
                    $value['FreeType'] = "在" . $value['fFreeNumber'] . "件内," . $value['fFreeMoney'] . "元以上 包邮";
                    break;
            }
            $data['dataFreeDetail'][$key]['FreeType'] = $value['FreeType'];
        }
        //获取界面
        $ui = SysUI::getUI($this->sObjectName, "view");
        if (!$ui) {//找不到界面，要报错
            //throw new UserException("找不到新建的界面，请联系管理员。");
        }

        $data['arrNoDelivery'] = [];
        $arrNoDelivery = ShipTemplateNoDelivery::findAll(['ShipTemplateID' => $_GET['ID']]);
        foreach ($arrNoDelivery as $noDelivery) {
            $arrCity=Area::find()->where(['ID' => explode(",", $noDelivery->sAreaID)])->all();
            foreach ($arrCity as $city){
                $data['arrNoDelivery'][$city->UpID][] = $city;
            }
        }
        foreach ($data['arrNoDelivery'] as $key=>$noDelivery){
            $data['arrNoDelivery'][$key]['provice']=Area::find()->where(['ID' =>$key])->one()->sName;
        }

        /*$data['ui'] = $ui;
        $data['arrUIData'] = $this->viewObjectPrepare(SysUI::getUIData($ui, $this->sObjectName, $_GET['ID']));
        $data['arrSysDetailObject'] = SysObject::find()->where(['ParentID'=>$this->sysObject->sObjectName])->all();*/


        return $this->render('view', $data);
    }
}