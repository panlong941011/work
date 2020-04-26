<?php
/**
 * Created by PhpStorm.
 * User: Yang Dingchuan
 * Date: 2018/4/20
 * Time: 18:59
 */

namespace union;

use Exception;
header("Content-type: text/html; charset=utf-8");
class UnionClient {
    //请求地址
    public $requestUrl = 'https://qr-test2.chinaums.com/netpay-route-server/api/';

    //秘钥
    public $key = 'fcAmtnx7MwismjWNhNKdHC44mNXtnEQeJkRrhKJwyrW2ysRR';

    public $msgSrcId = '3194';

    //支付参数,如有需要自行添加
    public $params = [
        'mid' => '',    //商户号
        'tid' => '',    //终端号
        'instMid' => 'QRPAYDEFAULT',    //业务类型
        'msgId' => '',    //消息id
        'msgSrc' => '',    //消息来源
        'msgType' => '',    //消息类型
        'requestTimestamp' => '',    //报文请求时间:yyyy-MM-dd HH:mm:ss
        'billNo' => '',    //账单号
        'billDate' => '',    //账单日期：yyyy-MM-dd
        'billDesc' => 'dcmuyi',    //账单描述
        'totalAmount' => '1',    //支付总金额
        'expireTime' => '',    //过期时间
        'notifyUrl' => '',    //支付结果通知地址
        'returnUrl' => '',    //网页跳转地址
        'qrCodeId' => '',    //二维码ID
        'systemId' => '',    //系统ID
        'secureTransaction' => '',    //担保交易标识
        'walletOption' => '',    //钱包选项
        'name' => '',    //实名认证姓名
        'mobile' => '',    //实名认证手机号
        'certType' => '',    //实名认证证件类型
        'certNo' => '',    //实名认证证件号
        'fixBuyer' => '',    //是否需要实名认证
        'limitCreditCard' => '',    //是否需要限制信用卡支付
        'signType' => 'md5',    //签名方式
        'sign' => '',    //签名
    ];

    /**
     * 请求
     * @return mixed
     * @throws Exception
     */
    public function request()
    {
        $params = $this->params;

        $sign = $this->generateSign($params, $params['signType']);
        $this->setParams('sign', $sign);
		echo(json_encode($this->params));
        //模拟请求
        $resp = $this->curl($this->requestUrl, $this->params);

        //准备验签
        $respList = json_decode($resp, true);
        if (!$this->verify($respList)) {
            throw new Exception('返回签名验证失败');
        }

        if ($respList['errCode']  != 'SUCCESS') {
            throw new Exception($respList['errMsg']);
        }

        return $respList;
    }

    /**
     * 模拟POST请求
     * @param $url
     * @param null $postFields
     * @return mixed
     * @throws Exception
     */
    protected function curl($url, $postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //有post参数-设置
        if (is_array($postFields) && 0 < count($postFields)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        }

        $header[] = "Content-type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);

        return $reponse;
    }

    /**
     * 验证签名是否正确
     * @param $data
     * @return bool
     */
    function verify($data) {
        //返回参数生成sign
        $signType = empty($data['signType']) ? 'md5' : $data['signType'];
        $sign = $this->generateSign($data, $signType);
		var_dump($sign);
        //返回的sign
        $returnSign = $data['sign'];
        if ($returnSign != $sign) {
            return false;
        }

        return true;
    }

    /**
     * 设置参数
     * @param $key
     * @param $valve
     */
    public function setParams($key, $valve) {
        $this->params[$key] = $valve;
    }

    /**
     * 根绝类型生成sign
     * @param $params
     * @param string $signType
     * @return string
     */
    public function generateSign($params, $signType = 'md5') {
        return $this->sign($this->getSignContent($params), $signType);
    }

    /**
     * 生成signString
     * @param $params
     * @return string
     */
    function getSignContent($params) {
        //sign不参与计算
        $params['sign'] = '';

        //排序
        ksort($params);

        $paramsToBeSigned = [];
        foreach ($params as $k=>$v) {
    		if(is_array($params[$k])){
    			$v = json_encode($v,JSON_UNESCAPED_UNICODE);
    		}else if(trim($v) == ""){
				continue;
			}
    		$paramsToBeSigned[] = $k.'='. $v;
        }
        unset ($k, $v);
        //签名字符串
        $stringToBeSigned = (implode('&', $paramsToBeSigned));
		//str_replace('¬','&not',$stringToBeSigned);
        $stringToBeSigned .= $this->key;
		echo $stringToBeSigned;
        return $stringToBeSigned;
    }

    /**
     * 生成签名
     * @param $data
     * @param string $signType
     * @return string
     */
    protected function sign($data, $signType = "md5") {
		
		$sign = md5(trim($data));
		return strtoupper($sign);
    }
}