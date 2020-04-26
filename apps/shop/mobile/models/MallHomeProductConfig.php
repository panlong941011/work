<?php

namespace myerm\shop\mobile\models;


/**
 * 首页商品设置
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年10月4日 18:33:11
 * @version v1.0
 */
class MallHomeProductConfig extends \myerm\shop\common\models\MallHomeProductConfig
{
    /**
     * 获取第$index条的商品配置数据
     * @param $index
     */
    public function getItem($index)
    {
        $arrData = \Yii::$app->cache->getOrSet("MallHomeProductConfig", function () {
            return static::find()->where(['bAcitve' => 1])->orderBy("lPos")->all();
        }, null, new \yii\caching\DbDependency(['sql' => 'SELECT MAX(dEditDate) FROM `MallHomeProductConfig`']));

        return $arrData[$index];
    }
}