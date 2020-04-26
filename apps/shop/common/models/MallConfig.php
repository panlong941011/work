<?php

namespace myerm\shop\common\models;

use yii\helpers\ArrayHelper;

/**
 * 商城配置类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年9月19日 23:18:45
 * @version v1.0
 */
class MallConfig extends ShopModel
{
    /**
     * 所有的配置的值
     */
    private static $arrConfig;
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }
    /**
     * 通过指定键值获取值
     * @param $sKey
     * @return mixed
     */
    public static function getValueByKey($sKey)
    {
        if (!static::$arrConfig) {
            static::getAllConfig();
        }

        return static::$arrConfig[$sKey];
    }

    /**
     * 获取所有的配置
     * @return mixed
     */
    public static function getAllConfig()
    {
        static::$arrConfig = \Yii::$app->cache->get("mallconfig");
        if (static::$arrConfig) {
            return static::$arrConfig;
        }

        static::$arrConfig = ArrayHelper::map(static::find()->asArray()->all(), 'sKey', 'sValue');
        \Yii::$app->cache->set("mallconfig", static::$arrConfig);

        return static::$arrConfig;
    }

    /**
     * 设置配置的值
     * @param $sKey
     * @param $sValue
     */
    public static function setValue($sKey, $sValue)
    {
        static::updateAll(['sValue' => $sValue], ['sKey' => $sKey]);
        return true;
    }

    /**
     * 更新缓存
     */
    public static function updateCache()
    {
        return \Yii::$app->cache->delete("mallconfig");
    }
}