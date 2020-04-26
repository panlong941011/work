<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2019112169368272",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "B3XyHrcmnDA3OKtaaJFXIA==",
		
		//异步通知地址
		'notify_url' => "https://yl.aiyolian.cn/pay/alinotify",
		
		//同步跳转
		'return_url' => "http://mitsein.com/alipay.trade.wap.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhQ0XoBmQhOgqxPSD0/8l8BVmkcdd7HAt2Cgw+l4zRCCrtZOluwO4sXYveh1qrzuB1afn4MtB66Zk+hP2TEJO7YqeNonaqKO8kYtEZWuCgwjNVeyS/T5z7fWYEuEkKMlm3kkejb/YqYbtgAg4DqySkSt7qDB5oo8Ve9PcFbB2V4QptAovw7sy2zyGcZr/nQ52S/in/7kVnpSA/0GBhV+PtPnDMuVxZyq6bpj1WMzlDBsSdrSzu/x0jiTERLHDyc4PpsOpnQtcHRBWjDzpRaacomg647l6nwWSc2tiMQzlAhWf3vz1q+qFWSecRdQUw5oswD/0kKu42hSiIARERDUhhwIDAQAB",
		
	
);