<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;
use myerm\common\components\Image;
use myerm\common\components\simple_html_dom;
use myerm\shop\backend\models\ProductModifyLog;
use myerm\shop\backend\models\ProductSKU;
use myerm\shop\backend\models\ProductSpecification;
use myerm\shop\common\models\Product;

class ProductController extends BackendController
{
    //編輯
//    public function getUI($sObjectName, $sAction)
//    {
//        $product = Product::find()
//            ->where(['lID' => $_REQUEST['ID']])
//            ->one()
//            ->toArray();
//        if ($product['bSale'] == 1) {
//            throw new UserException('上架的商品不可编辑');
//           // Product::Bsale();
//        } else {
//        $ui = SysUI::getUI($this->sObjectName, "edit");
//        }

//        return $ui;
//    }

    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];

        $list = [];

        $list['ID'] = '1';
        $list['sName'] = '已审核';
        $list['sKey'] = 'Main.Shop.Product.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Product.List.All&sTabID=checked&sExtra=checked';
        $data['arrList'][] = $list;

        $list['ID'] = '2';
        $list['sName'] = '待审核';
        $list['sKey'] = 'Main.Shop.Product.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Product.List.All&sTabID=check&sExtra=check';
        $data['arrList'][] = $list;

        $list['ID'] = '3';
        $list['sName'] = '已上架';
        $list['sKey'] = 'Main.Shop.Product.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Product.List.All&sTabID=onsale&sExtra=onsale';
        $data['arrList'][] = $list;



        $list['ID'] = '4';
        $list['sName'] = '待上架';
        $list['sKey'] = 'Main.Shop.Product.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Product.List.All&sTabID=offsale&sExtra=offsale';
        $data['arrList'][] = $list;


        $list['ID'] = '5';
        $list['sName'] = '已售罄';
        $list['sKey'] = 'Main.Shop.Product.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Product.List.All&sTabID=saleout&sExtra=saleout';
        $data['arrList'][] = $list;



        $list['ID'] = '6';
        $list['sName'] = '所有';
        $list['sKey'] = 'Main.Shop.Product.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Product.List.All&sTabID=all&sExtra=all';
        $data['arrList'][] = $list;

        return $this->renderPartial('@app/common/views/hometabs', $data);
    }

    public function beforeObjectEditSave($sysObject, $ID, $arrObjectData)
    {
        $arrNewPicPath = [];
        foreach ($_POST['newProductPic'] as $pic) {
            if (substr($pic, 0, 5) == 'data:') {
                $arrFileInfo = File::parseImageFromBase64($pic);
                $sFileName = NewID::make() . ".jpg";
                Image::resize($arrFileInfo[1], 640, File::getUploadDir() . "/" . $sFileName);
                $arrNewPicPath[] = str_ireplace(\Yii::$app->params['sUploadDir'] . "/", "",
                    File::getUploadDir() . "/" . $sFileName);
            } else {
                $arrNewPicPath[] = str_ireplace(\Yii::$app->params['sUploadUrl'] . "/", "", $pic);
            }
        }

        $arrObjectData['sMasterPic'] = $arrNewPicPath[0];
        $arrObjectData['sPic'] = json_encode($arrNewPicPath);
        $arrObjectData['sParameterArray'] = json_encode($_POST['arrObjectData']['Shop/Product']['ParameterArray'], true);//商品参数

        $oldData = Product::findByID($ID);
        if ($oldData->sName != $arrObjectData['sName']) {
            ProductModifyLog::saveLog($ID, "修改",
                "修改前的商品名称：" . $oldData->sName . "<br>修改后的商品名称：" . $arrObjectData['sName']);
        }

        if ($oldData->sRecomm != $arrObjectData['sRecomm']) {
            ProductModifyLog::saveLog($ID, "修改",
                "修改前的商品推荐词：" . $oldData->sRecomm . "<br>修改后的商品推荐词：" . $arrObjectData['sRecomm']);
        }

        if ($oldData->lStock != $arrObjectData['lStock']) {
            ProductModifyLog::saveLog($ID, "修改",
                "修改前的商品总库存：" . $oldData->lStock . "<br>修改后的商品总库存：" . $arrObjectData['lStock']);
        }

        if ($oldData->fPrice != $arrObjectData['fPrice']) {
            ProductModifyLog::saveLog($ID, "修改",
                "修改前的商品售价：" . $oldData->fPrice . "<br>修改后的商品售价：" . $arrObjectData['fPrice']);
        }

//        //新增进货价
//        if ($oldData->fShowPrice != $arrObjectData['fShowPrice']) {
//            ProductModifyLog::saveLog($ID, "修改",
//                "修改前的商品进货价：" . $oldData->fShowPrice . "<br>修改后的商品售价：" . $arrObjectData['fShowPrice']);
//        }


        $db = $this->sysObject->dbconn;
//        if ($arrObjectData['ProductTagID'] != ";;") {
//            $arrTag = explode(",", trim($arrObjectData['ProductTagID'], ";"));
//            $arrObjectData['ProductTagID'] = ";" . implode(";", $arrTag) . ";";
//            $db->createCommand("UPDATE Product SET FirstTagID='{$arrTag[0]}' WHERE lID='$ID'")->execute();
//        } else {
//            $arrObjectData['ProductTagID'] = "";
//            $db->createCommand("UPDATE Product SET FirstTagID=NULL WHERE lID='$ID'")->execute();
//        }

        return parent::beforeObjectEditSave($sysObject, $ID, $arrObjectData);
    }

    public function listDataConfig($sysList, $arrConfig)
    {
        if ($this->supplier) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'SupplierID',
                'sOper' => 'equal',
                'sValue' => $this->supplier->lID,
                'sSQL' => "SupplierID='" . $this->supplier->lID . "'"
            ];
        } elseif ($this->wholesalerSupplier) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'SupplierID',
                'sOper' => 'equal',
                'sValue' => $this->wholesalerSupplier->lID,
                'sSQL' => "SupplierID='" . $this->wholesalerSupplier->lID . "'"
            ];
        }

        if ($_POST['sExtra'] == 'all') {

        } elseif ($_POST['sExtra'] == 'offsale') {
            $arrConfig['arrFlt'][] = ['sField' => 'bSale', 'sOper' => 'equal', 'sValue' => '0'];
        } elseif ($_POST['sExtra'] == 'saleout') {
            $arrConfig['arrFlt'][] = ['sField' => 'lStock', 'sOper' => 'equal', 'sValue' => '0'];
            $arrConfig['arrFlt'][] = ['sField' => 'bSale', 'sOper' => 'equal', 'sValue' => '1'];
        } elseif ($_POST['sExtra'] == 'onsale') {
            $arrConfig['arrFlt'][] = ['sField' => 'bSale', 'sOper' => 'equal', 'sValue' => '1'];
            $arrConfig['arrFlt'][] = ['sField' => 'lStock', 'sOper' => 'larger', 'sValue' => '0'];
        } elseif ($_POST['sExtra'] == 'check') {
            $arrConfig['arrFlt'][] = ['sField' => 'bSale', 'sOper' => 'equal', 'sValue' => '1'];
            $arrConfig['arrFlt'][] = ['sField' => 'bCheck', 'sOper' => 'equal', 'sValue' => '0'];
        } elseif ($_POST['sExtra'] == 'checked') {
            $arrConfig['arrFlt'][] = ['sField' => 'bSale', 'sOper' => 'equal', 'sValue' => '1'];
            $arrConfig['arrFlt'][] = ['sField' => 'bCheck', 'sOper' => 'equal', 'sValue' => '1'];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }

    public function beforeObjectNewSave($sysObject, $arrObjectData)
    {
        $arrNewPicPath = [];
        foreach ($_POST['newProductPic'] as $pic) {
            if (substr($pic, 0, 5) == 'data:') {
                $arrFileInfo = File::parseImageFromBase64($pic);
                $sFileName = NewID::make() . ".jpg";
                Image::resize($arrFileInfo[1], 640, File::getUploadDir() . "/" . $sFileName);
                $arrNewPicPath[] = str_ireplace(\Yii::$app->params['sUploadDir'] . "/", "",
                    File::getUploadDir() . "/" . $sFileName);
            } else {
                $arrNewPicPath[] = str_ireplace(\Yii::$app->params['sUploadUrl'] . "/", "", $pic);
            }
        }

        $arrObjectData['sMasterPic'] = $arrNewPicPath[0];
        $arrObjectData['sPic'] = json_encode($arrNewPicPath);
        $arrObjectData['sParameterArray'] = json_encode($_POST['arrObjectData']['Shop/Product']['ParameterArray'], true);//商品参数
        if ($this->supplier) {
            $arrObjectData['SupplierID'] = $this->supplier->lID;//供应商
        } else{
            $oldProduct=Product::findOne(['sName'=>$arrObjectData['sName']]);
            if($oldProduct){
                $arrObjectData['SupplierID'] =$oldProduct->SupplierID;//供应商
            }
        }
//        $arrObjectData['bSale'] = 1;

//        if ($arrObjectData['ProductTagID'] != ";;") {
//            $arrTag = explode(",", trim($arrObjectData['ProductTagID'], ";"));
//            $arrObjectData['ProductTagID'] = ";" . implode(";", $arrTag) . ";";
//        } else {
//            $arrObjectData['ProductTagID'] = "";
//        }

//        $_POST['ProductTagID'] = $arrObjectData['ProductTagID'];

        return parent::beforeObjectNewSave($sysObject, $arrObjectData);
    }

    public function afterObjectEditSave($sysObject, $ID)
    {
        //保存商品的规格
        $lPos = 0;
        ProductSpecification::deleteAll(['ProductID' => $ID]);
        foreach ($_POST['specConfig'] as $sSpecCat => $arrSpec) {
            $lPos++;

            $arrPicPath = [];
            foreach ($arrSpec as $pic) {
                if (substr($pic, 0, 5) == 'data:') {
                    $arrFileInfo = File::parseImageFromBase64($pic);
                    $sFileName = NewID::make() . ".jpg";
                    Image::resize($arrFileInfo[1], 640, File::getUploadDir() . "/" . $sFileName);
                    $arrPicPath[] = str_ireplace(\Yii::$app->params['sUploadDir'] . "/", "",
                        File::getUploadDir() . "/" . $sFileName);
                } else {
                    $arrPicPath[] = str_ireplace(\Yii::$app->params['sUploadUrl'] . "/", "", $pic);
                }
            }

            $arrData = [
                'ProductID' => $ID,
                'sName' => $sSpecCat,
                'sValue' => implode(";", array_keys($arrSpec)),
                'sPic' => implode(";", $arrPicPath),
                'lPos' => $lPos
            ];
            ProductSpecification::saveData($arrData);
        }

        //保存商品的SKU
        $arrSpecCat = array_keys($_POST['specConfig']);
        ProductSKU::deleteAll(['ProductID' => $ID]);

        $i = 0;
        if (count($arrSpecCat) == 3) {
            foreach ($_POST['specConfig'][$arrSpecCat[0]] as $sSpec1 => $v) {
                foreach ($_POST['specConfig'][$arrSpecCat[1]] as $sSpec2 => $v) {
                    foreach ($_POST['specConfig'][$arrSpecCat[2]] as $sSpec3 => $v) {
                        $arrSku = [];
                        $arrSku[] = $arrSpecCat[0] . ":" . $sSpec1;
                        $arrSku[] = $arrSpecCat[1] . ":" . $sSpec2;
                        $arrSku[] = $arrSpecCat[2] . ":" . $sSpec3;

                        $arr = explode(",", $_POST['specVal'][$i]);
                        $arrData = [
                            'ProductID' => $ID,
                            'sValue' => implode(";", $arrSku),
                            'fPrice' => $arr[0],
                            'fCostPrice' => $arr[3],
                            'lStock' => $arr[2],
                            'fBuyerPrice' => $arr[1],
                        ];

                        ProductSKU::saveData($arrData);

                        $i++;
                    }
                }
            }
        } elseif (count($arrSpecCat) == 2) {
            foreach ($_POST['specConfig'][$arrSpecCat[0]] as $sSpec1 => $v) {
                foreach ($_POST['specConfig'][$arrSpecCat[1]] as $sSpec2 => $v) {
                    $arrSku = [];
                    $arrSku[] = $arrSpecCat[0] . ":" . $sSpec1;
                    $arrSku[] = $arrSpecCat[1] . ":" . $sSpec2;

                    $arr = explode(",", $_POST['specVal'][$i]);
                    $arrData = [
                        'ProductID' => $ID,
                        'sValue' => implode(";", $arrSku),
                        'fPrice' => $arr[0],
                        'fCostPrice' => $arr[3],
                        'lStock' => $arr[2],
                        'fBuyerPrice' => $arr[1],
                    ];

                    ProductSKU::saveData($arrData);

                    $i++;
                }
            }
        } else {
            foreach ($_POST['specConfig'][$arrSpecCat[0]] as $sSpec1 => $v) {
                $arrSku = [];
                $arrSku[] = $arrSpecCat[0] . ":" . $sSpec1;

                $arr = explode(",", $_POST['specVal'][$i]);
                $arrData = [
                    'ProductID' => $ID,
                    'sValue' => implode(";", $arrSku),
                    'fPrice' => $arr[0],
                    'fCostPrice' => $arr[3],
                    'lStock' => $arr[2],
                    'fBuyerPrice' => $arr[1],
                ];

                ProductSKU::saveData($arrData);

                $i++;
            }
        }

        \Yii::$app->productparamvalue->clear($ID);
        foreach ($_POST['paramValue'] as $lParamID => $sValue) {
            \Yii::$app->productparamvalue->saveData($ID, $lParamID, $sValue);
        }

        return parent::afterObjectEditSave($sysObject, $ID); // TODO: Change the autogenerated stub
    }

    public function afterObjectNewSave($sysObject, $ID)
    {
        ProductModifyLog::saveLog($ID, "新建", "新建商品");

        //保存商品的规格
        $lPos = 0;
        ProductSpecification::deleteAll(['ProductID' => $ID]);
        foreach ($_POST['specConfig'] as $sSpecCat => $arrSpec) {
            $lPos++;

            $arrPicPath = [];
            foreach ($arrSpec as $pic) {
                if (substr($pic, 0, 5) == 'data:') {
                    $arrFileInfo = File::parseImageFromBase64($pic);
                    $sFileName = NewID::make() . ".jpg";
                    Image::resize($arrFileInfo[1], 640, File::getUploadDir() . "/" . $sFileName);
                    $arrPicPath[] = str_ireplace(\Yii::$app->params['sUploadDir'] . "/", "",
                        File::getUploadDir() . "/" . $sFileName);
                } else {
                    $arrPicPath[] = str_ireplace(\Yii::$app->params['sUploadUrl'] . "/", "", $pic);
                }
            }

            $arrData = [
                'ProductID' => $ID,
                'sName' => $sSpecCat,
                'sValue' => implode(";", array_keys($arrSpec)),
                'sPic' => implode(";", $arrPicPath),
                'lPos' => $lPos
            ];
            ProductSpecification::saveData($arrData);
        }

        //保存商品的SKU
        $arrSpecCat = array_keys($_POST['specConfig']);

        ProductSKU::deleteAll(['ProductID' => $ID]);

        $i = 0;
        if (count($arrSpecCat) == 3) {
            foreach ($_POST['specConfig'][$arrSpecCat[0]] as $sSpec1 => $v) {
                foreach ($_POST['specConfig'][$arrSpecCat[1]] as $sSpec2 => $v) {
                    foreach ($_POST['specConfig'][$arrSpecCat[2]] as $sSpec3 => $v) {
                        $arrSku = [];
                        $arrSku[] = $arrSpecCat[0] . ":" . $sSpec1;
                        $arrSku[] = $arrSpecCat[1] . ":" . $sSpec2;
                        $arrSku[] = $arrSpecCat[2] . ":" . $sSpec3;

                        $arr = explode(",", $_POST['specVal'][$i]);
                        $arrData = [
                            'ProductID' => $ID,
                            'sValue' => implode(";", $arrSku),
                            'fPrice' => $arr[0],
                            'fCostPrice' => $arr[3],
                            'lStock' => $arr[2],
                            'fBuyerPrice' => $arr[1],
                        ];

                        ProductSKU::saveData($arrData);

                        $i++;
                    }
                }
            }
        } elseif (count($arrSpecCat) == 2) {
            foreach ($_POST['specConfig'][$arrSpecCat[0]] as $sSpec1 => $v) {
                foreach ($_POST['specConfig'][$arrSpecCat[1]] as $sSpec2 => $v) {
                    $arrSku = [];
                    $arrSku[] = $arrSpecCat[0] . ":" . $sSpec1;
                    $arrSku[] = $arrSpecCat[1] . ":" . $sSpec2;

                    $arr = explode(",", $_POST['specVal'][$i]);
                    $arrData = [
                        'ProductID' => $ID,
                        'sValue' => implode(";", $arrSku),
                        'fPrice' => $arr[0],
                        'fCostPrice' => $arr[3],
                        'lStock' => $arr[2],
                        'fBuyerPrice' => $arr[1],
                    ];

                    ProductSKU::saveData($arrData);

                    $i++;
                }
            }
        } else {
            foreach ($_POST['specConfig'][$arrSpecCat[0]] as $sSpec1 => $v) {
                $arrSku = [];
                $arrSku[] = $arrSpecCat[0] . ":" . $sSpec1;

                $arr = explode(",", $_POST['specVal'][$i]);
                $arrData = [
                    'ProductID' => $ID,
                    'sValue' => implode(";", $arrSku),
                    'fPrice' => $arr[0],
                    'fCostPrice' => $arr[3],
                    'lStock' => $arr[2],
                    'fBuyerPrice' => $arr[1],
                ];

                ProductSKU::saveData($arrData);

                $i++;
            }
        }

        foreach ($_POST['paramValue'] as $lParamID => $sValue) {
            \Yii::$app->productparamvalue->saveData($ID, $lParamID, $sValue);
        }

        $db = $this->sysObject->dbconn;
//        if ($_POST['ProductTagID']) {
//            $arrTag = explode(",", trim($_POST['ProductTagID'], ";"));
//            $db->createCommand("UPDATE Product SET FirstTagID='{$arrTag[0]}' WHERE lID='$ID'")->execute();
//        } else {
//            $db->createCommand("UPDATE Product SET FirstTagID=NULL WHERE lID='$ID'")->execute();
//        }

        return parent::afterObjectNewSave($sysObject, $ID); // TODO: Change the autogenerated stub
    }

    public function getNewFooterAppend()
    {
        $data = [];
        $data['Product'] = Product::findByID($_GET['ID']);

//        $data['arrParam'] = \Yii::$app->productparam->findAllParam();
//        $arrParamValue = \Yii::$app->productparamvalue->getArrParamValue($_GET['ID']);
//
//        $data['arrParamValue'] = [];
//        foreach ($arrParamValue as $v) {
//            $data['arrParamValue'][$v->ProductParamID] = $v;
//        }

        return $this->renderPartial('newfooter', $data);
    }

//    public function getViewFooterAppend()
//    {
//        $data = [];
//
//        $data['arrParam'] = \Yii::$app->productparam->findAllParam();
//        $arrParamValue = \Yii::$app->productparamvalue->getArrParamValue($_GET['ID']);
//
//        $data['arrParamValue'] = [];
//        foreach ($arrParamValue as $v) {
//            $data['arrParamValue'][$v->ProductParamID] = $v;
//        }
//
//        return $this->renderPartial('viewfooter', $data);
//    }

    public function getListButton()
    {
        $data = [];
        $data['bWholesaler'] = $this->supplier->BuyerID;
        return $this->renderPartial('listbutton', $data);
    }

    /**
     * 删除处理
     */
    public function actionDel()
    {
        //通过数据源获取数据库连接
        $sDataSourceKey = "ds_" . $this->sysObject->DataSourceID;
        $db = \Yii::$app->$sDataSourceKey;

        //准备事务
        $transaction = $db->beginTransaction();

        //推送数据
//        $product_id_list = '';
        try {
            $arrData = $this->listBatch();

            foreach ($arrData as $data) {
                Product::del($data[$this->sysObject->sIDField]);
                //推送数据
//                if (array_search($data, $arrData) == 0) {
//                    $product_id_list = $data[$this->sysObject->sIDField];
//                } else {
//                    $product_id_list .= ',' . $data[$this->sysObject->sIDField];
//                }
            }

            //提交
            $transaction->commit();
        } catch (\yii\base\Exception $e) {
            $transaction->rollBack();
            $sRespone = json_encode(['bSuccess' => false, 'sMsg' => $e->getMessage()]);
        }

        if (!$sRespone) {
            $sRespone = json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
        }

        return "var ret = " . $sRespone;
    }

    public function formatListData($arrData)
    {
        foreach ($arrData as $key => $data) {
            $arrData[$key]['lSale'] = number_format($data['lSale']);
            $arrData[$key]['lSaleShow'] = number_format($data['lSaleShow']);
            $arrData[$key]['sPathNameProductCatID'] = str_replace("/", "&nbsp;", $data['sPathNameProductCatID']);
        }

        return $arrData;
    }

    /**
     * 上架处理
     */
    public function actionOn()
    {
        //通过数据源获取数据库连接
        $sDataSourceKey = "ds_" . $this->sysObject->DataSourceID;
        $db = \Yii::$app->$sDataSourceKey;

        //准备事务
        $transaction = $db->beginTransaction();

        try {
            $arrData = $this->listBatch();

            foreach ($arrData as $data) {
                Product::onSale($data[$this->sysObject->sIDField]);
            }

            //提交
            $transaction->commit();
        } catch (\yii\base\Exception $e) {
            $transaction->rollBack();
            $sRespone = json_encode(['bSuccess' => false, 'sMsg' => $e->getMessage()]);
        }

        if (!$sRespone) {
            $sRespone = json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
        }

        return "var ret = " . $sRespone;
    }

    /**
     * 下架处理
     */
    public function actionOff()
    {
        //通过数据源获取数据库连接
        $sDataSourceKey = "ds_" . $this->sysObject->DataSourceID;
        $db = \Yii::$app->$sDataSourceKey;

        //准备事务
        $transaction = $db->beginTransaction();

        //推送数据
//        $product_id_list = '';
        try {
            $arrData = $this->listBatch();

            foreach ($arrData as $data) {
                Product::offSale($data[$this->sysObject->sIDField]);
                //推送数据
//                if (array_search($data, $arrData) == 0) {
//                    $product_id_list = $data[$this->sysObject->sIDField];
//                } else {
//                    $product_id_list .= ',' . $data[$this->sysObject->sIDField];
//                }
            }

            //提交
            $transaction->commit();
        } catch (\yii\base\Exception $e) {
            $transaction->rollBack();
            $sRespone = json_encode(['bSuccess' => false, 'sMsg' => $e->getMessage()]);
        }

        if (!$sRespone) {
            $sRespone = json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
        }

        return "var ret = " . $sRespone;
    }

    public function getListTableLineButton($data)
    {
        return $this->renderPartial('listtablelinebutton', ['data' => $data]);
    }

    public function actionJs()
    {
        return $this->renderPartial('js', []);
    }

    public function actionQrcode()
    {
        $product = Product::findByID($_GET['id']);
        return $this->renderPartial('qrcode', ['product' => $product]);
    }

    /**
     * 下载来三斤图片
     * @author caiguiqin
     * @time 2019年1月29日 15:21:35
     */
    public function actionDownloadfromlsj()
    {
        $lIDs = $_REQUEST['sSelectedID'];//7428;7430
        //切割数组
        $arrID = explode(';', $lIDs);
        $savePath = \Yii::$app->params['sUploadDir'] . "/userfile/upload/";//保存路径
        foreach ($arrID as $v) {
            //查找商品表的 sPic,sMasterPic,sContent
            $product = Product::find()->select('sName,lID,sPic,sMasterPic,sContent')->where(['lID' => $v])->one();
            //先判断是否是自己商城的图片

            // 下载主图
            if ($product['sMasterPic']) {
                $arrImg = explode('/', $product['sMasterPic']);
                if (!strpos($arrImg[2], '-')) {
                    //比如2019
                    $downloadPath = "http://image.laisanjin.cn/" . $product['sMasterPic'];
                    $this->download($downloadPath, $savePath, $product['sMasterPic'], 1);
                }
            }

            //下载商品详情图
            if ($product['sPic']) {
                $arrPic = json_decode($product['sPic'], true);
                foreach ($arrPic as $pic) {
                    if (!strpos($arrImg[2], '-')) {
                        //比如2019
                        $downloadPath = "http://image.laisanjin.cn/" . $pic;
                        $this->download($downloadPath, $savePath, $pic, 1);
                    }
                }
            }

            //下载商品详情图片
            $simpleHtmlDomModel = new simple_html_dom();
            $simpleHtmlDomModel->load($product['sContent']);
            $ssContent = $product['sContent'];
            foreach ($simpleHtmlDomModel->find('img') as $imgKey => $imgValue) {
                $downloadUrl = $imgValue->src;
                $saveUrl = $this->download($downloadUrl, $savePath, '', 2);
                $ssContent = str_replace($downloadUrl, $saveUrl, $ssContent);
            }
            $product->sContent = $ssContent;
            $product->save();
        }
        $sRespone = json_encode(['bSuccess' => true, 'sMsg' => '同步成功']);
        return "var ret = " . $sRespone;
    }

    /**
     * @param $url 下载图片路径
     * @param $savePath  保存路径
     * @param string $img 路径数据库全路径
     * @param int $cycleTimes
     * @return bool|string
     * @author caiguiqin
     * @time 2019年1月29日 15:55:12
     */
    public function download($url, $savePath, $img, $type)
    {
        $fileName = pathinfo($url, PATHINFO_BASENAME); //文件名字
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        $file = curl_exec($ch);
        curl_close($ch);

        if ($type == 1) {
            $arrImg = explode('/', $img);
            $dirName = $arrImg[2];
            $dirName1 = $arrImg[3];

        }
        if ($type == 2) {
            $data = date('Y-m-d', time());
            $arrTime = explode('-', $data);
            $dirName = $arrTime[0];
            $dirName1 = $arrTime[1] . "-" . $arrTime[2];
        }
        $this->mkdir($savePath . $dirName);
        $this->mkdir($savePath . $dirName . '/' . $dirName1);
        $resource = fopen($savePath . $dirName . '/' . $dirName1 . '/' . $fileName, 'a');
        fwrite($resource, $file);
        fclose($resource);

        $url = "/userfile/upload/" . $dirName . '/' . $dirName1 . '/' . $fileName;
        return $url;
    }

    /**
     * @param $folderName
     * @param int $mode
     * @return bool
     * @author caiguiqin
     * @time 2019年1月29日 15:55:20
     */
    public function mkdir($folderName, $mode = 0777)
    {
        if (is_dir($folderName) || @mkdir($folderName, $mode)) {
            return true;
        }
        if (!mkdir(dirname($folderName), $mode)) {
            return false;
        }
        return @mkdir($folderName, $mode);
    }

    public function actionGz()
    {
        return $this->renderPartial('gz', []);
    }

    public function actionXy()
    {
        return $this->renderPartial('xy', []);
    }

    public function getViewButton()
    {
        $data = [];
        return $this->renderPartial('viewbutton', $data);
    }

    public function actionCheck()
    {
        if (\Yii::$app->request->isPost) {
            $product = Product::findOne($_POST['lID']);
            if ($product->Reviewer) {
                $product->Terminator = \Yii::$app->backendsession->SysUserID;
                if ($_POST['bCheck']) {
                    $product->bCheck = 1;
                }
            } else {
                $product->Reviewer = \Yii::$app->backendsession->SysUserID;
            }
            $product->sRemark = $_POST['sRemark'];
            $product->dReviewer = date('Y-m-d H:i:s');
            $product->save();

            return $this->asJson(['status' => true, 'msg' => '提交成功']);
        } else {
            $product = Product::findOne($_GET['lID']);
            if ($product->bCheck) {
                return $this->asJson(['bSuccess' => false, 'sMsg' => '该商品已审核！']);
            }
            $data = $this->renderPartial("check");
            return $this->asJson(['bSuccess' => true, 'data' => $data]);
        }

    }
}


