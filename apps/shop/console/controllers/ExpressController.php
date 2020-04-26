<?php

namespace console\controllers;

use myerm\shop\common\models\MallConfig;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * 快递接口
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2018年7月2日 15:22:06
 * @version v1.3
 */
class ExpressController extends Controller
{
    /**
     * 同步超时的物流信息
     */
    public function actionSync()
    {
        \Yii::$app->expresstrace->sync();
        return ExitCode::OK;
    }

    /**
     * 处理订单明细里没有被跟踪的物流
     */
    public function actionOrdernotrace()
    {
        \Yii::$app->expresstrace->orderNoTrace();
        return ExitCode::OK;
    }

}