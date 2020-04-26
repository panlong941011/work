<?php

namespace console\controllers;

use myerm\common\libs\NewID;
use myerm\shop\common\models\OrderBatchPay;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionGet($param)
    {
        echo json_encode($_SERVER);
    }


    public function actionDo()
    {
        $arrProduct = \Yii::$app->db->createCommand("SELECT * FROM Product WHERE bDel='0' AND bSale='1' AND lStock>'0' ORDER BY RAND() LIMIT 3")->queryAll();

        foreach ($arrProduct as $product) {
            if (\Yii::$app->db->createCommand("SELECT COUNT(*) FROM ProductSKU WHERE ProductID='".$product['lID']."'")->queryScalar()) {
                $sku = \Yii::$app->db->createCommand("SELECT sValue FROM ProductSKU WHERE ProductID='".$product['lID']."' AND lStock>0 ORDER BY RAND() LIMIT 1")->queryScalar();
                \Yii::$app->ordercheckout->add([
                    'ProductID' => $product['lID'],
                    'lQty' => 1,
                    'sSKU' => $sku,
                    'dNewDate' => \Yii::$app->formatter->asDatetime(time())
                ]);
            } else {
                \Yii::$app->ordercheckout->add([
                    'ProductID' => $product['lID'],
                    'lQty' => 1,
                    'dNewDate' => \Yii::$app->formatter->asDatetime(time())
                ]);
            }
        }


        $arrPostData = [
            'AddressID' => \Yii::$app->db->createCommand("SELECT lID FROM MemberAddress ORDER BY RAND() LIMIT 1")->queryScalar(),
            'arrMessage' => null
        ];

        $return = \Yii::$app->order->saveOrder($arrPostData);
        if ($return['status']) {

            $sTradeNo = NewID::make();//批量付款的商户订单号

            //保存批量付款的信息
            OrderBatchPay::batchPaySave($sTradeNo, $return['orderids']);

            $return['sTradeNo'] = $sTradeNo;

           // echo json_encode($return);
        }

       // echo json_encode($return);
    }

}