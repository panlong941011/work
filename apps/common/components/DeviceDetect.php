<?php

namespace myerm\common\components;

use Yii;


class DeviceDetect extends \alexandernst\devicedetect\DeviceDetect
{
    public function init()
    {
        parent::init();

        \Yii::$app->params['isMobile'] = \Yii::$app->params['devicedetect']['isMobile'];
        \Yii::$app->params['isTablet'] = \Yii::$app->params['devicedetect']['isTablet'];
        \Yii::$app->params['isDesktop'] = \Yii::$app->params['devicedetect']['isDesktop'];
        \Yii::$app->params['isWeChat'] = Yii::$app->wechat->isWechat;

        unset(\Yii::$app->params['devicedetect']);
    }
}
