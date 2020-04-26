<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\kuaidi100\models\ExpressCompany;
use myerm\shop\backend\controllers\OrderController;
use myerm\shop\common\models\NotifyConfig;
use myerm\shop\common\models\Order;
use myerm\shop\common\models\OrderAddress;
use myerm\shop\common\models\OrderDetail;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\OrderLogistics;
use yii\base\UserException;
use yii;

include "../common/libs/BaseExcel.php";
set_time_limit(0);
ini_set("memory_limit", "8048M");

/**
 * 订单批量发货
 * @author: bean
 * @time: 2017-1-10 11:51:54
 */
class OrderbatchdeliverController extends ObjectController
{
    const API_SUCCESS = 1;
    const API_FAILED = -1;

    /**
     * HOME
     * @author: bean
     * @time: 2017-1-10 11:52:06
     */
    public function actionHome()
    {
        return $this->render('home');
    }

    /**
     * RUN
     * @author: bean
     * @time: 2017-1-10 11:52:11
     */
    public function actionRun()
    {
        $excel = new \BaseExcel();
        $excel->file = $_FILES['file']['tmp_name'];
        Yii::trace($_FILES);
        //@move_uploaded_file($_FILES["file"]["tmp_name"],Yii::getAlias('@webroot/userfile1/upload/'.date('Ymdhis',time()).'-'.Yii::$app->session2->sysuser->lID.'.xls'));
        $arrSheetData = $excel->import();

        $arrMessage = [];
        //存储上传文件  ldz 2017-6-8 17:07:44
        if (file_exists($excel->file)) {
            @move_uploaded_file($_FILES['file']['tmp_name'], "../../shop/console/runtime/" . date('Ymdhis', time()) . "-" . $_FILES['file']['name']);
        }
        Yii::trace($arrSheetData, '$arrSheetData');

        $lDo = 0;
        $arrCurrentOrder = [];
        foreach ($arrSheetData['Worksheet'] as $k => $v) {
            $arr = [];
            if ($k >= 1 && trim($v[0])) {
                //判断是否供应商账号
                if (\Yii::$app->backendsession->SysRoleID == 3) {
                    $arr['ShipNo'] = trim($v[0]);//快递单号
                    $arr['Company'] = trim($v[1]);//快递公司
                    $arr['Sname'] = $v[12];//订单号
                    $arr['GoodName'] = $v[4];//商品名称
                    $arr['sSKU'] = $v[5];//商品规格
                } else {
                    $arr['ShipNo'] = trim($v[0]);//快递单号
                    $arr['Company'] = trim($v[1]);//快递公司
                    $arr['Sname'] = $v[12];//订单号
                    $arr['GoodName'] = $v[4];//商品名称
                    $arr['sSKU'] = $v[5];//商品规格
                }
                $arr['ShipID'] = 'wuliu';
            } else {
                continue;
            }

            $order = \Yii::$app->order->getByNo($arr['Sname']);
            //增加查询条件，商品名字和订单ID并不能决定唯一性，所以要增加sSKU
            if ($arr['sSKU']) {
                $orderdetail = OrderDetail::find()
                    ->where(['and', ['=', 'sName', $arr['GoodName']], ['=', 'OrderID', $order->lID],
                        ['=', 'sSKU', $arr['sSKU']]])
                    ->one();
            } else {
                $orderdetail = OrderDetail::find()
                    ->where(['and', ['=', 'sName', $arr['GoodName']], ['=', 'OrderID', $order->lID],])
                    ->one();
            }


            //判断订单是否发货 ldz 2017-6-6 10:18:29
            if ($order->StatusID == 'delivered' && !in_array($arr['Sname'], $arrCurrentOrder)) {
                $arrMessage[] = $order->sName . "：发货失败（该订单已经发货了）";
                continue;
            }

            if ($order->StatusID == 'unpaid') {
                $arrMessage[] = $order->sName . "：发货失败（该订单未付款）";
                continue;
            }
            //检查订单状态
            if ($order->StatusID == 'success' || $order->StatusID == 'closed') {
                $arrMessage[] = $order->sName . "：发货失败（该订单已关闭）";
                continue;
            }
//            if (!empty($orderdetail->sShipNo) && !empty($orderdetail->ShipCompanyID)) {
//                $arrMessage[] = $order->sName . "：发货失败（该商品已发货）";
//                continue;
//            }
            if ($orderdetail->StatusID == 'success') {
                $arrMessage[] = $order->sName . "：发货失败（该商品已退款）";
                continue;
            }
            if ($orderdetail->StatusID == 'refunding') {
                $arrMessage[] = $order->sName . "：发货失败（该商品正在退款）";
                continue;
            }
            if (empty($arr['ShipNo'])) {
                $arrMessage[] = $order->sName . "：发货失败（快递单号不能为空）";
                continue;
            }
            if (!preg_match('/^[a-z0-9]+$/i', $arr['ShipNo'])) {
                $arrMessage[] = $order->sName . "：发货失败（快递单号格式不正确）" . $arr['ShipNo'];
                continue;
            }
            if (empty($arr['Company'])) {

                $arrMessage[] = $order->sName . "：发货失败（快递公司不能为空）";
                continue;
            }

            /**
             * 检查快递公司是否存在
             * @var ExpressCompany $company
             */
            $company = ExpressCompany::find()
                ->where(['like', 'sName', $arr['Company']])
                ->one();

            if (!$company) {
                $arrMessage[] = $order->sName . "：发货失败（快递公司不存在）";
                continue;
            } else {
                $arr['CompanyID'] = $company->ID;
            }

            $arr['DetailID'] = $orderdetail->lID;

            $result = $this->ship($arr, $orderdetail, $order);
            if (!$result) {
                $arrMessage[] = $order->sName . "：发货失败（系统错误，请联系管理员）";
                continue;
            } else {
                $arrCurrentOrder[] = $arr['Sname'];//记录本次发货的订单编号
                OrderLogistics::deleteAll(['OrderID' => $order->lID, 'sExpressNo' => null]);

                //新增到物流订单表，多加个[0]，为了数据库格式统一
                $orderDetailInfo = [];
                $orderDetailInfo[0]['sName'] = $orderdetail->sName;
                $orderDetailInfo[0]['sSKU'] = $orderdetail->sSKU;
                $orderDetailInfo[0]['lQuantity'] = $orderdetail->lQuantity;

                //订单物流编号
                $i = 1;
                $bSaved = true;
                while ($bSaved) {
                    $OrderLogistics = OrderLogistics::findOne(['sName' => $order->sName . '-' . $i]);
                    if ($OrderLogistics) {
                        $i++;
                    } else {
                        $array['sName'] = $order->sName . '-' . $i;
                        $bSaved = false;
                    }
                }

                $array['sProductInfo'] = json_encode($orderDetailInfo);
                $array['sOrderDetailID'] = ";" . $orderdetail->lID . ";";
                $array['OrderID'] = $order->lID;
                $array['sExpressNo'] = $arr['ShipNo'];
                $array['sExpressCompany'] = $company->sKdBirdCode;
                $array['ExpressOrderStatusID'] = 3;
                $array['ShipID'] = "wuliu";
                $array['dShipDate'] = \Yii::$app->formatter->asDatetime(time());
                $array['SupplierID'] = $order->SupplierID;
                $orderLogisticsID = OrderLogistics::addValue($array);
            }

            $arrMessage[] = $order->sName . "：发货成功";

            $lDo++;

        }
        $arrMessage['update'] = "成功发货数：" . $lDo;

        $data['arrMessage'] = $arrMessage;
        return $this->render('home', $data);
    }


    /**
     * 发货
     */
    public function ship($data, $detail, $order)
    {
        if (\Yii::$app->request->isPost) {

            //去掉空格
            $data['ShipNo'] = trim($data['ShipNo']);

            //物流信息订阅
            if ($data['ShipID'] == 'wuliu') {
                //如果签收的机制是根据物流跟踪的信息，则需要订阅
                if (MallConfig::getValueByKey('sOrderCompleteDependOn') == 'wuliu') {
                    $return = \Yii::$app->expresstrace->poll([
                        'sNo' => $data['ShipNo'],
                        'ExpressCompanyID' => $data['CompanyID']
                    ]);

                    if ($return === false) {
                        return $this->asJson(['status' => false, 'msg' => '物流信息订阅失败']);
                    }
                }
            } else {
                $data['CompanyID'] = "";
                $data['ShipNo'] = "";
            }

            $detail->ship([
                'ShipID' => $data['ShipID'],
                'ShipCompanyID' => $data['CompanyID'],
                'sShipNo' => $data['ShipNo']
            ]);

            //更新订单状态为已发货
            $order->updateShipStatus();

            return true;
        } else {
            $data = [];
            $data['order'] = Order::findByID($data['OrderID']);
            $data['arrCompany'] = ExpressCompany::find()->orderBy("sPinYin,sName")->all();

            return true;
        }
    }
}