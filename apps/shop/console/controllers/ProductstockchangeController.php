<?php

namespace console\controllers;

use myerm\shop\common\models\PreOrder;
use myerm\shop\common\models\ProductSKU;
use yii\console\Controller;

/**
 * 商品库存变动记录
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author  何城城  <lumingchen@qq.com>
 * @since 2018年5月8日 18:49
 * @version v1.0
 */
class ProductstockchangeController extends Controller
{
    /**
     * 定时任务关闭未付款库存变动记录
     * @author panlong
     * @time 2018-05-15
     */
    public function actionClosechange()
    {
        //获取自动关闭订单设置时间
        $lAutoCloseTime = \Yii::$app->mallconfig->getValueByKey('lOrderAutoCloseTime');

        $arrUnpaid = \Yii::$app->productstockchange->getUnpaidInfo();
        foreach ($arrUnpaid as $unpaid) {
            //如果超出系统设置订单关闭时间
            if (time() - strtotime($unpaid['dNewDate']) >= $lAutoCloseTime * 60 * 60) {
                //关闭商品库存变动记录，加回库存
                //判断是否有规格 by hcc on 2018/6/8
                if ($unpaid['sSKU']) {
                    $productSKU = ProductSKU::findByProductIDAndSKU($unpaid['ProductID'], $unpaid['sSKU']);
                    $productSKU->lStock += $unpaid['lChange'];
                    $productSKU->save();
                }

                $product = \Yii::$app->product->findbyid($unpaid['ProductID']);
                $product->lStock += $unpaid['lChange'];
                $product->save();

                $unpaid->dCloseDate = \Yii::$app->formatter->asDatetime(time());
                $unpaid->save();

                PreOrder::updateAll(
                    [
                        'dCloseDate' => \Yii::$app->formatter->asDatetime(time()),
                        'bClosed' => 1,
                        'sCloseReason' => '超时未扣款'
                    ],
                    ['sName' => $unpaid->sName]
                );
            }
        }
    }
}