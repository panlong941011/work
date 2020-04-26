<?php

namespace myerm\shop\common\models;

use yii\helpers\ArrayHelper;


/**
 * 经销商配置
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年12月25日19:34:38
 * @version v1.0
 */
class SellerConfig extends ShopModel
{
    public static function all()
    {
        return ArrayHelper::map(static::find()->asArray()->all(), 'sKey', 'sValue');
    }

    public static function set($sKey, $sValue)
    {
        static::updateAll(['sValue' => $sValue], ['sKey' => $sKey]);
    }
}