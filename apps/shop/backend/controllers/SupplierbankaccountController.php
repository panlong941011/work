<?php

namespace myerm\shop\backend\controllers;



/**
 * 供应商提现账户
 */
class SupplierbankaccountController extends BackendController
{
    public function beforeObjectNewSave($sysObject, $arrObjectData)
    {
        //获取供应商ID
        $SysUserID = \Yii::$app->backendsession->SysUserID;
        $supplier = \Yii::$app->supplier->findBySysUserID($SysUserID);
        $arrObjectData['SupplierID'] = $supplier['lID'];
        //判断是否设置为缺省
        //若设置，则修改之前的缺省数据
        //若未设置，则直接保存
        if($arrObjectData['bDefault']=="1"){
            \Yii::$app->supplierbankaccount->updateDefault($supplier['lID']);
        }
        return parent::beforeObjectNewSave($sysObject, $arrObjectData);
    }
    
}
