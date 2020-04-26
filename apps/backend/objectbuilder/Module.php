<?php

namespace myerm\backend\objectbuilder;

use yii\base\BootstrapInterface;


class Module extends \myerm\backend\common\Module implements BootstrapInterface
{
    public $controllerNamespace = 'myerm\backend\objectbuilder\controllers';

    public $allowedIPs = ['127.0.0.1', '::1'];

    public function init()
    {
        parent::init();

        if (!$this->checkAccess()) {
            exit(json_encode([
                'status' => 0,
                'message' => '非法访问，请把' . \Yii::$app->getRequest()->getUserIP() . "加入白名单。"
            ]));
        }
    }

    protected function checkAccess()
    {
        $ip = \Yii::$app->getRequest()->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter,
                        $pos))
            ) {
                return true;
            }
        }

        \Yii::warning('非法进入，IP是:' . $ip, __METHOD__);
        return false;
    }
}