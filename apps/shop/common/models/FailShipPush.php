<?php

namespace myerm\shop\common\models;


/**
 * 发货推送失败记录类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author panlong
 * @time 2018-05-31
 * @version v1.0
 */
class FailShipPush extends ShopModel
{
    const API_SUCCESS = 100;

    /**
     * 保存推送失败的物流信息
     * @param array $arrData 物流信息
     * @author panlong
     * @time 2018-05-31
     */
    public function saveFailship($arrData)
    {
        $failShipPush = new static();
        $failShipPush->sName = $arrData['sName'];
        $failShipPush->OrderID = $arrData['OrderID'];
        $failShipPush->OrderDetailID = $arrData['OrderDetailID'];
        $failShipPush->sShipNo = $arrData['sShipNo'];
        $failShipPush->ShipCompanyID = $arrData['ShipCompanyID'];
        $failShipPush->BuyerID = $arrData['BuyerID'];
        $failShipPush->sCloudShipApiUrl = $arrData['sCloudShipApiUrl'];
        $failShipPush->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $failShipPush->sProductInfo = $arrData['sProductInfo'];
        $failShipPush->lNum = $arrData['lLogisticsNo'];
        $failShipPush->sFailReason = $arrData['sFailReason'];
        $failShipPush->lChildID = $arrData['lChildID'];
        $failShipPush->OrderLogisticsID = $arrData['OrderLogisticsID'];
        $failShipPush->save();
    }

    /**
     * 查找所有失败ID
     * @return array|\yii\db\ActiveRecord[]
     * @author hechengcheng
     * @time 2018年7月25日11:31:30
     */
    public static function findAllID()
    {
        return static::find()->select('lID')->all();
    }

    /**
     * 自动推送发货信息
     * @author hechengcheng
     * @time 2018年7月25日16:06:05
     */
    public function autoShipPush()
    {
        //查询所有失败ID
        $arrFailID = static::findAllID();
        foreach ($arrFailID as $ID) {
            $failShipPush = static::findOne($ID);
            $buyer = Buyer::findByID($failShipPush->BuyerID);

            $arrData = [];
            $arrData['ShipID'] = 'wuliu';
            $arrData['sExpressCompany'] = $failShipPush->ShipCompanyID;
            $arrData['sExpressNo'] = $failShipPush->sShipNo;
            $arrData['lID'] = $failShipPush->lChildID;
            $arrData['sName'] = $failShipPush->sName;
            $arrData['productInfo'] = json_decode($failShipPush->sProductInfo);
            $arrData['num'] = $failShipPush->lNum;
            $arrShipPushResult = $this->cloudOrderLogistics($buyer->sDeliveryOrderLogistics, $arrData);
            $arrShipPushResult = json_decode($arrShipPushResult, true);

            if ($arrShipPushResult['code'] === static::API_SUCCESS) {
                if ($arrShipPushResult['lID']) {
                    //记录子平台订单物流ID
                    OrderLogistics::saveChildID($failShipPush->OrderLogisticsID, $arrShipPushResult['lID']);

                    $arrFailShipPush = FailShipPush::findAll(['OrderLogisticsID'=>$failShipPush->OrderLogisticsID]);
                    foreach ($arrFailShipPush as $fail) {
                        $fail->lChildID = $arrShipPushResult['lID'];
                        $fail->save();
                    }
                }
                $failShipPush->delete();
            }
        }
    }

    /**
     * 云平台发货推送给子平台
     * by:pl
     * time:18.05.10
     */
    public function cloudOrderLogistics($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}