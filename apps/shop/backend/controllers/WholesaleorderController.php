<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\libs\StrTool;
use myerm\backend\common\libs\SystemTime;
use myerm\backend\system\models\SysList;
use myerm\backend\system\models\SysObject;
use myerm\common\models\ExpressCompany;
use myerm\shop\backend\models\Order;
use myerm\shop\backend\models\Refund;
use myerm\shop\common\models\Area;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\OrderAddress;
use myerm\shop\common\models\OrderDetail;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\Seller;
use yii\base\UserException;

/**
 * 订单管理
 */
class WholesaleorderController extends BackendController
{

    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];

        $list = [];



        $list['ID'] = 'paid';
        $list['sName'] = '待发货';
        $list['sKey'] = 'Main.Shop.WholesaleOrder.List.Supplier';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleOrder.List.Wholesale&sTabID=paid&sExtra=paid';
        $data['arrList'][] = $list;

        $list['ID'] = 'delivered';
        $list['sName'] = '已发货';
        $list['sKey'] = 'Main.Shop.WholesaleOrder.List.Supplier';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleOrder.List.Wholesale&sTabID=delivered&sExtra=delivered';
        $data['arrList'][] = $list;

        $list['ID'] = 'success';
        $list['sName'] = '已完成';
        $list['sKey'] = 'Main.Shop.WholesaleOrder.List.Supplier';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleOrder.List.Wholesale&sTabID=success&sExtra=success';
        $data['arrList'][] = $list;

        $list['ID'] = 'closed';
        $list['sName'] = '已关闭';
        $list['sKey'] = 'Main.Shop.WholesaleOrder.List.Supplier';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleOrder.List.Wholesale&sTabID=closed&sExtra=closed';
        $data['arrList'][] = $list;

        $list['ID'] = 'refund';
        $list['sName'] = '退款中';
        $list['sKey'] = 'Main.Shop.WholesaleOrder.List.Supplier';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleOrder.List.Wholesale&sTabID=refund&sExtra=refund';
        $data['arrList'][] = $list;

        $list['ID'] = 'all';
        $list['sName'] = '全部';
        $list['sKey'] = 'Main.Shop.WholesaleOrder.List.Supplier';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.WholesaleOrder.List.Wholesale&sTabID=all&sExtra=all';
        $data['arrList'][] = $list;
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


        $arrDetailData = [];
        foreach ($arrOrderDetail as $orderDetail) {
            $arrDetailData[$orderDetail->OrderID]['sProductName'][] = "<a href='javascript:;'onclick=\"parent.addTab($(this).text(), '/shop/product/view?ID=" . $orderDetail->ProductID . "')\">" . $orderDetail->sName . "</a>";
            $arrDetailData[$orderDetail->OrderID]['sSKU'][] = $orderDetail->sSKU;
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
        $arrSpecialCol = [];

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

            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => $this->BuyerID,
                'sSQL' => "BuyerID='" . $this->BuyerID . "'"
            ];
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
            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => $this->BuyerID,
                'sSQL' => "BuyerID='" . $this->BuyerID . "'"
            ];
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
                    \Yii::trace($field->sFieldAs . '---' . $sValue);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arrHeader[$listKey + $i - 1 - $lAddress] . $lRow, $sValue, \PHPExcel_Cell_DataType::TYPE_STRING);

                }

            }
        }

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(28);


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
        if (\Yii::$app->backendsession->SysRoleID == 1) {

        } elseif ($this->BuyerID) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => $this->BuyerID,
                'sSQL' => "BuyerID='" . $this->BuyerID . "'"
            ];
        } else {
            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => '-1',
                'sSQL' => "BuyerID=-1"
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


        return parent::listDataConfig($sysList, $arrConfig);
    }

    public function actionView()
    {
        $data = [];
        $data['data'] = Order::findByID($_GET['ID']);
        if (!$data['data']) {
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "您查看的对象不存在。"));
        }
        $data['BuyerName'] = Buyer::getsName($data['data']->BuyerID);
        $data['arrSysDetailObject'] = SysObject::find()->where(['ParentID' => 'Shop/Order'])->all();

        return $this->render('detail', $data);
    }

    public function getListButton()
    {
        $data = [];
        return $this->renderPartial("listbutton", $data);
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
        } elseif ($data['data']->RefundStatusID) {
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "订单已申请退款，无需重复申请。"));
        }


        $arrStatus = [
            'unpaid' => '未付款',
            'paid' => '已确认',
            'closed' => '订单关闭',
            'delivered' => '已发货',
            'success' => '交易成功',
            'exception' => '付款异常'
        ];
        $data['data']->StatusID = $arrStatus[$data['data']->StatusID];
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
        $order = Order::findOne($_POST['orderID']);
        if (!empty($order->RefundStatusID)) {
            //已申请退款
            return $this->asJson(['status' => false, 'msg' => '该商品已申请退款，请勿重复申请！']);
        } elseif ($order->StatusID == 'paid') {
            //未发货 全额退款
            $res = Refund::saveRefund([
                'TypeID' => 'onlymoney',
                'sReason' => '未发货退款',
                'OrderID' => $order->lID,
                'SupplierID' => $order->SupplierID,
                'fBuyerPaid' => $order->fBuyerPaid,
                'fRefundApply' => $order->fBuyerPaid,
                'sExplain' => '未发货退款',
                'imgList' => '',
                'lItemTotal' => 1,
                'lRefundItem' => 1,
                'fRefundReal' => $order->fBuyerPaid,
                'fRefundProduct' => $order->fBuyerPaid,
                'BuyerID' => $this->BuyerID,
            ]);
            if ($res) {
                //订单调整为退款中
                $order->RefundStatusID = 'refunding';
                $order->save();
            }
        } elseif ($order->StatusID == 'delivered') {
            //如果该订单已发货，则需要完善退款相关信息
            if (!empty($_POST['sShipNo'])) {
                $TypeID = 'moneyandproduct';
            } else {
                $TypeID = 'onlymoney';
            }
            if (empty($_POST['fMoney']) || $_POST['fMoney'] < 0) {
                return $this->asJson(['status' => false, 'msg' => '请填写退款金额']);
            }
            if ($_POST['fMoney'] > $order->fBuyerPaid) {
                return $this->asJson(['status' => false, 'msg' => '退款金额不得大于付款金额！']);
            }
            $res = Refund::saveRefund([
                'TypeID' => $TypeID,
                'sReason' => $_POST['sReason'],
                'OrderID' => $order->lID,
                'SupplierID' => $order->SupplierID,
                'fBuyerPaid' => $order->fBuyerPaid,
                'fRefundApply' => $_POST['fMoney'],
                'sExplain' => $_POST['sReason'],
                'imgList' => $_POST['sRefundPic'],
                'lItemTotal' => $_POST['lTotalNum'],
                'lRefundItem' => $_POST['lRefundNum'],
                'fRefundReal' => $_POST['fMoney'],
                'fRefundProduct' => $_POST['fMoney'],
                'BuyerID' => $this->BuyerID,
            ]);
            if ($res) {
                //订单调整为退款中
                $order->RefundStatusID = 'refunding';
                $order->save();
            }
        } else {
            return $this->asJson(['status' => false, 'msg' => '该订单已完成交易，不支持退款！']);
        }

        return $this->asJson(['status' => true, 'msg' => '退款申请成功']);
    }
}