<?php

namespace myerm\shop\mobile\controllers;

use myerm\common\components\CommonEvent;
use myerm\shop\common\models\Member;
use myerm\shop\common\models\RecommendProduct;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\SellerOrder;
use myerm\shop\mobile\models\Group;
use myerm\shop\mobile\models\Groupaddress;
use myerm\shop\mobile\models\GroupMember;
use myerm\shop\mobile\models\Order;
use myerm\shop\mobile\models\OrderPayLog;
use myerm\shop\mobile\models\Pay;
use myerm\shop\mobile\models\Redbag;
use myerm\shop\mobile\models\Redbagproduct;
use yii\helpers\Url;

/**
 * 购物车
 */
class CartController extends Controller
{
    /**
     * 添加商品至购物车
     * @return string
     */
    public function actionAddtocart()
    {
        $productid = explode(',', trim($_POST['productid'], ','));
        $quantity = explode(',', trim($_POST['quantity'], ','));
        foreach ($productid as $key => $lID) {
            $config = [
                'ProductID' => $lID,
                'lQty' => $quantity[$key],
                'bGroup' => $_POST['group']
            ];
            \Yii::$app->cart->addToCart($config);
        }
        $data = [];
        $data['status'] = true;
        $data['cartproductnum'] = \Yii::$app->cart->lProductQty;
        $data['productid'] = $productid;
        $data['message'] = "添加成功！";


        return $this->asJson($data);
    }


    /**
     * 立即购买
     * @return string
     */
    public function actionBuy()
    {
        $arrSpec = [];
        if (isset($_POST['spec'])) {
            foreach ($_POST['spec'] as $sSpec => $sVal) {
                $arrSpec[] = $sSpec . ":" . $sVal;
            }
        }

        //保存到确认清单之中。
        \Yii::$app->ordercheckout->clear();
        \Yii::$app->ordercheckout->add([
            'ProductID' => $_POST['productid'],
            'lQty' => $_POST['quantity'],
            'sSKU' => implode(";", $arrSpec),
            'GroupID' => $_POST['GroupID'],
            'WholesaleID' => $_POST['wholesaleid']//渠道商品 ouyangyz 2018-8-9 16:08:17
        ]);

        $data = [];
        $data['status'] = true;
        $data['message'] = "";

        return $this->asJson($data);
    }

    /**
     * 清空购物车中失效的商品
     */
    public function actionClear()
    {
        \Yii::$app->cart->clear($_POST['cartid']);

        $data = [];
        $data['status'] = true;
        $data['message'] = "";


        return $this->asJson($data);
    }

    /**
     * 更新商品的购买数量
     */
    public function actionUpdateqty()
    {
        $data = [];
        $data['status'] = $_POST;
        $return = \Yii::$app->cart->updateQty($_POST['cartid'], $_POST['quantity']);

        $data = [];
        $data['status'] = $return;
        if (!$data['status']) {
            $data['message'] = "库存不足";
        }

        return $this->asJson($data);
    }

    /**
     * 购物车去结算
     * @return \yii\web\Response
     */
    public function actionAddtocheckout()
    {
        \Yii::$app->ordercheckout->clear();
        $arrCart = \Yii::$app->cart->findByIDs($_POST['cartid']);
        foreach ($arrCart as $cart) {
            \Yii::$app->ordercheckout->add([
                'ProductID' => $cart->ProductID,
                'CartID' => $cart->ID,
                'lQty' => $cart->lQuantity,
                'sSKU' => $cart->sSKU,
                'dNewDate' => $cart->dNewDate,
                'bGroup' => $cart->bGroup
            ]);
        }

        $data = [];
        $data['status'] = true;
        $data['message'] = "";

        return $this->asJson($data);
    }

    /**
     * 订单确认页
     */
    public function actionCheckout()
    {
        $data = [];
        $this->getView()->title = "订单确认页";
        $checkoutProduct = \Yii::$app->ordercheckout->arrCheckoutProduct;
        if ($checkoutProduct[0]->GroupID) {
            $this->redirect('checkoutgroup');
        }
        if ($_GET['addressid']) {
            $address = \Yii::$app->memberaddress::findByID($_GET['addressid']);
        } else {
            $address = \Yii::$app->memberaddress->defAddress;
        }
        $data['address'] = $address;
        $data['checkoutProduct'] = $checkoutProduct;

        $bNoDeliver = false;
        $fShipMoney = 0;
        $totalPrice = 0;
        $data['redProduct'] = [];
        $date = \Yii::$app->formatter->asDatetime(time());
        foreach ($checkoutProduct as $cProduct) {
            $product = $cProduct->product;
            $totalPrice += $product->fPrice * $cProduct->lQuantity;
            $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date], ['ProductID' => $product->lID]])->one();
            if ($redProduct) {
                $data['redProduct'][$product->lID]['sName'] = $redProduct->sName;
                $data['redProduct'][$product->lID]['fChange'] = $redProduct->fChange;
                $data['redProduct'][$product->lID]['lQuantity'] = $cProduct->lQuantity;
                $totalPrice -= $redProduct->fChange * $cProduct->lQuantity;
            }
            $param = [
                'CityID' => $address->CityID,
                'ShipTemplateID' => $product->ShipTemplateID,
                'Number' => $cProduct->lQuantity,
                'fTotalMoney' => number_format($product->fPrice * $cProduct->lQuantity, 2),
                'Weight' => $product->lWeight
            ];
            $ship = \Yii::$app->shiptemplate->computeShip($param);

            //判断是否处于不发货地区 panlong 2019年9月16日14:13:47
            if ($ship['status'] == -1) {
                $bNoDeliver = true;
            }
            $fShipMoney += $ship['fShipMoney'];
        }

        $data['bNoDeliver'] = $bNoDeliver;

        $data['shipcount'] = $fShipMoney;

        $totalPrice = $totalPrice + $fShipMoney;
        $data['totalPrice'] = $totalPrice;

        //如果是顶级代理下单，增收服务费(订单总价*0.01) panlong 2019年9月11日16:13:18
        $fService = 0;
        // $seller = Seller::findOne(['MemberID' => \Yii::$app->frontsession->member->lID]);
        // if ($seller && $seller->TypeID == Seller::TOP) {
        // $fService = ($product->fSalePrice * $checkoutProduct->lQuantity + $fShipMoney) * SellerOrder::SERVICE_PRECENT;
        // }
        $data['fService'] = $fService;
        //匹配优惠券
        $data['redbag'] = [];
        if (!$data['redProduct']) {
            $redbag = Redbag::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['bUserd' => 0], ['<', 'fTopMoney', $totalPrice]])->orderBy('fChange desc')->one();
            $data['redbag'] = $redbag;
        }
        return $this->render("checkout", $data);
    }


    /**
     * 购物车页面
     */
    public function actionIndex_0303()
    {
        $data = [];

        $this->getView()->title = "购物车";

        //组装前端要的数据
        $data['arrSupplier'] = [];
        $data['arrInvalid'] = [];
        $arrCartProduct = \Yii::$app->cart->cartProduct;
        foreach ($arrCartProduct as $cartProduct) {
            $supplier = $cartProduct->product->supplier;
            if (!isset($data['arrSupplier'][$supplier->lID])) {
                $data['arrSupplier'][$supplier->lID] = [
                    'shop_id' => $supplier->lID,
                    'shop_name' => $supplier->sName,
                    'shop_link' => '',
                    'is_selected' => true
                ];
            }

            $product = $cartProduct->product;


            $arrProductInfo = [
                'id' => $cartProduct->ID,
                'pic' => \Yii::$app->request->imgUrl . '/' . $cartProduct->sPic,
                'name' => $product->sName,
                'price' => $product->fPrice,
                'total' => $cartProduct->fTotal,

                'num' => $cartProduct->lQuantity,
                'link' => \yii\helpers\Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detail",
                    'id' => $product->lID
                ], true),
                'stock' => $product->stock,

                'is_selected' => true
            ];

            //失效状态判断
            $arrProductInfo['invalid_state'] = $cartProduct->sInvalidStatus;

            if ($arrProductInfo['invalid_state']) {
                $data['arrInvalid'][] = $arrProductInfo;
            } else {
                $data['arrSupplier'][$supplier->lID]['products'][] = $arrProductInfo;
            }
        }


        //统计数量、总价
        $data['lQty'] = 0;
        $data['fTotal'] = 0;
        foreach ($data['arrSupplier'] as $SupplierID => $v) {
            if (!$v['products']) {
                unset($data['arrSupplier'][$SupplierID]);
            } else {
                foreach ($v['products'] as $p) {
                    $data['lQty'] += $p['num'];
                    $data['fTotal'] += $p['total'];
                }
            }
        }

        $arrRed = Redbag::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['bUserd' => 0]])->orderBy('fChange desc')->asArray()->all();
        $data['arrRed'] = $arrRed;
        return $this->render("index", $data);
    }

    /**
     * 保存订单
     */
    public function actionSaveorder()
    {
        $arrPostData = [
            'AddressID' => $_POST['addressid'],
            'arrMessage' => $_POST['message'],
            'redbagID' => $_POST['redbagID']
        ];

        $return = $this->saveOrder($arrPostData);
        //添加参团人员

        return $this->asJson($return);
    }

    /**
     * 保存订单
     */
    public function saveOrder($arrParam)
    {
        $return = [];
        if (!$arrParam['AddressID']) {
            $return['status'] = false;
            $return['message'] = "收货地址不能为空";
            return $return;
        }
        //把失效的商品去除
        $checkoutProduct = \Yii::$app->ordercheckout->arrCheckoutProduct;

        if ($checkoutProduct[0]->bGroup || $checkoutProduct[0]->GroupID) {
            $address = Groupaddress::findOne($arrParam['AddressID']);
        } else {
            $address = \Yii::$app->memberaddress::findByID($arrParam['AddressID']);
        }
        $arrParam['address'] = $address;

        //检测冻品在线商品
        $bcheck = false;
        $totalMoney = 0;
        foreach ($checkoutProduct as $cProduct) {
            if ($cProduct->product->SupplierID == 767 && !$cProduct->GroupID) {
                $bcheck = true;
                $totalMoney += $cProduct->product->fPrice * $cProduct->lQuantity;
            }
            if ($cProduct->product->SupplierID == 768 && !$cProduct->GroupID) {
                if ($cProduct->lQuantity < $cProduct->product->lGroupNum) {
                    $return['status'] = false;
                    $num = $cProduct->product->lGroupNum;
                    $return['message'] = "来瓜分苹果满" . $num . "件配送！";
                    return $return;
                }
            }
            //计算运费
            $param = [
                'CityID' => $address->CityID,
                'ShipTemplateID' => $cProduct->product->ShipTemplateID,
                'Number' => $cProduct->lQuantity,
                'fTotalMoney' => number_format($cProduct->product->fSalePrice * $cProduct->lQuantity, 2),
                'Weight' => $cProduct->product->lWeight
            ];

            $ship = \Yii::$app->shiptemplate->computeShip($param);
            //判断是否处于不发货地区 panlong 2019年9月16日14:13:47
            if ($ship['status'] == -1) {
                $return['status'] = false;
                $return['message'] = "该地区不发货";
                return $return;
            }
            $fShipMoney = $ship['fShipMoney'];
            if (isset($arrParam['fShipMoney'][$cProduct->product->SupplierID])) {
                $arrParam['fShipMoney'][$cProduct->product->SupplierID] += $fShipMoney;
            } else {
                $arrParam['fShipMoney'][$cProduct->product->SupplierID] = $fShipMoney;
            }

        }
        if ($bcheck && $totalMoney < 1) {
            $return['status'] = false;
            $return['message'] = '冻品系列商品需购满118配送！';
            return $return;
        }

        $arrParam['OrderType'] = 1;


        $res = \Yii::$app->order->saveOrder($arrParam);
        return $res;
    }

    /**
     * 收银台
     * @param $no
     */
    public function actionCashier($no)
    {
        $order = Order::find()->where(['lID' => $no])->one();
        $fTotalFee = 0;
        $dNewDate = '';
        if ($order) {
            $fTotalFee = $order->fSumOrder;
            $sTradeNo = $order->lID;
            $dNewDate = $order->dNewDate;
            //检查库存
            $arrDetail = $order->arrDetail;
            foreach ($arrDetail as $detail) {
                $product = $detail->product;
                if ($product->lStockReal < $detail->lQuantity) {
                    //库存不足
                    $order->StatusID = 'closed';
                    $order->save();
                    $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true));
                    return;
                }
            }
        } else {
            $arrOrder = Order::find()->where(['sTradeNo' => $no])->all();
            foreach ($arrOrder as $order) {
                $log = OrderPayLog::find()->where(['sOrderID' => $order->lID])->one();
                if ($log) {
                    $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true));
                    return;
                }
                //检查库存
                $arrDetail = $order->arrDetail;
                foreach ($arrDetail as $detail) {
                    $product = $detail->product;
                    if ($product->lStockReal < $detail->lQuantity) {
                        //库存不足
                        //库存不足
                        $order->StatusID = 'closed';
                        $order->save();
                        $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true));
                        return;
                    }
                }
                $fTotalFee += $order->fSumOrder;
                $sTradeNo = $order->sTradeNo;
                $dNewDate = $order->dNewDate;
            }
        }
        return $this->render("pay", ['fTotalFee' => $fTotalFee, 'sTradeNo' => $sTradeNo, 'dNewDate' => $dNewDate]);
    }

    /**
     * 支付参数 微信
     */
    public function actionPay()
    {
        $backURL = "https://yl.aiyolian.cn/pay/wxnotify";//支付回调接口不能少（可以由前台传入，前期可以由后台定死）
        $id = $_GET['ID'];
        $order = \Yii::$app->order->findOne($id);
        $fSumPaid = 0;
        if ($order) {
            $fSumPaid = $order->fSumOrder; //应付款 订单数据验证
            $sTradeNo = $order->sName;
        } else {
            $arrOrder = Order::find()->where(['sTradeNo' => $id])->all();
            foreach ($arrOrder as $order) {
                $fSumPaid += $order->fSumOrder; //应付款 订单数据验证
                $sTradeNo = $order->sTradeNo;
            }
        }
        $openID = \Yii::$app->frontsession->member->sOpenID;
        $pay = new Pay();
        return $pay->payinfo($fSumPaid, $sTradeNo, $backURL, $openID);
    }

    /*
     * 支付参数 支付宝
     */
    public function actionPayali()
    {
        $pay = new Pay();
        $sTradeNo = 'T2019111223032844921';
        $fSumPaid = 0.01;
        return $pay->payOrderAlipay($sTradeNo, $fSumPaid);
    }


    //社区团购

    /**
     * 购物车去结算
     * @return \yii\web\Response
     */
    public function actionAddgroupcheckout()
    {
        \Yii::$app->ordercheckout->clear();
        $arrProductID = $_POST['productIDs'];
        $arrProductNum = $_POST['productNum'];
        $groupID = $_POST['groupID'];

        foreach ($arrProductID as $key => $ProductID) {
            \Yii::$app->ordercheckout->add([
                'ProductID' => $ProductID,
                'CartID' => '',
                'lQty' => $arrProductNum[$key],
                'sSKU' => '',
                'dNewDate' => date('Y-m-d H:i:s'),
                'GroupID' => $groupID
            ]);
        }

        $data = [];
        $data['status'] = true;
        $data['productID'] = $arrProductID;
        $data['groupID'] = $groupID;

        return $this->asJson($data);
    }

    public function actionCheckoutgroup()
    {
        $data = [];
        $this->getView()->title = "订单确认页";
        $checkoutProduct = \Yii::$app->ordercheckout->arrCheckoutProduct;
        if (!$checkoutProduct[0]->GroupID) {
            $this->redirect('checkout');
        }
        $group = Group::findOne($checkoutProduct[0]->GroupID);
        $address = Groupaddress::findOne(['MemberID' => $group->MemberID]);

        $data['address'] = $address;

        $data['checkoutProduct'] = $checkoutProduct;

        $bNoDeliver = false;
        $fShipMoney = 0;
        $totalPrice = 0;
        $data['redProduct'] = [];
        $date = \Yii::$app->formatter->asDatetime(time());
        foreach ($checkoutProduct as $cProduct) {
            $product = $cProduct->product;
            $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date], ['ProductID' => $product->lID]])->one();
            if ($redProduct) {
                $data['redProduct'][$product->lID]['sName'] = $redProduct->sName;
                $data['redProduct'][$product->lID]['fChange'] = $redProduct->fChange;
                $data['redProduct'][$product->lID]['lQuantity'] = $cProduct->lQuantity;
                $totalPrice -= $redProduct->fChange * $cProduct->lQuantity;
            }
            $totalPrice += $product->fPrice * $cProduct->lQuantity;
            $param = [
                'CityID' => $address->CityID,
                'ShipTemplateID' => $product->ShipTemplateID,
                'Number' => $cProduct->lQuantity,
                'fTotalMoney' => number_format($product->fPrice * $cProduct->lQuantity, 2),
                'Weight' => $product->lWeight
            ];
            $ship = \Yii::$app->shiptemplate->computeShip($param);

            //判断是否处于不发货地区 panlong 2019年9月16日14:13:47
            if ($ship['status'] == -1) {
                $bNoDeliver = true;
            }
            $fShipMoney += $ship['fShipMoney'];
        }

        $data['bNoDeliver'] = $bNoDeliver;

        $data['shipcount'] = $fShipMoney;

        $totalPrice = $totalPrice + $fShipMoney;
        $data['totalPrice'] = $totalPrice;

        //如果是顶级代理下单，增收服务费(订单总价*0.01) panlong 2019年9月11日16:13:18
        $fService = 0;
        // $seller = Seller::findOne(['MemberID' => \Yii::$app->frontsession->member->lID]);
        // if ($seller && $seller->TypeID == Seller::TOP) {
        // $fService = ($product->fSalePrice * $checkoutProduct->lQuantity + $fShipMoney) * SellerOrder::SERVICE_PRECENT;
        // }
        $data['fService'] = $fService;
        //匹配优惠券

        $data['redbag'] = [];
        if (!$data['redProduct']) {
            $redbag = Redbag::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['bUserd' => 0], ['<', 'fTopMoney', $totalPrice]])->orderBy('fChange desc')->one();
            $data['redbag'] = $redbag;
        }
        return $this->render("checkoutgroup", $data);
    }


    /**
     * 购物车页面
     */
    public function actionIndex()
    {
        $data = [];

        //组装前端要的数据
        $data['arrSupplier'] = [];
        $data['arrInvalid'] = [];
        $date = \Yii::$app->formatter->asDatetime(time());
        $recommendProduct = RecommendProduct::findOne(9);
        $timerDate = $recommendProduct->dStrart;
        $timerMsg = '距开始';
        if ($date > $recommendProduct->dStrart && $date < $recommendProduct->dEnd) {
            $timerDate = $recommendProduct->dEnd;
            $timerMsg = '距截单';
        }
        $data['timerDate'] = $timerDate;
        $data['timerMsg'] = $timerMsg;

        $bRedProduct = 0;
        $arrCartProduct = \Yii::$app->cart->cartProduct;
        foreach ($arrCartProduct as $cart) {
            $product = $cart->product;

            $supplier = $product->supplier;
            if (!isset($data['arrSupplier'][$supplier->lID])) {
                $data['arrSupplier'][$supplier->lID] = [
                    'shop_id' => $supplier->lID,
                    'shop_name' => $supplier->sName,
                    'shop_link' => '',
                    'is_selected' => true
                ];
            }

            $arrProductInfo = [
                'id' => $cart->ID,
                'pic' => $product->masterPic,
                'name' => $product->sName,
                'price' => $product->fGroupPrice,
                'fMarketPrice' => '市场价：￥' . $product->fMarketPrice,
                'total' => $product->fGroupPrice,

                'num' => $cart->lQuantity,
                'link' => \yii\helpers\Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detailgroup",
                    'id' => $product->lID
                ], true),
                'stock' => '库存：' . $product->lStock,

                'is_selected' => true
            ];
            //失效状态判断
            $data['arrSupplier'][$supplier->lID]['products'][] = $arrProductInfo;
        }


        //统计数量、总价
        $data['lQty'] = 0;
        $data['fTotal'] = 0;
        foreach ($data['arrSupplier'] as $SupplierID => $v) {
            if (!$v['products']) {
                unset($data['arrSupplier'][$SupplierID]);
            } else {
                foreach ($v['products'] as $p) {
                    $data['lQty'] += $p['num'];
                    $data['fTotal'] += $p['total'];
                }
            }
        }

        //判断是否有商品券
        $date = \Yii::$app->formatter->asDatetime(time());
        $data['arrRed'] = [];
        $data['redProduct'] = [];
        $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date]])->asArray()->all();
        if ($redProduct) {
            $bRedProduct = 0;
            $data['redProduct'] = $redProduct;
        } elseif (!$redProduct) {
            $bRedProduct = Redbagproduct::addRedbagproduct();
            $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date]])->asArray()->all();
            $data['redProduct'] = $redProduct;
        }
        if (!$redProduct) {
            //通用券
            $arrRed = Redbag::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['bUserd' => 0]])->orderBy('fChange desc')->asArray()->all();
            $data['arrRed'] = $arrRed;
        }
        $data['bRedProduct'] = $bRedProduct;

        //团长信息
        $MemberID = \Yii::$app->frontsession->MemberID;
        $seller = Seller::findOne(['lID' => $MemberID]);
        if ($seller) {
            $data['shoptitle'] = $seller->sName;
        } else {
            $seller = \Yii::$app->frontsession->urlSeller;
            if (!$seller) {
                $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home/invite"], true));
            }

        }


        $this->getView()->title = $seller->sName;
        $data['shop'] = $seller;
        $data['shoptitle'] = $seller->sName;

        $data['groupaddress'] = Groupaddress::findOne(['MemberID' => $seller->lID]);
        $data['member'] = $seller->member;

        $arrOrder = Order::find()->orderBy('dNewDate desc')->limit(60)->all();
        $buyers = [];
        foreach ($arrOrder as $key => $order) {
            $member = $order->member;
            $buyers[$key]['logo'] = $member->sAvatarPath;
            $buyers[$key]['sName'] = $member->sName;
        }
        $data['buyers'] = json_encode($buyers);

        return $this->render("cart", $data);
    }

    public function actionCheckoutcart()
    {
        $data = [];
        $this->getView()->title = "订单确认页";
        $checkoutProduct = \Yii::$app->ordercheckout->arrCheckoutProduct;

        if (!$checkoutProduct[0]->bGroup) {
            $this->redirect('checkoutgroup');
        }
        $MemberID = \Yii::$app->frontsession->MemberID;
        $bSeller = 1;
        $seller = Seller::findOne(['lID' => $MemberID]);
        if (!$seller) {
            $bSeller = 0;
            $seller = \Yii::$app->frontsession->urlSeller;
        }
        $address = Groupaddress::findOne(['MemberID' => $seller->lID]);

        $data['address'] = $address;
        $data['member'] = Member::findOne($seller->lID);
        $data['seller'] = $seller;

        $data['checkoutProduct'] = $checkoutProduct;

        $bNoDeliver = false;
        $fShipMoney = 0;
        $totalPrice = 0;
        $data['redProduct'] = [];
        $date = \Yii::$app->formatter->asDatetime(time());
        foreach ($checkoutProduct as $cProduct) {
            $product = $cProduct->product;
            $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date], ['ProductID' => $product->lID],['>','lCount',0]])->one();
            if ($redProduct) {
                $lCount = $cProduct->lQuantity;
                if (!$bSeller) {
                    $lCount = $redProduct->lCount > $cProduct->lQuantity ? $cProduct->lQuantity : $redProduct->lCount;
                }

                $data['redProduct'][$product->lID]['sName'] = $redProduct->sName;
                $data['redProduct'][$product->lID]['fChange'] = $redProduct->fChange;
                $data['redProduct'][$product->lID]['lQuantity'] = $lCount;
                $totalPrice -= $redProduct->fChange * $lCount;
            }
            $totalPrice += $product->fGroupPrice * $cProduct->lQuantity;
            $param = [
                'CityID' => $address->CityID,
                'ShipTemplateID' => $product->ShipTemplateID,
                'Number' => $cProduct->lQuantity,
                'fTotalMoney' => number_format($product->fGroupPrice * $cProduct->lQuantity, 2),
                'Weight' => $product->lWeight
            ];
            $ship = \Yii::$app->shiptemplate->computeShip($param);

            //判断是否处于不发货地区 panlong 2019年9月16日14:13:47
            if ($ship['status'] == -1) {
                $bNoDeliver = true;
            }
            $fShipMoney += $ship['fShipMoney'];
        }

        $data['bNoDeliver'] = $bNoDeliver;

        $data['shipcount'] = $fShipMoney;

        $totalPrice = $totalPrice + $fShipMoney;
        $data['totalPrice'] = $totalPrice;

        //如果是顶级代理下单，增收服务费(订单总价*0.01) panlong 2019年9月11日16:13:18
        $fService = 0;
        $data['fService'] = $fService;
        //匹配优惠券

        $data['redbag'] = [];
        if (!$data['redProduct']) {
            $redbag = Redbag::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['bUserd' => 0], ['<', 'fTopMoney', $totalPrice]])->orderBy('fChange desc')->one();
            $data['redbag'] = $redbag;
        }
        return $this->render("checkoutgroup", $data);
    }

    //立即购买

    /**
     * 添加商品至购物车
     * @return string
     */
    public function actionAddgroupcart()
    {
        $productid = explode(',', trim($_POST['productid'], ','));
        $quantity = explode(',', trim($_POST['quantity'], ','));
        \Yii::$app->ordercheckout->clear();
        foreach ($productid as $key => $lID) {

            $config = [
                'ProductID' => $lID,
                'lQty' => $quantity[$key],
                'bGroup' => $_POST['group']
            ];
            $cartID = \Yii::$app->cart->addToCart($config);
            \Yii::$app->ordercheckout->add([
                'ProductID' => $lID,
                'CartID' => $cartID,
                'lQty' => $quantity[$key],
                'sSKU' => '',
                'dNewDate' => date(),
                'bGroup' => $_POST['group']
            ]);
        }
        $data = [];
        $data['status'] = true;
        $data['productid'] = $productid;
        $data['message'] = "添加成功！";
        return $this->asJson($data);
    }
}