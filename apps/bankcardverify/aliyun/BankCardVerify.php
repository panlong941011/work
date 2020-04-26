<?php

namespace myerm\bankcardverify\aliyun;

use myerm\common\models\MyERMModel;

class BankCardVerify extends MyERMModel
{
    public $sAppCode = "";

    /**
     * 执行校验
     */
    public function execute($bankcard, $cardNo, $realName, $Mobile)
    {
        $host = "https://aliyun-bankcard-verify.apistore.cn";
        $path = "/bank";
        $method = "GET";
        $appcode = $this->sAppCode;
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "Mobile=$Mobile&bankcard=$bankcard&cardNo=$cardNo&realName=$realName";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $result = curl_exec($curl);
        \Yii::trace($result);

        curl_close($curl);

        return json_decode($result, true);
    }
}