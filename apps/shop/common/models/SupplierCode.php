<?php

namespace myerm\shop\common\models;

/**
 * 订单类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明
 * @time 2017年10月22日 23:07:33
 * @version v1.0
 */
class SupplierCode extends ShopModel
{

    public static $registerTree = [];

    public static function tableName()
    {
        return 'SupplierCode';
    }

    /**
     * 取大农云供应商id和大农云供应商编码对应关系
     */
    public static function getCodeRelation()
    {
        $actionName = \Yii::$app->controller->action->id;
        if (isset(self::$registerTree[$actionName])) {
            return self::$registerTree[$actionName];
        }
        $codeList = self::find()->asArray()->all();
        $relation = [];
        if (!empty($codeList)) {
            foreach ($codeList as $singleCode) {
                $lID            = $singleCode['lID'];
                $sCode          = $singleCode['sCode'];
                $relation[$lID] = $sCode;
            }
        }
        self::$registerTree[$actionName] = $relation;
        return $relation;
    }

    /**
     * 根据供大农云供应商id取来三斤供应商编码
     * 步骤：1.根据大农云供应商id取到来三斤供应商id    2.根据来三斤供应商id取来三斤供应商编码
     */
    public static function getCodeById($id)
    {
        $idRelation   = Supplier::getDnyLsjRelation();
        $lsjId        = isset($idRelation[$id]) ? $idRelation[$id] : 0;
        $codeRelation = self::getCodeRelation();
        $code         = isset($codeRelation[$lsjId]) ? $codeRelation[$lsjId] : '';
        return $code;
    }

}