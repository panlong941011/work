<?php

namespace myerm\shop\backend\controllers;


use myerm\backend\common\controllers\ObjectController;
use myerm\shop\common\models\ProductBrand;


/**
 * 品牌
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月25日 15:04:07
 * @version v1.0
 */
class ProductbrandController extends ObjectController
{
    public function getNewFooterAppend()
    {
        $data = [];
        return $this->renderPartial('newfooter', $data);
    }

    public function beforeDel($arrData)
    {
        foreach ($arrData as $data) {
            if (ProductBrand::findByID($data[$this->sysObject->sIDField])->lProductNum > 0) {
                throw new \yii\base\UserException('已有商品使用了该品牌，不可删除。');
            }
        }
    }
}