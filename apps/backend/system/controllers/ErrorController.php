<?php
namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\system\syssolution\SysSolution;

/**
 * 出错页面
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-19 15:15
 * @version v2.0
 */
class ErrorController extends ObjectController
{
    public function actionHome()
    {
        $arrData = [];

        $arrData['exception'] = \Yii::$app->errorHandler->exception;

        return $this->render('home', $arrData);
    }
}