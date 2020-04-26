<?php

namespace myerm\shop\common\models;

use myerm\common\models\ExpressCompany;
/**
 * 发货地址管理类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 何城城
 * @time 2018年05月30日 09:40:35
 * @version v1.0
 */
class KDShipAddress extends ShopModel
{
    /**
     * 查询数据
     * @return array
     * @param int $ID 发货地址ID
     * @author hcc
     * @time 2018-6-1 10:40:12
     * */
    public static function getShipAddressInfo($ID)
    {
        $shipAddressInfo = static::find()
            ->where(['lID' => $ID])
            ->asArray()
            ->one();

        return $shipAddressInfo;
    }

    /**
     * 查询数据
     * @return array
     * @param int $ID 发货地址ID
     * @author cgq
     * @time 2018-07-02
     * */
    public static function getSupplierShipAddress($ID)
    {
        $shipAddressInfo = static::find()
            ->where(['SupplierID' => $ID])
            ->asArray()
            ->all();

        return $shipAddressInfo;
    }

    /**
     * 保存发货地址信息
     * @param $param
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @author hechengcheng
     * @time 2018-6-1 10:42:30
     */
    public static function saveValue($param)
    {
        //获取供应商ID
        $SysUserID = \Yii::$app->backendsession->SysUserID;
        $supplier = \Yii::$app->supplier->findBySysUserID($SysUserID);
        $SupplierID = $supplier['lID'];

        //判断该条记录是否设置为默认
        //如果设置为默认，则将该供应商原有的默认发货地址改为不默认
        if ($param['bDefault'] == 1) {
            $oldShipAddress = static::findOne([
                'SupplierID' => $SupplierID,
                'bDefault' => 1,
            ]);
            if ($oldShipAddress) {
                $oldShipAddress->bDefault = 0;
                $oldShipAddress->save();
            }
        }

        //判断lID是否存在
        //若存在，则为保存编辑信息；若不存在，则为保存新建信息
        if ($param['lID']) {
            $shipAddress = static::findOne(['lID' => $param['lID']]);
        } else {
            $shipAddress = new static();
            $shipAddress->dNewDate = \Yii::$app->formatter->asDatetime(time());
            $shipAddress->NewUserID = \Yii::$app->backendsession->SysUserID;
        }

        $shipAddress->sName = $param['sName'];
        $shipAddress->SupplierID = $SupplierID;
        $shipAddress->sShipper = $param['sShipper'];
        $shipAddress->sMobile = $param['sMobile'];
        $shipAddress->sPostCode = $param['sPostCode'];
        $shipAddress->ProvinceID = $param['ProvinceID'];
        $shipAddress->CityID = $param['CityID'];
        $shipAddress->AreaID = $param['AreaID'];
        $shipAddress->sAddress = $param['sAddress'];
        $shipAddress->bDefault = $param['bDefault'];
        $shipAddress->SettlementMethodID = $param['SettlementMethodID'];
        $shipAddress->sKdBirdCode = $param['sKdbirdCode'];
        $shipAddress->sExpressCompany = ExpressCompany::findOne(['sKdBirdCode'=>$param['sKdbirdCode']])->sName;
        $shipAddress->sExpressName = $param['sExpressName'];
        $shipAddress->sExpressPassword = $param['sExpressPassword'];
        $shipAddress->sExpressCode = $param['sExpressCode'];
        $shipAddress->sExpressKey = $param['sExpressKey'];
        $shipAddress->sExpressSendSite = $param['sExpressSendSite'];
        $shipAddress->ExpressBusinessID = $param['ExpressBusinessID'];
        $shipAddress->EditUserID = \Yii::$app->backendsession->SysUserID;
        $shipAddress->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $shipAddress->save();

        return $shipAddress->lID;
    }

    /**
     * 保存编辑
     * */
    public static function editSave($param)
    {
        return static::saveValue($param);
    }

    /**
     * 查询数据
     * @return array
     * @param int $ID 发货地址ID
     * @author cgq
     * @time 2018-07-12
     * */
    public static function  getAddersstemplateInfo($ID)
    {
        $shipAddressInfo = static::find()
            ->where(['lID' => $ID])
            ->asArray()
            ->one();

        return $shipAddressInfo;
    }

}