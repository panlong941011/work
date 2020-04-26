<?php

namespace myerm\shop\common\models;


/**
 * 发货地址管理类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 何城城  <lumingchen@qq.com>
 * @time 2018年05月30日 09:40:35
 * @version v1.0
 */
class ShipAddress extends ShopModel
{
	/**
	 * 配置数据源
	 * @return \yii\db\Connection
	 */
	public static function getDb()
	{
		return \Yii::$app->get('ds_cloud');
	}
    /**
     * 查询数据
     * $ID int 主键ID
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
     *  保存发货地址信息
     * @param array $param 数据格式如下：
     *  $param = [
     *     'sName' => 模板名称，
     *     'lID' => 模板ID,       编辑时使用
     *     'SupplierID' => 供应商ID，
     *     'sShipper' => 发货人姓名，
     *     'sMobile' => 发货人手机号，
     *     'sPostCode' => 邮编，
     *     'ProvinceID' => 省份ID，
     *     'CityID' => 城市ID，
     *     'AreaID' => 区/县ID，
     *     'sAddress' => 发货详细地址，
     *     'bDefault' => 是否默认，
     *     'sKdbirdCode' => 快递鸟使用的快递公司编码，
     *     'sExpressName' => 快递账号，
     *     'sExpressPassword' => 快递密码，
     *     'sExpressCode' => 月结编码，
     *     'sExpressKey' => 快递合作密钥，
     *     'sExpressSendSite' => 快递网点名称，
     * ];
     */
    public static function saveValue($param)
    {
        //判断是否传供应商ID过来
        //如果有，则表示该条记录为管理员新建，将供应商ID保存
        //如果没有，则表示该条记录为供应商新建，通过登录session取出供应商ID保存
        if ($param['arrObjectData']['Shop/ShipAddress']['SupplierID']) {
            $supplierID = $param['arrObjectData']['Shop/ShipAddress']['SupplierID'];
        } else {
            //获取供应商ID
            $SysUserID = \Yii::$app->backendsession->SysUserID;
            $supplier = \Yii::$app->supplier->findBySysUserID($SysUserID);
            $supplierID = $supplier['lID'];
        }

        //判断该条记录是否设置为默认
        //如果设置为默认，则将该供应商原有的默认发货地址改为不默认
        if ($param['bDefault'] == 1) {
            $oldShipAddress = static::findOne([
                'SupplierID' => $supplierID,
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
        $shipAddress->SupplierID = $supplierID;
        $shipAddress->sShipper = $param['sShipper'];
        $shipAddress->sMobile = $param['sMobile'];
        $shipAddress->sPostCode = $param['sPostCode'];
        $shipAddress->ProvinceID = $param['ProvinceID'];
        $shipAddress->CityID = $param['CityID'];
        $shipAddress->AreaID = $param['AreaID'];
        $shipAddress->sAddress = $param['sAddress'];
        $shipAddress->bDefault = $param['bDefault'];
        $shipAddress->ClearingWayID = $param['ClearingWayID'];
        $shipAddress->sKdbirdCode = $param['sKdbirdCode'];
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

}