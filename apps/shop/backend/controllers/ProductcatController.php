<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;
use myerm\common\components\Image;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\ProductCat;

/**
 * 产品分类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月25日 15:04:07
 * @version v1.0
 */
class ProductcatController extends ObjectController
{
    public function listDataConfig($sysList, $arrConfig)
    {
        if ($_POST['sExtra'] > 1) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'GradeID',
                'sOper' => 'equal',
                'sValue' => intval($_POST['sExtra']) - 1
            ];
        }

        return $arrConfig;
    }

    public function beforeDel($arrData)
    {
        foreach ($arrData as $data) {
            if (ProductCat::findByID($data[$this->sysObject->sIDField])->lProductNum > 0) {
                throw new \yii\base\UserException('所选分类底下有商品，不可删除。');
            } elseif (ProductCat::hasSubCat($data[$this->sysObject->sIDField])) {
                throw new \yii\base\UserException('所选分类底下有子分类，不可删除。');
            }
        }
    }

    public function beforeObjectEditSave($sysObject, $ID, $arrObjectData)
    {
        if ($_POST['arrObjectData']['Shop/ProductCat']['sPicBase64']) {
            $arrFileInfo = File::parseImageFromBase64($_POST['arrObjectData']['Shop/ProductCat']['sPicBase64']);
            $sFileName = NewID::make() . ".jpg";
            Image::resize($arrFileInfo[1], 320, File::getUploadDir() . "/" . $sFileName);
            $arrObjectData['sPicPath'] = str_ireplace(\Yii::$app->params['sUploadDir'] . "/", "",
                File::getUploadDir() . "/" . $sFileName);
        }

        if ($arrObjectData['GradeID'] == 1) {
            $arrObjectData['UpID'] = null;
        } elseif ($arrObjectData['GradeID'] == 2) {
            $arrObjectData['TopCatID'] = ProductCat::findByID($arrObjectData['UpID'])->lID;
        } elseif ($arrObjectData['GradeID'] == 3) {
            $up = ProductCat::findByID($arrObjectData['UpID']);
            $arrObjectData['SecondCatID'] = $up->lID;
            $arrObjectData['TopCatID'] = $up->UpID;
        }

        return parent::beforeObjectNewSave($sysObject, $arrObjectData); // TODO: Change the autogenerated stub
    }

    public function beforeObjectNewSave($sysObject, $arrObjectData)
    {
        if ($_POST['arrObjectData']['Shop/ProductCat']['sPicBase64']) {
            $arrFileInfo = File::parseImageFromBase64($_POST['arrObjectData']['Shop/ProductCat']['sPicBase64']);
            $sFileName = NewID::make() . ".jpg";
            Image::resize($arrFileInfo[1], 320, File::getUploadDir() . "/" . $sFileName);
            $arrObjectData['sPicPath'] = str_ireplace(\Yii::$app->params['sUploadDir'] . "/", "",
                File::getUploadDir() . "/" . $sFileName);
        }

        if ($arrObjectData['GradeID'] == 1) {
            $arrObjectData['UpID'] = null;
        } elseif ($arrObjectData['GradeID'] == 2) {
            $arrObjectData['TopCatID'] = ProductCat::findByID($arrObjectData['UpID'])->lID;
        } elseif ($arrObjectData['GradeID'] == 3) {
            $up = ProductCat::findByID($arrObjectData['UpID']);
            $arrObjectData['TopCatID'] = $up->lID;
            $arrObjectData['SecondCatID'] = $up->UpID;
        }

        return parent::beforeObjectNewSave($sysObject, $arrObjectData); // TODO: Change the autogenerated stub
    }

    public function getNewFooterAppend()
    {
        $data = [];
        return $this->renderPartial('newfooter', $data);
    }

    public function actionShowref()
    {
        if ($_GET['sFieldAs'] == 'ProductCatID') {
            $data = [];
            return $this->renderPartial('catselect',
                ['sTreeHTML' => $this->getTreeHtml(null)]);
        } else {
            return parent::actionShowref();
        }
    }

    public function getTreeHtml($ParentID)
    {
        $sHTML = "";
        $arrSub = ProductCat::getSubs($ParentID);
        if ($arrSub) {
            $sHTML = "<ul>";
            foreach ($arrSub as $subCat) {
                $sHTML .= "<li data-jstree='{ \"opened\" : false }'><a href=\"javascript:;\" onclick=\"refSave('Shop/Product', 'ProductCatID', {ID:'" . $subCat->lID . "', sName:'" . $subCat->sName . "'});closeModal()\">" . $subCat->sName . "</a>";
                $sHTML .= $this->getTreeHtml($subCat->lID);
                $sHTML .= "</li>";
            }
            $sHTML .= "</ul>";
        }

        return $sHTML;
    }

    public function formatListData($arrData)
    {
        foreach ($arrData as $key => $data) {
            $arrData[$key]['lProductNum'] = number_format($data['lProductNum']);
        }

        return $arrData;
    }

    public function afterEditSave()
    {
        $db = $this->sysObject->dbconn;
        $db->createCommand("call up_productcat(0)")->execute();
        return parent::afterEditSave(); // TODO: Change the autogenerated stub
    }

    public function afterNewSave()
    {
        $db = $this->sysObject->dbconn;
        $db->createCommand("call up_productcat(0)")->execute();
        return parent::afterNewSave(); // TODO: Change the autogenerated stub
    }
}