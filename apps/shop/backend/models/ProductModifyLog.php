<?php

namespace myerm\shop\backend\models;

use myerm\shop\common\models\ShopModel;


/**
 * 商品操作日志
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年9月28日 17:24:22
 * @version v1.0
 */
class ProductModifyLog extends ShopModel
{
    /**
     * @param $ProductID
     * @param $sAction
     * @param $sContent
     */
    public static function saveLog($ProductID, $sAction, $sContent)
    {
        $log = new static();
        $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $log->NewUserID = \Yii::$app->backendsession->SysUserID;
        $log->ProductID = $ProductID;
        $log->sAction = $sAction;
        $log->sContent = $sContent;
        $log->save();

        return true;
    }
}