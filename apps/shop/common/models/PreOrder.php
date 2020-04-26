<?php

namespace myerm\shop\common\models;

use myerm\shop\backend\models\Order;
use myerm\shop\backend\models\OrderAddress;
use myerm\shop\backend\models\OrderDetail;

/**
 * 预下单
 */
class PreOrder extends ShopModel
{

    /**
     * 关联库存变动记录
     * @return \yii\db\ActiveQuery
     * @author hechengcheng
     * @time 2019/5/13 15:08
     */
    public function getProductStockChange()
    {
        return ProductStockChange::find()->where(['OrderID' => $this->lID])->all();
    }

    /**
     * 获取商品信息
     * @return \yii\db\ActiveQuery
     * @author hechengcheng
     * @time 2019/5/13 15:08
     */
    public function getProduct($ProductID)
    {
        return \myerm\shop\backend\models\Product::findOne($ProductID);
    }


    /**
     * 生成订单号，长格式的时间+随机码
     */
    public static function makeOrderCode()
    {
        return str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time())) . rand(10000, 99999);
    }

    /**
     * 确认渠道订单
     * @author hechengcheng
     * @time 2019/5/13 15:30
     */
    public function confirmOrder()
    {
        if ($this->bClosed != 0) {
            return ['bSuccess' => false, 'sMsg' => '该订单已确认，无需重复提交'];
        }
        //判断渠道款是否充足
        $buyer = Buyer::findByID($this->BuyerID);
        if ($buyer && $buyer->fBalance >= $this->fTotal) {
            //根据预备订单，商品库存变化记录 生成真订单
            //获取购买商品
            $arrProduct = $this->productStockChange;
            foreach ($arrProduct as $change) {
                $product = $this->getProduct($change->ProductID);
                if ($product->lStock < $change->lChange) {
                    return ['bSuccess' => false, 'sMsg' => '订单商品存在库存不足'];
                }
                //扣除库存
                $product->lStock -= $change->lChange;
                $product->lSale += $change->lChange;
                $product->save();
            }
            //1生成真订单//1.1 Order
            $order = new Order();
            $order->sName = $this->sName;
            $order->sClientSN = $this->sOrderNo;
            $order->dNewDate = \Yii::$app->formatter->asDatetime(time());
            $order->dEditDate = \Yii::$app->formatter->asDatetime(time());
            $order->SupplierID = $product->SupplierID;
            $order->BuyerID = $this->BuyerID;
            $order->StatusID = Order::STATUS_PAID;
            $order->fShip = $this->fShip;
            $order->fBuyerProductPaid = $this->fTotal;
            $order->fSupplierProductIncome = $this->fTotal;
            $order->fProfit = 0;
            $order->fBuyerPaid = $this->fTotal;
            $order->fSupplierIncome = $this->fTotal;
            $order->sMessage = $this->sMessage;
            $order->PurchaseID = $this->BuyerID;
            $order->WholesalerID = $this->WholesalerID;
            $order->TypeID = 'wholesale';
            if ($this->OrderType) {
                $order->TypeID = 'pay';
            }
            $order->save();
            //1.2 OrderAddress
            $orderAddress = new OrderAddress();
            $orderAddress->sName = $this->sReceiverName;
            $orderAddress->dNewDate = \Yii::$app->formatter->asDatetime(time());
            $orderAddress->dEditDate = \Yii::$app->formatter->asDatetime(time());
            $orderAddress->OrderID = $order->lID;
            $orderAddress->ProvinceID = $this->ProvinceID;
            $orderAddress->CityID = $this->CityID;
            $orderAddress->AreaID = $this->AreaID;
            $orderAddress->sAddress = $this->sAddress;
            $orderAddress->sMobile = $this->sMobile;
            $orderAddress->save();
            $order->OrderAddressID = $orderAddress->lID;
            $order->save();


            //1.3 OrderDetail
            foreach ($arrProduct as $change) {
                $product = $this->getProduct($change->ProductID);
                $orderDetail = new OrderDetail();
                $orderDetail->sName = $product->sName;
                $orderDetail->OrderID = $order->lID;
                $orderDetail->dNewDate = \Yii::$app->formatter->asDatetime(time());
                $orderDetail->dEditDate = \Yii::$app->formatter->asDatetime(time());
                $orderDetail->BuyerID = $this->BuyerID;
                $orderDetail->ProductID = $product->lID;
                $orderDetail->ShipTemplateID = $product->ShipTemplateID;
                $orderDetail->sPic = $product->sMasterPic;
                $orderDetail->lQuantity = $change->lChange;
                $orderDetail->fBuyerSalePrice = $product->fSupplierPrice;
                $orderDetail->fBuyerPrice = $product->fSupplierPrice;
                $orderDetail->fSupplierPrice = $product->fSupplierPrice;
                $orderDetail->fBuyerPaidTotal = $product->fSupplierPrice;
                $orderDetail->fSupplierIncomeTotal = $product->fSupplierPrice;
                $orderDetail->save();
            }
            //2 扣除渠道款
            $param = [
                'sName' => '订单' . $order->sName . '扣款',//交易流水说明文本
                'fMoney' => -$this->fTotal, //变动金额，可以为正，可以为负
                'MemberID' => $this->BuyerID, //渠道商或供应商ID
                'RoleType' => 'buyer', //身份标识，2种，buyer为渠道商，supplier为供应商
                'TypeID' => 'buyer_buyproduct', //交易类型，5种 @see DealFlow::$TypeID,不为空
                'DealID' => $order->lID //对应流水类型的ID,例子 如果是下单，就订单ID；充值 充值流水ID
            ];
            $dealFlow = new DealFlow();
            $dealFlow->change($param);
            $this->bClosed = 1;
            $this->save();

            return ['bSuccess' => true, 'sMsg' => '提交成功'];
        } else {
            return ['bSuccess' => false, 'sMsg' => '充值款不足，请及时充值'];
        }
    }

    /*
     * 商品验证
     */
    public static function checkImportRecord($arr)
    {
        $arr['status'] = 1;
        if (empty($arr['orderName'])) {
            $arr['sRemark'] .= "订单编号不能为空;";
            $arr['status'] = 0;
        }
        if ($arr['lNum'] < 1) {
            $arr['sRemark'] .= "商品购买数量必须大于0;";
            $arr['status'] = 0;
        }
        if (empty($arr['sName'])) {
            $arr['sRemark'] .= "收件人不能为空;";
            $arr['status'] = 0;
        }
        if (empty($arr['sMobile'])) {
            $arr['sRemark'] .= "收件人手机号码不能为空;";
            $arr['status'] = 0;
        }
        if (empty($arr['productName'])) {
            $arr['sRemark'] .= "商品名称不能为空;";
            $arr['status'] = 0;
        }
        if (empty($arr['sAddress'])) {
            $arr['sRemark'] .= "详细地址不能为空;";
            $arr['status'] = 0;
        }
        $product = Product:: findOne($arr['PrductID']);

        //判断商品是否存在
        if (!$product) {
            $arr['sRemark'] .= "有链商品ID错误;";
            $arr['status'] = 0;
        } else {
            $arr['cloudProductName'] = $product->sName;
            $arr['ShipTemplateID'] = $product->ShipTemplateID;
            $arr['sStandard'] = $product->sStandard;;//规格
            $arr['sTaste'] = $product->sTaste;//口味
            $arr['fPrice'] = $product->fSupplierPrice;//单价

            //判断商品库存
            if ($product->lStock < $arr['lNum']) {
                $arr['sRemark'] .= "该商品库存不足;";
                $arr['status'] = 0;
            }
            //判断该商品是否上架
            if (!$product->bSale || $product->bDel) {
                $arr['sRemark'] .= "该商品暂未上架;";
                $arr['status'] = 0;
            }
            //判断是团购商品
            if ($product->bGroup) {
                $arr['bGroupProdcut'] = 1;
                $arr['lGroupNum'] = $product->lGroupNum;
            }
            //判断是否大宗
            if ($product->lBulk && $arr['lNum'] < $product->lBulkNo) {
                $arr['sRemark'] .= "大宗商品，不满足发货件数;";
                $arr['status'] = 0;
            }
        }

        //判断收货地址信息是否正确
        $address = self::getOrderAddress($arr['sProvince'], $arr['sCity'], $arr['sArea'], $arr['sAddress']);
        if ($address['status']) {
            $arr['sAddressFinal'] = $address['address'];
            $arr['ProvinceID'] = $address['ProvinceID'];
            $arr['CityID'] = $address['CityID'];
            $arr['AreaID'] = $address['AreaID'];
        } else {
            $arr['sRemark'] .= '地址填写错误；';
            $arr['status'] = 0;
        }
        if ($arr['status'] != 0) {
            $arr['status'] = 1;
            $arr['sRemark'] = '导入成功';
        }
        return $arr;
    }


    /*
        * 地址解析
        */
    public static function getOrderAddress($sProvince, $sCity, $sArea, $sAddress)
    {
        if (empty($sProvince) || empty($sCity)) {
            return self::resolveOrderAddress($sAddress);
        } else {
            $data = [];
            $data['status'] = false;
            $data['ProvinceID'] = '';
            $data['CityID'] = '';
            $data['AreaID'] = '';
            $data['address'] = $sAddress;
            $arrProvince = \Yii::$app->cache->get('yl_arrProvince');
            //将省市区 基本不会变更存储于缓存中
            //省
            if ($arrProvince == false) {
                $arrProvince = Area::find()->select('ID,sName')->where(['sType' => 'Province'])->all();
                \Yii::$app->cache->set('yl_arrProvince', $arrProvince);
            }
            //市
            $arrCity = \Yii::$app->cache->get('yl_arrCity');
            //将省市区 基本不会变更存储于缓存中
            if ($arrCity == false) {
                $arrCity = Area::find()->select('ID,sName,UpID')->where(['sType' => 'City'])->all();
                \Yii::$app->cache->set('yl_arrCity', $arrCity);
            }
            //区
            $arrArea = \Yii::$app->cache->get('yl_arrArea');
            //将省市区 基本不会变更存储于缓存中
            if ($arrArea == false) {
                $arrArea = Area::find()->select('ID,sName,UpID')->where(['sType' => 'Area'])->all();
                \Yii::$app->cache->set('yl_arrArea', $arrArea);
            }
            //省
            foreach ($arrProvince as $province) {
                $provinceName = $province->sName;
                $provinceNameL = str_replace('省', '', $provinceName);
                $provinceName = $provinceNameL . '省';
                if ($sProvince == $provinceNameL || $sProvince == $provinceName) {
                    $data['ProvinceID'] = $province->ID;
                    break;
                }
            }

            //市
            foreach ($arrCity as $city) {
                if ($city->UpID == $data['ProvinceID']) {
                    if (substr($city->sName, 0, 6) == substr($sCity, 0, 6)) {
                        $data['CityID'] = $city->ID;
                        break;
                    }
                }
            }

            //区
            foreach ($arrArea as $area) {
                if ($area->UpID == $data['CityID']) {
                    $areaName = $area->sName;
                    $areaNameL = str_replace('区', '', $areaName);
                    $areaName = $areaNameL . '区';
                    if ($sArea == $areaName || $sArea == $areaNameL) {
                        $data['AreaID'] = $area->ID;
                        break;
                    }
                }
            }
            if (empty($data['ProvinceID']) || empty($data['CityID'])) {
                return $data;
            } else {
                $data['status'] = true;
                return $data;
            }
        }
    }

    /*
     * 地址解析
     */
    public static function resolveOrderAddress($address)
    {
        $data = [];
        $data['status'] = false;
        $data['ProvinceID'] = '';
        $data['CityID'] = '';
        $data['AreaID'] = '';
        $data['address'] = '';
        if (empty($address)) {
            return $data;
        }


        $arrSuperCity = ['上海', '北京', '天津', '重庆'];
        $arrProvince = \Yii::$app->cache->get('yl_arrProvince');
        //将省市区 基本不会变更存储于缓存中
        //省
        if ($arrProvince == false) {
            $arrProvince = Area::find()->select('ID,sName')->where(['sType' => 'Province'])->all();
            \Yii::$app->cache->set('yl_arrProvince', $arrProvince);
        }
        //市
        $arrCity = \Yii::$app->cache->get('yl_arrCity');
        //将省市区 基本不会变更存储于缓存中
        if ($arrCity == false) {
            $arrCity = Area::find()->select('ID,sName,UpID')->where(['sType' => 'City'])->all();
            \Yii::$app->cache->set('yl_arrCity', $arrCity);
        }
        //区
        $arrArea = \Yii::$app->cache->get('yl_arrArea');
        //将省市区 基本不会变更存储于缓存中
        if ($arrArea == false) {
            $arrArea = Area::find()->select('ID,sName,UpID')->where(['sType' => 'Area'])->all();
            \Yii::$app->cache->set('yl_arrArea', $arrArea);
        }
        //省
        foreach ($arrProvince as $province) {
            $provinceName = $province->sName;
            $provinceNameL = str_replace('省', '', $provinceName);
            $provinceName = $provinceNameL . '省';
            if (stristr($address, $provinceName)) {
                $data['ProvinceID'] = $province->ID;
                if (!in_array($province->sName, $arrSuperCity)) {
                    $address = str_replace($provinceName, '', $address);
                }
                break;
            } elseif (stristr($address, $provinceNameL)) {
                $data['ProvinceID'] = $province->ID;
                if (!in_array($province->sName, $arrSuperCity)) {
                    $address = str_replace($provinceNameL, '', $address);
                }
                break;
            }
        }

        //市
        foreach ($arrCity as $city) {
            if ($city->UpID == $data['ProvinceID']) {
                $cityName = $city->sName;
                $cityNameL = str_replace('市', '', $cityName);
                $cityName = $cityNameL . '市';
                if (stristr($address, $cityName)) {
                    $data['CityID'] = $city->ID;
                    $address = str_replace($cityName, '', $address);
                    break;
                } elseif (stristr($address, $cityNameL)) {
                    $data['CityID'] = $city->ID;
                    $address = str_replace($cityNameL, '', $address);
                    break;
                }
            }
        }

        //区
        foreach ($arrArea as $area) {
            if ($area->UpID == $data['CityID']) {
                $areaName = $area->sName;
                $areaNameL = str_replace('区', '', $areaName);
                $areaName = $areaNameL . '区';
                if (stristr($address, $areaName)) {
                    $data['AreaID'] = $area->ID;
                    $address = str_replace($areaName, '', $address);
                    break;
                } elseif (stristr($address, $areaNameL)) {
                    $data['AreaID'] = $area->ID;
                    $address = str_replace($areaNameL, '', $address);
                    break;
                }
            }
        }
        $data['address'] = $address;
        if (empty($data['ProvinceID']) || empty($data['CityID'])) {
            return $data;
        } else {
            $data['status'] = true;
            return $data;
        }
    }

    /**
     * 提交预订单
     */
    public static function wholesaleOrderSubmit($arrPostData)
    {
        //生成预订单
        $preOrder = new Product();
        $preOrder->sName = self::makeOrderCode();
        $preOrder->sOrderNo = $arrPostData['sNo'];
        $preOrder->dNewDate = date('Y-m-d, H:i:s');
        $preOrder->sReceiverName = $arrPostData['name'];
        $preOrder->sProvince = $arrPostData['province'];
        $preOrder->sCity = $arrPostData['city'];
        $preOrder->sArea = $arrPostData['area'];
        $preOrder->sAddress = $arrPostData['address'];
        $preOrder->sMobile = $arrPostData['phone'];
        $preOrder->BuyerID = $arrPostData['buyerID'];
        $preOrder->WholesalerID = $arrPostData['WholesalerID'];
        $preOrder->sMessage = $arrPostData['message'];
        $preOrder->fTotal = $arrPostData['fTotal'];
        $preOrder->fShip = $arrPostData['fShip'];
        $preOrder->ProvinceID = $arrPostData['ProvinceID'];
        $preOrder->CityID = $arrPostData['CityID'];
        $preOrder->AreaID = $arrPostData['AreaID'];
        $preOrder->fWholesale = $arrPostData['fWholesale'];
        $preOrder->save();

        //预订单详情
        foreach ($arrPostData['arrProduct'] as $product) {
            $productStockChange = new ProductStockChange();
            $productStockChange->sName = $preOrder->sName;
            $productStockChange->dNewDate = \Yii::$app->formatter->asDatetime(time());
            $productStockChange->dEditDate = $productStockChange->dNewDate;
            $productStockChange->BuyerID = $preOrder->BuyerID;
            $productStockChange->OrderID = $preOrder->lID;
            $productStockChange->ProductID = $product['ProductID'];
            $productStockChange->lChange = $product['lQuantity'];
            $productStockChange->save();
        }
    }

    /*
     * 提交到预订单
     */
    public static function savePrepOrder($sSelectedID, $BuyerID)
    {
        if ($sSelectedID == 'all') {
            //全选
            $arrImport = Import::find()->where(['and', ['buyerID' => $BuyerID], ['status' => 1]])->all();
        } else {
            $sSelectedID = explode(';', $sSelectedID);
            $arrImport = Import::find()->where(['and', ['lID' => $sSelectedID], ['status' => 1]])->all();
        }

        //遍历分组
        $arrRes = [];
        foreach ($arrImport as $import) {
            //根据运费模板，订单编号分组
            $key = $import->orderName . '_' . $import->ShipTemplateID;
            if (!$arrRes[$key]['detail']) {
                $arrRes[$key]['ShipTemplateID'] = $import->ShipTemplateID;//运费模板
                $arrRes[$key]['orderName'] = $import->orderName;//订单编号
                $arrRes[$key]['ProvinceID'] = $import->ProvinceID;//省
                $arrRes[$key]['CityID'] = $import->CityID;//市
                $arrRes[$key]['AreaID'] = $import->AreaID;//区
                $arrRes[$key]['sAddress'] = $import->sAddress;//详细地址
                $arrRes[$key]['sAddressFinal'] = $import->sAddressFinal;//解析后详细地址
                $arrRes[$key]['sProvince'] = $import->sProvince;//详细地址
                $arrRes[$key]['sCity'] = $import->sCity;//详细地址
                $arrRes[$key]['sArea'] = $import->sArea;//详细地址
                $arrRes[$key]['lNum'] = $import->lNum;//购买数量
                $arrRes[$key]['Weight'] = $import->lNum * $import->lWeight;//商品宗重量
                $arrRes[$key]['fTotalMoney'] = $import->lNum * $import->fPrice;//单品总价
                $arrRes[$key]['PrductID'] = $import->PrductID;//有链商品ID
                //收件人信息
                $arrRes[$key]['sName'] = $import->sName;//收件人
                $arrRes[$key]['sMobile'] = $import->sMobile;//收件人手机号
                $arrRes[$key]['sContent'] = $import->sContent;//备注
                $arrRes[$key]['detail'][0] = $import;

            } else {
                $arrRes[$key]['lNum'] += $import->lNum;//购买数量
                $arrRes[$key]['Weight'] += $import->lNum * $import->lWeight;//商品宗重量
                $arrRes[$key]['fTotalMoney'] += $import->lNum * $import->fPrice;//单品总价
                array_push($arrRes[$key]['detail'], $import);
            }
        }
        //计算运费相关信息，如果有默认地址计算运费  //否则返回运费相关信息
        $return = true;
        foreach ($arrRes as $key => $brand) {
            $param = [
                'CityID' => $brand['CityID'],
                'ShipTemplateID' => $brand['ShipTemplateID'],
                'Number' => $brand['lNum'],
                'fTotalMoney' => number_format($brand['fTotalMoney'], 2),
                'Weight' => $brand['Weight']
            ];

            $ship = \Yii::$app->shiptemplate->computeShip($param);
            if ($ship['status'] == -1) {
                $brand['detail'][0]->status = 0;
                $brand['detail'][0]->sRemark = $brand['detail'][0]->sProvince . '地区不发货';
                $brand['detail'][0]->save();
                $return = false;
                continue;
            }

            $fShipMoney = $ship['fShipMoney'];
            //生成预订单
            $preOrder = new PreOrder();

            $preOrder->sName = self::makeOrderCode();
            $preOrder->sOrderNo = $brand['orderName'];
            $preOrder->dNewDate = date('Y-m-d, H:i:s');
            $preOrder->sReceiverName = $brand['sName'];//收件人
            $preOrder->sMobile = $brand['sMobile'];//收件人手机
            $preOrder->sProvince = $brand['sProvince'];//省
            $preOrder->sCity = $brand['sCity'];//市
            $preOrder->sArea = $brand['sArea'];//区
            $preOrder->sAddress = $brand['sAddress'];//详细地址
            $preOrder->sAddressFinal = $brand['sAddressFinal'];//解析后详细地址
            $preOrder->ProvinceID = $brand['ProvinceID'];
            $preOrder->CityID = $brand['CityID'];
            $preOrder->AreaID = $brand['AreaID'];
            $preOrder->BuyerID = $BuyerID;//渠道商ID
            $preOrder->WholesalerID = '';//渠道商渠道人员，@TODO 如开发接单功能需要
            $preOrder->sMessage = $brand['sContent'];//备注
            $preOrder->fTotal = $brand['fTotalMoney'] + $fShipMoney;//总价
            $preOrder->fShip = $fShipMoney;
            $preOrder->fWholesale = $brand['fTotalMoney'];//商品总价
            $preOrder->save();
            //预订单详情
            foreach ($brand['detail'] as $product) {
                $productStockChange = new ProductStockChange();

                $productStockChange->sName = $preOrder->sName;
                $productStockChange->dNewDate = \Yii::$app->formatter->asDatetime(time());

                $productStockChange->dEditDate = $productStockChange->dNewDate;

                $productStockChange->BuyerID = $BuyerID;
                $productStockChange->OrderID = $preOrder->lID;
                $productStockChange->ProductID = $product->PrductID;
                $productStockChange->lChange = $product->lNum;
                $productStockChange->save();
                $product->status = 2;
                $product->save();
            }
        }
        return $return;
    }

}

