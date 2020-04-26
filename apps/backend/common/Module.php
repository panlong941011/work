<?php

namespace myerm\backend\common;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'myerm\backend\common\controllers';

    public function init()
    {
        parent::init();

        $this->setLayoutPath('@myerm/backend/common/views/layouts');
    }

    public function bootstrap($app)
    {
    }
}