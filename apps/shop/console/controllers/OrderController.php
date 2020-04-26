<?php

namespace console\controllers;

use myerm\shop\mobile\models\CloudOrder;
use myerm\shop\mobile\models\Order;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

/**
 * 订单后台处理
 */
class OrderController extends Controller
{

    /**
     * 前台付款订单，付款后 同步生成 后台已确认订单
     */
    public function actionAsyncpayorder()
    {
        //同步半个小时前的订单
        $arrOrder = Order::find()->where(['and',
            ['<', 'dPayDate', \Yii::$app->formatter->asDatetime(time())],
            ['StatusID' => 'paid'],
            ['bChecked' => 0]
        ])->all();
        foreach ($arrOrder as $order) {
            echo $order->sName;
            CloudOrder::saveOrder($order);
            $order->bChecked = 1;
            $order->save();
        }
    }
    /*
     * 订单物流同步
     */
    public function actionAsyncexpress()
    {
        //同步半个小时前的订单
        $arrOrder = Order::find()
            ->where(['and',
                ['<', 'dPayDate', \Yii::$app->formatter->asDatetime(time() - 5000)],
                ['StatusID' => 'paid'],
                ['bChecked' => 1]
            ])->with('detail')
            ->all();
        $arrOrderName = array_column($arrOrder, 'sName');
        $arrOrder =  ArrayHelper::index($arrOrder, 'sName');

        //查询云端订单
        $arrCloudOrder = CloudOrder::find()
            ->with('detail')
            ->where(['and',['sClientSN' => $arrOrderName],['<>','StatusID','paid']])
            ->all();
        foreach ($arrCloudOrder as $cloudOrder){
            $updateOrder=$arrOrder[$cloudOrder->sClientSN];
            $updateOrder->StatusID=$cloudOrder->StatusID;
            $updateOrder->RefundStatusID=$cloudOrder->RefundStatusID;
            if($cloudOrder->StatusID=='delivered'){
                $updateOrder->bAllShiped=1;
                $updateOrder->dShipDate = $cloudOrder->dShipDate;
            }
            $updateOrder->save();
            $updateOrderDetail=$arrOrder[$cloudOrder->sClientSN]->orderdetail;
            $orderDetail=$cloudOrder->detail;
            $updateOrderDetail->sShipNo=$orderDetail->sShipNo;
            $updateOrderDetail->ShipCompanyID=$orderDetail->ShipCompanyID;
            $updateOrderDetail->dShipDate=$orderDetail->dShipDate;
            $updateOrderDetail->save();
        }
    }

}