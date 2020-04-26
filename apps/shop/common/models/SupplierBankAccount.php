<?php
namespace myerm\shop\common\models;


/**
 * 供应商类提现账户类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年9月25日 11:25:19
 * @version v1.0
 */
class SupplierBankAccount extends ShopModel
{
    //修改供应商设置的缺省数据
    public function updateDefault($SupplierID)
    {
        $SupplierBankAccount = static::findone([
            'SupplierID' => $SupplierID,
            'bDefault' => 1
        ]);
        if($SupplierBankAccount){
            $SupplierBankAccount -> bDefault=0;
            $SupplierBankAccount -> save();
        }
        return true;
    }

    //通过供应商ID获取供应商设置的提现账户
    public function findBankAccountByID($SupplierID)
    {
        return self::find()->where(['SupplierID'=>$SupplierID])->all();
    }
}