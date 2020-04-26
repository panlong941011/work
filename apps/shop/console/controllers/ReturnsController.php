<?php

namespace console\controllers;

use myerm\shop\common\models\Returns;
use yii\console\Controller;

/**
 * 退货售后处理
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年11月10日 09:13:35
 * @version v1.0
 */
class ReturnsController extends Controller
{
    /**
     * 保存退货申请
     * @param $param 退货信息参数
     * @author panlong
     * @time 2018-06-05
     */
    public function actionSavereturns($param)
    {
        try {
            $data = json_decode(base64_decode($param), true);

            \Yii::$app->returns->saveReturns($data);

            echo json_encode(['code' => 1, 'data' => 'success']);
            return static::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            echo json_encode(['code' => -1, 'data' => $e->getMessage()]);
            return static::EXIT_CODE_NORMAL;
        }
    }

    /**
     * 修改退货申请
     * @param $param 退货信息参数
     * @author panlong
     * @time 2018-06-05
     */
    public function actionModifyreturns($param)
    {
        try {
            $data = json_decode(base64_decode($param), true);

            \Yii::$app->returns->modifyReturns($data);

            echo json_encode(['code' => 1, 'data' => 'success']);
            return static::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            echo json_encode(['code' => -1, 'data' => $e->getMessage()]);
            return static::EXIT_CODE_NORMAL;
        }
    }

    /**
     * 撤销退货申请
     * @param $param 退货信息参数
     * @author panlong
     * @time 2018-06-05
     */
    public function actionCancelreturns($param)
    {
        try {
            $data = json_decode(base64_decode($param), true);

            \Yii::$app->returns->cancelReturns($data);

            echo json_encode(['code' => 1, 'data' => 'success']);
            return static::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            echo json_encode(['code' => -1, 'data' => $e->getMessage()]);
            return static::EXIT_CODE_NORMAL;
        }
    }

    /**
     * 超时计划任务
     * @author panlong
     * @time 2018-7-13 17:00:07
     */
    public function actionTimeout()
    {
        \Yii::$app->returns->timeout();
    }
}