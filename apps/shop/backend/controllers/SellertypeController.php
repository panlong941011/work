<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\shop\common\models\Seller;


/**
 * 经销商类别
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年12月12日 11:11:28
 * @version v1.0
 */
class SellertypeController extends ObjectController
{
    public function beforeDel($arrData)
    {
        foreach ($arrData as $data) {
            if (Seller::find()->where(['TypeID' => $data['lID']])->count()) {
                throw new \yii\base\UserException('已有用户属于该经销商类型，不得删除');
            }
        }
    }
}