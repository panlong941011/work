<?php

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;
use myerm\common\libs\File;
use myerm\common\libs\NewID;
use myerm\common\models\ExpressCompany;


/**
 * 退款售后
 */
class Refund extends \myerm\shop\common\models\Refund
{
    const STATUS_SUCCESS = 'success';//

    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    /**
     * 通过
     * @return mixed
     */
    public function getByOrderDetailID($id)
    {
        return static::findOne(['OrderDetailID' => $id]);
    }

    /**
     * 修改申请
     * @param $data
     */
    public function modifyRefund($data)
    {
        $refund = static::findOne($data['lID']);
        $refund->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $refund->TypeID = $data['TypeID'];
        $refund->sReason = $data['sReason'];
        $refund->sExplain = $data['sExplain'];
        $refund->fRefundApply = $data['fRefundApply'];
        $refund->StatusID = 'wait';
        $refund->lRefundItem = $data['lRefundItem'];
        $refund->lItemTotal = $data['lItemTotal'];
        $refund->fRefundProduct = $data['fRefundProduct'];
        $refund->lAftersaleUserID = '';

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
                '退款金额' => '¥' . number_format($data['fRefundApply'], 2,'.',''),
                '退款类型' => $data['TypeID'] == 'onlymoney' ? "仅退款" : "退款退货",
                '退款原因' => $data['sReason'],
                '退款说明' => $data['sExplain'],
                '退款凭证' => $arrImg ? $arrImg : "--"
            ])
        ]);

        $event = new CommonEvent();
        $event->extraData = $refund;
        \Yii::$app->refund->trigger(static::EVENT_MODIFY_APPLY, $event);

        return true;
    }

    /**
     * 保存退款记录
     */
    public function saveRefund($data)
    {
        $refund = new static();
        $refund->sName = "R" . $data['OrderID'] . str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time()));
        $refund->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $refund->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $refund->MemberID = $data['MemberID']; //下单付款人
        $refund->SupplierID = $data['SupplierID'];
        $refund->StatusID = "wait";//等待供应商审核
        $refund->TypeID = $data['TypeID'];
        $refund->OrderID = $data['OrderID'];
        $refund->OrderDetailID = $data['OrderDetailID'];
        $refund->sOrderName = $data['sName'];
        $refund->sResource = 'dkxh';
        $refund->sReason = $data['sReason'];
        $refund->sExplain = $data['sExplain'];

        $refund->fRefundApply = $data['fRefundApply'];//申请退款金额
        $refund->fPaid = $data['fPaid'];//订单付款金额
        $refund->lRefundItem = $data['lRefundItem'];//退款数量
        $refund->lItemTotal = $data['lItemTotal'];//购买商品总数

        //供应商收入$fMoney =供应商商品供应总价  + 运费 - 退款扣除供应商部分
        $product = Product::findOne($data['ProductID']);
        if ($data['fRefundReal']) {
            $refund->fRefundReal = $data['fRefundReal'];
            $refund->fSupplierRefund = $product->fSupplierPrice * $data['lQuantity'] + $data['fShip'];
        } else {
            $refund->fSupplierRefund = number_format($refund->lRefundItem / $refund->lItemTotal * ($product->fSupplierPrice * $data['lQuantity'] + $data['fShip']), 2,'.','');
            $refund->fRefundReal = number_format($refund->lRefundItem / $refund->lItemTotal * $refund->fPaid, 2,'.','');
            $refund->fSupplierRefund = $product->fSupplierPrice * $data['lQuantity'] + $data['fShip'];
        }
        if ($data['fRefundProduct']) {
            $refund->fRefundProduct = $data['fRefundProduct'];
        } else {
            $refund->fRefundProduct = number_format($refund->lRefundItem / $refund->lItemTotal * ($refund->fPaid - $data['fShip']), 2,'.','');
        }


        $arrImg = [];
        if ($data['imgList']) {
            foreach ($data['imgList'] as $img) {
                $arrFileInfo = File::parseImageFromBase64($img);
                $sFileName = NewID::make() . "." . $arrFileInfo[0];
                $arrImg[] = 'https://yl.aiyolian.cn/'.File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
            }
        }

        $refund->sRefundVoucher = json_encode($arrImg);
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '买家',
            'StatusID' => 'apply',
            'sMessage' => json_encode([
                '退款金额' => '¥' . number_format($data['fRefundApply'], 2,'.',''),
                '退款类型' => $data['TypeID'] == 'onlymoney' ? "仅退款" : "退款退货",
                '退款原因' => $data['sReason'],
                '退款说明' => $data['sExplain']
            ])
        ]);
        return ['RefundID' => $refund->lID, 'imglist' => $arrImg];
    }


    /**
     * 计算这单最多能退的金额
     */
    public function computeMostRefundMoney($orderDetail)
    {
        $fRefundMoney = $orderDetail->fTotal;

        $arr = static::find()->select('OrderDetailID')->where([
            'OrderID' => $orderDetail->OrderID,
            'ShipTemplateID' => $orderDetail->ShipTemplateID,
        ])->andWhere("(IFNULL(StatusID, '')<>'success' AND IFNULL(StatusID, '')<>'closed')")->indexBy('OrderDetailID')->all();
        $arrOrderDetailID = array_keys($arr);


        $lCount = OrderDetail::find()->where(
            [
                'OrderID' => $orderDetail->OrderID,
                'ShipTemplateID' => $orderDetail->ShipTemplateID,
            ]
        )->andWhere(['not in', 'lID', $arrOrderDetailID])->count();

        if ($lCount > 1) {
            return $fRefundMoney;
        } else {
            return $fRefundMoney + $orderDetail->fShip;
        }
    }

    public function listData($config)
    {
        $lPage = $config['lPage'];

        $ret = [];
        $ret[] = static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])
            ->orderBy("dEditDate DESC")
            ->offset(($lPage - 1) * 10)
            ->limit(10)->with('order')->with('orderDetail')->all();

        $ret[] = static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])->count();

        return $ret;
    }

    public function getLRefundCount()
    {
        return static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])->andWhere([
            'not in',
            'StatusID',
            ['closed', 'success']
        ])->count();
    }

    /**
     * 填写退货信息
     */
    public function inputRefundShip($data)
    {
        $refund = static::findOne($data['id']);

        $refund->dShipDate = \Yii::$app->formatter->asDatetime(time());
        $refund->ShipCompanyID = $data['ShipCompanyID'];
        $refund->sShipNo = $data['sShipNo'];
        $refund->sMobile = $data['sMobile'];
        $refund->StatusID = "returned";

        $arrImg = [];
        if ($data['imgList']) {
            foreach ($data['imgList'] as $img) {
                $arrFileInfo = File::parseImageFromBase64($img);
                $sFileName = NewID::make() . "." . $arrFileInfo[0];
                $arrImg[] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
            }
        }

        $refund->sShipVoucher = json_encode($arrImg);
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '买家',
            'StatusID' => 'returned',
            'sMessage' => json_encode([
                '物流公司' => ExpressCompany::findOne($data['ShipCompanyID'])->sName,
                '快递单号' => $data['sShipNo'],
                '联系电话' => $data['sMobile'],
                '快递凭证' => $arrImg ? $arrImg : "--"
            ])
        ]);
    }


    /**
     * 修改退货信息
     */
    public function modifyRefundShip($data)
    {
        $refund = static::findOne($data['id']);

        $refund->dShipDate = \Yii::$app->formatter->asDatetime(time());
        $refund->ShipCompanyID = $data['ShipCompanyID'];
        $refund->sShipNo = $data['sShipNo'];
        $refund->sMobile = $data['sMobile'];
        $refund->StatusID = "returned";
        $refund->lAftersaleUserID = '';

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

        $refund->sShipVoucher = json_encode($arrImg);
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '买家',
            'StatusID' => 'modifyship',
            'sMessage' => json_encode([
                '物流公司' => ExpressCompany::findOne($data['ShipCompanyID'])->sName,
                '快递单号' => $data['sShipNo'],
                '联系电话' => $data['sMobile'],
                '快递凭证' => $arrImg ? $arrImg : "--"
            ])
        ]);
    }

    /**
     * 获取退款协商记录
     */
    public function getRefundLog($id)
    {
        return RefundLog::find()->where(['RefundID' => $id])->orderBy("dNewDate DESC")->all();
    }

    /**
     * 撤销申请
     */
    public function cancelApply()
    {
        $this->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $this->StatusID = 'closed';
        $this->save();
        RefundLog::saveLog([
            'RefundID' => $this->lID,
            'sWhoDo' => '买家',
            'StatusID' => 'closed',
            'sMessage' => json_encode([
                '关闭原因' => '买家撤销申请'
            ])
        ]);
    }

    //订单
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['lID' => 'OrderID']);
    }
}