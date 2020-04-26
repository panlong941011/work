<?php
/**
 * Created by PhpStorm.
 * User: Yang Dingchuan
 * Date: 2018/4/20
 * Time: 18:59
 */

namespace union;

require_once 'UnionClient.php';
require_once 'phpqrcode.php';
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
//二维码请求demo
$unionClient = new UnionClient();
$unionClient->requestUrl = 'https://qr-test2.chinaums.com/netpay-route-server/api/';
$unionClient->key = 'fcAmtnx7MwismjWNhNKdHC44mNXtnEQeJkRrhKJwyrW2ysRR';

//订单号
$billNo = $unionClient->msgSrcId . date('YmdHis') . rand(1000000, 9999999);

//参数自行添加或修改
$unionClient->setParams('mid', '898340149000005');    //商户号
$unionClient->setParams('tid', '88880001');    //终端号
$unionClient->setParams('msgType', 'bills.getQRCode');    //消息类型
$unionClient->setParams('msgSrc', 'WWW.TEST.COM');    //消息来源
$unionClient->setParams('instMid', 'YUEDANDEFAULT');    //业务类型
$unionClient->setParams('billNo', '319420180710165504676');    //账单号
$unionClient->setParams('totalAmount', 1);    //支付总金额(单位：分)
//$unionClient->setParams('billDate', date('Y-m-d', time()));    //账单日期：yyyy-MM-dd
$unionClient->setParams('billDate', '2018-07-10');    //账单日期：yyyy-MM-dd
$unionClient->setParams('billDesc', '缴费项目：校车费319420180710165504676订单号：');    //账单描述
$unionClient->setParams('notifyUrl', 'CallbackDemo.php');    //支付结果通知地址
$unionClient->setParams('returnUrl', 'www.baidu.com');    //网页跳转地址
//$unionClient->setParams('requestTimestamp', date('Y-m-d H:i:s', time()));    //报文请求时间:yyyy-MM-dd HH:mm:ss
$unionClient->setParams('requestTimestamp', '2018-07-10 09:46:27');    //报文请求时间:yyyy-MM-dd HH:mm:ss

$res = $unionClient->request();

$payUrl = $res['billQRCode'];

/**
 * 生成二维码图片保存
 * 文件夹需要权限
 */
$fileName = 'qr.png';
\QRcode::png($payUrl, $fileName, 3, 4, 3, true);

?>

<!--'<img src="qr.png" alt="使用微信扫描支付">'-->