<?php

namespace myerm\shop\backend\models;

use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;

/**
 * 订单退款类
 */
class Refund extends \myerm\shop\common\models\Refund
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

//apply=待提交
//wait=供应商待确认
//success=退款成功
//closed=退款关闭
//appeal='申诉中'

    /**
     * 修改申请
     * @param $data
     */
    public function modifyRefund($data)
    {
        $refund = static::findOne($data['lID']);
        $refund->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $refund->TypeID = $data['TypeID'];//退款类型
        $refund->sReason = $data['sReason'];//退款原因
        $refund->sExplain = $data['sExplain'];//退款原因
        $refund->fRefundApply = $data['fRefundApply'];//申请退款金额
        $refund->StatusID = 'wait';//供应商待确认
        $refund->lRefundItem = $data['lRefundItem'];//退款商品数量
        $refund->lItemTotal = $data['lItemTotal'];//退款商品总数
        $refund->BuyerID = $data['BuyerID'];//退款申请人

        $arrImg = [];
        if ($data['imgList']) {
            foreach ($data['imgList'] as $img) {
                if (substr($img, 0, 5) == 'data:') {
                    $arrFileInfo = File::parseImageFromBase64($img);
                    $sFileName = NewID::make() . "." . $arrFileInfo[0];
                    $arrImg[] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                } else {
                    $arrImg[] = str_ireplace(\Yii::$app->request->imgUrl . "/", "", $img);;
                }
            }
        }

        $refund->sRefundVoucher = json_encode($arrImg);
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '买家',
            'StatusID' => 'modifyapply',
            'sMessage' => json_encode([
                '退款金额' => '¥' . number_format($data['fRefundApply'], 2),
                '退款类型' => $data['TypeID'] == 'onlymoney' ? "仅退款" : "退款退货",
                '退款原因' => $data['sReason'],
                '退款说明' => $data['sExplain'],
                '退款凭证' => $arrImg ? $arrImg : "--"
            ])
        ]);


        return true;
    }

    /**
     * 保存退款记录
     */
    public static function saveRefund($data)
    {

        $refund = new static();

        $refund->sName = "R" . $data['OrderID'] . str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time()));
        $refund->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $refund->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $refund->SupplierID = $data['SupplierID'];
        $refund->StatusID = "wait";//等待卖家确认退款申请
        $refund->TypeID = $data['TypeID'];
        $refund->OrderID = $data['OrderID'];
        $refund->OrderDetailID = null;
        $refund->ShipTemplateID = null;
        $refund->sReason = $data['sReason'];
        $refund->sExplain = $data['sExplain'];
        $refund->fRefundApply = $data['fRefundApply'];
        $refund->fPaid = $data['fBuyerPaid'];
        $refund->lRefundItem = $data['lRefundItem'];
        $refund->lItemTotal = $data['lItemTotal'];
        $refund->MemberID = $data['BuyerID'];
        $refund->BuyerID = $data['BuyerID'];
        if ($data['fRefundReal']) {
            $refund->fRefundReal = $data['fRefundReal'];
        }
        if ($data['fRefundProduct']) {
            $refund->fRefundProduct = $data['fRefundProduct'];
        }

        $arrImg = [];
        if ($data['imgList']) {
            foreach ($data['imgList'] as $img) {
                $arrFileInfo = File::parseImageFromBase64($img);
                $sFileName = NewID::make() . "." . $arrFileInfo[0];
                $arrImg[] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
            }
        }

        $refund->sRefundVoucher = json_encode($arrImg);
        $refund->save();
        //增加流水
        $sMessage = json_encode([
            '退款金额' => '¥' . number_format($data['fRefundApply'], 2),
            '退款类型' => $data['TypeID'] == 'onlymoney' ? "仅退款" : "退款退货",
            '退款原因' => $data['sReason'],
            '退款说明' => $data['sExplain'],
            '退款凭证' => $arrImg ? $arrImg : "--"
        ]);

        $log = new Refundlog();
        $log->RefundID = $refund->lID;
        $log->sWhoDo = '渠道商';
        $log->StatusID = 'apply';
        $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $log->sMessage = $sMessage;
        $log->save();

        return ['RefundID' => $refund->lID, 'imglist' => $arrImg];
    }

    public static function test()
    {
        $refundLog = Refundlog::find()->all();
        echo '<pre>';
        var_dump($refundLog);
    }
}