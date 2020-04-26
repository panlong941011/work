<?php

namespace myerm\shop\backend;
use myerm\shop\common\components\Wechat;

class Module extends \myerm\backend\common\Module
{
    public $controllerNamespace = 'myerm\shop\backend\controllers';

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $wechat = new Wechat();
        $wechat->init();

        $config = require(__DIR__ . '/config/config.php');
        unset($config['components']['errorHandler']);
        unset($config['components']['request']);

        \Yii::$app->setComponents(array_merge(\Yii::$app->components, $config['components']));

        $this->controllerMap = $config['controllers'];
    }
}