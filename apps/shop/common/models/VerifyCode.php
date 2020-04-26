<?php

namespace myerm\shop\common\models;


/**
 * 验证码类
 */
class VerifyCode extends ShopModel
{
    public static function send($sMobile)
    {
        static::deleteAll("dNewDate<'" . (\Yii::$app->formatter->asDatetime(time() - 300)) . "'");

        $code = static::findOne(\Yii::$app->frontsession->ID);
        if ($code && \Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($code->dNewDate) < 60) {
            return ['status' => false, 'message' => '60秒内只能发送一次验证码'];
        }

        if (!$code) {
            $code = new static();
            $code->SessionID = \Yii::$app->frontsession->ID;
        }

        $code->sCode = rand(1000, 9999);
        $code->sMobile = $sMobile;
        $code->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $code->save();

        $sContent = '您本次的验证码是：' . $code->sCode . '。请不要把验证码泄露给其他人，如非本人操作请忽略！';

        //手机短信通知
        \Yii::$app->sms->send($sMobile, $sContent);

        return ['status' => true];
    }

    public static function getCode()
    {
        return static::findOne(\Yii::$app->frontsession->ID)->sCode;
    }

    public static function getCodeByMobile($mobile)
    {
        return static::find()->where(['sMobile' => $mobile])->orderBy('dNewDate desc')->one()->sCode;
    }
}