<?php

namespace myerm\shop\mobile\controllers;

use myerm\common\components\CommonEvent;
use myerm\shop\common\models\FrontSession;
use myerm\shop\common\models\GoldRechargeConfig;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\OrderBatchPay;
use myerm\shop\common\models\PreOrder;
use myerm\shop\mobile\models\Order;
use myerm\shop\mobile\models\OrderPayLog;


/**
 * 支付控制器
 */
class PayController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionSuccess()
    {
        $this->getView()->title = "支付成功";

        return $this->render("success");
    }

    /**
     * 金币充值微信通知
     */
    public function actionRechargewxnotify()
    {
        $response = \Yii::$app->wechat->payment->handleNotify(function ($notify, $successful) {

            if ($successful) {

                $arr = explode("-", $notify->out_trade_no);

                $MemberID = $arr[1];
                $fPaid = $notify->total_fee / 100;


                //获取赠金币
                $arrConfig = GoldRechargeConfig::all();
                krsort($arrConfig);
                $fGive = 0;
                foreach ($arrConfig as $config) {
                    if ($fPaid >= $config->fFull) {
                        $fGive = $config->fGive;
                        break;
                    }
                }

                \Yii::$app->goldrecharge->recharge([
                    'sName' => $arr[0],
                    'fPaid' => $fPaid,
                    'fGive' => $fGive,
                    'MemberID' => $MemberID,
                    'NewUserID' => null,
                    'sNote' => null,
                    'PaymentID' => 'wx',
                    'dPayDate' => \Yii::$app->formatter->asDatetime(time()),
                ]);

                \Yii::$app->goldflow->change([
                    'fChange' => $fPaid + $fGive,
                    'sName' => '微信充值',
                    'MemberID' => $MemberID,
                    'NewUserID' => null,
                    'TypeID' => 1
                ]);
            }

            return true; // 或者错误消息
        });

        $response->send();
    }

    /**
     * 微信支付回调
     */
    public function actionWxnotify()
    {
        $body = @file_get_contents('php://input');
        $data = $this->xml_array($body);
        $log = new OrderPayLog();
        $log->sTradeNo = $data['out_trade_no'];
        $log->sTransactionID = $data['transaction_id'];
        $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $log->fPaid = $data['total_fee'];
        $log->PaymentID = 'wx';
        $log->sPayInfo = json_encode($data);
        $log->save();
        //1.检查返回状态码
        if ($data['return_code'] !== 'SUCCESS') {
            return $this->buildStr(false);
        }

        //2.检查业务结果
        if ($data['result_code'] !== 'SUCCESS') {
            return $this->buildStr(false);
        }

        $order = Order::findOne(['sName' => $log->sTradeNo]);
        if ($order) {
            if ($order->StatusID == 'paid') {
                return $this->buildStr(true);
            } else {
                $log->sOrderID = $order->lID;
                $log->MemberID = $order->MemberID;
                $order->dPayDate = $log->dNewDate;
                $order->fPaid = $log->fPaid / 100;
                $order->StatusID = 'paid';
                $order->PaymentID = 'wx';
                $log->StausID = 1;
                $order->save();
                $order->wxPaySuccess();
                $log->save();
            }
        } else {
            $log->StausID = 1;
            $log->save();
            $arrOrder = Order::find()->where(['sTradeNo' => $log->sTradeNo])->all();
            foreach ($arrOrder as $order) {
                if ($order->StatusID == 'paid') {
                } else {
                    $log = new OrderPayLog();
                    $log->sTradeNo = $data['out_trade_no'];
                    $log->sTransactionID = $data['transaction_id'];
                    $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
                    $log->fPaid = $data['total_fee'];
                    $log->PaymentID = 'wx';
                    $log->sPayInfo = json_encode($data);
                    $log->sOrderID = $order->lID;
                    $log->MemberID = $order->MemberID;
                    $log->StausID = 1;
                    $log->save();


                    $order->dPayDate = $log->dNewDate;
                    $order->fPaid = $order->fSumOrder;
                    $order->StatusID = 'paid';
                    $order->PaymentID = 'wx';
                    $order->save();
                    $order->wxPaySuccess();
                }
            }
        }

        return $this->buildStr(true);
    }

    /**
     * 小程序
     */
    public function actionWxnotifyxcx()
    {
        $body = @file_get_contents('php://input');
        $data = $this->xml_array($body);
        $log = new OrderPayLog();
        $log->sTradeNo = $data['out_trade_no'];
        $log->sTransactionID = $data['transaction_id'];
        $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $log->fPaid = $data['total_fee'];
        $log->PaymentID = 'wx';
        $log->sPayInfo = json_encode($data);
        $log->save();
        //1.检查返回状态码
        if ($data['return_code'] !== 'SUCCESS') {
            return $this->buildStr(false);
        }

        //2.检查业务结果
        if ($data['result_code'] !== 'SUCCESS') {
            return $this->buildStr(false);
        }

        $order = Order::findOne(['sName' => $log->sTradeNo]);
        if ($order) {
            if ($order->StatusID == 'paid') {
                return $this->buildStr(true);
            } else {

                $log->sOrderID = $order->lID;
                $log->MemberID = $order->MemberID;
                $order->dPayDate = $log->dNewDate;
                $order->fPaid = $log->fPaid / 100;
                $order->StatusID = 'paid';
                $order->PaymentID = 'xcx';
                $log->StausID = 1;
                $order->save();
                $order->wxPaySuccess();
            }
        }
        $log->save();
        return $this->buildStr(true);
    }

    public function xml_array($str)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    public function buildStr($bSuccess = true, $msg = 'OK')
    {
        return "<xml>
                <return_code><![CDATA[" . ($bSuccess ? 'SUCCESS' : 'FAIL') . "]]></return_code>
                <return_msg><![CDATA[" . $msg . "]]></return_msg>
              </xml>";
    }

    /*
     * 支付宝
     */
    public function actionAlinotify()
    {
        $log = new OrderPayLog();
        $log->sTradeNo = $_POST['out_trade_no'];
        //$log->sTransactionID = $data['transaction_id'];
        $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
        //$log->fPaid = $data['total_fee'];
        $log->PaymentID = 'alipay';
        $log->sPayInfo = json_encode($_POST);
        $log->save();
        //1.检查返回状态码
        return "success";
    }

    public function actionT()
    {
        $sTradeNo = '2020021902375921339';
        $order = Order::findOne(['sName' => $sTradeNo]);
        if ($order) {
            if ($order->StatusID == 'paid') {
                return $this->buildStr(true);
            } else {
                $order->dPayDate = $order->dNewDate;
                $order->fPaid = $order->fSumOrder;
                $order->StatusID = 'paid';
                $order->PaymentID = 'wx';
                $order->save();
                $order->wxPaySuccess();
            }
        } else {
            $arrOrder = Order::find()->where(['sTradeNo' => $sTradeNo])->all();
            foreach ($arrOrder as $order) {
                if ($order->StatusID == 'paid') {
                } else {
                    $order->dPayDate = $order->dNewDate;
                    $order->fPaid = $order->fSumOrder;
                    $order->StatusID = 'paid';
                    $order->PaymentID = 'wx';
                    $order->save();
                    $order->wxPaySuccess();
                }
            }
        }

    }

}