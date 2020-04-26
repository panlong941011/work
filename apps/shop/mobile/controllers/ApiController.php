<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 5:19
 */

namespace myerm\shop\mobile\controllers;


use myerm\common\components\Func;
use myerm\shop\common\models\Member;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\SellerFlow;
use myerm\shop\common\models\SellerOrder;
use myerm\shop\common\models\SellerWithdrawLog;
use myerm\shop\common\models\Upgrade;
use myerm\shop\common\models\Withdraw;
use myerm\shop\mobile\models\CloudOrder;
use myerm\shop\mobile\models\Order;
use myerm\shop\mobile\models\OrderPayLog;
use myerm\shop\mobile\models\Redbag;
use myerm\shop\mobile\models\Refund;
use myerm\shop\mobile\models\WXUser;
use yii\helpers\ArrayHelper;

header('Content-Type: text/html; charset=utf-8'); //网页编码

/**
 * 通用接口控制器
 */
class ApiController extends Controller
{
    const RECEIVED = 'received';
    public $enableCsrfValidation = false;

    const APPID = 'wxde428775a97e86be';//公众号appid
    const XCXAPPID = 'wxb7ad5f05ff005ccc';//小程序appid
    const MCHID = '1549064271';//商户号
    const KEY = '6sAlW88V7GWSCAZXCBo3SBiSIvXLVUZ6';//商户号密钥

    /**
     * 清除所有缓存
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-8 16:18:32
     */
    public function actionClearall()
    {
        \Yii::$app->cache->flush();

        return json_encode(['status' => 'success']);
    }

    /**
     * 增加分账接收方账户接口
     * @return string
     * @author hechengcheng
     * @time 2019年9月18日14:32:29
     */
    public function actionAddwx($appID = '', $openID = '')
    {
        $appID = self::APPID;
        $openID = 'oMwD002UvzWOd3S9RLb3CF_P-SCU';//分润账号

        //加密数组
        $sign = [];
        $sign['appid'] = $appID; //服务号ID;
        $sign['mch_id'] = static::MCHID;
        $sign['nonce_str'] = Func::getNonce();
        $sign['receiver'] = json_encode([
            'account' => $openID,
            'relation_type' => 'STAFF',
            'type' => 'PERSONAL_OPENID',
        ]);
        $sign['sign_type'] = 'HMAC-SHA256';

        //加密结果
        $data = [];
        $data['appid'] = $appID; //服务号ID;
        $data['mch_id'] = static::MCHID;
        $data['nonce_str'] = $sign['nonce_str'];
        //$data['sign'] = strtoupper(MD5($this->arrayToString($sign) . "&key=6sAlW88V7GWSCAZXCBo3SBiSIvXLVUZ6"));
        $data['sign'] = strtoupper(hash_hmac("sha256", Func::arrayToString($sign) . '&key=' . static::KEY, static::KEY));

        $data['receiver'] = json_encode([
            'account' => $openID,
            'relation_type' => 'STAFF',
            'type' => 'PERSONAL_OPENID',
        ]);
        $data['sign_type'] = 'HMAC-SHA256';

        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingaddreceiver';

        $res = Func::HttpRequest($url, $data);
        var_dump($res);
    }

    /**
     * 增加分账接收方账户接口
     * @return string
     * @author hechengcheng
     * @time 2019年9月18日14:32:29
     */
    public function actionDeletewx()
    {
        $allUser = WXUser::find()->all();

        foreach ($allUser as $user) {
            //加密数组
            $sign = [];
            $sign['appid'] = 'wxde428775a97e86be'; //服务号ID;
            $sign['mch_id'] = '1549064271';
            $sign['nonce_str'] = $this->getNonce();
            $sign['receiver'] = json_encode([
                'account' => $user->sOpenID,
                'type' => 'PERSONAL_OPENID',
            ]);
            $sign['sign_type'] = 'HMAC-SHA256';

            //加密结果
            $data = [];
            $data['appid'] = 'wxde428775a97e86be'; //服务号ID;
            $data['mch_id'] = '1549064271';
            $data['nonce_str'] = $sign['nonce_str'];
            //$data['sign'] = strtoupper(MD5($this->arrayToString($sign) . "&key=6sAlW88V7GWSCAZXCBo3SBiSIvXLVUZ6"));
            $data['sign'] = strtoupper(hash_hmac("sha256", $this->arrayToString($sign) . '&key=6sAlW88V7GWSCAZXCBo3SBiSIvXLVUZ6', '6sAlW88V7GWSCAZXCBo3SBiSIvXLVUZ6'));

            print '<pre>';
            $data['receiver'] = json_encode([
                'account' => $user->sOpenID,
                'type' => 'PERSONAL_OPENID',
            ]);
            $data['sign_type'] = 'HMAC-SHA256';

            $url = 'https://api.mch.weixin.qq.com/pay/profitsharingremovereceiver';

            $res = $this->HttpRequest($url, $data);

            print '<pre>';
            var_dump($res);
        }
    }

    /**
     * 调用分账接口
     * @return string
     * @author hechengcheng
     * @time 2019年9月18日14:32:29
     */
    public function actionSplitmoney()
    {
        $arrOrder = Order::find()->where(['SupplierID' => 700, 'StatusID' => 'success'])->all();
        foreach ($arrOrder as $order) {
            $appid = $order->PaymentID == 'xcx' ? static::XCXAPPID : static::APPID;
            $arrReceiver = [];
            if ($order->fService > 0) {
                $data = [];
                $data['type'] = 'PERSONAL_OPENID';
                $data['account'] = 'oMwD002UvzWOd3S9RLb3CF_P-SCU';
                $data['amount'] = $order->fService * 100;
                $data['description'] = $order->sName;
                $arrReceiver[] = $data;
            }


            //加密数组
            $nonce_str = Func::getNonce();
            $out_order_no = $order->sName . '1';
            $sign = [];
            $sign['appid'] = $appid; //服务号ID;
            $sign['mch_id'] = static::MCHID;
            $sign['nonce_str'] = $nonce_str;
            $sign['out_order_no'] = $out_order_no;
            $sign['receivers'] = json_encode($arrReceiver);
            $sign['sign_type'] = 'HMAC-SHA256';
            $sign['transaction_id'] = $order->payLog->sTransactionID;

            print '<pre>';
            var_dump($sign);
            //exit();

            //加密结果
            $arrData = [];
            $arrData['appid'] = $appid; //服务号ID;
            $arrData['mch_id'] = static::MCHID;
            $arrData['nonce_str'] = $nonce_str;
            $arrData['out_order_no'] = $out_order_no;
            $arrData['receivers'] = $sign['receivers'];
            $arrData['sign_type'] = 'HMAC-SHA256';
            $arrData['transaction_id'] = $sign['transaction_id'];
            $arrData['sign'] = strtoupper(hash_hmac("sha256", Func::arrayToString($sign) . '&key=' . static::KEY, static::KEY));

            $url = 'https://api.mch.weixin.qq.com/secapi/pay/multiprofitsharing';

            $res = Func::HttpRequestShare($url, $arrData);

            var_dump($res);
        }
    }

    public function actionEnd()
    {
        $this->SplitMoneyEnd('wxde428775a97e86be', 'uOVyl6lFiG6sdVoq6KHruRBCENIjtbpo', '2019102510504484447', '4200000451201910256787189553');
    }


    /**
     * 前台付款订单，付款后 同步生成 后台已确认订单
     */
    public function actionAsyncpayorder()
    {
        //同步半个小时前的订单
        $arrOrder = Order::find()->where(['and',
            ['<', 'dPayDate', \Yii::$app->formatter->asDatetime(time())],
            ['StatusID' => 'paid'],
            ['bChecked' => 0]
        ])->all();
        foreach ($arrOrder as $order) {
            echo $order->sName;
            CloudOrder::saveOrder($order);
            $order->bChecked = 1;
            $order->save();
        }

    }

    /*
      * 订单物流同步
      */
    public function actionAsyncexpress()
    {
        //同步半个小时前的订单
        $arrOrder = Order::find()
            ->where(['and',
                ['<', 'dPayDate', \Yii::$app->formatter->asDatetime(time() - 100)],
                ['StatusID' => 'paid'],
                ['bChecked' => 1]
            ])->with('detail')
            ->all();

        $arrOrderName = array_column($arrOrder, 'sName');
        $arrOrder = ArrayHelper::index($arrOrder, 'sName');

        //查询云端订单
        $arrCloudOrder = CloudOrder::find()
            ->with('detail')
            ->where(['and', ['sClientSN' => $arrOrderName], ['<>', 'StatusID', 'paid']])
            ->all();
        foreach ($arrCloudOrder as $cloudOrder) {
            $updateOrder = $arrOrder[$cloudOrder->sClientSN];
            $updateOrder->StatusID = $cloudOrder->StatusID;
            $updateOrder->RefundStatusID = $cloudOrder->RefundStatusID;
            if ($cloudOrder->StatusID == 'delivered') {
                $updateOrder->bAllShiped = 1;
                $updateOrder->dShipDate = $cloudOrder->dShipDate;
            }
            $updateOrder->save();
            $updateOrderDetail = $arrOrder[$cloudOrder->sClientSN]->orderdetail;
            $orderDetail = $cloudOrder->detail;
            $updateOrderDetail->sShipNo = $orderDetail->sShipNo;
            $updateOrderDetail->ShipCompanyID = $orderDetail->ShipCompanyID;
            $updateOrderDetail->dShipDate = $orderDetail->dShipDate;
            $updateOrderDetail->save();
        }
    }

    /***********退款*****开始**************/
    public function actionOrderrefund()
    {
        //StatusID='success' and sResource='dkxh' and bCheck=0;
        $arrRefund = Refund::find()
            ->with('order')
            ->where(['StatusID' => 'success', 'sResource' => 'dkxh', 'bCheck' => 0])
            ->andWhere("dNewDate>'2020-02-20'")
            ->limit(1)
            ->all();

        echo '<pre>';

        foreach ($arrRefund as $refund) {
            $order = $refund->order;
            if ($refund->fRefundApply == $order->fSumOrder) {
                //全额
                $order->StatusID = 'closed';
                $order->RefundStatusID = 'success';
                //扣除渠道商待结算 全部扣除
            } else {
                $order->StatusID = 'delivered';
                $order->RefundStatusID = null;
                //扣除渠道商待结算  按比例扣除
            }
            $order->fRefund = $refund->fRefundApply;

            $this->refundSellerFlow($order->lID, $order->sName, $order->fSumOrder, $refund->fRefundApply, $order->StatusID);
            $orderPayLog = $order->payLog;
            $res = $this->refundmoney($refund->fRefundApply, $orderPayLog);
            var_dump($res);
            $order->save();
            $refund->bCheck = 1;
            $refund->save();
        }

    }

    public function refundmoney($fRefundApply, $orderPayLog)
    {

        $appid = static::APPID;
        //加密数组
        $nonce_str = Func::getNonce();
        $fRefundApply = $fRefundApply * 100 < $orderPayLog->fPaid ? $fRefundApply * 100 : $orderPayLog->fPaid;

        $sign = [];
        $sign['appid'] = $appid; //服务号ID;
        $sign['mch_id'] = static::MCHID;
        $sign['nonce_str'] = $nonce_str;
        $sign['transaction_id'] = $orderPayLog->sTransactionID;
        $sign['out_refund_no'] = $orderPayLog->sTradeNo . date('s');
        $sign['total_fee'] = (int)$orderPayLog->fPaid;
        $sign['refund_fee'] = (int)$fRefundApply;
        $sign['refund_desc'] = '拼团失败';
        $stringA = Func::arrayToString($sign);
        $stringSignTemp = $stringA . '&key=' . static::KEY;
        $signs = strtoupper(md5($stringSignTemp));
        $sign['sign'] = $signs;


        print '<pre>';
        var_dump($sign);
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $res = Func::HttpRequestShare($url, $sign);
        print '<pre>';
        echo '<br>';
        echo '转账结果：';
        var_dump($res);
    }

    /**
     * 退款成功扣除佣金流水
     * @param $arrNotify
     */
    private function refundSellerFlow($orderID, $orderName, $fSumOrder, $fRefund, $StatusID)
    {
        //发放待结算流水 panlong 2019年9月17日09:16:56
        $sellerOrder = SellerOrder::findOne(['OrderID' => $orderID]);
        if ($sellerOrder) {
            //发放vip待结算提成流水
            if ($sellerOrder->fSellerCommission > 0) {
                SellerFlow::refundWithdrawFlow([
                    'fMoney' => Func::numbleFormat($sellerOrder->fSellerCommission * $fRefund / $fSumOrder),
                    'OrderName' => $orderName,
                    'OrderID' => $orderID,
                    'seller' => $sellerOrder->seller
                ]);
            }

            //发放顶级代理待结算提成流水
            if ($sellerOrder->fUpSellerCommission > 0) {
                SellerFlow::refundWithdrawFlow([
                    'fMoney' => Func::numbleFormat($sellerOrder->fUpSellerCommission * $fRefund / $fSumOrder),
                    'OrderName' => $orderName,
                    'OrderID' => $orderID,
                    'seller' => $sellerOrder->upSeller
                ]);
            }
            $sellerOrder->StatusID = $StatusID;
            $sellerOrder->save();
        }

        return true;
    }
    /***********退款*****结束**************/


    /***********交易成功分账********开始***********/
    /**
     * 订单交易成功分佣
     */
    public function actionSuccessorder()
    {


        //发货后10天无售后
        //SupplierID=700 and dPayDate<'2019-10-26' and StatusID='delivered' LIMIT 10
        $arrOrder = Order::find()
            ->where(['StatusID' => 'delivered', 'RefundStatusID' => null])
            ->andWhere(['<', 'dShipDate', '2020-03-28'])
            ->andWhere(['>', 'lID', 1812])
            ->limit(100)->all();
        //$arrOrder = Order::find()->where(['lID'=>746])->limit(10)->all();
        foreach ($arrOrder as $order) {
            if ($order->StatusID != 'delivered') {
                continue;
            }
            //调整订单佣金流水
            $order->dReceiveDate = \Yii::$app->formatter->asDatetime(time());
            $order->StatusID = 'success';
            $order->save();
            /* 发放代理销售提成 panlong 2019年9月17日11:24:56 开始 */
            $sellerOrder = SellerOrder::findOne(['OrderID' => $order->lID]);
            if ($sellerOrder) {
                //发放vip提成
                if ($sellerOrder->fSellerCommission > 0) {
                    $seller = $sellerOrder->seller;
                    //查询待结算金额
                    $fChange = SellerFlow::find()->where(['TypeID' => 1, 'SellerID' => $seller->lID, 'OrderID' => $order->lID])->sum('fChange');
                    $sellerOrder->fSellerCommission = $fChange;
                    //增加可提现
                    SellerFlow::setCashFlow([
                        'fMoney' => $fChange,
                        'order' => $order,
                        'seller' => $seller
                    ]);
                }


                $sellerOrder->StatusID = $order->StatusID;
                $sellerOrder->save();
                echo '<pre>';
                //$this->sPlitMoney($order, $sellerOrder);
            }
        }
    }

    /**
     * 增加分账接收方账户接口
     * @return string
     * @author hechengcheng
     * @time 2019年9月18日14:32:29
     */
    private function addWxAccount($appID = '', $openID = '')
    {
        //加密数组
        $sign = [];
        $sign['appid'] = $appID; //服务号ID;
        $sign['mch_id'] = static::MCHID;
        $sign['nonce_str'] = Func::getNonce();
        $sign['receiver'] = json_encode([
            'account' => $openID,
            'relation_type' => 'STAFF',
            'type' => 'PERSONAL_OPENID',
        ]);
        $sign['sign_type'] = 'HMAC-SHA256';

        //加密结果
        $data = [];
        $data['appid'] = $appID; //服务号ID;
        $data['mch_id'] = static::MCHID;
        $data['nonce_str'] = $sign['nonce_str'];
        $data['sign'] = strtoupper(hash_hmac("sha256", Func::arrayToString($sign) . '&key=' . static::KEY, static::KEY));

        $data['receiver'] = json_encode([
            'account' => $openID,
            'relation_type' => 'STAFF',
            'type' => 'PERSONAL_OPENID',
        ]);
        $data['sign_type'] = 'HMAC-SHA256';

        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingaddreceiver';

        $res = Func::HttpRequest($url, $data);

        echo '<br>';
        echo '<br>';
        echo '<pre>';
        echo '添加分账用户结果';
        var_dump($res);
    }

    /**
     * 调用分账接口
     * @return string
     * @author hechengcheng
     * @time 2019年9月18日14:32:29
     */
    private function sPlitMoney($order, $sellerOrder)
    {
        $appid = $order->PaymentID == 'xcx' ? static::XCXAPPID : static::APPID;
        if ($order->StatusID != 'success') {
            return json_encode(['status' => false, 'msg' => '未交易成功订单不可分账']);
        }

        $arrReceiver = [];
        if ($order->fService > 0) {
            $data = [];
            $data['type'] = 'PERSONAL_OPENID';
            $data['account'] = 'oMwD002UvzWOd3S9RLb3CF_P-SCU';
            $data['amount'] = $order->fService * 100;
            $data['description'] = utf8_encode($order->sName);
            $arrReceiver[] = $data;
        }
        if ($sellerOrder->fSellerCommission > 0) {
            $amount = $order->fPaid * 0.3 - $order->fService;
            if ($sellerOrder->fSellerCommission < $amount) {
                $amount = $sellerOrder->fSellerCommission;
            }
            //添加分账接收方
            $member = Member::findOne($sellerOrder->SellerID);
            $openID = $order->PaymentID == 'xcx' ? $member->sXopenID : $member->sOpenID;
            $this->addWxAccount($appid, $openID);
            $data = [];
            $data['type'] = 'PERSONAL_OPENID';
            $data['account'] = $openID;
            $data['amount'] = $amount * 100;;
            $data['description'] = utf8_encode($order->sName);
            $arrReceiver[] = $data;
        }


        if ($sellerOrder->fUpSellerCommission > 0) {
            //添加分账接收方
            $member = Member::findOne($sellerOrder->UpSellerID);
            $openID = $order->PaymentID == 'xcx' ? $member->sXopenID : $member->sOpenID;
            $this->addWxAccount($appid, $openID);

            $data = [];
            $data['type'] = 'PERSONAL_OPENID';
            $data['account'] = $openID;
            $data['amount'] = $sellerOrder->fUpSellerCommission * 100;
            $data['description'] = utf8_encode($order->sName);
            $arrReceiver[] = $data;
        }


        //加密数组
        $nonce_str = Func::getNonce();
        $out_order_no = $order->sName;
        $sign = [];
        $sign['appid'] = $appid; //服务号ID;
        $sign['mch_id'] = static::MCHID;
        $sign['nonce_str'] = $nonce_str;
        $sign['out_order_no'] = $out_order_no;
        $sign['receivers'] = json_encode($arrReceiver);
        $sign['sign_type'] = 'HMAC-SHA256';
        $sign['transaction_id'] = $order->payLog->sTransactionID;

        print '<pre>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '分账数据1：';
        var_dump($sign);


        //加密结果
        $arrData = [];
        $arrData['appid'] = $appid; //服务号ID;
        $arrData['mch_id'] = static::MCHID;
        $arrData['nonce_str'] = $nonce_str;
        $arrData['out_order_no'] = $out_order_no;
        $arrData['receivers'] = $sign['receivers'];
        $arrData['sign_type'] = 'HMAC-SHA256';
        $arrData['transaction_id'] = $sign['transaction_id'];
        $arrData['sign'] = strtoupper(hash_hmac("sha256", Func::arrayToString($sign) . '&key=' . static::KEY, static::KEY));

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/multiprofitsharing';

        print '<pre>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '分账数据2：';
        var_dump($arrData);

        $res = Func::HttpRequestShare($url, $arrData);
        print '<pre>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '分账结果：';
        var_dump($res);
    }

    private function SplitMoneyEnd($appid, $nonce_str, $orderName, $transaction_id)
    {
        $sign = [];
        $sign['mch_id'] = self::MCHID;
        $sign['appid'] = $appid; //服务号ID;
        $sign['nonce_str'] = $nonce_str;
        $sign['transaction_id'] = $transaction_id;//微信支付的商户号
        $sign['out_order_no'] = $orderName;
        $sign['sign_type'] = 'HMAC-SHA256';
        $sign['description'] = '分账已完成';
        $key = self::KEY;
        //$sign['sign'] = strtoupper(hash_hmac("sha256", Func::arrayToString($sign) . '&key=' . $key, $key));
        $sign['sign'] = strtoupper(hash_hmac("sha256", Func::arrayToString($sign) . '&key=' . static::KEY, static::KEY));


        echo '<pre>';
        var_dump($sign);
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/profitsharingfinish';
        $res = Func::HttpRequestShare($url, $sign);
        echo '123123123123';
        var_dump($res);
    }

    /***********交易成功分账********结束***********/
    /***********微信提现********开始***********/
    public function actionTransfer()
    {
        $withdraw = Withdraw::find()->where(['and',
            ['>', 'dNewDate', '2020-03-11'],
            ['CheckID' => 1],
            ['sAccountNo' => null]
        ])->one();

        echo '<pre>';
        var_dump($withdraw);
        //exit;


        if (!$withdraw) {
            return false;
        }
        $log = SellerWithdrawLog::find()->where(['TypeID' => 1, 'sName' => $withdraw->sName])->one();
        if (!$log) {
            return false;
        }
        $withdraw->sAccountNo = $log->sOpenID;
        $withdraw->sAccountName = 'wx';
        $withdraw->save();
        $arrHandle = json_decode($log->sLog, true);
        if (!$arrHandle) {
            $arrHandle = [];
        }
        $result = $this->payTransfer($log->sName, $log->sOpenID, $log->fMoney);
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $log->dAcceptDate = \Yii::$app->formatter->asDatetime(time());
            $log->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
            $log->TypeID = 2;
            array_unshift($arrHandle, ['微信受理', $log->dAcceptDate]);
            array_unshift($arrHandle, ['确认到账', $log->dCompleteDate]);
            $log->sLog = json_encode($arrHandle);
            $log->save();
            //更改账户信息
            $seller = $log->seller;
            //fWithdraw, ,fSettlement,fWithdrawed
            $seller->fWithdraw += $log->fMoney;//已提现
            $seller->fSettlement -= $log->fMoney;//可提现
            $seller->fWithdrawed -= $log->fMoney;//提现中
            $seller->save();

        } else {
            $log->dAcceptDate = \Yii::$app->formatter->asDatetime(time());
            $log->TypeID = 0;
            $log->sFailReason = $result['err_code_des'];

            array_unshift($arrHandle, ['微信受理失败', $log->dAcceptDate]);
            $log->sLog = json_encode($arrHandle);
            $log->save();
            $withdraw->sAccountNo = null;
            $withdraw->sAccountName = null;
            $withdraw->save();
        }

    }

    //提现接口测试
    public function payTransfer($partner_trade_no, $openID, $amount)
    {
        $appid = static::APPID;
        //加密数组
        $nonce_str = Func::getNonce();
        $sign = [];
        $sign['mch_appid'] = $appid; //服务号ID;
        $sign['mchid'] = static::MCHID;
        $sign['nonce_str'] = $nonce_str;
        $sign['partner_trade_no'] = $partner_trade_no;
        $sign['openid'] = $openID;
        $sign['check_name'] = 'NO_CHECK';
        $sign['amount'] = (int)($amount * 100);
        $sign['desc'] = '来瓜分提现成功';
        $sign['spbill_create_ip'] = \Yii::$app->request->userIP;
        print '<pre>';
        var_dump($sign);
        $stringA = Func::arrayToString($sign);
        $stringSignTemp = $stringA . '&key=' . static::KEY;
        $signs = strtoupper(md5($stringSignTemp));
        $sign['sign'] = $signs;
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $res = Func::HttpRequestShare($url, $sign);
        print '<pre>';
        echo '<br>';
        echo '转账结果：';
        var_dump($res);
        return $res;
    }

    /***********微信提现********结束***********/

    public function actionT()
    {
        $order = Order::findOne(8150);
        $order->wxPaySuccess();
        //$orderPayLog = $order->payLog;
      // $this->refundmoney(29.9,$orderPayLog);
//        $partner_trade_no = 'ACT0001';
//        $openID = 'oyVXAwM-8AJd9ndKNAJAsq5s706k';
//        $amount = 0.3;
//        $this->payTransfer($partner_trade_no, $openID, $amount);
    }


    //申请退款
    public function actionRefund($id)
    {
        if ($id != 1) return;
        $arrID = [
        ];
        $arrOrder = Order::findAll($arrID);
        foreach ($arrOrder as $order) {
            if (!$order->RefundStatusID) {
                $TypeID = 'onlymoney';
                $fRefundReal = $order->fSumOrder;
                $fRefundProduct = $order->fSumOrder - $order->fShip;
                $orderDetail = $order->orderDetail;
                $refundData = [
                    'TypeID' => $TypeID,
                    'sReason' => '拼团失败',
                    'OrderDetailID' => $orderDetail->lID,
                    'fRefundApply' => $fRefundReal,
                    'sExplain' => '拼团失败',
                    'imgList' => '',
                    'lItemTotal' => 1,
                    'lRefundItem' => 1,
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
                //调整订单状态
                $order->RefundStatusID = 'refunding';
                $order->save();
                /* 如果是云订单调用云端API将订单退款状态传递至云端 开始*/
                $cloudOrder = CloudOrder::find()->where(['sClientSN' => $order->sName])->one();
                if ($cloudOrder) {
                    $cloudOrder->RefundStatusID = 'refunding';
                    $cloudOrder->save();
                }
            }
        }
    }

    /////////////////////////////////////银联支付新//////////////////////////////////////////////////////////////
    /// 公众号支付接口测试环境参数示例
    //商户号(mid):微信支付宝支付用这个：898340149000005  （全民付和无卡支付 暂在生产上测）
    //终端号(tid)：88880001
    //机构商户号(instMid)：YUEDANDEFAULT
    //消息来源(msgSrc)：WWW.TEST.COM
    //来源编号（msgSrcId）：3194
    //测试环境MD5密钥:fcAmtnx7MwismjWNhNKdHC44mNXtnEQeJkRrhKJwyrW2ysRR
    //https://qr-test2.chinaums.com/netpay-route-server/api/
    public function actionUpay(){
        $date = date("Y-d-m H:i:s",time());
        $merOrderId = "3015".time();
        $date = urlencode($date);
        $orderDesc= "来瓜分订单支付";//urlencode('武汉徐东路站95#汽油');


        $notifyUrl = 'https://yl.aiyolian.cn/api/uback';
        $returnUrl = 'https://yl.aiyolian.cn/member';
        $restr = 'attachedData=attachedData&instMid=QRPAYYUEDAN&merOrderId='.$merOrderId.'&mid=898340149000005&msgId=3194&msgSrc=WWW.SUPERB-PAY.COM&msgType=WXPay.jsPay&notifyUrl='.$notifyUrl.'&orderDesc='.$orderDesc.'&originalAmount=100&requestTimestamp='.$date.'&returnUrl='.$returnUrl.'&srcReserve=testing&systemId=3015&tid=88880001&totalAmount=1SsEnTy3RxTWyQK86JwswZpaE4EAHkdNP8sBzK5NNw3BYND8W';
        $sign = strtoupper(md5($restr));

        $host = 'https://qr-test2.chinaums.com/netpay-portal/webpay/pay.do?';
        $arr['msgId'] = 3194;
        $arr['msgSrc'] = 'WWW.SUPERB-PAY.COM';
        $arr['msgType'] = 'WXPay.jsPay';
        $arr['requestTimestamp'] = $date;
        $arr['merOrderId'] = $merOrderId;
        $arr['srcReserve'] = "testing"; //请求系统预留字段
        $arr['mid'] = "898340149000005";
        $arr['tid'] = "88880001";
        $arr['instMid'] = "QRPAYYUEDAN";
        $arr['attachedData'] = "attachedData"; //商户附加数据
        $arr['orderDesc'] = $orderDesc;
        $arr['originalAmount'] = 100;
        $arr['totalAmount'] = 1;
        $arr['notifyUrl'] = $notifyUrl; // 回调地址
        $arr['returnUrl'] = $returnUrl; //网页跳转地址
        $arr['systemId'] = 3015;
        $arr['sign'] = $sign;
        $arr_url = http_build_query($arr);
        $get_url = $host.$arr_url;
        header('Location: '.$get_url);
    }
    public function actionUback()
    {
        $log = new OrderPayLog();
        $log->sTradeNo = 'CST001';
        $log->sTransactionID = 'A01';
        $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $log->fPaid = 0.1;
        $log->PaymentID = 'wx';
        $log->sPayInfo = json_encode($_POST);
        $log->save();
        echo 'success';
    }
    public function actionUquery()
    {
        return parent::actions(); // TODO: Change the autogenerated stub
    }

    /////////////////////////////////////银联支付新//////////////////////////////////////////////////////////////
}
