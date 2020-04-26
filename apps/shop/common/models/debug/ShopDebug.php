<?php

namespace myerm\shop\common\models\debug;

use myerm\shop\common\models\ShopModel;

/**
 * ShopERM的Debug类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月12日 10:52:24
 * @version v2.0
 */
class ShopDebug extends ShopModel
{

    public function rules()
    {
        return [
            [['ID', 'sName', 'SessionID', 'dNewDate', 'bAjax', 'sMethod', 'sIP', 'sStatusCode', 'lSQLCount', 'lMailCount', 'sSavePath', 'lTimeUse'], 'required'],
        ];
    }

    /**
     * 保存debug的数据
     * @param $arrCollectSummary
     */
    public function saveDebugData($arrCollectSummary)
    {
        $this->setAttributes($arrCollectSummary, false);

        if ($this->validate()) {
            $this->save();
        }

        return true;
    }

    /**
     * 清除过期的debug日志
     */
    public function clearExpire()
    {
        if (\Yii::$app->params['debugExpireTime']) {
            $arrDebug = static::find()->select(['sSavePath'])->where("dNewDate<'" . \Yii::$app->formatter->asDatetime(time() - \Yii::$app->params['debugExpireTime']) . "'")->all();
        } else {
            $arrDebug = static::find()->select(['sSavePath'])->where("dNewDate<'" . \Yii::$app->formatter->asDatetime(time() - 5 * 86400) . "'")->all();
        }

        foreach ($arrDebug as $debug) {
            @unlink($debug->sSavePath);
        }

        if (\Yii::$app->params['debugExpireTime']) {
            static::deleteAll("dNewDate<'" . \Yii::$app->formatter->asDatetime(time() - \Yii::$app->params['debugExpireTime']) . "'");
        } else {
            static::deleteAll("dNewDate<'" . \Yii::$app->formatter->asDatetime(time() - 5 * 86400) . "'");
        }
    }
}