<?php

namespace myerm\shop\mobile\models;

use myerm\shop\common\models\Member;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\ShopModel;


/**
 * 微信用户类
 */
class Pay extends ShopModel
{
    /****************************zhangyue paytest on 2018年12月24日10:43:51*************************************/
    public $sAppID = 'wxde428775a97e86be';//'wxde428775a97e86be';//公众号appID  wxb7ad5f05ff005ccc //小程序
    public $APPSECRET = '0dabb15c43d0a1ecc8c17de1bd1d775f';// '0dabb15c43d0a1ecc8c17de1bd1d775f';//公众号秘钥 bdae14aee369f353c579bf04ec956cf4//小程序
    private $sParterNo = '1549064271';//商户号
    private $key = '6sAlW88V7GWSCAZXCBo3SBiSIvXLVUZ6';//商户号秘钥


    //控制器 方法
    public function payinfo($fSumPaid, $sTradeNo, $backURL, $openID)
    {
//        $fSumPaid = 0.01; //应付款 订单数据验证
//        $sTradeNo = 'PM12312312331231'; //订单交易号交易号，前台传入
//        //$backURL = $_POST['backURL'];
//        $backURL = "https://m.aiyolian.cn/pay/wxnotify";//支付回调接口不能少（可以由前台传入，前期可以由后台定死）
        //$openID = \Yii::$app->frontsession->sOpenID;
        $data['JsPayConfig'] = $this->getJsPayConfig(
            $openID,
            $fSumPaid,
            '有链订单支付',
            $backURL,
            $sTradeNo
        );

        return json_encode($data);//需要根据 场景定义
        //注 在getPrepayID方法 有 $userOpenID（用户的微信令牌，需要充 微信用户表去取：逻辑可以考虑 根据定单的交易号 找到下单人的userID 然后查找 微信用户表得到openid）
        //其他私有方法，后续会补上注释， 有想了解 微信官方有详解文档
    }


    public function getJsPayConfig($openID, $fFee = 0, $body = '', $sNotifyUrl = '', $sTradeNo = '')
    {
        $fFee = $fFee * 100;
        //获取预付款id
        $perPayID = $this->getPrepayID($openID, $fFee, $body, $sNotifyUrl, $sTradeNo);

        //加密数组
        $sign = [];
        $sign['appId'] = $this->sAppID; //服务号ID;
        $sign['nonceStr'] = $this->getNonce();
        $sign['timeStamp'] = time();
        $sign['package'] = 'prepay_id=' . $perPayID;
        $sign['signType'] = 'MD5';
        //加密结果
        $data = [];
        $data['timestamp'] = $sign['timeStamp'];
        $data['noncestr'] = $sign['nonceStr'];
        $data['package'] = $sign['package'];
        $data['signtype'] = $sign['signType'];
        $data['paysign'] = strtoupper(md5($this->arrayToString($sign) . "&key=" . $this->key));
        $data['appId'] = $this->sAppID; //服务号ID;
        $data['signature'] = $this->getSignature($data);
        return $data;
    }


    private function getNonce($length = 32)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    private function getPrepayID($userOpenID, $fPay = 0, $sBody = '', $sNotifyUrl = '', $sOrderNo = '')
    {
        $sUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $arrOption = [
            'appid' => $this->sAppID,//公众账号ID
            'mch_id' => $this->sParterNo,//微信支付分配的商户号
            'device_info' => 'WEB',//终端设备号(门店号或收银设备ID)，注意：PC网页或公众号内支付请传"WEB"
            'nonce_str' => $this->getNonce(),//随机字符串，不长于32位。
            'body' => $sBody,//商品或支付单简要描述
            'out_trade_no' => $sOrderNo,//商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
            'total_fee' => $fPay,//订单总金额，单位为分
            'notify_url' => $sNotifyUrl,//接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
            'trade_type' => 'JSAPI',//交易类型
            'openid' => $userOpenID,//openid
        ];


        $str = $this->arrayToString($arrOption) . "&key=" . $this->key;
        $arrOption['sign'] = strtoupper(md5($str));//签名
        $data = $this->HttpRequest($sUrl, $arrOption);

        return $data['prepay_id'];
    }

    private function arrayToString($arrOrg = [])
    {
        $arrRes = [];
        ksort($arrOrg);
        foreach ($arrOrg as $k => $v) {
            array_push($arrRes, ($k . "=" . $v));
        }
        return implode("&", $arrRes);
    }

    private function HttpRequest($sUrl = "", $arrOption = [])
    {
        $header[0] = "Content-type: text/xml";
        $ch = curl_init($sUrl);

        $sXmlData = '<xml>';
        foreach ($arrOption as $key => $val) {
            if (is_numeric($val)) {
                $str = "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $str = "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
            $sXmlData .= $str;
        }
        $sXmlData .= '</xml>';
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sXmlData);

        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);
        //微信api返回xml数据，需要转成array格式
        $data = $this->xml_array($data);
        return $data;
    }

    private function xml_array($str)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    private function getSignature($data)
    {
        $sign = [];
        $sign['jsapi_ticket'] = $this->getJsTicket();
        $sign['noncestr'] = $data['noncestr'];
        $sign['timestamp'] = $data['timestamp'];
        $sign['url'] = \Yii::$app->homeUrl . \Yii::$app->request->url;
        $signature = sha1($this->arrayToString($sign));
        return $signature;
    }

    //通过微信令牌获取ticket
    private function HttpRequestGet($sUrl)
    {
        $data = file_get_contents($sUrl);
        //微信api返回json数据，需要转成array格式
        $data = json_decode(json_encode(json_decode($data)), true);
        return $data['ticket'];
    }

    private function getJsTicket()
    {
        //接口地址
        $sUrl = "https://api.weixin.qq.com/cgi-bin/ticket/getticket";
        $arrOption = [
            'access_token' => $this->getAccess_token(),
            'type' => 'jsapi'
        ];

        $sUrl = $this->urlParse($sUrl, $arrOption);
        return $this->HttpRequestGet($sUrl);
    }

    private function getAccess_token()
    {
        $appid = $this->sAppID;
        $appsecret = $this->APPSECRET;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $jsoninfo = json_decode(file_get_contents($url, true));
        return $jsoninfo->access_token;
    }

    private function urlParse($sUrl = '', $arrOption = [])
    {
        if (empty($sUrl) || empty($arrOption)) {
            throw new Exception('param error');
        }
        $bHasFirstParam = (bool)strstr($sUrl, '?');
        $str = $this->arrayToString($arrOption);
        return $sUrl . ($bHasFirstParam ? "&" : "?") . $str;
    }

    /*
     * 获取小程序二维码
     */
    public function getXcxQrcode($page)
    {
        $access_token = $this->getAccess_token();
        $request_url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
        $request_data = array(
            'scene' => 'abcdef',
            'path' => $page,
            'width' => '690'
        );
        $ch = curl_init();
        // 请求地址
        curl_setopt($ch, CURLOPT_URL, $request_url);
        // 请求参数类型
        $param = json_encode($request_data) ;
        // 关闭https验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // post提交
        if($param){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }
        // 返回的数据是否自动显示
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 执行并接收响应结果
        $output = curl_exec($ch);
        // 关闭curl
        curl_close($ch);
        return base64_encode($output);
    }

    /************分账支付*********/
    public function getJsPayConfigfz($openID, $fFee = 0, $body = '', $sNotifyUrl = '', $sTradeNo = '')
    {
        $fFee = $fFee * 100;
        //获取预付款id
        $perPayID = $this->getPrepayIDfz($openID, $fFee, $body, $sNotifyUrl, $sTradeNo);

        //加密数组
        $sign = [];
        $sign['appId'] = $this->sAppID; //服务号ID;
        $sign['nonceStr'] = $this->getNonce();
        $sign['timeStamp'] = time();
        $sign['package'] = 'prepay_id=' . $perPayID;
        $sign['signType'] = 'MD5';
        //加密结果
        $data = [];
        $data['timestamp'] = $sign['timeStamp'];
        $data['noncestr'] = $sign['nonceStr'];
        $data['package'] = $sign['package'];
        $data['signtype'] = $sign['signType'];
        $data['paysign'] = strtoupper(md5($this->arrayToString($sign) . "&key=" . $this->key));
        $data['appId'] = $this->sAppID; //服务号ID;
        $data['signature'] = $this->getSignature($data);
        return $data;
    }

    private function getPrepayIDfz($userOpenID, $fPay = 0, $sBody = '', $sNotifyUrl = '', $sOrderNo = '')
    {
        $sUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $arrOption = [
            'appid' => $this->sAppID,//公众账号ID
            'mch_id' => $this->sParterNo,//微信支付分配的商户号
            'device_info' => 'WEB',//终端设备号(门店号或收银设备ID)，注意：PC网页或公众号内支付请传"WEB"
            'nonce_str' => $this->getNonce(),//随机字符串，不长于32位。
            'body' => $sBody,//商品或支付单简要描述
            'out_trade_no' => $sOrderNo,//商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
            'total_fee' => $fPay,//订单总金额，单位为分
            'notify_url' => $sNotifyUrl,//接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
            'trade_type' => 'JSAPI',//交易类型
            'openid' => $userOpenID,//openid
            'profit_sharing' => 'Y'
        ];


        $str = $this->arrayToString($arrOption) . "&key=" . $this->key;
        $arrOption['sign'] = strtoupper(md5($str));//签名
        $data = $this->HttpRequest($sUrl, $arrOption);

        return $data['prepay_id'];
    }
    /************分账支付*********/
    /****************************zhangyue paytest on 2018年12月24日10:43:51*************************************/

    /*
	 * 获取openID
	 */
    public function getOpenid($js_code = '')
    {
        $appid = $this->sAppID;//小程序 appID
        $appsecret = $this->APPSECRET;//小程序appsecret
        $js_code = $_GET['code'];
        $curl = curl_init();
        //使用curl_setopt() 设置要获得url地址
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $appsecret . '&js_code=' . $js_code . '&grant_type=authorization_code';
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置是否输出header
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置是否输出结果
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置是否检查服务器端的证书
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //使用curl_exec()将curl返回的结果转换成正常数据并保存到一个变量中
        $data = curl_exec($curl);
        //关闭会话
        curl_close($curl);
        $user = json_decode($data);
        //$user->UserID = $this->checkUser($user);
        $sellerID = $this->getMember($user);
        $res = [
            'openid' => $user->openid,
            'sellerID' => $sellerID
        ];
        return json_encode($res);
    }

    private function getMember($user)
    {
        $sellerID = 0;
        $member = Member::findOne(['unionid' => $user->unionid]);
        if (!$member) {
            $member = new Member();
            $member->unionid = $user->unionid;
        } else {
            $seller = Seller::findOne(['MemberID' => $member->lID]);
            if ($seller) {
                $sellerID = $seller->lID;
            } else {
                $sellerID = 0;
            }
        }
        $member->sXopenID = $user->openid;
        $member->save();
        return $sellerID;
    }

}