<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\libs\StrTool;
use myerm\backend\common\libs\SystemTime;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysList;
use myerm\backend\system\models\SysObject;
use myerm\common\models\ExpressCompany;
use myerm\shop\common\models\Area;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\KDShipAddress;
use myerm\shop\common\models\Order;
use myerm\shop\common\models\OrderAddress;
use myerm\shop\common\models\OrderDetail;
use myerm\shop\common\models\OrderLogistics;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\Seller;
use yii\base\UserException;
use yii\helpers\ArrayHelper;

/**
 * 订单管理
 */
class OrderController extends BackendController
{
    const API_SUCCESS = 0;
    const API_FAILED = -1;

    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];

        if ($this->supplier) {
            $list = [];



            $list['ID'] = 'paid';
            $list['sName'] = '待发货';
            $list['sKey'] = 'Main.Shop.Order.List.Supplier';
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Order.List.Supplier&sTabID=paid&sExtra=paid';
            $data['arrList'][] = $list;

            $list['ID'] = 'delivered';
            $list['sName'] = '已发货';
            $list['sKey'] = 'Main.Shop.Order.List.Supplier';
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Order.List.Supplier&sTabID=delivered&sExtra=delivered';
            $data['arrList'][] = $list;

            $list['ID'] = 'success';
            $list['sName'] = '已完成';
            $list['sKey'] = 'Main.Shop.Order.List.Supplier';
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Order.List.Supplier&sTabID=success&sExtra=success';
            $data['arrList'][] = $list;

            $list['ID'] = 'closed';
            $list['sName'] = '已关闭';
            $list['sKey'] = 'Main.Shop.Order.List.Supplier';
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Order.List.Supplier&sTabID=closed&sExtra=closed';
            $data['arrList'][] = $list;

            $list['ID'] = 'refund';
            $list['sName'] = '退款中';
            $list['sKey'] = 'Main.Shop.Order.List.Supplier';
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Order.List.Supplier&sTabID=refund&sExtra=refund';
            $data['arrList'][] = $list;

            $list['ID'] = 'all';
            $list['sName'] = '全部';
            $list['sKey'] = 'Main.Shop.Order.List.Supplier';
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Order.List.Supplier&sTabID=all&sExtra=all';
            $data['arrList'][] = $list;

        } elseif ($this->buyer) {

            $sListKey = "Main.Shop.Order.List.Buyer";

            $list = [];

            $list['ID'] = 'all';
            $list['sName'] = '全部';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=all&sExtra=all';
            $data['arrList'][] = $list;

            $list['ID'] = 'unpaid';
            $list['sName'] = '待付款';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=unpaid&sExtra=unpaid';
            $data['arrList'][] = $list;

            $list['ID'] = 'paid';
            $list['sName'] = '待发货';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=paid&sExtra=paid';
            $data['arrList'][] = $list;


            $list['ID'] = 'delivered';
            $list['sName'] = '已发货';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=delivered&sExtra=delivered';
            $data['arrList'][] = $list;


            $list['ID'] = 'success';
            $list['sName'] = '已完成';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=success&sExtra=success';
            $data['arrList'][] = $list;

            $list['ID'] = 'closed';
            $list['sName'] = '已关闭';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=closed&sExtra=closed';
            $data['arrList'][] = $list;

            $list['ID'] = 'refund';
            $list['sName'] = '退款中';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=refund&sExtra=refund';
            $data['arrList'][] = $list;

            $list['ID'] = 'exception';
            $list['sName'] = '付款异常';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=exception&sExtra=exception';
            $data['arrList'][] = $list;
        } else {

            $sListKey = "Main.Shop.Order.List.All";
            $list = [];
            $list['ID'] = 'all';
            $list['sName'] = '全部';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=all&sExtra=all';
            $data['arrList'][] = $list;

            $list['ID'] = 'unpaid';
            $list['sName'] = '待付款';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=unpaid&sExtra=unpaid';
            $data['arrList'][] = $list;

            $list['ID'] = 'paid';
            $list['sName'] = '待发货';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=paid&sExtra=paid';
            $data['arrList'][] = $list;


            $list['ID'] = 'delivered';
            $list['sName'] = '已发货';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=delivered&sExtra=delivered';
            $data['arrList'][] = $list;


            $list['ID'] = 'success';
            $list['sName'] = '已完成';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=success&sExtra=success';
            $data['arrList'][] = $list;

            $list['ID'] = 'closed';
            $list['sName'] = '已关闭';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=closed&sExtra=closed';
            $data['arrList'][] = $list;

            $list['ID'] = 'refund';
            $list['sName'] = '退款中';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=refund&sExtra=refund';
            $data['arrList'][] = $list;

            $list['ID'] = 'exception';
            $list['sName'] = '付款异常';
            $list['sKey'] = $sListKey;
            $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=exception&sExtra=exception';
            $data['arrList'][] = $list;
        }

        return $this->renderPartial('@app/common/views/hometabs', $data);
    }

    public function formatListData($arrData)
    {
        $arrOrderID = [];
        foreach ($arrData as $data) {
            $arrOrderID[] = $data['lID'];
        }

        $arrOrderDetail = OrderDetail::find()
            ->where(['OrderID' => $arrOrderID])
            ->all();

        $arrOrderAddress = OrderAddress::find()
            ->where(['OrderID' => $arrOrderID])
            ->all();


//        $arrSellerOrder = [];
//        if ($_POST['sListKey'] == 'Main.Shop.Order.List.Seller') {
//            $arrSellerOrder = \Yii::$app->sellerorder::find()
//                ->where(['OrderID' => $arrOrderID])
//                ->with('seller')
//                ->with('upSeller')
//                ->with('upUpSeller')
//                ->indexBy('OrderID')
//                ->all();
//        }


        $arrDetailData = [];
        foreach ($arrOrderDetail as $orderDetail) {
            $arrDetailData[$orderDetail->OrderID]['sProductName'][] = "<a href='javascript:;'onclick=\"parent.addTab($(this).text(), '/shop/product/view?ID=" . $orderDetail->ProductID . "')\">" . $orderDetail->sName . "</a>";
            $arrDetailData[$orderDetail->OrderID]['sSKU'][] = $orderDetail->sSKU;
            //$arrDetailData[$orderDetail->OrderID]['fDetailPrice'][] = $orderDetail->fPrice;
            //$arrDetailData[$orderDetail->OrderID]['fCostPrice'][] = $orderDetail->fCostPrice;
            $arrDetailData[$orderDetail->OrderID]['lQty'][] = $orderDetail->lQuantity;
            $arrDetailData[$orderDetail->OrderID]['sDetailStatus'][] = $orderDetail->sStatus;
            $arrDetailData[$orderDetail->OrderID]['sDelivery'][] = $orderDetail->sShip;
            $arrDetailData[$orderDetail->OrderID]['sShipNo'][] = "<a href='javascript:;'onclick=\"parent.addTab('查询物流信息', '/shop/express/query?CompanyID=" . $orderDetail->ShipCompanyID . "&sNo=" . $orderDetail->sShipNo . "')\"> " . $orderDetail->sShipNo . "&nbsp;</a>";
            $arrDetailData[$orderDetail->OrderID]['sShipCompany'][] = ExpressCompany::findOne($orderDetail->ShipCompanyID)->sName;
            $arrDetailData[$orderDetail->OrderID]['dShipDate'][] = $orderDetail->dShipDate;
            $arrDetailData[$orderDetail->OrderID]['dSignDate'][] = $orderDetail->dSignDate;

            if ($orderDetail->dShipDate) {
                $arrDetailData[$orderDetail->OrderID]['bShip'] = 1;
            }

            if (!$orderDetail->dShipDate) {
                $arrDetailData[$orderDetail->OrderID]['bAllShip'] = false;
            }
        }

        foreach ($arrOrderAddress as $OrderAddress) {
            $arrDetailData[$OrderAddress->OrderID]['AreaIDOrderAddressID'][] = Area::findByID($OrderAddress->AreaID)->sName;
            $arrDetailData[$OrderAddress->OrderID]['CityIDOrderAddressID'][] = Area::findByID($OrderAddress->CityID)->sName;
            $arrDetailData[$OrderAddress->OrderID]['ProvinceIDOrderAddressID'][] = Area::findByID($OrderAddress->ProvinceID)->sName;
            $arrDetailData[$OrderAddress->OrderID]['sAddressOrderAddressID'][] = $OrderAddress->sAddress;
            $arrDetailData[$OrderAddress->OrderID]['sNameOrderAddressID'][] = $OrderAddress->sName;
            $arrDetailData[$OrderAddress->OrderID]['sMobileOrderAddressID'][] = $OrderAddress->sMobile;

        }

        //数据输出到列表
        foreach ($arrData as $key => $data) {
            $arrData[$key]['sProductName'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sProductName']);
            $arrData[$key]['sSKU'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sSKU']);
            $arrData[$key]['fDetailPrice'] = $this->listDataTemplate($arrDetailData[$data['lID']]['fDetailPrice']);
            $arrData[$key]['fCostPrice'] = $this->listDataTemplate($arrDetailData[$data['lID']]['fCostPrice']);
            $arrData[$key]['lQty'] = $this->listDataTemplate($arrDetailData[$data['lID']]['lQty']);
            $arrData[$key]['sDetailStatus'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sDetailStatus']);
            $arrData[$key]['sDelivery'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sDelivery']);
            $arrData[$key]['sShipNo'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sShipNo']);
            $arrData[$key]['sShipCompany'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sShipCompany']);
            $arrData[$key]['dShipDate'] = $this->listDataTemplate($arrDetailData[$data['lID']]['dShipDate']);
            $arrData[$key]['dSignDate'] = $this->listDataTemplate($arrDetailData[$data['lID']]['dSignDate']);
            $arrData[$key]['bShip'] = $arrDetailData[$data['lID']]['bShip'];
            $arrData[$key]['bAllShip'] = $arrDetailData[$data['lID']]['bAllShip'];
            $arrData[$key]['AreaIDOrderAddressID'] = $this->listDataTemplate($arrDetailData[$data['lID']]['AreaIDOrderAddressID']);
            $arrData[$key]['CityIDOrderAddressID'] = $this->listDataTemplate($arrDetailData[$data['lID']]['CityIDOrderAddressID']);
            $arrData[$key]['ProvinceIDOrderAddressID'] = $this->listDataTemplate($arrDetailData[$data['lID']]['ProvinceIDOrderAddressID']);
            $arrData[$key]['sAddressOrderAddressID'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sAddressOrderAddressID']);
            $arrData[$key]['sNameOrderAddressID'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sNameOrderAddressID']);
            $arrData[$key]['sMobileOrderAddressID'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sMobileOrderAddressID']);


            if ($_POST['sListKey'] == 'Main.Shop.Order.List.Seller') {
                $arrData[$key]['SellerID'] = "<a href='javascript:;'onclick=\"parent.addTab($(this).text(), '/shop/seller/view?ID=" . $arrSellerOrder[$data['lID']]->seller->lID . "')\">" . $arrSellerOrder[$data['lID']]->seller->sName . "</a>";
                $arrData[$key]['UpSellerID'] = "<a href='javascript:;'onclick=\"parent.addTab($(this).text(), '/shop/seller/view?ID=" . $arrSellerOrder[$data['lID']]->upSeller->lID . "')\">" . $arrSellerOrder[$data['lID']]->upSeller->sName . "</a>";
                $arrData[$key]['UpUpSellerID'] = "<a href='javascript:;'onclick=\"parent.addTab($(this).text(), '/shop/seller/view?ID=" . $arrSellerOrder[$data['lID']]->upUpSeller->lID . "')\">" . $arrSellerOrder[$data['lID']]->upUpSeller->sName . "</a>";
                $arrData[$key]['fSellerCommission'] = number_format($arrSellerOrder[$data['lID']]->fSellerCommission,
                    2);
                $arrData[$key]['fUpSellerCommission'] = number_format($arrSellerOrder[$data['lID']]->fUpSellerCommission,
                    2);
                $arrData[$key]['fUpUpSellerCommission'] = number_format($arrSellerOrder[$data['lID']]->fUpUpSellerCommission,
                    2);
            }
        }


        return $arrData;
    }

    public function listDataTemplate($arrData)
    {
        $sHtml = [];
        $count = count($arrData);

        if ($count == 1) {
            return $arrData[0];
        }

        foreach ($arrData as $key => $data) {
            $data = $data ? $data : '&nbsp;';
            $sHtml[] = \yii\helpers\Html::tag('div', $data, [
                'style' => [
                    'padding' => '5px 0',
                    'border-bottom' => $key < ($count - 1) ? '1px solid #E7ECF1' : '0'
                ]
            ]);
        }
        return implode('', $sHtml);
    }

    public function getListTableLineButton($data)
    {
        if (!$this->buyer) {
            return $this->renderPartial("button", ['data' => $data]);
        }
    }

    public function actionJs()
    {
        return $this->renderPartial("js");
    }

    public function actionExport()
    {
        set_time_limit(0);
        ini_set("memory_limit", "8048M");

        $sysObject = SysObject::findOne(['sObjectName' => $this->sObjectName]);

        include \Yii::getAlias("@app") . "/common/libs/PHPExcel.php";
        include \Yii::getAlias("@app") . '/common/libs/PHPExcel/IOFactory.php';

        $objPHPExcel = new \PHPExcel();

        $arrChar = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z'
        ];

        $sPrefix = "";
        $lKey = 0;
        foreach ($sysObject->field as $i => $field) {
            if ($i % 26 == 0 && $i > 25) {
                $lKey = 0;
                if ($sPrefix == "") {
                    $sPrefix = "A";
                } else {
                    $sPrefix = $arrChar[array_search($sPrefix, $arrChar) + 1];
                }
            }

            $arrHeader[] = $sPrefix . $arrChar[$lKey];
            $lKey++;
        }
        /**/
        $arrSpecialCol = [
            'sDeliveryNo' => '快递单号(供应商填写)',
            'DeliveryCompanyID' => '快递公司(供应商填写)'
        ];

        $colIndex = 0;
        foreach ($arrSpecialCol as $key => $value) {
            $objPHPExcel->getActiveSheet()->setCellValue($arrHeader[$colIndex] . '1', $value);
            $colIndex++;
        }
        $beginCol = count($arrSpecialCol); //内容开始的列数

        $listKey = $beginCol;
        /**/

        $sysList = SysList::find()->where("sKey='" . \Yii::$app->request->post('sListKey') . "'")->one();

        $arrConfig = $_POST;

        foreach ($sysList->cols as $i => $col) {
            //不导出订单lID
            if ($col->field->sFieldAs == 'lID' || $col->field->sFieldAs == 'ProvinceIDOrderAddressID' || $col->field->sFieldAs == 'CityIDOrderAddressID' || $col->field->sFieldAs == 'AreaIDOrderAddressID') {
                continue;
            }

            if ($col->field->sFieldAs == 'BuyerID') {
                $objPHPExcel->getActiveSheet()->setCellValue($arrHeader[$listKey] . '1', '寄件人');
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue($arrHeader[$listKey] . '1', $col->field->sName);
            }

            $arrConfig['arrDispCol'][] = $col->field->sFieldAs;
            $listKey++;

            //增加渠道商客服电话字段
            if ($col->field->sFieldAs == 'BuyerID') {
                $objPHPExcel->getActiveSheet()->setCellValue($arrHeader[$listKey] . '1', '寄件人电话');
                $arrConfig['arrDispCol'][] = 'sServiceTel';
                $listKey++;
            }
        }

        //关闭分页，查询所有的数据
        $arrConfig['bCanPage'] = 0;

        if ($_POST['sSelectedID'] == "all") {
            if (!StrTool::isEmpty(\Yii::$app->request->post('sSearchKeyWord'))) {
                $arrConfig['arrFlt'][] = [
                    'sField' => 'sName',
                    'sOper' => 'center',
                    'sValue' => \Yii::$app->request->post('sSearchKeyWord')
                ];
            }

            if ($_POST['arrSearchField']) {
                foreach ($_POST['arrSearchField'] as $sField => $arr) {
                    $arrConfig['arrFlt'][] = [
                        'sField' => $sField,
                        'sOper' => $arr['sOper'],
                        'sValue' => $arr['sValue']
                    ];
                }
            }

            $arrConfig = $this->listDataConfig($sysList, $arrConfig);

            $arrData = $sysList->getListdata($arrConfig)['arrData'];
        } else {
            $arrSelectedID = explode(";", $_POST['sSelectedID']);
            $arrConfig['arrFlt'] = [];
            $arrConfig['arrFlt'][] = [
                'sField' => $sysObject->sIDField,
                'sOper' => 'in',
                'sValue' => "'" . implode("','", $arrSelectedID) . "'"
            ];

            if ($_POST['arrSearchField']) {
                foreach ($_POST['arrSearchField'] as $sField => $arr) {
                    $arrConfig['arrFlt'][] = [
                        'sField' => $sField,
                        'sOper' => $arr['sOper'],
                        'sValue' => $arr['sValue']
                    ];
                }
            }

            $arrConfig['sDataRegion'] = 'all';

            $arrConfig = $this->listDataConfig($sysList, $arrConfig);

            $arrData = $sysList->getListdata($arrConfig)['arrData'];
        }

        $arrOrderID = [];
        foreach ($arrData as $data) {
            $arrOrderID[] = $data['lID'];
        }

        $arrOrderDetailData = [];
        $arrOrderDetail = OrderDetail::find()
            ->where(['OrderID' => $arrOrderID])
            ->with('shipCompany')
            ->all();
        foreach ($arrOrderDetail as $detail) {
            $arrOrderDetailData[$detail->OrderID][] = $detail;
        }

        $lRow = 1;//excel的行
        foreach ($arrData as $data) {
            $listKey = $beginCol;

            foreach ($arrOrderDetailData[$data['lID']] as $detail) {
                $lRow++;
                $sAddress = '';
                $lAddress = 0;
                foreach ($sysList->cols as $i => $col) {
                    $field = $col->field;

                    if ($field->sFieldAs == 'lID') {
                        continue;
                    }

                    //验证是否供应商角色
                    if (\Yii::$app->backendsession->SysRoleID == 3) {
                        if ($i > 0) {
                            $i++;
                        }
                    } else {
                        if ($i > 3) {
                            $i++;
                        }
                    }

                    if ($field->sDataType == 'List' || $field->sDataType == 'ListTable') {
                        $sValue = $data[$field->sFieldAs]['sName'];
                    } elseif ($field->sDataType == 'MultiList') {
                        $sValue = $sComm = "";
                        foreach ($data[$field->sFieldAs] as $arrValue) {
                            $sValue .= $sComm . $arrValue['sName'];
                            $sComm = ";";
                        }
                    } elseif ($field->sDataType == 'Bool') {
                        if ($data[$field->sFieldAs]) {
                            $sValue = \Yii::t('app', '是');
                        } else {
                            $sValue = \Yii::t('app', '否');
                        }
                    } elseif ($field->sDataType == 'Date') {
                        if ($field->attr['dFormat'] == 'short') {
                            if ($data[$field->sFieldAs]) {
                                $sValue = SystemTime::getShortDate($data[$field->sFieldAs], $field->attr['lTimeOffset']);
                            } else {
                                $sValue = '';
                            }
                        } elseif ($data[$field->sFieldAs]) {
                            $sValue = SystemTime::getLongDate($data[$field->sFieldAs], $field->attr['lTimeOffset']);
                        } else {
                            $sValue = "";
                        }
                    } elseif ($field->sDataType == 'Virtual') {
                        if ($field->sFieldAs == 'sProductName') {
                            $sValue = $detail->sName;
                        } elseif ($field->sFieldAs == 'sSKU') {
                            $sValue = $detail->sSKU;
                        } elseif ($field->sFieldAs == 'fDetailPrice') {
                            $sValue = $detail->fPrice;
                        } elseif ($field->sFieldAs == 'fCostPrice') {
                            $sValue = $detail->fCostPrice;
                        } elseif ($field->sFieldAs == 'sDetailStatus') {
                            $sValue = $detail->sStatus;
                        } elseif ($field->sFieldAs == 'sDelivery') {
                            $sValue = $detail->sShip;
                        } elseif ($field->sFieldAs == 'sShipNo') {
                            $sValue = $detail->sShipNo;
                        } elseif ($field->sFieldAs == 'sShipCompany') {
                            $sValue = $detail->shipCompany->sName;
                        } elseif ($field->sFieldAs == 'dShipDate') {
                            $sValue = $detail->dShipDate;
                        } elseif ($field->sFieldAs == 'lQty') {
                            $sValue = $detail->lQuantity;
                        } elseif ($field->sFieldAs == 'sNameOrderAddressID') {
                            $sValue = $detail->order->orderAddress->sName;
                        } elseif ($field->sFieldAs == 'sMobileOrderAddressID') {
                            $sValue = $detail->order->orderAddress->sMobile;
                        } elseif ($field->sFieldAs == 'ProvinceIDOrderAddressID') {
                            $sAddress .= $detail->order->orderAddress->province->sName;
                        } elseif ($field->sFieldAs == 'CityIDOrderAddressID') {
                            $sAddress .= $detail->order->orderAddress->city->sName;
                        } elseif ($field->sFieldAs == 'AreaIDOrderAddressID') {
                            $sAddress .= $detail->order->orderAddress->area->sName;
                        } elseif ($field->sFieldAs == 'sAddressOrderAddressID') {
                            $sValue = $sAddress . $detail->order->orderAddress->sAddress;
                        } elseif ($field->sFieldAs == 'dSignDate') {
                            $sValue = $detail->order->dSignDate;
                        }

                    } else {
                        $sValue = $data[$field->sFieldAs];
                    }

                    if ($field->sFieldAs == 'ProvinceIDOrderAddressID' || $field->sFieldAs == 'CityIDOrderAddressID' || $field->sFieldAs == 'AreaIDOrderAddressID') {
                        $lAddress++;
                        continue;
                    }

                    //判断是否供应商账号
                    if (\Yii::$app->backendsession->SysRoleID == 3) {
                        //如果是土葩葩，使用它的客服电话，其余使用来三斤客服电话 panlong 2019-2-12 13:58:56
                        if ($detail->buyer->lID == 11) {
                            $sMobile = '4008015919';
                        } else {
                            $sMobile = '4000089698';
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arrHeader[3] . $lRow, $sMobile,
                            \PHPExcel_Cell_DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arrHeader[$listKey + $i - $lAddress] . $lRow, $sValue, \PHPExcel_Cell_DataType::TYPE_STRING);
                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arrHeader[$listKey + $i - 1 - $lAddress] . $lRow, $sValue, \PHPExcel_Cell_DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arrHeader[5] . $lRow, $detail->buyer->sServiceTel,
                            \PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                }

            }
        }
        if (\Yii::$app->backendsession->SysRoleID == 3) {
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(28);
        } else {
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(28);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Type: application/msexcel");

        if (stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
            header('Content-Disposition:inline;filename="' . $sysObject->sName . \Yii::t('app',
                    '导出') . date("Y-m-d-His") . '.xls"');
        } else {
            header('Content-Disposition:inline;filename="' . urlencode($sysObject->sName . \Yii::t('app',
                        '导出')) . date("Y-m-d-His") . '.xls"');
        }

        header("Content-Transfer-Encoding: binary");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
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
        } elseif ($this->buyer) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => $this->buyer->lID,
                'sSQL' => "BuyerID='" . $this->buyer->lID . "'"
            ];
        }

        if ($_POST['sExtra'] == 'all') {

        } elseif ($_POST['sExtra'] == 'unpaid') {
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'unpaid'];
        } elseif ($_POST['sExtra'] == 'paid') {
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'paid'];
            $arrConfig['arrFlt'][] = [
                'sField' => 'RefundStatusID',
                'sOper' => 'notequal',
                'sValue' => 'refunding',
                'sSQL' => "IFNULL(RefundStatusID, '')<>'refunding'"
            ];
        } elseif ($_POST['sExtra'] == 'delivered') {
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'delivered'];
            $arrConfig['arrFlt'][] = [
                'sField' => 'RefundStatusID',
                'sOper' => 'notequal',
                'sValue' => 'refunding',
                'sSQL' => "IFNULL(RefundStatusID, '')<>'refunding'"
            ];
        } elseif ($_POST['sExtra'] == 'success') {
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'success'];
        } elseif ($_POST['sExtra'] == 'closed') {
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'closed'];
        } elseif ($_POST['sExtra'] == 'refund') {
            $arrConfig['arrFlt'][] = ['sField' => 'RefundStatusID', 'sOper' => 'equal', 'sValue' => 'refunding'];
        } elseif ($_POST['sExtra'] == 'exception') {
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'exception'];
        }

        //搜索商品
        if ($arrConfig['arrSearchField']['sProductName']['sValue']) {
            $arrProduct = Product::find()->select(['lID'])->where([
                'LIKE',
                'sName',
                $arrConfig['arrSearchField']['sProductName']['sValue']
            ])->indexBy('lID')->all();
            $arrProductID = array_keys($arrProduct);
            $arrProductID[] = -1;

            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sValue' => "(SELECT OrderID FROM OrderDetail WHERE ProductID IN (" . implode(",", $arrProductID) . "))"
            ];
        }

        if ($arrConfig['arrSearchField']['SellerCommission']['sValue']) {
            $arrSeller = Seller::find()->select(['lID'])->where(['sName' => $arrConfig['arrSearchField']['SellerCommission']['sValue']])->indexBy('lID')->all();
            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sValue' => "(SELECT OrderID FROM SellerOrder WHERE SellerID IN (" . implode(",",
                        array_keys($arrSeller)) . ") OR UpSellerID  IN (" . implode(",",
                        array_keys($arrSeller)) . ") OR UpUpSellerID  IN (" . implode(",",
                        array_keys($arrSeller)) . "))"
            ];
        }

        if ($arrConfig['arrSearchField']['SellerID']['sValue']) {
            $arrID = Seller::find()->select(['lID'])->where("sName='" . $arrConfig['arrSearchField']['SellerID']['sValue'] . "'")->indexBy('lID')->all();
            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sValue' => "(SELECT OrderID FROM SellerOrder WHERE SellerID IN (" . implode(",",
                        array_keys($arrID)) . "))"
            ];
        }

        if ($arrConfig['arrSearchField']['UpSellerID']['sValue']) {
            $arrID = Seller::find()->select(['lID'])->where("sName='" . $arrConfig['arrSearchField']['UpSellerID']['sValue'] . "'")->indexBy('lID')->all();
            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sValue' => "(SELECT OrderID FROM SellerOrder WHERE UpSellerID IN (" . implode(",",
                        array_keys($arrID)) . "))"
            ];
        }

        if ($arrConfig['arrSearchField']['UpUpSellerID']['sValue']) {
            $arrID = Seller::find()->select(['lID'])->where("sName='" . $arrConfig['arrSearchField']['UpUpSellerID']['sValue'] . "'")->indexBy('lID')->all();
            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sValue' => "(SELECT OrderID FROM SellerOrder WHERE UpUpSellerID IN (" . implode(",",
                        array_keys($arrID)) . "))"
            ];
        }

        if ($arrConfig['arrSearchField']['sShipNo']['sValue']) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sValue' => "(SELECT OrderID FROM OrderDetail WHERE sShipNo='" . $arrConfig['arrSearchField']['sShipNo']['sValue'] . "')"
            ];
        }

        if ($arrConfig['arrSearchField']['sNameOrderAddressID']['sValue']) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sValue' => "(SELECT OrderID FROM OrderAddress WHERE sName='" . $arrConfig['arrSearchField']['sNameOrderAddressID']['sValue'] . "')"
            ];
        }

        if ($arrConfig['arrSearchField']['sMobileOrderAddressID']['sValue']) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sValue' => "(SELECT OrderID FROM OrderAddress WHERE sMobile='" . $arrConfig['arrSearchField']['sMobileOrderAddressID']['sValue'] . "')"
            ];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }


    public function actionBatchship()
    {
        if (\Yii::$app->request->isPost) {

            set_time_limit(0);
            ini_set("memory_limit", "8048M");

            include \Yii::getAlias("@app") . "/common/libs/PHPExcel.php";
            include \Yii::getAlias("@app") . '/common/libs/PHPExcel/IOFactory.php';

            //读取Excel表格的内容
            $inputFileType = \PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);

            $bFlag = true;
            foreach ($objPHPExcel->getAllSheets() as $sheet) {
                $sheetData = $sheet->toArray(null, true, true, true);

                $arrField = array_shift($sheetData);
                $arrFieldIDKey = array();
                foreach ($arrField as $k => $v) {
                    if (!trim($v)) {
                        unset($arrField[$k]);
                    } else {
                        $field = SysField::findOne(['sObjectName' => 'Shop/Order', 'sName' => $v]);

                        if (!$field) {
                            $arrMsg[] = "字段[" . $v . "]不存在。";
                            $bFlag = false;
                        } else {
                            $arrField[$v] = $k;
                            $arrFieldIDKey[$arrField[$k]['sFieldAs']] = $arrField[$k];
                            $arrFieldIDKey[$arrField[$k]['sFieldAs']]['key'] = $k;
                        }
                    }
                }


                $isExist = false;
                foreach ($arrField as $k => $arr) {
                    foreach (['lID'] as $sKeyField) {
                        if (StrTool::equalsIgnoreCase($sKeyField, $arr['sFieldAs'])) {
                            $isExist = true;
                        }
                    }
                }

                if (!$isExist) {
                    $arrMsg[] = "Excel文件的列中并不包含ID";
                    $bFlag = false;
                }

                $arrMsg['update'] = 0;
                $arrMsg['insert'] = 0;

                if ($bFlag) {
                    foreach ($sheetData as $row => $data) {
                        $OrderID = $data[$arrField['ID']];
                        $sProductName = $data[$arrField['商品']];
                        $sSku = $data[$arrField['规格']];
                        $sShipCompany = $data[$arrField['物流公司']];
                        $sShipNo = $data[$arrField['快递单号']];
                        $sShipID = $data[$arrField['发货方式']] == '物流' ? "wuliu" : "self";

                        if ($sSku) {
                            $detail = OrderDetail::findOne([
                                'OrderID' => $OrderID,
                                'sName' => $sProductName,
                                'sSku' => $sSku
                            ]);
                        } else {
                            $detail = OrderDetail::findOne([
                                'OrderID' => $OrderID,
                                'sName' => $sProductName,
                            ]);
                        }

                        if (!$detail->bShiped) {

                        }
                    }
                }
            }

            print_r($arrField);
            exit;

        } else {
            $data = [];
            return $this->render('batchship', $data);
        }
    }

    public function actionView()
    {
        $data = [];
        $data['data'] = Order::findByID($_GET['ID']);
        if (!$data['data']) {
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "您查看的对象不存在。"));
        } elseif (!$this->supplier) {
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "您无权查看订单详情。"));
        }
        $data['BuyerName'] = Buyer::getsName($data['data']->BuyerID);
        $data['arrSysDetailObject'] = SysObject::find()->where(['ParentID' => 'Shop/Order'])->all();

        return $this->render('detail', $data);
    }

    public function actionGetshipdetail($ID)
    {
        $data = [];
        $data['order'] = Order::findByID($ID);
        $data['arrCompany'] = ExpressCompany::find()->orderBy("sPinYin,sName")->all();

        //待发货数
        $data['lWaitDeliver'] = OrderDetail::find()
            ->where([
                'and',
                ['OrderID' => $ID],
                ['is', 'dShipDate', null]
            ])
            ->count();

        return $this->renderPartial("shipdetail", $data);
    }

    public function actionConfirmreceive()
    {
        $arrData = $this->listBatch();

        foreach ($arrData as $data) {
            $order = \Yii::$app->order::findByID($data['lID']);
            $order->confirmReceive();
        }

        return $this->asJson(['bSuccess' => true, 'sMsg' => '操作成功']);
    }


    public function getListButton()
    {
        $data = [];
        return $this->renderPartial("listbutton", $data);
    }

    public function actionModifymessage($ID)
    {
        if (\Yii::$app->request->isPost) {

            $order = Order::find()->where(['lID' => $ID])->one();
            $order->sNote = $_POST['message'];
            $order->save();

            return $this->asJson(['status' => true, 'message' => '操作成功']);
        } else {
            $data = [];
            $data['order'] = Order::find()->where(['lID' => $ID])->one();

            return $this->renderPartial("modifymessage", $data);
        }
    }

    public function actionModifyaddress($ID)
    {
        if (\Yii::$app->request->isPost) {

            $update = [
                'ProvinceID' => $_POST['province'],
                'CityID' => $_POST['city'],
                'AreaID' => $_POST['area'],
                'sAddress' => $_POST['address'],
                'sName' => $_POST['name'],
                'sMobile' => $_POST['mobile'],
            ];

            OrderAddress::updateAll($update, ['OrderID' => $ID]);
            return $this->asJson(['status' => true, 'message' => '操作成功']);
        } else {
            $data = [];
            $data['address'] = OrderAddress::find()->where(['OrderID' => $ID])->one();

            return $this->renderPartial("modifyaddress", $data);
        }
    }

    public function actionProvince($ID)
    {
        $arrProvince = Area::find()->where(['sType' => 'province'])->all();
        foreach ($arrProvince as $province) {
            echo "<option value='" . $province->ID . "' " . ($province->ID == $ID ? "selected" : "") . ">" . $province->sName . "</option>";
        }
    }


    public function actionCity($ID, $UpID)
    {
        $arrCity = Area::find()->where(['UpID' => $UpID])->all();
        foreach ($arrCity as $city) {
            echo "<option value='" . $city->ID . "' " . ($city->ID == $ID ? "selected" : "") . ">" . $city->sName . "</option>";
        }
    }

    public function actionArea($ID, $UpID)
    {
        $arrArea = Area::find()->where(['UpID' => $UpID])->all();
        foreach ($arrArea as $area) {
            echo "<option value='" . $area->ID . "' " . ($area->ID == $ID ? "selected" : "") . ">" . $area->sName . "</option>";
        }
    }

    /**
     * 弹出发货地址模板
     * @param sSelectedID
     * 2018.7.6 cgq
     */
    public function actionAlerttemplate()
    {
        $ids = $_REQUEST['sSelectedID'];
        if (!$ids) {
            return $this->asJson(['bSuccess' => false, 'sMsg' => '请选择ID']);
        }

        //判读订单状态
        $arrID = explode(';', $ids);
        foreach ($arrID as $id) {
            $orderInfo = Order::findOne($id);
            if ($orderInfo['StatusID'] == 'delivered') {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderInfo['sName'] . "已发货"]);
            }
            if ($orderInfo['StatusID'] == 'closed') {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderInfo['sName'] . "已关闭"]);
            }
            if ($orderInfo['StatusID'] == 'unpaid') {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderInfo['sName'] . "未付款"]);
            }
            if ($orderInfo['StatusID'] == 'success') {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderInfo['sName'] . "交易已完成"]);
            }

            //查看订单详情是否存在退款订单
            $orderDetailInfo = OrderDetail::findAll(['OrderID' => $id]);
            foreach ($orderDetailInfo as $orderDetail) {
                $orderDetailResult = OrderDetail::findOne(['lID' => $orderDetail->lID]);
                $orderReturn = $orderDetailResult->getBRefunding();
                if ($orderReturn) {
                    return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderInfo['sName'] . "存在退款状态"]);
                }
            }
            $result = OrderLogistics::getOrderInfo($id);
            if ($result['status'] == 1) {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderInfo['sName'] . "已获取快递单号"]);
            }
        }

        //为了获取供应商ID
        $orderResult = Order::findOne($arrID[0]);

        $data = [];
        //把选取的id传到视图中
        $data['ids'] = $ids;

        //查找供应商 发货地址选择模板
        $shipAddress = KDShipAddress::getSupplierShipAddress($orderResult['SupplierID']);
        if (!$shipAddress) {
            return $this->asJson(['bSuccess' => false, 'sMsg' => "该供应商没有发货地址模板"]);
        }
        $shipAddressName = [];

        $addressDefault = [];//默认发货地址模板

        foreach ($shipAddress as $k => $shipAddressInfo) {
            $shipAddressName[$shipAddressInfo['lID']] = $shipAddressInfo['sName'];
            if ($shipAddressInfo['bDefault'] == 1) {
                //选取默认值
                $addressDefault = $shipAddress[$k];
            }
        }

        //如果没有默认地址 就选择第一个
        if (!$addressDefault) {
            $addressDefault = $shipAddress[0];
        }

        //获取所有的模板名称
        $data['sName'] = $shipAddressName;
        //获取省市县的中文名字
        $addressDefault['province'] = $this->getName($addressDefault['ProvinceID']);
        $addressDefault['city'] = $this->getName($addressDefault['CityID']);
        $addressDefault['area'] = $this->getName($addressDefault['AreaID']);

        //获取快递名称
        $courierName = ExpressCompany::findOne(['sKdBirdCode' => $addressDefault['sKdBirdCode']]);
        $addressDefault['courierName'] = $courierName['sName'];

        $data['info'] = $addressDefault;

        //默认地址的lid
        $data['defaultID'] = $addressDefault['lID'];
        $data = $this->renderPartial("shippingtemplate", $data);
        return $this->asJson(['bSuccess' => true, 'data' => $data]);

    }

    /**
     * 获取地区名称
     * @param $id
     * @return 城市中文名字
     * cgq 2018.7.6
     */
    public function getName($id)
    {
        $name = Area::findOne($id);
        return $name['sName'];
    }

    /**
     * 获取发货地址模板信息
     * @param
     * 2018.7.6 cgq
     */
    public function actionGettemplateinfo()
    {
        $id = $_POST['id'];
        $data = KDShipAddress::getAddersstemplateInfo($id);
        //获取省市县的中文名字
        $data['province'] = $this->getName($data['ProvinceID']);
        $data['city'] = $this->getName($data['CityID']);
        $data['area'] = $this->getName($data['AreaID']);

        //获取快递名称信息
        $couriername = ExpressCompany::findOne(['sKdBirdCode' => $data['sKdBirdCode']]);
        $data['courierName'] = $couriername['sName'];

        if ($data) {
            return $this->asJson(['bSuccess' => true, 'data' => $data]);
        }
        return $this->asJson(['bSuccess' => false, 'sMsg' => '未获取模板信息']);
    }

    /**
     * 获取快递单号
     * @param
     * 2018.7.6 cgq
     */
    public function actionGetdelivery()
    {
        $ids = $_POST['ids'];//订单id
        $arrOrderID = explode(";", $ids);
        $tempalteID = $_POST['templateID'];//发货地址模板id
        //获取模板信息，获取发货人信息，获取商品信息，
        $shippingtemplate = KDShipAddress::getAddersstemplateInfo($tempalteID);//模板信息

        $data = [];//传到视图的数据

        foreach ($arrOrderID as $orderID) {
            //查找收货人信息
            $consignee = OrderAddress::getConsigneeinfomation($orderID);
            //订单物流表查看,是否有拆单
            $result = OrderLogistics::getOrderInfo($orderID);

            $product = [];

            //如果是土葩葩，使用它的客服电话，其余使用来三斤客服电话 hechengcheng 2019-2-14 18:22:40
            $order = \myerm\shop\mobile\models\Order::find()->select('lID, sName, BuyerID')->where(['lID' => $orderID])->one();
            if ($order->BuyerID == 11) {
                $shippingtemplate['sMobile'] = '4008015919';
            } else {
                $shippingtemplate['sMobile'] = '4000089698';
            }

            if ($result) {
                //有存在订单物流表中，说明有进行拆单处理
                foreach ($result as $orderInfo) {
                    $product['ordercid'] = $orderInfo['sName'];//子订单号
                    $product['productInfo'] = $orderInfo['sProductInfo'];//json数据
                    //将信息传入快递鸟接口
                    $deliveryInfo = $this->arrangeInfo($product, $shippingtemplate, $consignee);//返回快递鸟信息
                    if ($deliveryInfo['Success']) {
                        $logisticCode = $deliveryInfo['Order']['LogisticCode'];
                        $shipperCode = $deliveryInfo['Order']['ShipperCode'];

                        $sExpressOrderInfo = $this->getExpressOrderInfo($shippingtemplate, $consignee, $deliveryInfo, $orderInfo['sProductInfo']);

                        if (!empty($deliveryInfo['PrintTemplate'])) {
                            $printTemplate = $deliveryInfo['PrintTemplate'];
                        } else {
                            $printTemplate = "";
                        }

                        //获取快递单号成功，存入物流订单表（快递公司,快递名称）
                        $orderLogisticsResult = OrderLogistics::modifyLogistics($product['ordercid'], $logisticCode, $shipperCode, $sExpressOrderInfo, $printTemplate);
                        if ($orderLogisticsResult) {
                            //获取快递公司的ID
                            $expressCompany = ExpressCompany::findOne(['sKdBirdCode' => $shipperCode]);

                            //增加expresstrace表的信息
                            \Yii::$app->expresstrace->poll([
                                'sNo' => $logisticCode,
                                'ExpressCompanyID' => $expressCompany->ID
                            ]);

                            //修改订单详情表物流信息
                            $childOrderID = trim($orderInfo['sOrderDetailID'], ';');
                            $childOrderID = explode(';', $childOrderID);

                            $sendChild = [];//传给子平台的数组

                            foreach ($childOrderID as $orderKey => $orderDetailID) {
                                //推送消息
                                $detail = OrderDetail::findOne($orderDetailID);

                                $sendChild['productInfo'][$orderKey]['productID'] = $detail->ProductID;
                                if ($detail->sSKU) {
                                    $sendChild['productInfo'][$orderKey]['sSKU'] = $detail->sSKU;
                                } else {
                                    $sendChild['productInfo'][$orderKey]['sSKU'] = "";
                                }
                                //已经拆单的订单 商品数量 按订单物流表
                                $orderLogisticsResult = json_decode($orderInfo['sProductInfo'], true);
                                $sendChild['productInfo'][$orderKey]['lQuantity'] = $orderLogisticsResult[$orderKey]['lQuantity'];

//                                修改订单详情表物流信息
                                $detail->modifyShip(
                                    [
                                        'ShipID' => "wuliu",
                                        'ShipCompanyID' => $expressCompany->ID,
                                        'sShipNo' => $logisticCode,
                                    ]
                                );
                            }

                            //改变订单发货状态,判断是否要改变订单状态
                            $order = Order::findOne($orderID);
                            $order->updateShipStatus();
                        }
                    } else {
                        OrderLogistics::failureLogistics($product['ordercid'], $deliveryInfo['Reason']);
                    }
                }

            } else {
                //不存在订单物流表中，先进行拆单保存处理
                $arr = [];

                //去查找orderdetail表，找出订单详情的商品信息
                $orderDetailModel = new OrderDetail();
                $orderDetail = $orderDetailModel->findByOrderID($orderID);

                $orderDetailID = ";";
                foreach ($orderDetail as $k => $orderDetailInfo) {
                    $sKeyword = Product::find()->select('lID,sName,sKeyword')->where(['lID' => $orderDetailInfo['ProductID']])->one();
                    $arr[$k]['ProductID'] = $orderDetailInfo['ProductID'];
                    $arr[$k]['sName'] = $orderDetailInfo['sName'];
                    $arr[$k]['sSKU'] = $orderDetailInfo['sSKU'];
                    $arr[$k]['sKeyword'] = $sKeyword['sKeyword'];
                    $arr[$k]['lQuantity'] = $orderDetailInfo['lQuantity'];
                    $orderDetailID .= $orderDetailInfo['lID'] . ";";
                }
                $orderName = Order::findOne($orderID);
                $productInfo = json_encode($arr);

                $array['sName'] = $orderName['sName'] . "-1";
                $array['sProductInfo'] = $productInfo;
                $array['sOrderDetailID'] = $orderDetailID;
                $array['OrderID'] = $orderID;
                $array['ShipID'] = "wuliu";
                $array['SupplierID'] = $orderName->SupplierID;
                $orderLogisticsID = OrderLogistics::addValue($array);

                $product['ordercid'] = $array['sName'];//子订单号
                $product['productInfo'] = $productInfo;//订单商品详情

                $deliveryInfo = $this->arrangeInfo($product, $shippingtemplate, $consignee);//返回快递鸟信息

                if ($deliveryInfo['Success']) {
                    $logisticCode = $deliveryInfo['Order']['LogisticCode'];
                    $shipperCode = $deliveryInfo['Order']['ShipperCode'];

                    //获取订单物流表信息
                    $OrderLogisticsInfo = OrderLogistics::findOne($orderLogisticsID);
                    $sProductInfo = $OrderLogisticsInfo['sProductInfo'];

                    //获取打印面单数据
                    $sExpressOrderInfo = $this->getExpressOrderInfo($shippingtemplate, $consignee, $deliveryInfo, $sProductInfo);

                    if (!empty($deliveryInfo['PrintTemplate'])) {
                        $printTemplate = $deliveryInfo['PrintTemplate'];
                    } else {
                        $printTemplate = "";
                    }

                    //获取快递单号成功，存入物流订单表
                    $orderLogisticsResult = OrderLogistics::modifyLogistics($product['ordercid'], $logisticCode, $shipperCode, $sExpressOrderInfo, $printTemplate);
                    if ($orderLogisticsResult) {
                        //订单详情和expresstrace存储的是ID
                        $expressCompany = ExpressCompany::findOne(['sKdBirdCode' => $shipperCode]);

                        //修改订单详情表物流信息
                        $childOrderID = trim($OrderLogisticsInfo['sOrderDetailID'], ';');
                        $childOrderID = explode(';', $childOrderID);

                        //增加expresstrace表的信息
                        \Yii::$app->expresstrace->poll([
                            'sNo' => $logisticCode,
                            'ExpressCompanyID' => $expressCompany->ID
                        ]);

                        $sendChild = [];//传给子平台的参数

                        foreach ($childOrderID as $orderKey => $orderDetailID) {
                            //推送消息
                            $detail = OrderDetail::findOne($orderDetailID);

                            $sendChild['productInfo'][$orderKey]['productID'] = $detail->ProductID;
                            if ($detail->sSKU) {
                                $sendChild['productInfo'][$orderKey]['sSKU'] = $detail->sSKU;
                            } else {
                                $sendChild['productInfo'][$orderKey]['sSKU'] = "";
                            }
                            $sendChild['productInfo'][$orderKey]['lQuantity'] = $detail->lQuantity;

                            //修改订单详情表物流信息
                            $detail->modifyShip(
                                [
                                    'ShipID' => "wuliu",
                                    'ShipCompanyID' => $expressCompany->ID,
                                    'sShipNo' => $logisticCode,
                                ]
                            );
                        }

                        //改变订单发货状态,判断是否要改变订单状态
                        $order = Order::findOne($orderID);
                        $order->updateShipStatus();
                    }

                } else {
                    OrderLogistics::failureLogistics($product['ordercid'], $deliveryInfo['Reason']);
                }
            }
        }

        //页面的参数
        foreach ($arrOrderID as $orderID) {
            $result = OrderLogistics::selectOrderLogisticsInfo($orderID);
            foreach ($result as $orderInfo) {
                $data[] = $orderInfo;
            }
        }

        $data = $this->renderPartial("getnumber", ['data' => $data]);
        return $this->asJson(['bSuccess' => true, 'data' => $data]);
    }

    /**
     * 整理发送给快递鸟的信息
     * @param $product 商品信息
     * @param $shippingtemplate 模板信息
     * @param $consignee 收货地址
     * 2018.7.6 cgq
     */
    public function arrangeInfo($product, $shippingtemplate, $consignee)
    {
        //发货人信息
        $sender = [];
        //发货人信息
        $sender["Name"] = $shippingtemplate['sShipper'];   //发件人
        $sender["Mobile"] = $shippingtemplate['sMobile'];  //发件人电话
        $sender["ProvinceName"] = $this->getName($shippingtemplate['ProvinceID']);  //省
        $sender["CityName"] = $this->getName($shippingtemplate['CityID']);   //市
        $sender["ExpAreaName"] = $this->getName($shippingtemplate['AreaID']);   //区
        $sender["Address"] = $shippingtemplate['sAddress'];   //详细地址
        if (strpos($sender["Address"], '+')) {
            $sender["Address"] = str_replace("+", "", $sender["Address"]);
        }
        if (strpos($sender["Address"], '#')) {
            $sender["Address"] = str_replace("#", "", $sender["Address"]);
        }
        //邮政、EMS需要邮编，不然报错
        if ($shippingtemplate['sKdBirdCode'] == 'YZPY' || $shippingtemplate['sKdBirdCode'] == 'EMS') {
            $sender["PostCode"] = $shippingtemplate['sPostCode'];
        }

        //收件人信息
        $receiver = [];
        $receiver["Name"] = $consignee['sName'];
        $receiver["Mobile"] = $consignee['sMobile'];
        $receiver["ProvinceName"] = $this->getName($consignee['ProvinceID']);
        $receiver["CityName"] = $this->getName($consignee['CityID']);
        $receiver["ExpAreaName"] = $this->getName($consignee['AreaID']);
        $receiver["Address"] = $consignee['sAddress'];
        if (strpos($receiver["Address"], '+')) {
            $receiver["Address"] = str_replace("+", "", $receiver["Address"]);
        }
        if (strpos($receiver["Address"], '#')) {
            $receiver["Address"] = str_replace("#", "", $receiver["Address"]);
        }
        //邮政、EMS需要邮编，不然报错
        if ($shippingtemplate['sKdBirdCode'] == 'YZPY' || $shippingtemplate['sKdBirdCode'] == 'EMS') {
            $areaResult = Area::findOne($consignee['AreaID']);
            $receiver["PostCode"] = $areaResult['sPostCode'];
        }

        //邮费支付方式:1-现付，2-到付，3-月结，4-第三方支付
        switch ($shippingtemplate['ClearingWayID']) {
            case "new_clearing";
                $shippingtemplate['ClearingWayID'] = -1;
                break;
            case "to_pay";
                $shippingtemplate['ClearingWayID'] = -2;
                break;
            case "monthly_clearing";
                $shippingtemplate['ClearingWayID'] = 3;
                break;
            default:
                $shippingtemplate['ClearingWayID'] = 4;
        }

        return $this->kdBirdApiOrder($product, $shippingtemplate, $sender, $receiver);

    }

    /**
     * 快递鸟接口
     * @param $product 商品信息
     * @param $shippingtemplate 模板信息
     * @param $sender 发件人信息
     * @param $receiver 收件人
     * 2018.7.6 cgq
     */
    public function kdBirdApiOrder($product, $shippingtemplate, $sender, $receiver)
    {
        //电商ID 密钥 登录快递鸟官网 http://www.kdniao.com/login?referrer=%2fUserCenter%2fv2%2fUserHome.aspx 账号laisanjin 密码 laisanjin 查询
        //电商ID
        defined('EBusinessID') or define('EBusinessID', '1304430');

        //电商加密私钥，快递鸟提供，注意保管，不要泄漏
        defined('AppKey') or define('AppKey', 'fc8044e4-7a70-4c2c-86f2-af65cc290b2e');

        //请求url，
        //正式环境地址：http://api.kdniao.com/api/Eorderservice 【正式环境慎用，快递单号需要公司支付的】
        //测试环境地址：http://testapi.kdniao.cc:8081/api/EOrderService
        defined('ReqURL') or define('ReqURL', \Yii::$app->params['KDBirdUrl']);

        $eorder = []; //构造电子面单提交信息 数组key不能更改名字 需和快递鸟接口参数保持一致

        //德邦只支持月结模式【PayType=3】或到付模式【PayType=2】

        $eorder["PayType"] = $shippingtemplate['ClearingWayID'];   //邮费支付方式:1-现付，2-到付，3-月结，4-第三方支付

        if (trim($shippingtemplate['sKdBirdCode']) == 'DBL') {
            $eorder["PayType"] = 3;
        }

        if ($shippingtemplate['sKdBirdCode'] == 'JD') {
            $eorder["ExpType"] = 6;   //快递类型：6 其他销售平台，用京东物流
        } elseif ($shippingtemplate['sKdBirdCode'] == 'SF' || $shippingtemplate['sKdBirdCode'] == 'DBL') {
            $eorder["ExpType"] = $shippingtemplate["ExpressBusinessID"];
        } else {
            $eorder["ExpType"] = 1;   //快递类型：1-标准快件
        }

        $arrNoTemplate = ['YTO', 'HTKY', 'YD', 'JD', 'YZPY'];
        if (in_array($shippingtemplate['sKdBirdCode'], $arrNoTemplate)) {
            $eorder["IsReturnPrintTemplate"] = 0;  //返回电子面单模板：0-不需要；1-需要
        } else {
            $eorder["IsReturnPrintTemplate"] = 1;  //返回电子面单模板：0-不需要；1-需要
        }

        $eorder["ShipperCode"] = $shippingtemplate['sKdBirdCode'];  //快递公司编码
        $eorder["CustomerName"] = $shippingtemplate['sExpressName'];  //快递公司账号
        $eorder["CustomerPwd"] = $shippingtemplate['sExpressPassword']; //快递公司密码
        $eorder["SendSite"] = $shippingtemplate['sExpressSendSite']; //网点名称
        $eorder['MonthCode'] = $shippingtemplate['sExpressCode']; //月结编码
        $eorder["OrderCode"] = $product['ordercid'];  //订单号

        //商品信息
        $productInfo = json_decode($product['productInfo'], true);
        $productName = '<br>';
        $num = 0;
        foreach ($productInfo as $v) {
            $v['sName'] = $v['sKeyword'] ? $v['sKeyword'] : $v['sName'];
            $productName .= $v['sName'] . " " . $v['sSKU'] . "*" . $v['lQuantity'] . ";";
            $num += $v['lQuantity'];
        }

        if (strpos($productName, '+')) {
            $productName = str_replace("+", "", $productName);
        }
        if (strpos($receiver["Address"], '#')) {
            $productName = str_replace("#", "", $productName);
        }

        $commodityOne = [];
        $commodityOne["GoodsName"] = $productName;
        $commodityOne["Goodsquantity"] = $num;
        $commodityOne['GoodsWeight'] = 1;

        $commodity = [];
        $commodity[] = $commodityOne;

        $eorder["Sender"] = $sender; //发货人信息
        $eorder["Receiver"] = $receiver; //收件人信息
        $eorder["Commodity"] = $commodity;

        $jsonParam = json_encode($eorder, JSON_UNESCAPED_UNICODE); //调用电子面单

        $jsonResult = $this->submitEOrder($jsonParam);

        //解析电子面单返回结果
        $result = json_decode($jsonResult, true);
        return $result;

    }

    /**
     * Json方式 调用电子面单接口 lcx
     */
    function submitEOrder($requestData)
    {
        $datas = array(
            'EBusinessID' => EBusinessID,
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, AppKey);
        $result = $this->sendPost(ReqURL, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     * 电商Sign签名生成 lcx
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey)
    {
        return urlencode(base64_encode(md5($data . $appkey)));
    }

    /**
     * 电子面单 post提交数据 lcx
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    function sendPost($url, $datas)
    {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if (empty($url_info['port'])) {
            $url_info['port'] = 80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        $httpheader .= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 获取打印模板信息
     * @param $product 商品信息
     * @param $shippingtemplate 模板信息
     * @param $consignee 收货人地址
     * @param $sProductInfo 商品信息
     * @param $deliveryInfo 接口返回的数据
     * 2018.7.6 cgq
     */
    public function getExpressOrderInfo($shippingtemplate, $consignee, $deliveryInfo, $sProductInfo)
    {
        //打印面单需要的模板信息
        //发货人
        $tempalte["senderName"] = $shippingtemplate['sShipper'];   //发件人
        $tempalte["senderMobile"] = $shippingtemplate['sMobile'];  //发件人电话
        $tempalte["senderProvinceName"] = $this->getName($shippingtemplate['ProvinceID']);  //省
        $tempalte["senderCityName"] = $this->getName($shippingtemplate['CityID']);   //市
        $tempalte["senderExpAreaName"] = $this->getName($shippingtemplate['AreaID']);   //区
        $tempalte["senderAddress"] = $shippingtemplate['sAddress'];   //详细地址

        //收件人
        $tempalte["receiverName"] = $consignee['sName'];
        $tempalte["receiverMobile"] = $consignee['sMobile'];
        $tempalte["receiverProvinceName"] = $this->getName($consignee['ProvinceID']);
        $tempalte["receiverCityName"] = $this->getName($consignee['CityID']);
        $tempalte["receiverExpAreaName"] = $this->getName($consignee['AreaID']);
        $tempalte["receiverAddress"] = $consignee['sAddress'];

        //商品信息
        $productInfo = json_decode($sProductInfo, true);
        $arrData = [];
        foreach ($productInfo as $key => $value) {
            $arrData[$key]['sName'] = $value['sKeyword'] ? $value['sKeyword'] : $value['sName'];
            $arrData[$key]['sSKU'] = $value['sSKU'];
            $arrData[$key]['lQuantity'] = $value['lQuantity'];
        }
        $tempalte['sProductInfo'] = $arrData;

        //接口返回
        $tempalte['OrderCode'] = $deliveryInfo['Order']['OrderCode'];
        $tempalte['ShipperCode'] = $deliveryInfo['Order']['ShipperCode'];
        $tempalte['LogisticCode'] = $deliveryInfo['Order']['LogisticCode'];
        $tempalte['MarkDestination'] = $deliveryInfo['Order']['MarkDestination'];
        $tempalte['OriginCode'] = $deliveryInfo['Order']['OriginCode'];
        $tempalte['OriginName'] = $deliveryInfo['Order']['OriginName'];
        $tempalte['SortingCode'] = $deliveryInfo['Order']['SortingCode'];
        $tempalte['PackageCode'] = $deliveryInfo['Order']['PackageCode'];
        $tempalte['KDNOrderCode'] = $deliveryInfo['Order']['KDNOrderCode'];

        return json_encode($tempalte);
    }

    /**************************************************************
     *
     *  使用特定function对数组中所有元素做处理
     * @param  string &$array 要处理的字符串
     * @param  string $function 要执行的函数
     * @return boolean $apply_to_keys_also     是否也应用到key上
     * @access public
     *
     *************************************************************/
    function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }

            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }

    /**************************************************************
     *
     *  将数组转换为JSON字符串（兼容中文）
     * @param  array $array 要转换的数组
     * @return string      转换得到的json字符串
     * @access public
     *
     *************************************************************/
    function JSON($array)
    {
        arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }

    /**
     * 打印电子面单弹出窗口
     * 2018.7.12 cgq
     */
    public function actionAlertexpressnumber()
    {
        $ids = $_REQUEST['sSelectedID'];
        $arrOrderID = explode(';', $ids);

        //快递公司
        $expressCompany = [];

        //快递单号,子订单号，快递公司
        $data = [];
        //判断是否是同一家物流公司
        foreach ($arrOrderID as $orderIDkey => $orderID) {
            $result = OrderLogistics::getOrderInfo($orderID);
            //先判断是否是已经获取快递单号的
            if ($result[0]['ExpressOrderStatusID'] == 0) {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderID . "未获取快递单号"]);

            } elseif ($result[0]['ExpressOrderStatusID'] == 2) {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderID . "已经打印面单，如需补打请到物流表补打"]);

            } elseif ($result[0]['ExpressOrderStatusID'] == 3) {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $orderID . "不是快递面单"]);

            }

            $expressCompany[$orderIDkey] = $result[0]['sExpressCompany'];

            foreach ($result as $logisticsInfokey => $orderLogisticsInfo) {
                $couriername = ExpressCompany::findOne(['sKdBirdCode' => $orderLogisticsInfo['sExpressCompany']]);
                $data[$orderIDkey][$logisticsInfokey]['sName'] = $orderLogisticsInfo['sName'];
                $data[$orderIDkey][$logisticsInfokey]['sExpressCompany'] = $couriername['sName'];
                $data[$orderIDkey][$logisticsInfokey]['sExpressNo'] = $orderLogisticsInfo['sExpressNo'];
            }
        }

        $arrCount = array_unique($expressCompany);
        if (count($arrCount) >= 2) {
            return $this->asJson(['bSuccess' => false, 'sMsg' => "请选择同一家物流公司"]);
        }

        //获取模板数据
        $expressOrderInfo = [];

        foreach ($arrOrderID as $orderID) {
            $result = OrderLogistics::selectOrderLogisticsInfo($orderID);
            foreach ($result as $k => $v) {
                if ($v['sReturnedTemplate']) {
                    $expressOrderInfo[] = $v['sReturnedTemplate'];
                } else {
                    $expressOrderInfo[] = json_decode($v['sExpressOrderInfo'], true);
                }
            }
        }

        $companyName = $arrCount[0];
        //传入模板文件

        if ($companyName == "HTKY") {//百世
            $html = $this->renderPartial("bs", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "YD") {//韵达
            $html = $this->renderPartial("yd", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "JD") {//京东
            $html = $this->renderPartial("jd", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "ZTO") {//中通
            $html = $this->renderPartial("yz", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "YTO") {//圆通
            $html = $this->renderPartial("yt", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "STO") {//申通
            $html = $this->renderPartial("st", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "YZPY") {//邮政包裹
            $html = $this->renderPartial("yzbg", ['data' => $expressOrderInfo]);
        } else {
            //通用模板
            $html = $this->renderPartial("yz", ['data' => $expressOrderInfo]);
        }

        $expressOrderInfoJson = json_encode($expressOrderInfo);

        $data = $this->renderPartial("print", ['data' => $data, 'html' => $html, 'companyName' => $companyName, 'expressOrderInfoJson' => $expressOrderInfoJson, 'ids' => $ids]);
        return $this->asJson(['bSuccess' => true, 'data' => $data]);
    }

    /**
     * @param id
     * 更改面单状态
     * 2018.7.12 cgq
     */
    public function actionChangestatus()
    {
        $ids = $_REQUEST['id'];
        $arrOrderID = explode(';', $ids);
        foreach ($arrOrderID as $lID) {
            OrderLogistics::changeStatus($lID);
        }
    }

    /**
     * 拆分订单物流弹出窗口
     * @return \yii\web\Response
     * @author hechengcheng
     * @time 2018年7月12日15:07:13
     */
    public function actionSeparateexpress()
    {
        $OrderDetail = OrderDetail::findArrByOrderID($_POST['sSelectedID']);
        $Order = Order::findByID($_POST['sSelectedID']);

        foreach ($OrderDetail as $v) {
            //判断是否存在已发货商品
            if ($v['dShipDate']) {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "该订单存在已发货商品，不可拆分"]);
            }
            //判断是否存在退款中商品
            if ($v['StatusID'] == "refunding") {
                return $this->asJson(['bSuccess' => false, 'sMsg' => "该订单存在退款中商品，不可拆分"]);
            }
        }

        if ($Order->StatusID == "closed") {
            //判断订单是否交易关闭
            return $this->asJson(['bSuccess' => false, 'sMsg' => "该订单交易关闭，不可拆分"]);
        } elseif ($Order->StatusID == "unpaid") {
            //判断订单是否未付款
            return $this->asJson(['bSuccess' => false, 'sMsg' => "该订单未付款，不可拆分"]);
        }

        //弹出窗口
        $data = [];
        $OrderInfo = OrderLogistics::getOrderInfo($_POST['sSelectedID']);

        foreach ($OrderInfo as $value) {
            $data['sProductInfo'][] = json_decode($value['sProductInfo'], true);
        }

        foreach ($OrderDetail as $key => $detail) {
            $product = Product::find()->select('lID,sName,sKeyword')->where(['lID' => $detail['ProductID']])->one();
            $OrderDetail[$key]['sKeyword'] = $product->sKeyword;
        }

        $data['OrderDetail'] = $OrderDetail;

        $view = $this->renderPartial("separateexpress", $data);
        return $this->asJson(['bSuccess' => true, 'data' => $view]);
    }

    /**
     * 发货弹窗
     * @param $ID
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @author cgq
     * @time 2018年7月17日11:32:23
     */
    public function actionAlertship($ID)
    {
        $data = [];
        $data['order'] = Order::findByID($ID);
        $data['arrCompany'] = ExpressCompany::find()->orderBy("sPinYin,sName")->all();

        //判断是否存在退款中的商品
        if ($data['order']['RefundStatusID']) {
            return $this->asJson(['bSuccess' => false, 'sMsg' => "订单号" . $data['order']['sName'] . "存在退款状态"]);
        }

        //判断是否已拆分物流
        $orderLogistics = OrderLogistics::findOne(['OrderID' => $ID]);
        if (!$orderLogistics) {
            //不存在订单物流表中，先进行拆单保存处理
            $arr = [];

            //查找orderdetail表，找出订单详情的商品信息
            $orderDetailModel = new OrderDetail();
            $orderDetail = $orderDetailModel->findByOrderID($ID);
            $orderDetailID = ";";
            foreach ($orderDetail as $k => $orderDetailInfo) {
                $arr[$k]['sName'] = $orderDetailInfo['sName'];
                $arr[$k]['sSKU'] = $orderDetailInfo['sSKU'];
                $arr[$k]['lQuantity'] = $orderDetailInfo['lQuantity'];
                $orderDetailID .= $orderDetailInfo['lID'] . ";";
            }

            $orderName = Order::findOne($ID);
            $productInfo = json_encode($arr);

            $array['sName'] = $orderName['sName'] . "-1";
            $array['sProductInfo'] = $productInfo;
            $array['sOrderDetailID'] = $orderDetailID;
            $array['OrderID'] = $ID;
            $array['SupplierID'] = $orderName['SupplierID'];
            OrderLogistics::addValue($array);
        }

        $OrderInfo = OrderLogistics::getOrderInfo($ID);
        foreach ($OrderInfo as $value) {
            $data['sProductInfo'][] = json_decode($value['sProductInfo'], true);
            $data['lID'][] = $value['lID'];
            $data['sOrderDetailID'][] = $value['sOrderDetailID'];
        }

        $data = $this->renderPartial("splitship", $data);
        return $this->asJson(['bSuccess' => true, 'data' => $data]);
    }

    /**
     * 拆单的发货确定按钮点击
     * @return \yii\web\Response
     * @author cgq
     * @time 2018年7月17日11:32:39
     */
    public function actionSplitship()
    {
        if (\Yii::$app->request->isPost) {
            $arrShip = $_POST['arrShip'];
            foreach ($arrShip as $ship) {
                $expressCompany = ExpressCompany::findOne(['sKdBirdCode' => $ship['CompanyID']]);//快递公司
                //去除首尾空格，保证能正常获取物流信息 by hechengcheng on 2018年11月26日11:47:28
                $expressNo = trim($ship['sShipNo']);
                //判断发货方式是物流还是自配
                if ($ship['ShipID'] == "wuliu") {
                    $return = \Yii::$app->expresstrace->poll([
                        'sNo' => $expressNo,
                        'ExpressCompanyID' => $expressCompany['ID']
                    ]);

                    if ($return === false) {
                        return $this->asJson(['status' => false, 'msg' => '物流信息订阅失败']);
                    }
                } else {
                    //自配操作
                    $ship['CompanyID'] = "";
                    $expressNo = "";
                }
                $array = [];
                $array['sExpressNo'] = $expressNo;
                $array['sExpressCompany'] = $ship['CompanyID'];
                $array['ExpressOrderStatusID'] = 3;
                $array['orderID'] = $ship['lID'];
                $array['type'] = "lID";
                $array['ShipID'] = $ship['ShipID'];
                $array['dShipDate'] = \Yii::$app->formatter->asDatetime(time());

                $result = OrderLogistics::modifyValue($array);
                if ($result) {
                    $orderLogisticsInfo = OrderLogistics::findByID($ship['lID']);
                    $orderInfo = Order::findByID($orderLogisticsInfo['OrderID']);
                    $buyModel = Buyer::findByID($orderInfo->BuyerID);
                    $arrOrderID = [];

                    $arr = [];
                    $num = explode('-', $orderLogisticsInfo->sName);
                    $arr['num'] = $num[1];
                    $arr['sExpressNo'] = $expressNo; //快递单号
                    $arr['sExpressCompany'] = $expressCompany['ID'];//快递公司
                    $arr['ShipID'] = $ship['ShipID'];//发货方式
                    $arr['sName'] = $orderInfo->sName;//订单表的sName

                    $orderDetailID = explode(';', trim($ship['sOrderDetailID'], ';'));
                    foreach ($orderDetailID as $orderKey => $orderDetail) {
                        $detail = OrderDetail::findByID($orderDetail);

                        $arr['productInfo'][$orderKey]['productID'] = $detail->ProductID;
                        if ($detail->sSKU) {
                            $arr['productInfo'][$orderKey]['sSKU'] = $detail->sSKU;
                        } else {
                            $arr['productInfo'][$orderKey]['sSKU'] = "";
                        }
                        //商品的数量要取订单物流表中的数据
                        $orderLogisticsResult = json_decode($orderLogisticsInfo->sProductInfo, true);
                        $arr['productInfo'][$orderKey]['lQuantity'] = $orderLogisticsResult[$orderKey]['lQuantity'];

                        //更新状态
                        $detail->ship([
                            'ShipID' => $ship['ShipID'],
                            'ShipCompanyID' => $expressCompany['ID'],
                            'sShipNo' => $expressNo
                        ]);

                        $arrOrderID[] = $detail['OrderID'];
                    }
                    //更新订单状态
                    $order = Order::findOne($arrOrderID[0]);
                    $order->updateShipStatus();

                }

            }
            return $this->asJson(['status' => true, 'msg' => '发货成功']);
        }
    }

    //修改物流弹窗
    public function actionAlertmodifyship($ID)
    {
        $data = [];
        $data['order'] = OrderLogistics::getOrderInfo($ID);
        $data['arrCompany'] = ExpressCompany::find()->all();

        //判断该订单是否是快递鸟接口获取的
        if ($data['order'][0]['ExpressOrderStatusID'] != 3) {
            return $this->asJson(['bSuccess' => false, 'sMsg' => "该订单是获取快递单号得到的快递单号，不可修改"]);
        }

        $data = $this->renderPartial("modifyship", $data);
        return $this->asJson(['bSuccess' => true, 'data' => $data]);
    }

    //修改物流
    public function actionModifyship()
    {
        $arrShip = $_POST['arrShip'];
        foreach ($arrShip as $v) {
            $expressCompany = ExpressCompany::findOne(['ID' => $v['CompanyID']]);//快递公司
            $orderLogisticsModel = OrderLogistics::findByID($v['lID']);
            $arrOrderDetailID = explode(';', trim($orderLogisticsModel->sOrderDetailID, ";")); //订单详情数组
            if ($orderLogisticsModel->sExpressNo != $v['sShipNo'] || $orderLogisticsModel->sExpressCompany != $v['CompanyID'] || $orderLogisticsModel->ShipID != $v['ShipID']) {
                //有对快递单号或者物流进行修改
                //去除首尾空格，保证能正常获取物流信息 by hechengcheng on 2018年11月26日11:47:28
                $sShipNo = trim($v['sShipNo']);
                if ($v['ShipID'] == "wuliu") {
                    $return = \Yii::$app->expresstrace->poll([
                        'sNo' => $sShipNo,
                        'ExpressCompanyID' => $expressCompany['ID']
                    ]);

                    if ($return === false) {
                        return $this->asJson(['status' => false, 'msg' => '物流信息订阅失败']);
                    }

                } else {
                    $sShipNo = "";
                    $expressCompany['ID'] = "";
                }

                foreach ($arrOrderDetailID as $orderKey => $orderDetailID) {
                    $detail = OrderDetail::findOne($orderDetailID);

                    //修改订单详情表
                    $detail->modifyShip(
                        [
                            'ShipID' => $v['ShipID'],
                            'ShipCompanyID' => $expressCompany['ID'],
                            'sShipNo' => $sShipNo,
                        ]
                    );
                }

                //对订单物流表进行修改
                $array = [];
                $array['sExpressNo'] = $sShipNo;
                $array['sExpressCompany'] = $v['CompanyID'];
                $array['ExpressOrderStatusID'] = 3;
                $array['orderID'] = $v['lID'];
                $array['type'] = "lID";
                $array['ShipID'] = $v['ShipID'];
                $array['dShipDate'] = \Yii::$app->formatter->asDatetime(time());
                $result = OrderLogistics::modifyValue($array);
            }
        }
        return $this->asJson(['status' => true, 'message' => '修改物流成功']);
    }

    /**
     * 一键拆分订单物流弹出窗口
     * @return \yii\web\Response
     * @author hechengcheng
     * @time 2018年7月20日10:11:50
     */
    public function actionQuickseparate()
    {
        $arrData = $this->listBatch();
        $arrOrderID = ArrayHelper::getColumn($arrData, 'lID');
        $data = [];

        //检验部分
        foreach ($arrData as $data) {
            $order = Order::findByID($data['lID']);
            $orderDetail = $order->arrDetail;

            if ($order->StatusID == "closed") {
                //判断订单是否交易关闭
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单" . $data['sName'] . "交易关闭，不可拆分"]);
            } elseif ($order->StatusID == "unpaid") {
                //判断订单是否未付款
                return $this->asJson(['bSuccess' => false, 'sMsg' => "订单" . $data['sName'] . "未付款，不可拆分"]);
            }

            foreach ($orderDetail as $detail) {
                //判断是否存在已发货商品
                if ($detail->dShipDate) {
                    return $this->asJson(['bSuccess' => false, 'sMsg' => "订单" . $data['sName'] . "存在已发货商品，不可拆分"]);
                }
                //判断是否存在退款中商品
                if ($detail->StatusID == "refunding") {
                    return $this->asJson(['bSuccess' => false, 'sMsg' => "订单" . $data['sName'] . "存在退款中商品，不可拆分"]);
                }
            }
        }

        //查询订单商品信息，sSKU存在空和null两种情况，无法在数据库处理
        $allOrderDetail = OrderDetail::findAll(['OrderID' => $arrOrderID]);

        $productInfo = [];
        $temp = [];
        foreach ($allOrderDetail as $detail) {
            //拼接字符串，组成一维数组
            $temp[] = $detail->ProductID . ',' . $detail->sName . ',' . $detail->sSKU;
            //去重
            $temp = array_unique($temp);

            foreach ($temp as $key => $value) {
                $array = explode(',', $value);
                $productInfo[$key]['ProductID'] = $array[0];
                $productInfo[$key]['sName'] = $array[1];
                $productInfo[$key]['sSKU'] = $array[2];
            }
        }

        $data['productInfo'] = $productInfo;
        $data['OrderID'] = $arrOrderID;

        $view = $this->renderPartial("quickseparate", $data);
        return $this->asJson(['bSuccess' => true, 'data' => $view]);
    }

    /**
     * 退款界面
     * @return string
     * @throws UserException
     * @author hechengcheng
     * @time 2019/7/17 8:44
     */
    public function actionRefund()
    {
        $data = [];
        $data['data'] = Order::findByID($_GET['ID']);
        if (!$data['data']) {
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "您查看的对象不存在。"));
        } elseif ($data['data']->StatusID == 'success') {
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "订单已交易成功，不可申请退款。"));
        } elseif ($data['data']->StatusID == 'closed') {
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "订单已关闭，不可申请退款。"));
        }

        $data['arrSysDetailObject'] = SysObject::find()->where(['ParentID' => 'Shop/Order'])->all();

        return $this->render('refund', $data);
    }

    /**
     * 退款申请
     * @return \yii\web\Response
     * @author hechengcheng
     * @time 2019/7/17 14:08
     */
    public function actionRefundapply()
    {
        $orderDetail = OrderDetail::find()->where(['OrderID' => $_POST['orderID']])->all();

        $fRefundReal = '';
        $fRefundProduct = '';
        if (!$orderDetail->dShipDate) {
            if (!empty($orderDetail->sShipNo)) {
                return $this->asJson(['status' => false, 'msg' => '该商品已发货，请重新提交退款申请']);
            }
            $fRefundProduct = $orderDetail->fTotal;

            /* 根据商品价格占订单中同类运费模板商品总价比例退还运费 */
            $fTotalPrice = \Yii::$app->orderdetail->countProductPrice($orderDetail->OrderID, $orderDetail->ShipTemplateID);
            $fTotalShip = $orderDetail->fShip * ($orderDetail->fTotal / $fTotalPrice);
            $fRefundReal = $fTotalShip + $fRefundProduct;
        }

        $Refund = \Yii::$app->refund->saveRefund([
            'TypeID' => 'onlymoney',
            'sReason' => $_POST['sReason'],
            'OrderDetailID' => $orderDetail[0]->lID,
            'fRefundApply' => $_POST['money'],
            'sExplain' => $_POST['sReason'],
            'imgList' => $_POST['RefundPic'],
            'lItemTotal' => $_POST['lTotalNum'],
            'lRefundItem' => $_POST['lRefundNum'],
            'fRefundReal' => $fRefundReal,
            'fRefundProduct' => $fRefundProduct,
        ]);

        return $this->asJson(['status' => true, 'msg' => '退款申请成功']);
    }
}