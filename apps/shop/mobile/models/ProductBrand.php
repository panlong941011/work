<?php

namespace myerm\shop\mobile\models;


/**
 * 商品品牌
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年10月9日 22:23:09
 * @version v1.0
 */
class ProductBrand extends \myerm\shop\common\models\ProductBrand
{
    /**
     * 获取所有可以在前台显示的数组
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getShowBrands()
    {
        return static::find()->select(['lID', 'sName'])->where(['bShow' => 1])->asArray()->all();
    }
}