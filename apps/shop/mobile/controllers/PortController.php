<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019/9/19
 * Time: 10:25
 */

namespace myerm\shop\mobile\controllers;


use myerm\shop\mobile\models\Order;
use myerm\shop\mobile\models\Pay;

class PortController extends \yii\web\Controller
{
    /*
* 获取openID
*/
    public function actionOpenid()
    {
        $js_code = $_GET['code'];
        if (empty($js_code)) {
            return false;
        }

        //大卡
        //appID:wx37d83e0acfe554d9
        //appsecret:5bc5b0162dd9dd3711e955774588f674
        //达咖选货
        //wxb7ad5f05ff005ccc
        //bdae14aee369f353c579bf04ec956cf4
        $pay = new Pay();
        $pay->sAppID = 'wx37d83e0acfe554d9';
        $pay->APPSECRET = '5bc5b0162dd9dd3711e955774588f674';


        return $pay->getOpenid($js_code);
    }

    /*
   * 小程序支付接口
   */
    public function actionPay()
    {
        $sTradeNo = $_GET['sTradeNo']; //订单交易号交易号，前台传入
        $userOpenID = $_GET['openid'];
        $backURL = 'https://yl.aiyolian.cn/pay/wxnotifyxcx';//支付回调接口不能少（可以由前台传入，前期可以由后台定死）
        if (empty($sTradeNo) || empty($userOpenID)) {
            return json_encode(['status' => false, 'msg' => '参数不完整']);
        }
        //订单编号支付
        $order = Order::find()->where(['and', ['sName' => $sTradeNo], ['StatusID' => 'unpaid']])->select('fSumOrder')->one();
        $fSumPaid = $order->fSumOrder;
        if ($fSumPaid <= 0) {
            return json_encode(['status' => false, 'msg' => '支付金额错误']);
        }
        $pay = new Pay();
        $pay->sAppID = 'wx37d83e0acfe554d9';
        $pay->APPSECRET = '5bc5b0162dd9dd3711e955774588f674';
        $data = $pay->getJsPayConfigfz(
            $userOpenID,
            $fSumPaid,
            '有链订单支付',
            $backURL,
            $sTradeNo
        );
        $data['status'] = true;
        return json_encode($data);
    }

    /*
     * 小程序二维码
     */
    public function actionQrcode()
    {
        $page = $_GET['page'];
        $pay = new Pay();
        $pay->sAppID = 'wxb7ad5f05ff005ccc';
        $pay->APPSECRET = 'bdae14aee369f353c579bf04ec956cf4';
        return $pay->getXcxQrcode($page);
    }
}