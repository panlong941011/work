<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;

/**
 * 物流跟踪查询
 */
class BackendController extends ObjectController
{
    //供应商属性，如果是供应商登录，就赋值这个属性
    protected $supplier = null;

    //渠道商属性，如果是渠道商登录，就赋值这个属性 by hcc on 2018/5/16
    protected $buyer = null;

    //渠道供应商属性
    protected $wholesalerSupplier = null;
    //渠道商ID
    protected $BuyerID = -1;
    //供应商ID
    protected $SupplierID = null;
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\Yii::$app->backendsession->SysRoleID == 3) {
                //供应商
                $this->supplier = \Yii::$app->supplier->findBySysUserID(\Yii::$app->backendsession->SysUserID);
                $this->SupplierID=$this->supplier->lID;
            } elseif (\Yii::$app->backendsession->SysRoleID == 4||\Yii::$app->backendsession->SysRoleID == 9) {
                //渠道商,渠道商
                $this->buyer = \Yii::$app->buyer->findOne(['SysUserID'=>\Yii::$app->backendsession->SysUserID]);
                $this->BuyerID = $this->buyer->lID;
            } elseif (\Yii::$app->backendsession->SysRoleID == 10) {
                //渠道供应商
                $this->wholesalerSupplier = \Yii::$app->supplier->findBySysUserID(\Yii::$app->backendsession->SysUserID);
                $this->supplier = $this->wholesalerSupplier;
                $this->SupplierID=$this->supplier->lID;
                $this->BuyerID = $this->wholesalerSupplier->BuyerID;
            } elseif (\Yii::$app->backendsession->SysRoleID == 11) {
                //试用渠道商
                $this->buyer = \Yii::$app->buyer->findBySysUserID(\Yii::$app->backendsession->SysUserID);
                $this->BuyerID = $this->buyer->lID;
            } elseif (\Yii::$app->backendsession->SysRoleID == 12) {
                //供应商&试用渠道商
                $this->wholesalerSupplier = \Yii::$app->supplier->findBySysUserID(\Yii::$app->backendsession->SysUserID);
                $this->supplier = $this->wholesalerSupplier;
                $this->SupplierID=$this->supplier->lID;
                $this->BuyerID = $this->wholesalerSupplier->BuyerID;
            }
            return true;
        }


        return true;
    }
}