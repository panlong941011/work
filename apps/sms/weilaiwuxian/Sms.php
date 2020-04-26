<?php

namespace myerm\sms\weilaiwuxian;

use myerm\common\models\MyERMModel;
use myerm\sms\SmsInterface;

class Sms extends MyERMModel implements SmsInterface
{
    public $sAccount = "";
    public $sPass = "";

    public function send($sMobile, $sContent)
    {
    	
        \Yii::trace([
            'sMobile' => $sMobile,
            'sContent' => $sContent
        ], 'smsInfo');

        $param = [
            'cust_code' => $this->sAccount,
            'content' => urlencode($sContent),
            'destMobiles' => $sMobile,
            'sign' => md5(urlencode($sContent) . $this->sPass)
        ];
	  
        $post_data = $this->handleParam($param);

        $ch = curl_init();
        ob_start();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, 'http://123.58.255.70:8860');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        $info = ob_get_clean();
	  
	    
        if (\yii\helpers\StringHelper::startsWith($info, 'SUCCESS')) {
            return true;
        } else {
            return false;
        }
    }

    public function handleParam($param)
    {
        $data = '';
        foreach ($param as $key => $value) {
            $data .= $key . '=' . $value . '&';
        }

        return $data;
    }
}