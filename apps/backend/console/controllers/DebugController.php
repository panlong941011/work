<?php

namespace console\controllers;

use myerm\backend\common\models\debug\SysDebug;
use yii\console\Controller;

class DebugController extends Controller
{
    public function actionClearExpire()
    {
        SysDebug::clearExpire();
    }
}