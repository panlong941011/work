<?php

namespace myerm\shop\mobile\controllers;

use EasyWeChat\Payment\Order;
use myerm\common\components\CommonEvent;
use myerm\common\libs\File;
use myerm\common\libs\NewID;
use myerm\common\models\ExpressCompany;
use myerm\shop\common\models\GoldRecharge;
use myerm\shop\common\models\GoldRechargeConfig;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\OrderBatchPay;
use myerm\shop\common\models\Refund;
use myerm\shop\common\models\RefundMoneyBack;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\SellerFlow;
use myerm\shop\common\models\SellerShop;
use myerm\shop\common\models\VerifyCode;
use myerm\shop\mobile\models\CloudOrder;
use myerm\shop\mobile\models\Supplier;
use yii\helpers\Url;
use yii\web\HttpException;


/**
 * 会员控制器
 */
class MemberController extends Controller
{
    /**
     * 微信回调的事件
     */
    const EVENT_WECHAT_CALLBACK = 'wechat_callback';

    /**
     * 退出
     */
    const EVENT_LOGOUT = 'logout';

    public function behaviors()
    {
        return [
            [
                'class' => 'myerm\shop\mobile\filters\LoginAccessControl',
                'except' => [
                    'login',
                    'loginpost',
                    'reg',
                    'forgot',
                    'regcontract',
                    'sendforgotcode',
                    'sendregcode',
                    'wechatcallback',
                    'shopavatar',
                    'index'
                ],
            ],
        ];
    }

    /**
     * 注册
     */
    public function actionReg()
    {
        if (\Yii::$app->request->isPost) {

            if ($_POST['verifycode'] != VerifyCode::getCode()) {
                return $this->asJson(['status' => false, 'message' => '验证码不正确']);
            }

            $member = \Yii::$app->member->findByMobile($_POST['mobile']);
            if ($member) {
                return $this->asJson(['status' => false, 'message' => '手机号已经被注册']);
            }

            \Yii::$app->member->reg($_POST['mobile'], $_POST['password']);

            return $this->asJson(['status' => true]);
        } else {
            $data = [];

            $this->getView()->title = "注册";

            return $this->render("reg", $data);
        }
    }

    public function actionRegcontract()
    {
        return $this->render("regcontract", []);
    }

    /**
     * 登陆页
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $data = [];

        if ($_GET['sReturnUrl']) {
            $data['sReturnUrl'] = urldecode($_GET['sReturnUrl']);
        } elseif (\Yii::$app->request->referrer) {
            //跳转到上一个地址
            $data['sReturnUrl'] = \Yii::$app->request->referrer;
        } else {
            //跳转到首页
            $data['sReturnUrl'] = \Yii::$app->request->mallHomeUrl;
        }

        //如果已经是登陆状态，跳转指定URL。
        if (\Yii::$app->frontsession->bLogin) {
            return $this->redirect($data['sReturnUrl']);
        }

        $this->getView()->title = "登录";

        if (!MallConfig::getValueByKey('bRequireMobileReg')) {
            return $this->render("wxlogin", $data);
        } else {
            return $this->render("mobilelogin", $data);
        }

    }

    /**
     * 登陆提交
     * @author Mars
     * @time 2017年9月20日 13:42:06
     */
    public function actionLoginpost()
    {
        if (\Yii::$app->request->isPost && MallConfig::getValueByKey('bRequireMobileReg')) {
            return $this->asJson(\Yii::$app->frontsession->mobileLogin($_POST['mobile'], $_POST['password']));
        }

        if (\Yii::$app->frontsession->bLogin) {
            return $this->redirect(urldecode($_GET['sReturnUrl']));
        }

        //微信验证
        $oauth = \Yii::$app->wechat->oauth;
        \Yii::$app->wechat->setReturnUrl(urldecode($_GET['sReturnUrl']));

        return $oauth->redirect(Url::toRoute([
            \Yii::$app->request->shopUrl . "/member/wechatcallback",
            'sReturnUrl' => urldecode($_GET['sReturnUrl'])
        ], true));
    }

    /**
     * 微信验证返回的控制器
     * @author Mars
     * @time 2017年9月20日 13:42:06
     */
    public function actionWechatcallback()
    {
        // 获取 OAuth 授权结果用户信息
        $user = \Yii::$app->wechat->oauth->user();
        \Yii::trace(var_export($user->toArray(), true), "微信用户原始信息");

        //触发回调事件
        \Yii::trace("触发微信回调事件");
        $event = new CommonEvent;
        $event->extraData = $user;
        $this->trigger(self::EVENT_WECHAT_CALLBACK, $event);
        //返回
        return $this->redirect(urldecode($_GET['sReturnUrl']));
    }

    public function actionSendregcode($mobile)
    {
        if (\Yii::$app->request->isPost) {
            $member = \Yii::$app->member->findByMobile($mobile);
            if ($member) {
                return $this->asJson(['status' => false, 'message' => '手机号已经被注册']);
            }
            return $this->asJson(VerifyCode::send($mobile));
        }
    }

    /**
     * 个人中心首页
     */
    public function actionIndex()
    {
        $this->getView()->title = "个人中心";
        $data = [];
        $data['member'] = \Yii::$app->frontsession->member;
        if (\Yii::$app->frontsession->MemberID) {
            $data['arrStatusCount'] = \Yii::$app->order->getStatusCount(\Yii::$app->frontsession->MemberID);
        }
        $seller = Seller::findOne(['MemberID' => \Yii::$app->frontsession->MemberID]);
        $data['seller'] = $seller;
        if ($seller && $seller->TypeID == Seller::TOP) {
            $data['bTop'] = true;
            $data['seller'] = \Yii::$app->seller->seller;
            $data['shop'] = \Yii::$app->sellershop->getShop($seller->lID);
            $supplierID = \Yii::$app->frontsession->member->SupplierID;

            $unDelivere = CloudOrder::find()
                ->where(['Order.StatusID' => 'paid', 'Order.SupplierID' => $supplierID])
                ->leftJoin('ylcloud.OrderDetail', 'Order.lID=OrderDetail.OrderID')
                ->sum('OrderDetail.lQuantity');
            $data['unDelivere'] = $unDelivere;

        } else {
            $TopID = \Yii::$app->seller->topSeller;
            $shop = \Yii::$app->sellershop->getShop($TopID);
            if ($TopID) {
                $data['shop'] = $shop;
            }
        }

        return $this->render("index", $data);
    }

    /**
     * 收货地址管理
     */
    public function actionAddresslist()
    {
        $data = [];

        $data['arrAddress'] = \Yii::$app->memberaddress->findAllAddress();

        $this->getView()->title = "地址选择";

        return $this->render("addresslist", $data);
    }

    /**
     * 订单搜索结果
     */
    public function actionOrdersearch()
    {
        if (\Yii::$app->request->isAjax) {
            return $this->actionOrderlistmore();
        } else {
            $data = [];
            $this->getView()->title = "我的订单";
            $data['data'] = $this->actionOrderlistmore();
            return $this->render("ordersearch", $data);
        }
    }

    public function actionOrderlistmore()
    {
        if (!in_array($_GET['type'], ['unpaid', 'paid', 'closed', 'delivered', 'success', 'refund'])) {
            $_GET['type'] = "";
        }
        $seller = SellerShop::findOne(['lID' => \Yii::$app->frontsession->MemberID]);
        if($_GET['type']!='unpaid'&&$seller){
          return  $this->getProfitorder();
        }
        $lPage = intval($_GET['index']) > 1 ? intval($_GET['index']) : 1;
        $result = \Yii::$app->order->memberList([
            'MemberID' => \Yii::$app->frontsession->MemberID,
            'StatusID' => $_GET['type'],
            'sKeyword' => $_GET['keyword'],
            'lPage' => $lPage
        ]);

        $arrOrder = $result[1];

        $arrProductData = [];
        foreach ($arrOrder as $order) {
            $data = [];
            $data['shopname'] = '';
            $data['sName'] = $order->sName;
            $data['status'] = $order->sStatus;
            $data['OrderType'] = $order->OrderType;
            $data['order_id'] = $order->lID;
            $data['allNum'] = 0;
            $data['all'] = $order->fSumOrder;
            $data['freight'] = $order->fSupplierShip;
            $data['shipped'] = $order->bHasShip;
            $data['commodity'] = [];
            $data['link'] = Url::toRoute([
                \Yii::$app->request->shopUrl . '/supplier/detail',
                'id' => $order->SupplierID
            ]);
            foreach ($order->arrDetail as $detail) {
                $data['allNum'] += $detail->lQuantity;

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
                    'refund_status' => $detail->sStatus == '退款关闭' ? "" : $detail->sStatus,
                ];
            }

            $arrProductData[] = $data;
        }

        $isMore = true;
        if ($lPage > floor($result[0] / 10)) {
            $isMore = false;
        }

        return json_encode(['status' => true, 'isMore' => $isMore, 'data' => $arrProductData]);
    }

    /**
     * 订单管理
     */
    public function actionOrderlist()
    {
        if (\Yii::$app->request->isAjax) {
            return $this->actionOrderlistmore();
        } else {
            $data = [];
            $this->getView()->title = "我的订单";
            $data['data'] = $this->actionOrderlistmore();
            return $this->render("orderlist", $data);
        }
    }

    /**
     * 微信付款
     */
    public function actionWxpay()
    {
        $OrderID = $_GET['id'];
        $order = \Yii::$app->order::findByID($OrderID);
        if (!$order) {
            return $this->asJson(['status' => false, 'message' => "订单不存在"]);
        } elseif ($order->StatusID != 'unpaid') {
            return $this->asJson(['status' => false, 'message' => "只有待付款的订单才可以付款"]);
        }

        $sTradeNo = NewID::make();//批量付款的商户订单号

        //保存批量付款的信息
        OrderBatchPay::batchPaySave($sTradeNo, [$OrderID]);

        return $this->redirect("/cart/cashier?no=$sTradeNo");

        if (\Yii::$app->params['isWeChat']) {
            //$order->fDue = 0.01;
            $attributes = [
                'trade_type' => 'JSAPI',
                // JSAPI，NATIVE，APP...
                'body' => '支付',
                'out_trade_no' => $sTradeNo,
                'total_fee' => $order->fDue * 100,
                // 单位：分
                'notify_url' => Url::toRoute(['/pay/wxnotify'], true),
                //支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => \Yii::$app->frontsession->sOpenID,
            ];

            $order = new Order($attributes);
            $result = \Yii::$app->wechat->payment->prepare($order);
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
                $return['config'] = \Yii::$app->wechat->payment->configForJSSDKPayment($result->prepay_id);
                $return['status'] = true;
            } else {
                $return['status'] = false;
                $return['message'] = $result->get('return_msg');
            }
        } else {
            require '../../../common/libs/wechat_app_pay/Wechatconfig.php';
            require '../../../common/libs/wechat_app_pay/WechatAPISDK.php';

            $params['total_fee'] = $order->fDue * 100;
            $params['body'] = '支付';
            $params['out_trade_no'] = $sTradeNo;
            $params['spbill_create_ip'] = get_client_ip();

            $wechat_config = [];
            $wechat_config['app_id'] = "wx61e9efc271be87f2";// 公众号身份标识
            $wechat_config['app_secret'] = "8eb1bd96a60f8231e13e6f0874561309";// 权限获取所需密钥 Key
            $wechat_config['pay_sign_key'] = "Qn5LM9fCDMDNNeFEqITN4cxCNyzKceB8szwWmUqT4laGqK5SapxD8r38gC3qM9BK8UEc2VeiDWs4OJcg8W6rtjLvHc0Jraq6fS7ph1YUzbPMHyQutwqgohHuSDifytCw";// 加密密钥 Key，也即appKey
            $wechat_config['partner_id'] = '1218891802';// 财付通商户身份标识
            $wechat_config['partner_key'] = 'c72fecdb206e8d7cee2b4f4da861693f';// 财付通商户权限密钥 Key
            $wechat_config['notify_url'] = 'http:/www.funboxpower/wechat_notify.php';// 微信支付完成服务器通知页面地址
            $wechat_config['cacert_url'] = dirname(__FILE__) . '/1218891801_20140425185952.pfx';

            $wechat = new \Wechat($wechat_config);
            $access_token = $wechat->getAccessToken();
            $tran_result = $wechat->createOrder($access_token, $params);
        }


        return $this->asJson($return);
    }

    /**
     * 确认收货
     */
    public function actionConfirmreceive()
    {
        $OrderID = $_GET['id'];
        $order = \Yii::$app->order::findByID($OrderID);

        if (!$order) {
            return $this->asJson(['status' => false, 'message' => "订单不存在"]);
        } elseif ($order->MemberID != \Yii::$app->frontsession->MemberID) {
            return $this->asJson(['status' => false, 'message' => "只有自己的订单才可以确认收货"]);
        } elseif ($order->StatusID != \myerm\shop\common\models\Order::STATUS_DELIVERED) {
            return $this->asJson(['status' => false, 'message' => "只有已发货的订单才可以确认收货"]);
        }
        //确认收货
        $order->confirmReceive();

        return $this->asJson(['status' => true]);
    }


    /**&
     * 物流跟踪
     */
    public function actionTrace()
    {
        $OrderID = $_GET['id'];
        $order = \Yii::$app->order->findByID($OrderID);

        $data = [];
        if (!$order) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        } elseif ($order->MemberID != \Yii::$app->frontsession->MemberID) {
            $seller = \Yii::$app->frontsession->seller;
            $sellerOrder = \Yii::$app->sellerorder->findByID($OrderID);

            if (!$seller || !$sellerOrder || $seller->lID != $sellerOrder->SellerID && $seller->lID != $sellerOrder->UpSellerID && $seller->lID != $sellerOrder->UpUpSellerID) {
                return $this->redirect(\Yii::$app->request->mallHomeUrl);
            }
            $data['status'] = true;
            $data['arrTrace'] = $order->arrTrace;
        } else {
            $data['status'] = true;
            $data['arrTrace'] = $order->arrTrace;
        }

        $this->getView()->title = "物流信息";

        return $this->render("trace", $data);
    }

    /**
     * 查看订单详情
     * @param $id
     */
    public function actionOrder($id)
    {
        $OrderID = $_GET['id'];
        $order = \Yii::$app->order::findByID($OrderID);

        $data = [];
        if (!$order) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }
        $data['bMyOrder'] = true;//是我自己下的单

        $data['order'] = $order;
        if (($order->StatusID == 'paid' || $order->StatusID == 'delivered') && !$order->RefundStatusID) {
            $data['canRefund'] = true;
        } else {
            $data['canRefund'] = false;
        }
        $this->getView()->title = "订单详情";

        return $this->render("order", $data);
    }

    public function actionRefundpolicy()
    {
        $this->getView()->title = "退款政策";
        return $this->render("refundpolicy", ['desc' => MallConfig::getValueByKey('sAfterSaleNote')]);
    }

    /**
     * 填写退货信息
     */
    public function actionRefundship($id)
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->refund->inputRefundShip([
                'id' => $id,
                'ShipCompanyID' => ExpressCompany::findOne([
                    'sName' => preg_replace("/^[a-z]\-/i", "", $_POST['company'])
                ])->ID,
                'sShipNo' => $_POST['shipno'],
                'sMobile' => $_POST['mobile'],
                'imgList' => $_POST['imglist']
            ]);

            //如果是云订单，将退货物流提交至云端
            $refund = \Yii::$app->refund->findByID($id);
            if ($refund->order->bCloud) {

                $arrShipVoucher = json_decode($refund->sShipVoucher, true);
                $arrShipImg = [];
                if (is_array($arrShipVoucher)) {
                    foreach ($arrShipVoucher as $sShipVoucher) {
                        $arrShipImg[] = \Yii::$app->request->imgUrl . '/' . $sShipVoucher;
                    }
                }

                $arrRefundVoucher = json_decode($refund->sRefundVoucher, true);
                $arrRefundImg = [];
                if (is_array($arrRefundVoucher)) {
                    foreach ($arrRefundVoucher as $sRefundVoucher) {
                        $arrRefundImg[] = \Yii::$app->request->imgUrl . '/' . $sRefundVoucher;
                    }
                }

                $data_api = [];
                $data_api['sn'] = $refund->sName;
                $data_api['shipno'] = $_POST['shipno'];
                $data_api['shipcompany'] = ExpressCompany::findOne(['sName' => preg_replace("/^[a-z]\-/i", "", $_POST['company'])])->ID;
                $data_api['mobile'] = $_POST['mobile'];
                $data_api['img'] = json_encode($arrRefundImg);

                $result = \Yii::$app->dnyapi->returnShip('v1/refund/returnship', $data_api);

                if ($result['status'] != '10000') {
                    return $this->asJson(['status' => false]);
                }
            }
            return $this->asJson(['status' => true]);
        } else {
            $data = [];

            $data['refund'] = \Yii::$app->refund::findByID($id);

            $data['arrCompany'] = ExpressCompany::find()->orderBy("sPinYin")->all();

            $this->getView()->title = "填写退货物流信息";

            return $this->render("refundship", $data);
        }
    }

    /**
     * 用户修改退货信息
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionModifyrefundship($id)
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->refund->modifyRefundShip([
                'id' => $id,
                'ShipCompanyID' => ExpressCompany::findOne([
                    'sName' => preg_replace("/^[a-z]\-/i", "", $_POST['company'])
                ])->ID,
                'sShipNo' => $_POST['shipno'],
                'sMobile' => $_POST['mobile'],
                'imgList' => $_POST['imglist']
            ]);

            //如果是云订单，将退货物流提交至云端
            $refund = \Yii::$app->refund->findByID($id);
            if ($refund->order->bCloud) {

                $arrShipVoucher = json_decode($refund->sShipVoucher, true);
                $arrShipImg = [];
                if (is_array($arrShipVoucher)) {
                    foreach ($arrShipVoucher as $sShipVoucher) {
                        $arrShipImg[] = \Yii::$app->request->imgUrl . '/' . $sShipVoucher;
                    }
                }

                $data_api = [];
                $data_api['sn'] = $refund->sName;
                $data_api['shipno'] = $_POST['shipno'];
                $data_api['shipcompany'] = ExpressCompany::findOne(['sName' => preg_replace("/^[a-z]\-/i", "", $_POST['company'])])->ID;
                $data_api['mobile'] = $_POST['mobile'];
                $data_api['img'] = json_encode($arrShipImg);

                $result = \Yii::$app->dnyapi->modifyReturnship('v1/refund/modifyreturnship', $data_api);
                if ($result['status'] != '10000') {
                    return $this->asJson(['status' => false]);
                }
            }

            return $this->asJson(['status' => true]);
        } else {
            $data = [];

            $data['refund'] = \Yii::$app->refund::findByID($id);

            $data['arrCompany'] = ExpressCompany::find()->orderBy("sPinYin")->all();

            $this->getView()->title = "修改退货物流信息";

            $imgList = json_decode($data['refund']->sShipVoucher, true);
            if ($imgList) {
                foreach ($imgList as $i => $img) {
                    $imgList[$i] = \Yii::$app->request->imgUrl . '/' . $img;
                }
            }
            $data['imgList'] = json_encode($imgList);

            return $this->render("modifyrefundship", $data);
        }
    }

    /**
     * 用户发起退款
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionRefundapply($id)
    {
        $order = \Yii::$app->order::findOne($id);
        $orderDetail = $order->orderDetail;
        if (\Yii::$app->request->isPost) {
            if ($_POST['type'] == '仅退款') {
                $TypeID = 'onlymoney';
            } else {
                $TypeID = 'moneyandproduct';
            }
            /* 如果是未发货退款申请，提交时再验证一次订单状态 */
            if ($_POST['unship']) {
                if (!empty($orderDetail->sShipNo)) {
                    return $this->asJson(['status' => false, 'id' => $orderDetail->OrderID, 'msg' => '该商品已发货，请重新提交退款申请']);
                }
                $fRefundReal = $order->fSumOrder;
            } else {
                $fRefundReal = $_POST['money'];
            }
            $fRefundProduct = $order->fSumOrder - $order->fShip;
            $refundData = [
                'TypeID' => $TypeID,
                'sReason' => $_POST['reason'],
                'OrderDetailID' => $id,
                'fRefundApply' => $fRefundReal,
                'sExplain' => $_POST['explain'],
                'imgList' => $_POST['imglist'],
                'lItemTotal' => $_POST['totalnum'],
                'lRefundItem' => $_POST['refundnum'],
                'fRefundReal' => $fRefundReal,
                'fRefundProduct' => $fRefundProduct,
                'fPaid' => $order->fPaid,
                'fShip' => $order->fShip,
                'sClientSN' => $order->sName,
                'OrderID' => $order->lID,
                'OrderDetailID' => $orderDetail->lID,
                'SupplierID' => $order->SupplierID,
                'MemberID' => $order->MemberID,
                'sName' => $order->sName,
                'ProductID' => $orderDetail->ProductID,
                'lQuantity' => $orderDetail->lQuantity
            ];
            $Refund = \Yii::$app->refund->saveRefund($refundData);
            $RefundID = $Refund['RefundID'];
            //调整订单状态
            $order->RefundStatusID = 'refunding';
            $order->save();
            /* 如果是云订单调用云端API将订单退款状态传递至云端 开始*/
            $cloudOrder = CloudOrder::find()->where(['sClientSN' => $order->sName])->one();
            if ($cloudOrder) {
                $cloudOrder->RefundStatusID = 'refunding';
                $cloudOrder->save();
            }
            /* 如果是云订单调用云端API将订单退款状态传递至云端 结束 */
            return $this->asJson(['status' => true, 'id' => $RefundID, 'message' => '提交成功']);
        } else {

            $data = [];
            if ($orderDetail->StatusID == 'success' || $orderDetail->StatusID == 'refunding') {
                $RefundID = Refund::find()->where(['OrderDetailID' => $orderDetail->lID])->one();
                $data['RefundID'] = $RefundID->lID;
            }

            /* 根据商品价格占订单中同类运费模板商品总价比例退还运费 */
            $fTotalShip = $order->fShip;
            $data['fTotalShip'] = $fTotalShip;
            $data['fRefundMoney'] = $order->fPaid;

            /*  订单若未发货,则默认填写退款数量，金额等数据 */
            if (!$orderDetail->dShipDate) {
                $data['defaultItem'] = $orderDetail->lQuantity;
                $data['defaultMoney'] = $orderDetail->fTotal + $fTotalShip;
                $data['unship'] = true;
            }

            if (!$orderDetail) {
                throw new HttpException(404);
            } elseif ($order->MemberID != \Yii::$app->frontsession->MemberID) {
                throw new HttpException(404);
            }
            $data['orderDetail'] = $orderDetail;
            $this->getView()->title = "退款申请";
            return $this->render("refundapply", $data);
        }
    }

    /**
     * 退款详情
     * @param $id
     * @return string
     * @throws HttpException
     */
    public function actionRefunddetail($id)
    {
        $refundDetail = \Yii::$app->refund::findByID($id);
        $orderDetail = $refundDetail->orderDetail;

        if (!$orderDetail) {
            throw new HttpException(404);
        } elseif ($orderDetail->order->MemberID != \Yii::$app->frontsession->MemberID) {
            throw new HttpException(404);
        }

        $data['orderDetail'] = $orderDetail;
        $data['refund'] = $refundDetail;

        $this->getView()->title = "退款详情";

        return $this->render("refunddetail", $data);
    }


    /**
     * 旧的修改退款信息，留作备份
     * @param $id
     */
    public function actionModifyrefundold($id)
    {
        if (\Yii::$app->request->isPost) {

            if ($_POST['type'] == '仅退款') {
                $TypeID = 'onlymoney';
            } else {
                $TypeID = 'moneyandproduct';
            }

            \Yii::$app->refund->modifyRefund([
                'TypeID' => $TypeID,
                'sReason' => $_POST['reason'],
                'lID' => $id,
                'fRefundApply' => $_POST['money'],
                'sExplain' => $_POST['explain'],
                'imgList' => $_POST['imglist'],
                'lItemTotal' => $_POST['totalnum'],
                'lRefundItem' => $_POST['refundnum']
            ]);

            return $this->asJson(['status' => true]);
        } else {
            $refundDetail = \Yii::$app->refund::findByID($id);
            $orderDetail = $refundDetail->orderDetail;

            if (!$orderDetail) {
                throw new HttpException(404);
            } elseif ($orderDetail->order->MemberID != \Yii::$app->frontsession->MemberID) {
                throw new HttpException(404);
            }

            if ($orderDetail) {
                $data['fRefundMoney'] = \Yii::$app->refund->computeMostRefundMoney($orderDetail);
            }

            /* 判断是否为最后一类运费模板商品 */
            $data['another'] = \Yii::$app->orderdetail->isLastProduct($orderDetail->lID, $orderDetail->OrderID, $orderDetail->ShipTemplateID);

            /*  订单若未发货,则默认填写退款数量，金额等数据 */
            if (empty($orderDetail->sShipNo)) {
                $data['defaultItem'] = $orderDetail->lQuantity;
                $data['defaultMoney'] = $orderDetail->fTotal;
                if ($data['another'] == 1) {
                    $data['defaultMoney'] += $orderDetail->fShip;
                }
                $data['unship'] = true;
            }

            $data['orderDetail'] = $orderDetail;
            $data['refund'] = $refundDetail;

            $imgList = json_decode($refundDetail->sRefundVoucher, true);
            if ($imgList) {
                foreach ($imgList as $i => $img) {
                    $imgList[$i] = \Yii::$app->request->imgUrl . '/' . $img;
                }
            }
            $data['imgList'] = json_encode($imgList);

            $this->getView()->title = "修改申请";

            return $this->render("modifyrefund", $data);
        }
    }

    /**
     * 修改退款信息
     * @param $id
     */
    public function actionModifyrefund($id)
    {
        if (\Yii::$app->request->isPost) {
            if ($_POST['type'] == '仅退款') {
                $TypeID = 'onlymoney';
            } else {
                $TypeID = 'moneyandproduct';
            }

            $refund = Refund::findByID($id);
            $fRefundProduct = $refund->orderDetail->fTotal * ($_POST['refundnum'] / $_POST['totalnum']);
            \Yii::$app->refund->modifyRefund([
                'TypeID' => $TypeID,
                'sReason' => $_POST['reason'],
                'lID' => $id,
                'fRefundApply' => $_POST['money'],
                'sExplain' => $_POST['explain'],
                'imgList' => $_POST['imglist'],
                'lItemTotal' => $_POST['totalnum'],
                'lRefundItem' => $_POST['refundnum'],
                'fRefundProduct' => $fRefundProduct
            ]);

            return $this->asJson(['status' => true]);
        } else {
            $refundDetail = \Yii::$app->refund::findByID($id);
            $orderDetail = $refundDetail->orderDetail;

            if (!$orderDetail) {
                throw new HttpException(404);
            } elseif ($orderDetail->order->MemberID != \Yii::$app->frontsession->MemberID) {
                throw new HttpException(404);
            }

            if ($orderDetail) {
                $data['fRefundMoney'] = \Yii::$app->refund->computeMostRefundMoney($orderDetail);
            }

            /* 根据商品价格占订单中同类运费模板商品总价比例退还运费 */
            $fTotalPrice = \Yii::$app->orderdetail->countProductPrice($orderDetail->OrderID, $orderDetail->ShipTemplateID);
            $fTotalShip = $orderDetail->fShip * ($orderDetail->fTotal / $fTotalPrice);
            $data['fTotalShip'] = $fTotalShip;
            $data['fRefundMoney'] = $orderDetail->fTotal + $fTotalShip;

            /*  订单若未发货,则默认填写退款数量，金额等数据 */
            if (empty($orderDetail->sShipNo)) {
                $data['defaultItem'] = $orderDetail->lQuantity;
                $data['defaultMoney'] = $orderDetail->fTotal;
                $data['unship'] = true;
            }

            $data['orderDetail'] = $orderDetail;
            $data['refund'] = $refundDetail;

            $imgList = json_decode($refundDetail->sRefundVoucher, true);
            if ($imgList) {
                foreach ($imgList as $i => $img) {
                    $imgList[$i] = \Yii::$app->request->imgUrl . '/' . $img;
                }
            }
            $data['imgList'] = json_encode($imgList);

            $this->getView()->title = "修改申请";

            return $this->render("modifyrefund", $data);
        }
    }

    /**
     * 撤销退款申请 modified by ShiShuimu, on Apr. 26th, 2019
     */
    public function actionCancelapply($id)
    {
        \Yii::$app->refund->cancelApply($id);
        $Refund = Refund::findByID($id);

        $Order = \Yii::$app->order->findbyid($Refund->OrderID);
        //如果是云订单，调用API通知云端
        if ($Order->bCloud) {
            $orderDetail = \Yii::$app->orderdetail->findbyid($Refund->OrderDetailID);
            $product = \Yii::$app->product->findbyid($orderDetail->ProductID);

            $data_api = [];
            $data_api['sn'] = $Refund->sName;
            $data_api['ordersn'] = $Refund->order->sName;
            $data_api['pID'] = $product->lCloudID;


            $result = \Yii::$app->dnyapi->cancelRefund('v1/refund/cancel', $data_api);
            if ($result['status'] != '10000') {
                return $this->asJson(['status' => false]);
            }
        }
        return $this->asJson(['status' => true]);
    }

    /**
     * 退款协商记录
     */
    public function actionRefundlog($id)
    {
        $this->getView()->title = "协商记录";
        $data['arrRefundLog'] = \Yii::$app->refund->getRefundLog($id);
        return $this->render("refundlog", $data);
    }

    /**
     * 退款去向
     */
    public function actionRefundmoney($id)
    {
        $this->getView()->title = "退款去向";
        $data['refundmoney'] = RefundMoneyBack::findOne(['RefundID' => $id]);
        return $this->render("refundmoney", $data);
    }

    /**
     * 退款列表
     * @param $id
     */
    public function actionRefundlist()
    {
        $data = [];

        $this->getView()->title = "退款/售后";

        $data['data'] = $this->actionRefundlistitem();

        return $this->render("refundlist", $data);
    }

    public function actionRefundlistitem()
    {
        $lPage = intval($_GET['index']) ? intval($_GET['index']) : 1;

        $ret = \Yii::$app->refund->listData([
            'lPage' => $lPage
        ]);

        $arrData = [];
        $arrData['datalist'] = [];
        foreach ($ret[0] as $data) {
            $arrData['datalist'][] = [
                'shopName' => $data->order->supplier->sName,
                'status' => $data->sStatus,
                'shoplink' => Url::toRoute([
                    \Yii::$app->request->shopUrl . '/supplier/detail',
                    'id' => $data->SupplierID
                ]),
                'link' => Url::toRoute([
                    '/member/refunddetail',
                    'id' => $data->lID
                ]),
                'images' => \Yii::$app->request->imgUrl . '/' . $data->orderDetail->sPic,
                'title' => $data->orderDetail->sName,
                'spec' => $data->orderDetail->sSKU,
                'pay' => number_format($data->fPaid, 2),
                'refund' => number_format($data->fRefundApply, 2),
            ];
        }

        $isMore = true;
        if ($lPage > floor($ret[1] / 10)) {
            $isMore = false;
        }

        $arrData['isMore'] = $isMore;

        return json_encode($arrData);
    }

    public function actionSendforgotcode($mobile)
    {
        if (\Yii::$app->request->isPost) {
            $member = \Yii::$app->member->findByMobile($mobile);
            if (!$member) {
                return $this->asJson(['status' => false, 'message' => '手机号不存在']);
            }

            return $this->asJson(VerifyCode::send($mobile));
        }
    }


    public function actionForgot()
    {
        if (\Yii::$app->request->isPost) {

            if ($_POST['verifycode'] != VerifyCode::getCode()) {
                return $this->asJson(['status' => false, 'message' => '验证码不正确']);
            }

            $member = \Yii::$app->member->findByMobile($_POST['mobile']);
            if (!$member) {
                return $this->asJson(['status' => false, 'message' => '手机号不存在']);
            }

            $member->modifyPass($_POST['password']);

            return $this->asJson(['status' => true]);
        } else {

            $data = [];

            if ($_GET['sReturnUrl']) {
                $data['sReturnUrl'] = urldecode($_GET['sReturnUrl']);
            } elseif (\Yii::$app->request->referrer) {
                //跳转到上一个地址
                $data['sReturnUrl'] = \Yii::$app->request->referrer;
            } else {
                //跳转到首页
                $data['sReturnUrl'] = \Yii::$app->request->mallHomeUrl;
            }


            $this->getView()->title = "找回密码";
            return $this->render("forgot", $data);
        }
    }

    public function actionSetting()
    {
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        $this->getView()->title = $shop->sName;
        return $this->render("setting", []);
    }

    public function actionLogout()
    {
        $this->trigger(self::EVENT_LOGOUT);
        return $this->redirect("/member");
    }

    public function actionSendmodifypasscode()
    {
        $member = \Yii::$app->member->findByID(\Yii::$app->frontsession->MemberID);
        if (!$member) {
            return $this->asJson(['status' => false, 'message' => '请登录后再操作']);
        }

        if (\Yii::$app->request->isPost) {
            $return = VerifyCode::send($member->sMobile);
            if ($return['status']) {
                $return['message'] = "已发送至尾号为" . substr($member->sMobile, -4) . "的手机";
            }
        }

        return $this->asJson($return);
    }

    public function actionModifypass()
    {
        if (\Yii::$app->request->isPost) {

            if ($_POST['verifycode'] != VerifyCode::getCode()) {
                return $this->asJson(['status' => false, 'message' => '验证码不正确']);
            }

            $member = \Yii::$app->member->findByID(\Yii::$app->frontsession->MemberID);
            $member->modifyPass($_POST['password']);

            return $this->asJson(['status' => true]);
        } else {
            $this->getView()->title = "修改密码";
            return $this->render("modifypass", []);
        }
    }

    /**
     * 获取头像
     */
    public function actionAvatar()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,
            \Yii::$app->frontsession->member->sAvatarPath ? \Yii::$app->frontsession->member->sAvatarPath : "http://" . $_SERVER['HTTP_HOST'] . "/images/order/person.png");
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        echo $output;

        curl_close($ch);
    }

    /**
     * 获取当前店铺的头像
     */
    public function actionShopavatar()
    {
        if (\Yii::$app->frontsession->urlSeller) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,
                \Yii::$app->frontsession->urlSeller->member->sAvatarPath ? \Yii::$app->frontsession->urlSeller->member->sAvatarPath : "http://" . $_SERVER['HTTP_HOST'] . "/images/order/person.png");
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array('User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $output = curl_exec($ch);

            echo $output;

            curl_close($ch);
        } else {

        }
    }

    /**
     * 金币充值
     */
    public function actionRecharge()
    {
        if (\Yii::$app->request->isPost) {

            $attributes = [
                'trade_type' => 'JSAPI',
                // JSAPI，NATIVE，APP...
                'body' => '支付',
                'out_trade_no' => GoldRecharge::makeCode() . "-" . \Yii::$app->frontsession->MemberID,
                'total_fee' => $_POST['number'] * 100,
                // 单位：分
                'notify_url' => Url::toRoute(['/pay/rechargewxnotify'], true),
                //支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => \Yii::$app->frontsession->sOpenID,
            ];

            $return = [];
            $order = new Order($attributes);
            $result = \Yii::$app->wechat->payment->prepare($order);
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
                $return['config'] = \Yii::$app->wechat->payment->configForJSSDKPayment($result->prepay_id);
                $return['status'] = true;
            } else {
                $return['status'] = false;
                $return['message'] = $result->get('return_msg');
            }

            return $this->asJson($return);
        } else {
            $this->getView()->title = "金币充值";
            return $this->render("recharge", ['arrConfig' => GoldRechargeConfig::all()]);
        }
    }

    /**
     * 金币明细
     */
    public function actionGoldflow()
    {
        $data['sDataJson'] = $this->actionGoldflowitem();

        $this->getView()->title = "金币明细";

        return $this->render("goldflow", $data);
    }


    public function actionGoldflowitem()
    {
        $page = $_GET['page'] ? intval($_GET['page']) : 1;

        $type = "";
        if ($_GET['type'] == "收入") {
            $type = 1;
        } elseif ($_GET['type'] == "支出") {
            $type = 2;
        }

        $arrFlow = \Yii::$app->goldflow->listData(
            ['page' => $page, 'type' => $type]
        );

        $arrData = [];
        foreach ($arrFlow as $flow) {
            $arrData[] = [
                'detailTitle' => $flow->sName,
                'commission' => ($flow->fChange > 0 ? "+" : "") . $flow->fChange,
                'detailTime' => $flow->dNewDate,
            ];
        }

        return json_encode($arrData);
    }

    /**
     * 取消订单
     * @return \yii\web\Response
     * @author hechengcheng
     * @time 2019/5/8 18:03
     */
    public function actionCancelorder()
    {
        $OrderID = $_GET['id'];
        $order = \Yii::$app->order::findByID($OrderID);

        if (!$order) {
            return $this->asJson(['status' => false, 'message' => "订单不存在"]);
        } elseif ($order->MemberID != \Yii::$app->frontsession->MemberID) {
            return $this->asJson(['status' => false, 'message' => "只有自己的订单才可以取消"]);
        } elseif ($order->StatusID != \myerm\shop\common\models\Order::STATUS_UNPAID) {
            return $this->asJson(['status' => false, 'message' => "只有待确认的订单才可以取消"]);
        }
        //取消订单
        $order->dCloseDate = \Yii::$app->formatter->asDatetime(time());;
        $order->StatusID = 'closed';
        $order->save();
        return $this->asJson(['status' => true]);

    }

    /*
     * 注册后选择角色
     */
    public function actionChooserole()
    {
        return $this->render("chooserole");
    }

    /*
     *合作成功
     */
    public function actionCooperate()
    {
        return $this->render("cooperate");
    }

    /***店铺信息变更开始***/
    //更改店名
    public function actionModifyshopname()
    {
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        if (\Yii::$app->request->isPost) {
            $shop->sName = $_POST['shopName'];
            $shop->save();
            return $this->asJson(['status' => true, 'message' => '修改成功']);
        } else {
            $this->getView()->title = $shop->sName;
            $data = [];
            $data['shopName'] = $shop->sName;
            return $this->render("modifyshopname", $data);
        }
    }

    //更改logo
    public function actionModifylogo()
    {
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        if (\Yii::$app->request->isPost) {
            if ($_POST['imglist']) {
                $img = $_POST['imglist'][0];
                if (substr($img, 0, 5) == 'data:') {
                    $arrFileInfo = File::parseImageFromBase64($img);
                    $sFileName = date('YmdHis') . "." . $arrFileInfo[0];
                    $path = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                    $shop->sLogoPath = $path;
                    $shop->save();
                }
            }
            return $this->asJson(['status' => true, 'message' => '修改成功']);
        } else {
            $this->getView()->title = $shop->sName;
            $data = [];
            $data['sLogoPath'] = $shop->sLogoPath;
            return $this->render("modifylogo", $data);
        }
    }

    //更改轮播图
    public function actionModifybanner()
    {
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        if (\Yii::$app->request->isPost) {
            if (empty($_POST['imglist'])) {
                return $this->asJson(['status' => false, 'message' => '请选择图片后再提交']);
            }
            $arrImg = [];
            if ($_POST['imglist']) {
                foreach ($_POST['imglist'] as $key => $img) {
                    if ($key < 3) {
                        if (substr($img, 0, 5) == 'data:') {
                            $arrFileInfo = File::parseImageFromBase64($img);
                            $sFileName = date('YmdHis') . $key . "." . $arrFileInfo[0];
                            $arrImg[] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                        } else {
                            $arrImg[] = str_ireplace(\Yii::$app->request->imgUrl . "/", "", $img);;
                        }
                    }
                }
                $shop->sImg = json_encode($arrImg);
                $shop->save();
            }

            return $this->asJson(['status' => true, 'message' => '修改成功']);
        } else {
            $this->getView()->title = $shop->sName;
            $data = [];
            $data['sImg'] = json_decode($shop->sImg);
            return $this->render("modifybanner", $data);
        }
    }

    //更改店长说
    public function actionModifymsg()
    {
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        if (\Yii::$app->request->isPost) {
            $shop->sMsg = $_POST['sMsg'];
            $shop->save();
            return $this->asJson(['status' => true, 'message' => '修改成功']);
        } else {
            $this->getView()->title = $shop->sName;
            $data = [];
            $data['sMsg'] = $shop->sMsg;
            return $this->render("modifymsg", $data);
        }
    }

    //更改公众号二维码
    public function actionModifyqrcode()
    {
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        if (\Yii::$app->request->isPost) {
            $path = '';
            if ($_POST['imglist']) {
                $img = $_POST['imglist'][0];
                if (substr($img, 0, 5) == 'data:') {
                    $arrFileInfo = File::parseImageFromBase64($img);
                    $sFileName = date('YmdHis') . "." . $arrFileInfo[0];
                    $path = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                }
            }
            $shop->sQrcode = $path;
            $shop->save();
            return $this->asJson(['status' => true, 'message' => '修改成功']);
        } else {
            $this->getView()->title = $shop->sName;
            $data = [];
            $data['sLogoPath'] = $shop->sQrcode;
            $data['shop'] = $shop;
            return $this->render("modifyqrcode", $data);
        }
    }

    public function actionModifymobile()
    {
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        if (\Yii::$app->request->isPost) {
            $shop->sMobile = $_POST['sMobile'];
            $shop->save();
            return $this->asJson(['status' => true, 'message' => '修改成功']);
        } else {
            $this->getView()->title = $shop->sName;
            $data = [];
            $data['shop'] = $shop;
            return $this->render("modifymobile", $data);
        }
    }

    //更改个人微信二维码
    public function actionModifywxqrcode()
    {
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        if (\Yii::$app->request->isPost) {
            $path = '';
            if ($_POST['imglist']) {
                $img = $_POST['imglist'][0];
                if (substr($img, 0, 5) == 'data:') {
                    $arrFileInfo = File::parseImageFromBase64($img);
                    $sFileName = date('YmdHis') . "." . $arrFileInfo[0];
                    $path = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                }
            }
            $shop->sWXQrcode = $path;
            $shop->save();
            return $this->asJson(['status' => true, 'message' => '修改成功']);
        } else {
            $this->getView()->title = $shop->sName;
            $data = [];
            $data['sLogoPath'] = $shop->sWXQrcode;
            $data['shop'] = $shop;
            return $this->render("modifywxqrcode", $data);
        }
    }

    //更改公司简介
    public function actionModifycommsg()
    {
        $supplierID = \Yii::$app->frontsession->member->SupplierID;
        if ($supplierID) {
            $supplier = Supplier::findOne($supplierID);
            if (\Yii::$app->request->isPost) {
                $supplier->sContent = $_POST['sMsg'];
                $supplier->save();
                return $this->asJson(['status' => true, 'message' => '修改成功']);
            } else {
                $this->getView()->title = $supplier->sName;
                $data = [];
                $data['sMsg'] = $supplier->sContent;
                return $this->render("modifycommsg", $data);
            }
        }
    }
    /***店铺信息变更结束***/

    /*
     * 发货提醒
     */

    public function actionShip()
    {
        if (\Yii::$app->request->isAjax) {
            return $this->getShipOrder();
        } else {
            $data = [];
            $this->getView()->title = "发货提醒";
            $data['data'] = $this->getShipOrder();
            return $this->render("shiporderlist", $data);
        }
    }

    public function getShipOrder()
    {
        $supplierID = \Yii::$app->frontsession->member->SupplierID;
        //$arrOrder = \Yii::$app->order->memberShipOrder($supplierID);
        $arrOrder = CloudOrder::find()
            ->where(['StatusID' => 'paid', 'SupplierID' => $supplierID])
            ->with('detail')
            ->with('address')
            ->all();
        $arrProductData = [];
        foreach ($arrOrder as $order) {
            $data = [];
            $data['sName'] = $order->sName;
            $data['status'] = $order->sStatus;
            $data['allNum'] = $order->detail->lQuantity;
            $data['commodity'] = [];
            $sAddress = $order->address->province->sName . $order->address->city->sName;
            if ($order->address->area) {
                $sAddress .= $order->address->area->sName;
            }
            $sAddress .= $order->address->sAddress;
            $data['commodity'][] = [
                'images' => \Yii::$app->request->imgUrl . '/' . $order->detail->sPic,
                'title' => $order->detail->sName,
                'receiver' => '收件人：' . $order->address->sName,
                'sMobile' => '手机：' . $order->address->sMobile,
                'sAddress' => '收获地址：' . $sAddress,
            ];

            $arrProductData[] = $data;
        }
        return json_encode(['status' => true, 'data' => $arrProductData]);
    }

    /*
     * 收益订单
     */
    public function actionProfitorder()
    {
        if (\Yii::$app->request->isAjax) {
            return $this->getProfitorder();
        } else {
            $data = [];
            $this->getView()->title = "销售订单";
            $data['data'] = $this->getProfitorder();
            return $this->render("profitorderlist", $data);
        }
    }

    public function getProfitorder()
    {
        if (!in_array($_GET['type'], ['unpaid', 'paid', 'closed', 'delivered', 'success', 'refund'])) {
            $_GET['type'] = "";
        }

        $lPage = intval($_GET['index']) > 1 ? intval($_GET['index']) : 1;
        $arrOrder = \Yii::$app->order->getProfitorder([
            'MemberID' => \Yii::$app->frontsession->MemberID,
            'StatusID' => $_GET['type'],
            'sKeyword' => $_GET['keyword'],
            'lPage' => $lPage
        ]);

        $arrProductData = [];
        foreach ($arrOrder as $order) {
            $profit = $order->sellerFlow ? $order->sellerFlow->fChange : 0;
            $data = [];
            $data['shopname'] = '';
            $data['sName'] = $order->sName;
            $data['status'] = $order->sStatus;
            $data['OrderType'] = $order->OrderType;
            $data['order_id'] = $order->lID;
            $data['allNum'] = 0;
            $data['all'] = $order->fSumOrder;
            $data['freight'] = $order->fSupplierShip;
            $data['shipped'] = $order->bHasShip;
            $data['profit'] = '收益：+' . $profit;
            $sellerName=$order->seller->seller->sName;
            $sBuyerName=$order->MemberID==$order->seller->SellerID?$sellerName:$order->member->sName;
            $data['memberName'] = '买家：' .$sBuyerName;
            $data['seller'] = '卖家：' . $sellerName;
            $data['bself'] = $order->MemberID == \Yii::$app->frontsession->MemberID ? 1 : 0;
            $data['commodity'] = [];
            foreach ($order->arrDetail as $detail) {
                $data['allNum'] += $detail->lQuantity;
                $last='';
                if(strlen($detail->sName)>15){
                    $last='...';
                }
                $data['commodity'][] = [
                    'images' => \Yii::$app->request->imgUrl . '/' . $detail->sPic,
                    'title' => mb_substr($detail->sName,0,15).$last,
                    'spec' => $detail->sSKU,
                    'price' => $detail->fPrice,
                    'num' => $detail->lQuantity,
                    'isRefund' => $detail->bRefunding,
                    'link' => Url::toRoute([
                        '/member/order',
                        'id' => $order->lID
                    ]),
                    'refund_status' => $detail->sStatus == '退款关闭' ? "" : $detail->sStatus,
                ];
            }


            $arrProductData[] = $data;
        }

        $isMore = false;
        if (count($arrOrder) % 10 == 0) {
            $isMore = true;
        }

        return json_encode(['status' => true, 'isMore' => $isMore, 'data' => $arrProductData]);
    }

    /*
     * 收益明细
     */
    public function actionProfitdetail()
    {
        if (\Yii::$app->request->isAjax) {
            return $this->getProfitDetail();
        } else {
            $data = [];
            $this->getView()->title = "资金明细";
            $data['data'] = $this->getProfitDetail();
            return $this->render("profitorderdetail", $data);
        }
    }

    public function getProfitDetail()
    {


        $lPage = intval($_GET['index']) > 1 ? intval($_GET['index']) : 1;
        $memberID = \Yii::$app->frontsession->MemberID;
        $arrOrder = \Yii::$app->order->getSellerOrder($memberID, $lPage);


        $arrProductData = [];
        foreach ($arrOrder as $order) {
            $data = [];
            $data['sName'] = $order->sName;
            $data['order_id'] = $order->order->lID;
            $data['fSumOrder'] = '订单金额：' . $order->order->fSumOrder;
            $data['fSumOrder'] = '收益：+' . $order->fChange;
            $data['dPaydate'] = '下单时间：' . $order->order->dPayDate;
            $data['buyer'] = '买家：' . $order->order->member->sName;
            $data['seller'] = '卖家：' . $order->sellerOrder->seller->sName;
            $arrProductData[] = $data;
        }
        $isMore = false;
        if (count($arrOrder) % 10 == 0) {
            $isMore = true;
        }

        return json_encode(['status' => true, 'isMore' => $isMore, 'data' => $arrProductData]);
    }


}