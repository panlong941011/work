<?php

namespace myerm\shop\common\components;

use myerm\shop\backend\models\Order;
use myerm\shop\backend\models\Refund;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\PreOrder;
use myerm\shop\common\models\ProductStockChange;
use yii\base\Component;

/**
 *  前后台串联接口
 */
class DnyApi extends Component
{
    public $url;

    public $appid;

    public $appsec;

    /**
     * 查询库存
     * @param $id
     * @param null $sSku
     * @return mixed
     */
    public function stock($id, $sSku = null)
    {

    }

    /**
     * 请求api
     * @param $sUrl
     * @param $arrPostData
     * @return mixed
     * @throws \Exception
     */
    public function request($sUrl, $arrPostData = [])
    {

    }


    /**
     *  查询商品SKU的库存
     * @author Mars
     * @time 2018年6月22日 13:56:15
     */
    public function skuStock($id)
    {

    }

    public function get($path)
    {

    }

    public function command($command, $param = [])
    {

    }

    /**
     * 同步商品
     * @param $idlist
     * @return mixed
     * @throws \Exception
     */
    public function syncProduct($ProductID)
    {
        return $this->request("v1/product/detail?pID=" . $ProductID);
    }

    /**
     * 单件商品计算运费
     * @param $ProductID
     * @param $sProvince
     * @param $sCity
     * @param int $lNum
     * @return mixed
     * @throws \Exception
     */
    public function shipCount($ProductID, $sProvince, $sCity, $lNum = 1)
    {

    }

    /**
     * 订单计算运费
     * @return mixed
     * @throws \Exception
     */
    public function orderShipCount($arrSku, $sProvince, $sCity)
    {

    }

    /**
     * 提交订单
     */
    public function orderSubmit($arrPostData)
    {

    }

    /**
     * 订单确认扣款
     */
    public function orderConfirmPay($sOrderCode)
    {

    }

    /**
     * 修改退货信息
     */
    public function modifyReturnship($sUrl, $arrPostData)
    {

    }


    /**
     * 修改地址
     * @param $sUrl
     * @param $arrPostData
     * @return mixed
     * @throws \Exception
     */
    public function modifyAddress($sUrl, $arrPostData)
    {

    }

    /**
     * 退款申请
     * @param $arrPostData
     */
    public function refundApply($sUrl, $arrPostData)
    {

    }

    /**
     * 消费者发起退款
     * @return mixed
     * @throws \Exception
     */
    public function setRefund($sUrl, $arrPostData)
    {

    }

    /**
     * 消费者取消退款退货
     * @return mixed
     * @throws \Exception
     */
    public function cancelRefund($sUrl, $arrPostData)
    {

    }

    /**
     * 提交退货信息
     * @return mixed
     * @throws \Exception
     */
    public function returnShip($sUrl, $arrPostData)
    {

    }

    /**
     * 订单确认收货
     */
    public function confirmReceive($sOrderCode)
    {

    }

    /**
     * 订单状态
     */
    public function orderStatus($sOrderCode)
    {

    }

    /**
     * 订单状态
     */
    public function getConfig()
    {


    }

    /**
     * 用户登录
     */
    public function login()
    {

    }

    /**
     * 同步促销活动
     * @param $slID
     * @return mixed
     * @throws \Exception
     * @author hechengcheng
     * @time 2019/3/15 10:42
     */
    public function salesPromotion($slID)
    {

    }

    /**
     * 生成订单号，长格式的时间+随机码
     */
    public static function makeOrderCode()
    {
        return date('YmdHis') . rand(10000, 99999);
    }

    /**
     * 提交订单
     */
    public function wholesaleOrderSubmit($arrPostData)
    {
        //生成预订单
        $preOrder = new PreOrder();
        $preOrder->sName = self::makeOrderCode();
        $preOrder->sOrderNo = $arrPostData['sNo'];
        $preOrder->dNewDate = date('Y-m-d, H:i:s');
        $preOrder->sReceiverName = $arrPostData['name'];
        $preOrder->sProvince = $arrPostData['province'];
        $preOrder->sCity = $arrPostData['city'];
        $preOrder->sArea = $arrPostData['area'];
        $preOrder->sAddress = $arrPostData['address'];
        $preOrder->sMobile = $arrPostData['phone'];
        $preOrder->BuyerID = $arrPostData['buyerID'];
        $preOrder->WholesalerID = $arrPostData['WholesalerID'];
        $preOrder->sMessage = $arrPostData['message'];
        $preOrder->fTotal = $arrPostData['fTotal'];
        $preOrder->fShip = $arrPostData['fShip'];
        $preOrder->ProvinceID = $arrPostData['ProvinceID'];
        $preOrder->CityID = $arrPostData['CityID'];
        $preOrder->AreaID = $arrPostData['AreaID'];
        $preOrder->fWholesale = $arrPostData['fWholesale'];
        $preOrder->OrderType = $arrPostData['OrderType'];
        if ($arrPostData['OrderType']) {
            $preOrder->bClosed = -1;
        }
        $preOrder->save();
        //预订单详情
        foreach ($arrPostData['arrProduct'] as $product) {
            $productStockChange = new ProductStockChange();
            $productStockChange->sName = $preOrder->sName;
            $productStockChange->dNewDate = \Yii::$app->formatter->asDatetime(time());
            $productStockChange->dEditDate = $productStockChange->dNewDate;
            $productStockChange->BuyerID = $preOrder->BuyerID;
            $productStockChange->OrderID = $preOrder->lID;
            $productStockChange->ProductID = $product['ProductID'];
            $productStockChange->lChange = $product['lQuantity'];
            $productStockChange->save();
        }
    }

    /**
     * 取消订单
     * @param $arrPostData
     * @return mixed
     * @throws \Exception
     * @author hechengcheng
     * @time 2019/5/14 9:00
     */
    public function cancelOrder($arrPostData)
    {

    }

    /**
     * 渠道订单计算运费
     * @return mixed
     * @throws \Exception
     */
    public function wholesaleOrderShipCount($arrSku, $sProvince, $sCity)
    {

    }
}