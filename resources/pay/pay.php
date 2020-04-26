<?php
// MD5后转大写
// $sign = attachedData=attachedData&goods=[{"body":"武汉徐东路站95#汽油","goodsCategory":"95#汽油","goodsId":"001","goodsName":"95#汽油","price":"1","quantity":"1"}]&instMid=QRPAYYUEDAN&merOrderId=billNo&mid=898340149000005&msgId=3194&msgSrc=WWW.SUPERB-PAY.COM&msgType=WXPay.jsPay&notifyUrl=www.baidu.com&orderDesc=武汉徐东路站95#汽油&originalAmount=100&requestTimestamp=2017-06-28 09:54:38&returnUrl=www.baidu.com&srcReserve=testing&systemId=3015&tid=88880001&totalAmount=1SsEnTy3RxTWyQK86JwswZpaE4EAHkdNP8sBzK5NNw3BYND8W
// sign 追加下面链接后面
// 'https://qr-test2.chinaums.com/netpay-portal/webpay/pay.do?msgId=3194&msgSrc=WWW.SUPERB-PAY.COM&msgType=WXPay.jsPay&requestTimestamp='.$date.'&merOrderId='.$merOrderId.'&srcReserve=testing&mid=898340149000005&tid=88880001&instMid=QRPAYYUEDAN&goods='.$goods.'&attachedData=attachedData&orderDesc='.$orderDesc.'&originalAmount=100&totalAmount=1&notifyUrl='.$notifyUrl.'&returnUrl=zhihui.gz-gk.cn&systemId=3015&sign='

	$date = date("Y-d-m H:i:s",time());
    $merOrderId = "3015".time();
    $date = urlencode($date);
    $orderDesc= "武汉徐东路站95#汽油";//urlencode('武汉徐东路站95#汽油');
    $Cate = "95#汽油#汽油";//urlencode('95#汽油#汽油');
   $goods = urlencode('[{"body":"武汉徐东","goodsCategory":"95#汽油","goodsId":"001","goodsName":"95#汽油","price":"1","quantity":"1"}]');
    
    $notifyUrl = urlencode('zhihui.gz-gk.cn/index/wx_pay/notifyUrl/');
    $returnUrl = urlencode('zhihui.gz-gk.cn');
    $restr = 'attachedData=attachedData&goods='.$goods.'&instMid=QRPAYYUEDAN&merOrderId='.$merOrderId.'&mid=898340149000005&msgId=3194&msgSrc=WWW.SUPERB-PAY.COM&msgType=WXPay.jsPay&notifyUrl='.$notifyUrl.'&orderDesc='.$orderDesc.'&originalAmount=100&requestTimestamp='.$date.'&returnUrl='.$returnUrl.'&srcReserve=testing&systemId=3015&tid=88880001&totalAmount=1SsEnTy3RxTWyQK86JwswZpaE4EAHkdNP8sBzK5NNw3BYND8W';
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
    $arr['goods'] = $goods;
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
   // print_r($get_url);
	//die;
	//$get_url = 'https://qr-test2.chinaums.com/netpay-portal/wxpay/jsPay.do?msgId=3194&msgSrc=WWW.SUPERB-PAY.COM&msgType=WXPay.jsPay&requestTimestamp=2018-05-07%2B11%253A45%253A48&merOrderId=30151530762348&srcReserve=testing&mid=898340149000005&tid=88880001&instMid=QRPAYYUEDAN&goods=%255B%257B%2522body%2522%253A%2522%25E6%25AD%25A6%25E6%25B1%2589%25E5%25BE%2590%25E4%25B8%259C%25E8%25B7%25AF%25E7%25AB%259995%2523%25E6%25B1%25BD%25E6%25B2%25B9%2522%252C%2522goodsCategory%2522%253A%252295%2523%25E6%25B1%25BD%25E6%25B2%25B9%2522%252C%2522goodsId%2522%253A%2522001%2522%252C%2522goodsName%2522%253A%252295%2523%25E6%25B1%25BD%25E6%25B2%25B9%2522%252C%2522price%2522%253A%25221%2522%252C%2522quantity%2522%253A%25221%2522%257D%255D&attachedData=attachedData&orderDesc=%E6%AD%A6%E6%B1%89%E5%BE%90%E4%B8%9C%E8%B7%AF%E7%AB%9995%23%E6%B1%BD%E6%B2%B9&originalAmount=100&totalAmount=1&notifyUrl=zhihui.gz-gk.cn%252Findex%252Fwx_pay%252FnotifyUrl%252F&returnUrl=zhihui.gz-gk.cn&systemId=3015&sign=8CC03830E2B71A5A3D4A2F94765B9A32';
header('Location: '.$get_url);
    // https://qr-test2.chinaums.com/netpay-portal/wxpay/jsPay.do?msgId=3194&msgSrc=WWW.SUPERB-PAY.COM&msgType=WXPay.jsPay&requestTimestamp=2018-05-07%2B11%253A45%253A48&merOrderId=30151530762348&srcReserve=testing&mid=898340149000005&tid=88880001&instMid=QRPAYYUEDAN&goods=%255B%257B%2522body%2522%253A%2522%25E6%25AD%25A6%25E6%25B1%2589%25E5%25BE%2590%25E4%25B8%259C%25E8%25B7%25AF%25E7%25AB%259995%2523%25E6%25B1%25BD%25E6%25B2%25B9%2522%252C%2522goodsCategory%2522%253A%252295%2523%25E6%25B1%25BD%25E6%25B2%25B9%2522%252C%2522goodsId%2522%253A%2522001%2522%252C%2522goodsName%2522%253A%252295%2523%25E6%25B1%25BD%25E6%25B2%25B9%2522%252C%2522price%2522%253A%25221%2522%252C%2522quantity%2522%253A%25221%2522%257D%255D&attachedData=attachedData&orderDesc=%E6%AD%A6%E6%B1%89%E5%BE%90%E4%B8%9C%E8%B7%AF%E7%AB%9995%23%E6%B1%BD%E6%B2%B9&originalAmount=100&totalAmount=1&notifyUrl=zhihui.gz-gk.cn%252Findex%252Fwx_pay%252FnotifyUrl%252F&returnUrl=zhihui.gz-gk.cn&systemId=3015&sign=8CC03830E2B71A5A3D4A2F94765B9A32
