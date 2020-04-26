<?php

namespace myerm\shop\mobile\controllers;

use EasyWeChat\Payment\Order;
use myerm\common\components\CommonEvent;
use myerm\shop\common\models\Bank;
use myerm\shop\common\models\InviteConfig;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\Member;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\SellerConfig;
use myerm\shop\common\models\SellerShop;
use myerm\shop\common\models\SellerType;
use myerm\shop\common\models\VerifyCode;
use yii\helpers\Url;

/**
 * 经销商控制器

 */
class SellerController extends Controller
{
    //付款成功
    const EVENT_PAY_SUCCESS = 'paysuccess';

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class' => 'myerm\shop\mobile\filters\SellerAccessControl',
                'except' => [
                    'joindesc',
                    'join',
                    'joincontract',
                    'sendjoincode',
                    'joinwxnotify',
                    'joinpaysuccess',
                    'giftcheck',
                    'flowdesc'
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $data = [];

        $data['seller'] = \Yii::$app->frontsession->seller;
        $data['config'] = SellerConfig::all();
        $data['member'] = \Yii::$app->frontsession->member;

        $this->getView()->title = "团长中心";

        return $this->render("home", $data);
    }

    public function actionShopconfig()
    {
        $data['seller'] = \Yii::$app->frontsession->seller;
        if (!$data['seller']) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }

        if (\Yii::$app->request->isPost) {
            \Yii::$app->sellershop->shopConfig($_POST['shopname'], $_POST['logo']);
            \Yii::$app->seller->shopConfig($_POST['name'], $_POST['mobile']);
        } else {
            $data = [];
            $this->getView()->title = "店铺设置";
            $data['seller'] = \Yii::$app->frontsession->seller;
            return $this->render("shopconfig", $data);
        }
    }


    /**
     * 成为经销商
     * @return string|\yii\web\Response
     */
    public function actionJoindesc()
    {
        if (!isset($_GET['sShopUrl'])) {
            return $this->redirect(Url::toRoute(\Yii::$app->request->shopUrl . "/seller/joindesc", true));
        }

        $this->getView()->title = "成为经销商";
        return $this->render("joindesc");
    }

    /**
     * 入驻申请
     */
    public function actionJoin()
    {
        if (\Yii::$app->request->isPost) {

            if (\Yii::$app->frontsession->seller) {
                return $this->asJson(['status' => false, 'message' => '您已经是经销商']);
            }

            if ($_POST['code'] != VerifyCode::getCode()) {
                return $this->asJson(['status' => false, 'message' => '验证码不正确']);
            }

            $address = \Yii::$app->memberaddress->findByID($_POST['addressid']);

            if (!$address) {
                return $this->asJson(['status' => false, 'message' => '找不到地址']);
            }

            $sellerConfig = SellerConfig::findBysKey('sJoinGiftProductCfg');

            foreach (json_decode($sellerConfig['sValue']) as $sGiftTitle => $arrProduct) {

                if ($sGiftTitle == $_POST['giftTitle']) {

                    $fMoney = 0;
                    $sProduct = json_encode($arrProduct);
                    $sType = $sGiftTitle;
                    foreach ($arrProduct as $product) {
                        $fMoney += $product->fPrice;
                    }
                }
            }

            $sellType = SellerType::find()->where(['sName' => $_POST['type']])->one();


            /* 生成确认订单数据 */
            $sTradeNo = \Yii::$app->sellerjoin->makeTradeNo();

            $arrProduct = json_decode($sProduct);
            \Yii::$app->ordercheckout->clear();
            foreach ($arrProduct as $product) {
                \Yii::$app->ordercheckout->add([
                    'ProductID' => $product->ProductID,
                    'lQty' => $product->lQuantity,
                    'sSKU' => '',
                ]);
            }

            /* 生成订单 */
            $arrPostData = [
                'AddressID' => $_POST['addressid'],
                'arrMessage' => '',
                'sNoPrefix' => 'J',
            ];

            $return = \Yii::$app->order->saveOrder($arrPostData);

            if ($return['status']) {

                $sOrderID = ';';
                foreach ($return['orderids'] as $OrderID) {
                    $sOrderID .= $OrderID . ';';
                }

                $sellerJoin = \Yii::$app->sellerjoin->reg([
                    'sName' => $_POST['name'],
                    'sMobile' => $_POST['phone'],
                    'sType' => $_POST['type'],
                    'AddressID' => $_POST['addressid'],
                    'sProduct' => $sProduct,
                    'fMoney' => $fMoney,
                    'sType' => $sType,
                    'RecommSellerID' => $_POST['recommendID'],
                    'TypeID' => $sellType->lID,
                    'sOrderID' => $sOrderID,
                    'sTradeNo' => $sTradeNo,
                ]);

                if ($sellerJoin->fMoney > 0.00) {
                    $return['pay'] = true;

                    $attributes = [
                        'trade_type' => 'JSAPI',
                        // JSAPI，NATIVE，APP...
                        'body' => '支付',
                        'out_trade_no' => $sellerJoin->sTradeNo,
                        'total_fee' => $sellerJoin->fMoney * 100,
                        // 单位：分
                        'notify_url' => Url::toRoute(['/seller/joinwxnotify'], true),
                        //支付结果通知网址，如果不设置则会使用配置里的默认地址
                        'openid' => \Yii::$app->frontsession->sOpenID,
                    ];

                    $order = new Order($attributes);
                    $result = \Yii::$app->wechat->payment->prepare($order);
                    if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
                        $return['status'] = true;
                        $return['config'] = \Yii::$app->wechat->payment->configForJSSDKPayment($result->prepay_id);
                    } else {
                        $return['status'] = false;
                        $return['message'] = $result->get('return_msg');
                    }
                } else {
                    $return['status'] = true;
                    $return['pay'] = false;
                    $sellerJoin->joinSuccess();
                }

                return $this->asJson($return);
            }

            return $this->asJson($return);

        } else {
            $data['sellType'] = SellerType::findByID(1);
            $this->getView()->title = "入驻申请";

            if ($_GET['addressid']) {
                $address = \Yii::$app->memberaddress::findByID($_GET['addressid']);
            } else {
                $address = \Yii::$app->memberaddress->defAddress;
            }

            $data['address'] = $address;

            //如果是从选择大礼包页面跳转来，则设置cookie
            $data['sGiftTitle'] = $_COOKIE['sGiftTitle'];
            if ($_GET['giftTitle']) {
                setcookie("sGiftTitle", $_GET['giftTitle'], time() + 24 * 60 * 60);
                $data['sGiftTitle'] = $_GET['giftTitle'];
            }


            $sellerConfig = SellerConfig::findBysKey('sJoinGiftProductCfg');

            foreach (json_decode($sellerConfig['sValue']) as $sGiftTitle => $arrProduct) {

                if ($sGiftTitle == $_GET['giftTitle']) {

                    $fMoney = 0;

                    foreach ($arrProduct as $product) {
                        $fMoney += $product->fPrice;
                    }
                }
            }
            $data['fMoney'] = $fMoney;

            return $this->render("join", $data);
        }
    }

    /**
     * 升级大礼包页面
     * @return string
     * @author panlong
     * @time 2018-6-28 10:47:10
     */
    public function actionGiftcheck()
    {
        $this->getView()->title = "升级大礼包套餐";

//        if (Yii::$app->session2->user->bAgent) {
//            //已经是经销商了，直接跳到个人中心
//            return $this->redirect('index');
//        } elseif (!Yii::$app->session2->bLogin) {
//            //判断是否登录，未登录则跳到登录页
//            return $this->redirect('login');
//        } else {
//            //升级大礼包优化为多选套餐 modify by lcx 2017.10.20
//            $giftState = $this->upgradegift();
//            if (!empty($giftState)) {
//                if ($giftState == 1) {
//                    //如果已经添加到购物车了，直接跳到购物车界面
//                    return $this->redirect('/lsj/cart/index');
//                } else if ($giftState == 2) {
//                    //如果已经在待付款了，直接跳到待付款页面
//                    return $this->redirect(['/lsj/member/myorder', 'status' => 'unpay']);
//                }
//            }
//        }

        $config = SellerConfig::findBysKey('sJoinGiftProductCfg');
        $data['arrJuniorConfig'] = json_decode($config['sValue'], true);


        $no = 0;

        foreach ($data['arrJuniorConfig'] as $lKey => $value) {
            $no++;
            foreach ($value as $k => $giftproduct) {
                $product = Product::findOne($giftproduct['ProductID']);
                $data['arrJuniorConfig'][$lKey][$k]['sName'] = $product->sName;
                $data['arrJuniorConfig'][$lKey][$k]['image'] = MallConfig::getValueByKey('sImgRootUrl') . '/' . json_decode($product->sPic)[0];
            }
        }

        return $this->render('giftcheck', $data);
    }

    /**
     * 经销商协议
     */
    public function actionJoincontract()
    {
        $this->getView()->title = "经销商协议";
        return $this->render("joincontract");
    }

    /**
     * 发送验证码
     * @param $mobile
     * @return \yii\web\Response
     */
    public function actionSendjoincode($mobile)
    {
        if (\Yii::$app->request->isPost) {
            return $this->asJson(VerifyCode::send($mobile));
        }
    }

    /**
     * 申请入驻，微信支付通知
     */
    public function actionJoinwxnotify()
    {
        $response = \Yii::$app->wechat->payment->handleNotify(function ($notify, $successful) {

            $sellerJoin = \Yii::$app->sellerjoin->findByNo($notify->out_trade_no);

            if (!$sellerJoin) {//没有支付的数据
                return true;
            }

            if ($successful) {

                //处理订单
                $arrOrderID = [];
                foreach (explode(';', $sellerJoin->sOrderID) as $OrderID) {
                    if ($OrderID) {
                        $arrOrderID[] = $OrderID;
                    }
                }

                $arrOrder = \Yii::$app->order->findByIDs($arrOrderID);

                foreach ($arrOrder as $order) {

                    $order->wxPaySuccess($notify->toArray());

                    //触发器
                    foreach ($order->arrDetail as $orderDetail) {

                        $orderDetail->editUpgrade([
                            'No' => $notify->out_trade_no,
                            'MemberID' => $sellerJoin->MemberID,
                        ]);

                        $event = new CommonEvent();
                        $event->extraData = $orderDetail;//传值订单明细
                        \Yii::$app->orderdetail->trigger('paysuccess', $event);
                    }

                    $order->editUpgrade([
                        'No' => $notify->out_trade_no,
                        'MemberID' => $sellerJoin->MemberID,
                    ]);

                    $event = new CommonEvent();
                    $event->extraData = $order;//传值订单明细
                    \Yii::$app->order->trigger('paysuccess', $event);
                }

                //清空确认的购物车商品
                \Yii::$app->ordercheckout->clear(\Yii::$app->frontsession->ID);

                $sellerJoin->joinSuccess();

            }


            return true; // 或者错误消息
        });

        $response->send();
    }

    /**
     * 入驻成功
     */
    public function actionJoinpaysuccess()
    {
        return $this->render("joinsuccess");
    }

    /**
     * 绑定银行卡
     */
    public function actionBindbank()
    {
        if (!\Yii::$app->request->isPost) {
            $data = [];

            $data['seller'] = \Yii::$app->frontsession->seller;
            if (!$data['seller']) {
                return $this->redirect(\Yii::$app->request->mallHomeUrl);
            }

            $data['arrBank'] = Bank::find()->all();

            $this->getView()->title = "绑定银行卡";
            return $this->render("bindbank", $data);
        } else {

            if (!MallConfig::getValueByKey('bBankCardVerify')) {
                \Yii::$app->seller->bindBank(
                    [
                        'sBank' => $_POST['bank'],
                        'sBankAccount' => $_POST['card'],
                        'sBankRealName' => $_POST['name'],
                    ]
                );
                return $this->asJson(['status' => true]);
            } else {
                $result = \Yii::$app->bankcardverify->execute($_POST['card'], $_POST['idcard'], $_POST['name'],
                    $_POST['phone']);
                if ($result['error_code'] == 0) {
                    \Yii::$app->seller->bindBank(
                        [
                            'sBank' => $result['result']['information']['bankname'],
                            'sBankAccount' => $result['result']['bankcard'],
                            'sBankMobile' => $result['result']['mobile'],
                            'sBankIDCardNo' => $result['result']['cardNo'],
                            'sBankRealName' => $result['result']['realName'],
                        ]
                    );
                    return $this->asJson(['status' => true]);
                } else {
                    return $this->asJson(['status' => false, 'message' => $result['reason']]);
                }
            }
        }
    }

    /**
     * 已结算列表首页
     */
    public function actionSettlement()
    {
        $data = [];
        $data['seller'] = \Yii::$app->frontsession->seller;
        if (!$data['seller']) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }

        $data['sDataJson'] = $this->actionSettlementitem();

        $this->getView()->title = "已结算";

        return $this->render("settlement", $data);
    }

    /**
     * 已结算列表分页
     */
    public function actionSettlementitem()
    {
        if (!$_GET['type']) {
            $_GET['type'] = "全部";
        }

        $arrOrder = \Yii::$app->sellerorder->settlementList([
            'type' => $_GET['type'],
            'page' => $_GET['page']
        ]);


        if ($_GET['type'] == '全部' || !$_GET['type']) {
            $fSettlement = \Yii::$app->sellerorder->computeSttlement(\Yii::$app->frontsession->seller->lID, 'all');
        } elseif ($_GET['type'] == '销售提成') {
            $fSettlement = \Yii::$app->sellerorder->computeSttlement(\Yii::$app->frontsession->seller->lID, 'seller');
        } elseif ($_GET['type'] == '一级团队提成') {
            $fSettlement = \Yii::$app->sellerorder->computeSttlement(\Yii::$app->frontsession->seller->lID, 'first');
        } elseif ($_GET['type'] == '二级团队提成') {
            $fSettlement = \Yii::$app->sellerorder->computeSttlement(\Yii::$app->frontsession->seller->lID, 'second');
        }

        $arrData = ['fSettlement' => number_format($fSettlement, 2), 'data' => []];
        foreach ($arrOrder as $order) {

            if (($_GET['type'] == "全部" || $_GET['type'] == "销售提成") && $order->SellerID == \Yii::$app->frontsession->seller->lID) {
                $data['level'] = 0;
                $data['title'] = '销售提成';
                $data['items'] = [];
                foreach ($order->arrDetail as $detail) {
                    $data['items'][] = [
                        'link' => "/" . \Yii::$app->request->shopUrl . "/member/order?id=" . $order->OrderID,
                        'commodityName' => $detail->sName,
                        'commodityPrice' => $detail->sStatus != "退款成功" ? "￥" . number_format($detail->fProfit * $order->lSellerCommissionRate / 100,
                                2) : "",
                        'time' => $order->dNewDate,
                        'status' => $detail->sStatus ? $detail->sStatus : $order->order->sStatus
                    ];
                }

                $arrData['data'][] = $data;
            }

            if (($_GET['type'] == "全部" || $_GET['type'] == "一级团队提成") && $order->UpSellerID == \Yii::$app->frontsession->seller->lID) {
                $data['level'] = 1;
                $data['orderid'] = $order->OrderID;
                $data['title'] = '一级团队提成';
                $data['items'] = [];
                foreach ($order->arrDetail as $detail) {
                    $data['items'][] = [
                        'link' => "/" . \Yii::$app->request->shopUrl . "/member/order?id=" . $order->OrderID,
                        'commodityName' => $detail->sName,
                        'commodityPrice' => $detail->sStatus != "退款成功" ? "￥" . number_format($detail->fProfit * $order->lUpSellerCommissionRate / 100,
                                2) : "",
                        'time' => $order->dNewDate,
                        'status' => $detail->sStatus ? $detail->sStatus : $order->order->sStatus
                    ];
                }

                $arrData['data'][] = $data;
            }

            if (($_GET['type'] == "全部" || $_GET['type'] == "二级团队提成") && $order->UpUpSellerID == \Yii::$app->frontsession->seller->lID) {
                $data['level'] = 1;
                $data['title'] = '二级团队提成';
                $data['items'] = [];
                foreach ($order->arrDetail as $detail) {
                    $data['items'][] = [
                        'link' => "/" . \Yii::$app->request->shopUrl . "/member/order?id=" . $order->OrderID,
                        'commodityName' => $detail->sName,
                        'commodityPrice' => $detail->sStatus != "退款成功" ? "￥" . number_format($detail->fProfit * $order->lUpUpSellerCommissionRate / 100,
                                2) : "",
                        'time' => $order->dNewDate,
                        'status' => $detail->sStatus ? $detail->sStatus : $order->order->sStatus
                    ];
                }

                $arrData['data'][] = $data;
            }

        }

        return json_encode($arrData);
    }

    /**
     * 待结算列表首页
     */
    public function actionUnsettlement()
    {
        $data = [];
        $data['seller'] = \Yii::$app->frontsession->seller;
        if (!$data['seller']) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }

        $data['sDataJson'] = $this->actionUnsettlementitem();

        $this->getView()->title = "待结算";

        return $this->render("unsettlement", $data);
    }

    /**
     * 待结算列表分页
     */
    public function actionUnsettlementitem()
    {
        if (!$_GET['type']) {
            $_GET['type'] = "全部";
        }

        $arrOrder = \Yii::$app->sellerorder->unsettlementList([
            'type' => $_GET['type'],
            'page' => $_GET['page']
        ]);

        if ($_GET['type'] == '全部' || !$_GET['type']) {
            $fUnSettlement = \Yii::$app->sellerorder->computeUnsettlement(\Yii::$app->frontsession->seller->lID, 'all');
        } elseif ($_GET['type'] == '销售提成') {
            $fUnSettlement = \Yii::$app->sellerorder->computeUnsettlement(\Yii::$app->frontsession->seller->lID,
                'seller');
        } elseif ($_GET['type'] == '一级团队提成') {
            $fUnSettlement = \Yii::$app->sellerorder->computeUnsettlement(\Yii::$app->frontsession->seller->lID,
                'first');
        } elseif ($_GET['type'] == '二级团队提成') {
            $fUnSettlement = \Yii::$app->sellerorder->computeUnsettlement(\Yii::$app->frontsession->seller->lID,
                'second');
        }

        $arrData = ['fUnSettlement' => number_format($fUnSettlement, 2), 'data' => []];
        foreach ($arrOrder as $order) {

            if (($_GET['type'] == "全部" || $_GET['type'] == "销售提成") && $order->SellerID == \Yii::$app->frontsession->seller->lID) {
                $data['level'] = 0;
                $data['title'] = '销售提成';
                $data['items'] = [];
                foreach ($order->arrDetail as $detail) {
                    $data['items'][] = [
                        'link' => "/" . \Yii::$app->request->shopUrl . "/member/order?id=" . $order->OrderID,
                        'commodityName' => $detail->sName,
                        'commodityPrice' => $detail->sStatus != "退款成功" ? "￥" . number_format($detail->fProfit * $order->lSellerCommissionRate / 100,
                                2) : "",
                        'time' => $order->dNewDate,
                        'status' => $detail->sStatus ? $detail->sStatus : $order->order->sStatus
                    ];
                }

                $arrData['data'][] = $data;
            }

            if (($_GET['type'] == "全部" || $_GET['type'] == "一级团队提成") && $order->UpSellerID == \Yii::$app->frontsession->seller->lID) {
                $data['level'] = 1;
                $data['orderid'] = $order->OrderID;
                $data['title'] = '一级团队提成';
                $data['items'] = [];
                foreach ($order->arrDetail as $detail) {
                    $data['items'][] = [
                        'link' => "/" . \Yii::$app->request->shopUrl . "/member/order?id=" . $order->OrderID,
                        'commodityName' => $detail->sName,
                        'commodityPrice' => $detail->sStatus != "退款成功" ? "￥" . number_format($detail->fProfit * $order->lUpSellerCommissionRate / 100,
                                2) : "",
                        'time' => $order->dNewDate,
                        'status' => $detail->sStatus ? $detail->sStatus : $order->order->sStatus
                    ];
                }

                $arrData['data'][] = $data;
            }

            if (($_GET['type'] == "全部" || $_GET['type'] == "二级团队提成") && $order->UpUpSellerID == \Yii::$app->frontsession->seller->lID) {
                $data['level'] = 1;
                $data['title'] = '二级团队提成';
                $data['items'] = [];
                foreach ($order->arrDetail as $detail) {
                    $data['items'][] = [
                        'link' => "/" . \Yii::$app->request->shopUrl . "/member/order?id=" . $order->OrderID,
                        'commodityName' => $detail->sName,
                        'commodityPrice' => $detail->sStatus != "退款成功" ? "￥" . number_format($detail->fProfit * $order->lUpUpSellerCommissionRate / 100,
                                2) : "",
                        'time' => $order->dNewDate,
                        'status' => $detail->sStatus ? $detail->sStatus : $order->order->sStatus
                    ];
                }

                $arrData['data'][] = $data;
            }

        }

        return json_encode($arrData);
    }

    /**
     * 提现申请
     */
    public function actionWithdrawreq()
    {
        if (!\Yii::$app->request->isPost) {
            $data = [];

            $data['seller'] = \Yii::$app->frontsession->seller;
            if (!$data['seller']) {
                return $this->redirect(\Yii::$app->request->mallHomeUrl);
            }

            $this->getView()->title = "提现";
            return $this->render("withdrawreq", $data);
        } else {
            return $this->asJson(\Yii::$app->sellerwithdrawlog->withdraw($_POST['price']));
        }
    }

    /**
     * 提现申请
     */
    public function actionFrozen()
    {
        $data = [];
        $this->getView()->title = "冻结金额";
        $data['sDataJson'] = $this->actionFrozenitem();


        $data['seller'] = \Yii::$app->frontsession->seller;
        if (!$data['seller']) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }


        return $this->render("frozen", $data);
    }


    /**
     * 提现申请接口
     */
    public function actionFrozenitem()
    {
        $arrLog = \Yii::$app->sellerwithdrawlog->withdrawList($_GET['page'], 1);
        $arrData = [];
        foreach ($arrLog as $log) {
            $arrData[] = [
                'title' => "申请提现",
                'price' => "¥" . number_format($log->fMoney, 2),
                'time' => $log->dNewDate
            ];
        }

        return json_encode($arrData);
    }

    /**
     * 收支明细说明
     */
    public function actionFlowdesc()
    {
        $this->getView()->title = "收支明细说明";

        $sellerType = SellerType::find()->all();

        $data = [];

        foreach ($sellerType as $value) {
            if ($value->lID == 2) {
                $data['sSecondType'] = $value->sName;
            } elseif ($value->lID == 3) {
                $data['sThirdType'] = $value->sName;
            }

        }

        return $this->render("flowdesc", $data);
    }

    /**
     * 收支明细
     */
    public function actionFlow()
    {
        $data = [];
        $this->getView()->title = "收支明细";

        $data['seller'] = \Yii::$app->frontsession->seller;
        if (!$data['seller']) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }


        $data['sDataJson'] = $this->actionFlowitem();
        return $this->render("flow", $data);
    }

    /**
     * 收支明细接口
     */
    public function actionFlowitem()
    {
        $sType = "";
        if ($_GET['type'] == "收入" || !$_GET['type']) {
            $sType = 1;
        } elseif ($_GET['type'] == "支出") {
            $sType = 2;
        }

        $arrFlow = \Yii::$app->sellerflow->flowList([
            'page' => $_GET['page'],
            'type' => $sType,
            'date' => $_GET['date'],
        ]);

        $arrData = [];
        foreach ($arrFlow as $flow) {
            $arrData[] = [
                'orderTitle' => $flow->TypeID == 1 && $flow->OrderID ? '订单入账-' . $flow->arrOrderDetail[0]->sName : $flow->sName,
                'commission' => ($flow->fChange > 0 ? "+" : "") . $flow->fChange,
                'orderTime' => $flow->dNewDate,
                'orderType' => $flow->sCommissionType,
                'link' => "/seller/flowdetail?id=" . $flow->lID,
                'contrast' => $flow->fChange > 0 ? 'up' : "down"
            ];
        }

        return json_encode($arrData);
    }


    /**
     * 提现记录
     */
    public function actionWithdraw()
    {
        $data = [];
        $this->getView()->title = "提现记录";

        $data['seller'] = \Yii::$app->frontsession->seller;
        if (!$data['seller']) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }


        $data['sDataJson'] = $this->actionWithdrawitem();
        return $this->render("withdraw", $data);
    }

    /**
     * 提现记录接口
     */
    public function actionWithdrawitem()
    {
        $sType = "";
        if ($_GET['type'] == "提现中") {
            $sType = 1;
        } elseif ($_GET['type'] == "已提现") {
            $sType = 2;
        }

        $arrLog = \Yii::$app->sellerwithdrawlog->withdrawList($_GET['page'], $sType);

        $arrData = [];
        foreach ($arrLog as $log) {
            $arrData[] = [
                'title' => '申请提现',
                'price' => number_format($log->fMoney, 2),
                'time' => $log->dNewDate,
                'link' => "/seller/withdrawdetail?id=" . $log->lID,
                'status' => $log->TypeID == 0 ? "提现中" : $log->sStatus
            ];
        }

        return json_encode($arrData);
    }


    /**
     * 提现详情
     * @param $id
     */
    public function actionWithdrawdetail($id)
    {
        $log = \Yii::$app->sellerwithdrawlog->findByID($id);
        if ($log->SellerID != \Yii::$app->frontsession->MemberID) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }

        $this->getView()->title = "提现详情";

        return $this->render("withdrawdetail", ['log' => $log]);
    }


    /**
     * 收入结算说明
     */
    public function actionIncomedesc()
    {
        $this->getView()->title = "收入结算说明";
        return $this->render("incomedesc");
    }

    /**
     * 客户订单管理
     */
    public function actionOrderlist()
    {
        if (\Yii::$app->request->isAjax) {
            return $this->actionOrderlistmore();
        } else {
            $data = [];
            $this->getView()->title = "客户订单";
            $data['data'] = $this->actionOrderlistmore();
            return $this->render("orderlist", $data);
        }
    }

    /**
     * 客户订单管理接口
     * @return string
     */
    public function actionOrderlistmore()
    {
        if (!in_array($_GET['type'], ['unpaid', 'paid', 'closed', 'delivered', 'success', 'refund'])) {
            $_GET['type'] = "";
        }

        $sRange = "my";
        if ($_GET['range'] == '全部') {
            $sRange = "all";
        }

        if (strlen($_GET['keyword']) > 0) {
            $sRange = "all";
        }

        $lPage = intval($_GET['index']) > 1 ? intval($_GET['index']) : 1;
        $arrSellerOrder = \Yii::$app->sellerorder->orderList([
            'StatusID' => $_GET['type'],
            'sKeyword' => $_GET['keyword'],
            'sRange' => $sRange,
            'lPage' => $lPage
        ]);

        $arrProductData = [];
        foreach ($arrSellerOrder as $sellerOrder) {
            $order = $sellerOrder->order;

            $data = [];
            $data['shopname'] = $order->supplier->sName;
            $data['status'] = $order->sStatus;
            $data['order_id'] = $order->lID;
            $data['status'] = $order->sStatus;
            $data['shipped'] = $order->bHasShip;
            $data['commodity'] = [];
            $data['link'] = Url::toRoute([
                \Yii::$app->request->shopUrl . '/supplier/detail',
                'id' => $order->SupplierID
            ]);
            $data['orderlink'] = Url::toRoute([
                '/seller/order',
                'id' => $order->lID
            ]);

            $data['buyer'] = $order->member->sName;
            $data['receipter'] = $order->orderAddress->sName;
            $data['phone'] = $order->orderAddress->sMobile;
            $data['address'] = $order->orderAddress->province->sName . " " . $order->orderAddress->city->sName . " " . $order->orderAddress->area->sName . " " . $order->orderAddress->sAddress;
            $data['seller'] = $sellerOrder->seller->sName;

            $data['commissionLevelList'] = [];
            if ($sellerOrder->SellerID == \Yii::$app->frontsession->seller->lID) {
                $data['commissionLevelList'][] = [
                    'commission' => number_format($sellerOrder->fSellerCommission, 2),
                    'commissionLevel' => "销售提成"
                ];
            }

            if ($sellerOrder->UpSellerID == \Yii::$app->frontsession->seller->lID) {
                $data['commissionLevelList'][] = [
                    'commission' => number_format($sellerOrder->fUpSellerCommission, 2),
                    'commissionLevel' => "一级团队提成"
                ];
            }

            if ($sellerOrder->UpUpSellerID == \Yii::$app->frontsession->seller->lID) {
                $data['commissionLevelList'][] = [
                    'commission' => number_format($sellerOrder->fUpUpSellerCommission, 2),
                    'commissionLevel' => "二级团队提成"
                ];
            }


            foreach ($sellerOrder->arrDetail as $detail) {
                $data['commodity'][] = [
                    'images' => \Yii::$app->request->imgUrl . '/' . $detail->sPic,
                    'title' => $detail->sName,
                    'spec' => $detail->sSKU,
                    'price' => $detail->fPrice,
                    'num' => $detail->lQuantity,
                    'isRefund' => $detail->bRefunding,
                    'link' => Url::toRoute([
                        '/member/order',
                        'id' => $order->lID
                    ]),
                    'refundStatus' => $detail->sStatus == '退款关闭' ? "" : $detail->sStatus,
                ];
            }

            $arrProductData[] = $data;
        }

        return json_encode(['status' => true, 'data' => $arrProductData]);
    }

    /**
     * 团队管理
     */
    public function actionMyteam()
    {
        //判断用户是否顶级代理 panlong 2019年9月9日17:31:17
        $seller = Seller::findOne(['MemberID' => \Yii::$app->frontsession->member->lID]);
        if (!$seller) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }

        $data = [];
        $this->getView()->title = "团队管理";
        $data['sDataJson'] = $this->actionMyteamitem();

        $data['sTypeJson'][] = ['name' => '全部', 'isMark' => true];
        $arrSellerType = SellerType::find()->all();
        foreach ($arrSellerType as $type) {
            $data['sTypeJson'][] = [
                'name' => $type->sName,
                'isMark' => false
            ];
        }
        $data['sTypeJson'] = json_encode($data['sTypeJson']);

        return $this->render("myteam", $data);
    }

    /**
     * 团队管理接口
     */
    public function actionMyteamitem()
    {
        $sKeyword = $_GET['keyword'];
        $sType = $_GET['type'] ? $_GET['type'] : '普通用户';
        $lLimit = 10;
        $sOrderBy = "dNewDate";
        $lPage = $_GET['page'] > 0 ? $_GET['page'] : 1;
        $sOrderByDir = $_GET['orderbydir'] == 'up' ? "ASC" : "DESC";

        $sql = Member::find()
            ->where(['FromMemberID' => \Yii::$app->frontsession->member->lID]);

        //有关键词则根据关键词搜索，否则根据身份分类搜索
        if ($sKeyword) {
            $team = Member::find()
                ->where(['FromMemberID' => \Yii::$app->frontsession->member->lID])
                ->andwhere(['or',
                    ['like', 'sName', $sKeyword],
                    ['like', 'sMobile', $sKeyword],
                ])
                ->orderBy($sOrderBy . ' ' . $sOrderByDir)
                ->limit($lLimit)
                ->offset(($lPage - 1) * $lLimit)
                ->all();
            $lTeamNum = count($team);
        } else {
            if ($sType == '普通用户') {
                $sql->andWhere('lID not in (SELECT MemberID from seller)');
                $lTeamNum = Member::find()
                    ->where(['FromMemberID' => \Yii::$app->frontsession->member->lID])
                    ->andWhere('lID not in (SELECT MemberID from seller)')
                    ->count();
            } elseif ($sType == 'VIP用户') {
                $sql->andWhere('lID in (SELECT MemberID from seller)');
                $lTeamNum = Member::find()
                    ->where(['FromMemberID' => \Yii::$app->frontsession->member->lID])
                    ->andWhere('lID in (SELECT MemberID from seller)')
                    ->count();
            } elseif ($sType == '全部') {
                $lTeamNum = Member::find()
                    ->where(['FromMemberID' => \Yii::$app->frontsession->member->lID])
                    ->count();
            }

            $team = $sql->orderBy($sOrderBy . ' ' . $sOrderByDir)
                ->limit($lLimit)
                ->offset(($lPage - 1) * $lLimit)
                ->all();
        }

        $arrData = ['teamPerson' => $lTeamNum, 'personList' => []];

        //判断是否还有未加载数据 panlong 2019年9月10日09:27:25
        $bMore = false;
        if (count($team) == $lLimit) {
            $bMore = true;
        }
        $arrData['bMore'] = $bMore;

        foreach ($team as $member) {
            $data = [];
            $data['personName'] = $member->sName;
            $data['personLogo'] = $member->avatar;
            $data['ID'] = $member->lID;
            $data['link'] = "/seller/teamdetail?id=" . $member->lID;
            $data['bAgent'] = $member->bAgent;
            $data['dNewDate'] = $member->dNewDate;

            $arrData['personList'][] = $data;
        }

        return json_encode($arrData);
    }

    /**
     * 团队管理搜索
     */
    public function actionMyteamsearch()
    {
        $data = [];
        $this->getView()->title = "团队管理";
        $data['sDataJson'] = $this->actionMyteamitem();

        $data['sTypeJson'][] = ['name' => '全部', 'isMark' => true];
        $arrSellerType = SellerType::find()->all();
        foreach ($arrSellerType as $type) {
            $data['sTypeJson'][] = [
                'name' => $type->sName,
                'isMark' => false
            ];
        }
        $data['sTypeJson'] = json_encode($data['sTypeJson']);

        return $this->render("myteamsearch", $data);
    }

    /**
     * 客户管理
     */
    public function actionMycustomer()
    {
        $data = [];
        $this->getView()->title = "客户管理";
        $data['sDataJson'] = $this->actionMycustomeritem();

        return $this->render("mycustomer", $data);
    }

    /**
     * 客户管理接口
     */
    public function actionMycustomeritem()
    {
        if (!$_GET['orderby'] || $_GET['orderby'] == "注册时间") {
            $sOrderBy = "dNewDate";
        } elseif ($_GET['orderby'] == "累计消费") {
            $sOrderBy = "fSumConsume";
        }

        $result = \Yii::$app->member->customerList([
            'sOrderBy' => $sOrderBy,
            'sOrderByDir' => $_GET['orderbydir'] == 'up' ? "" : "DESC",
            'lPage' => $_GET['page'],
            'sType' => $_GET['type'] == "普通用户" ? "member" : ""
        ]);

        $arrMember = $result[1];
        $arrData = ['userNumber' => $result[0]];
        foreach ($arrMember as $member) {
            $arrData['userList'][] = [
                'customerName' => $member->sName,
                'customerLogo' => $member->avatar,
                'consumption' => $member->fSumConsume,
                'registerTime' => $member->dNewDate,
            ];
        }

        return json_encode($arrData);
    }


    /**
     * 发展团队说明
     */
    public function actionTeamdesc()
    {
        $this->getView()->title = "发展团队说明";
        return $this->render("teamdesc");
    }


    /**
     * 客户说明
     */
    public function actionCustomerdesc()
    {
        $this->getView()->title = "客户说明";
        return $this->render("customerdesc");
    }

    /**
     * 经销商详情
     * @param $id
     */
    public function actionTeamdetail($id)
    {
        $seller = \Yii::$app->seller->findByID($id);
        if (!$seller || $seller->lID != \Yii::$app->frontsession->MemberID && $seller->UpID != \Yii::$app->frontsession->MemberID && $seller->UpUpID != \Yii::$app->frontsession->MemberID) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }

        $this->getView()->title = "经销商详情";

        return $this->render("teamdetail", ['seller' => $seller]);
    }

    /**
     * 收支流水详情
     * @param $id
     */
    public function actionFlowdetail($id)
    {
        $flow = \Yii::$app->sellerflow->findByID($id);
        if ($flow->SellerID != \Yii::$app->frontsession->MemberID) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }

        if ($flow->OrderID) {
            $this->getView()->title = "流水详情-订单入账";
            return $this->render("flowdetail_income", ['flow' => $flow]);
        } else {
            $this->getView()->title = "流水详情-提现";
            return $this->render("flowdetail_withdraw", ['flow' => $flow]);
        }

    }

    public function actionSendwithdrawcode()
    {
        $seller = \Yii::$app->seller->findByID(\Yii::$app->frontsession->MemberID);
        if (!$seller) {
            return $this->asJson(['status' => false, 'message' => '请登录后再操作']);
        }

        if (\Yii::$app->request->isPost) {
            $return = VerifyCode::send($seller->sMobile);
            if ($return['status']) {
                $return['message'] = "已发送至尾号为" . substr($seller->sMobile, -4) . "的手机";
            }

            return $this->asJson($return);
        }
    }

    /**
     * 邀请好友
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年07月18日
     */
    public function actionInvitefriends()
    {
        if (MallConfig::getValueByKey("bUpgradeSellerAfterBuyAnything")) {
            $arrData = [];
            $arrData['img'] = InviteConfig::getConfig('sBackgroundImgFriends');
        } else {
            $sImg = InviteConfig::getConfig('sBackgroundImgFriends');
            $objSeller = \Yii::$app->frontsession->seller;
            $sceneValue = 'inviteFriends,' . $objSeller->MemberID . ',' . $objSeller->sName . ',' . \Yii::$app->frontsession->sOpenID;
            $objQRCode = \Yii::$app->wechat->qrcode->temporary($sceneValue, 2592000);
            $arrData = [];
            $arrData['img'] = $sImg;
            $arrData['url'] = \Yii::$app->wechat->qrcode->url($objQRCode->ticket);
        }

        return $this->render('invitefriends', $arrData);
    }

    /**
     * 邀请开店
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年07月18日
     */
    public function actionInviteshop()
    {
        if (MallConfig::getValueByKey("bUpgradeSellerAfterBuyAnything")) {
            $arrData = [];
            $arrData['img'] = InviteConfig::getConfig('sBackgroundImgShop');
        } else {
            $sImg = InviteConfig::getConfig('sBackgroundImgShop');
            $objSeller = \Yii::$app->frontsession->seller;
            $sceneValue = 'inviteShop,' . $objSeller->MemberID . ',' . $objSeller->sName . ',' . \Yii::$app->frontsession->sOpenID;
            $objQRCode = \Yii::$app->wechat->qrcode->temporary($sceneValue, 2592000);
            $arrData = [];
            $arrData['img'] = $sImg;
            $arrData['url'] = \Yii::$app->wechat->qrcode->url($objQRCode->ticket);
        }

        return $this->render('inviteshop', $arrData);
    }

    /**
     * 添加菜单
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年09月14日
     */
    public function actionAddmenu()
    {
        $arrMenu = [
            [
                "type" => "view",
                "name" => "大农云商城",
                "url" => "http://m.dny.group/shop0/home"
            ],
            [
                "type" => "view",
                "name" => "渠道中心",
                "url" => "http://m.dny.group/wholesaler"
            ],
            [
                "type" => "view",
                "name" => "经销中心",
                "url" => "http://m.dny.group/seller"
            ]
        ];
        $result = \Yii::$app->wechat->menu->add($arrMenu);
        var_dump($result);
    }

    /**
     * 升级用户为VIP用户（初级用户）
     * @author panlong
     * @time 2019年9月10日16:51:32
     */
    public function actionUpgrade()
    {
        $RecommendID = \Yii::$app->frontsession->member->lID;
        $ID = $_POST['ID'];

        if (!$ID) {
            return json_encode(['status' => false, 'msg' => '用户ID丢失']);
        }

        $seller = Seller::findOne(['MemberID' => $ID]);
        if ($seller) {
            return json_encode(['status' => false, 'msg' => '用户已是代理']);
        }

        $recommend = Seller::findOne(['MemberID' => $RecommendID]);
        if (!$recommend || $recommend->TypeID != Seller::TOP) {
            return json_encode(['status' => false, 'msg' => '没有操作权限']);
        }

        \Yii::$app->seller->levelUp($ID, $RecommendID, Seller::JUNIOR);

        return json_encode(['status' => true, 'msg' => '升级成功']);
    }
    public function actionDowngrade()
    {
        $RecommendID = \Yii::$app->frontsession->member->lID;
        $ID = $_POST['ID'];

        if (!$ID) {
            return json_encode(['status' => false, 'msg' => '用户ID丢失']);
        }

        $recommend = Seller::findOne(['MemberID' => $RecommendID]);
        if (!$recommend || $recommend->TypeID != Seller::TOP) {
            return json_encode(['status' => false, 'msg' => '没有操作权限']);
        }
        $seller = Seller::findOne(['MemberID' => $ID]);
        if ($seller) {
            $seller->delete();
            $shop=SellerShop::findOne($seller->lID);
            if($shop){
                $shop->delete();
            }
        }


        return json_encode(['status' => true, 'msg' => '取消成功']);
    }
}