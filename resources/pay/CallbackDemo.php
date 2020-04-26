<?php
/**
 * Created by PhpStorm.
 * User: Yang Dingchuan
 * Date: 2018/4/22
 * Time: 22:48
 */
 namespace union;

use Exception;
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
require_once 'UnionClient.php';

$params['msgType'] = 'wx.orderQuery';
$params['payTime'] = '2018-07-16 10:06:54';
$params['connectSys'] = 'OPENCHANNEL';
$params['merName'] = 'webpay测试商户';
$params['mid'] = '898310060514010';
$params['invoiceAmount'] = '1';
$params['settleDate'] = '2018-07-16';
$params['billFunds'] = '现金支付0.01元。';
$params['buyerId'] = 'oOUAZv-4JJdIINav2W3RN0Snh6Q8';
$params['tid'] = '88880001';
$params['targetMid'] = '220945617';
$params['targetOrderId'] = '4200000114201807169562799603';
$params['subBuyerId'] = 'oymNd1cAiGOZYpx-jjPbdWeddfjA';
$params['targetStatus'] = 'SUCCESS';
$params['seqId'] = '00266900894N';
$params['merOrderId'] = '319420180716100643296745';
$params['refundAmount'] = '0';
$params['targetSys'] = 'WXPay';
$params['msgSrc'] = 'WWW.TEST.COM';
$params['settleRefId'] = '00266900894N';
$params['totalAmount'] = '1';
$params['responseTimestamp'] = '2018-07-16 10:09:05';
$params['errCode'] = 'SUCCESS';
$params['buyerPayAmount'] = '0';
$params['goodsTradeNo'] = '1000201807161006469588276380';
$params['status'] = 'TRADE_SUCCESS';
$params['sign'] = 'D9A1BA0627603BC6524D233FED7183EB';

//验证签名
$unionClient = new UnionClient();
if (!$unionClient->verify($params)) {
    throw new Exception('签名验证失败');
}else{
	echo 'success';
}

//print_r($params);die;