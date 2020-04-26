<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\kuaidi100\models\ExpressCompany;
use myerm\shop\common\models\Import;
use myerm\shop\common\models\Importreturn;
use myerm\shop\common\models\NotifyConfig;
use myerm\shop\common\models\Order;
use myerm\shop\common\models\OrderAddress;
use myerm\shop\common\models\OrderDetail;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\OrderLogistics;
use myerm\shop\common\models\PreOrder;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\Wholesaler;
use myerm\shop\mobile\models\Supplier;
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
class WholesalerrefundController extends ObjectController
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
     * 导入渠道订单文件
     * @return string
     * @throws yii\base\InvalidConfigException
     * @author panlong
     * @time 2019年5月15日10:34:05
     */
    public function actionRun()
    {
        $excel = new \BaseExcel();
        $excel->file = $_FILES['file']['tmp_name'];
        $arrSheetData = $excel->import();
        $arrData = [];
        $BuyerID='';
        if (\Yii::$app->backendsession->SysRoleID == 4) {
            //渠道商
            $buyer = \Yii::$app->buyer->findBySysUserID(\Yii::$app->backendsession->SysUserID);
            $BuyerID=$buyer->lID;
        } elseif (\Yii::$app->backendsession->SysRoleID == 10) {
            //渠道供应商
            $wholesalerSupplier = \Yii::$app->supplier->findBySysUserID(\Yii::$app->backendsession->SysUserID);
            $BuyerID=$wholesalerSupplier->BuyerID;
        }
        $date = date("Y-m-d H:i:s");
        foreach ($arrSheetData['Sheet1'] as $k => $v) {
            $arr = [];
            if ($k >= 1) {
                $arr['PrductID'] = trim($v[0]);//有链商品ID
                $arr['orderName'] = trim($v[1]);//子平台订单编号
                $arr['productName'] = $v[2];//子平台商品名称
                $arr['lNum'] = $v[3];//退款商品数量
                $arr['lTotalNum'] = $v[4];//购买商品总数量
                $arr['sContent'] = $v[5];//退款说明
                $arr['sPic1'] = $v[6];//省
                $arr['sPic2'] = $v[7];//市
                $arr['sPic3'] = $v[8];//区
                $arr['sExpressNo'] = $v[9];//详细地址
                $arr['dNewDate'] = $date;//批次号
                $arr['sRemark'] = '';//错误说明
                $arr['buyerID'] = $BuyerID;
                $arr['status'] = 1;
                $arr['cloudProductName'] = '';
                $arr['fMoney'] = 0;//退款金额
                $arr['OrderID'] = 0;//退款金额
                $arr['sName'] = 0;//退款金额
                $arrData[] = Importreturn::checkImportRecord($arr);
            } else {
                continue;
            }
        }

        $table = [
            'PrductID',
            'orderName',
            'productName',
            'lNum',
            'lTotalNum',
            'sContent',
            'sPic1',
            'sPic2',
            'sPic3',
            'sExpressNo',
            'dNewDate',
            'sRemark',
            'buyerID',
            'status',
            'cloudProductName',
            'fMoney',
            'OrderID',
            'sName'
        ];
        //批量记录数据，记录在import表
        \Yii::$app->db->createCommand()->batchInsert('importreturn', $table, $arrData)->execute();

    }
}