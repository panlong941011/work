<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\kuaidi100\models\ExpressCompany;
use myerm\shop\common\models\Import;
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
class WholesalerbatchController extends ObjectController
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
        $BuyerID = '';
        if (\Yii::$app->backendsession->SysRoleID == 4) {
            //渠道商
            $buyer = \Yii::$app->buyer->findBySysUserID(\Yii::$app->backendsession->SysUserID);
            $BuyerID = $buyer->lID;
        } elseif (\Yii::$app->backendsession->SysRoleID == 10) {
            //渠道供应商
            $wholesalerSupplier = \Yii::$app->supplier->findBySysUserID(\Yii::$app->backendsession->SysUserID);
            $BuyerID = $wholesalerSupplier->BuyerID;
        } elseif (\Yii::$app->backendsession->SysRoleID == 11) {
            //试用渠道商
            $this->buyer = \Yii::$app->buyer->findBySysUserID(\Yii::$app->backendsession->SysUserID);
            $BuyerID = $this->buyer->lID;
        } elseif (\Yii::$app->backendsession->SysRoleID == 12) {
            //供应商&试用渠道商
            $this->wholesalerSupplier = \Yii::$app->supplier->findBySysUserID(\Yii::$app->backendsession->SysUserID);
            $BuyerID = $this->wholesalerSupplier->BuyerID;
        }

        $date = date("Y-m-d H:i:s");
        foreach ($arrSheetData['Sheet1'] as $k => $v) {
            $arr = [];
            if ($k >= 1&&trim($v[0])) {
                $arr['PrductID'] = trim($v[0]);//有链商品ID
                $arr['orderName'] = trim($v[1]);//子平台订单编号
                if(empty($arr['orderName'])){
                    $arr['orderName']='YL'.date('YmdHis') . rand(10000, 99999);
                }
                $arr['productName'] = $v[2];//子平台商品名称
                $arr['lNum'] = $v[3];//购买数量
                $arr['sName'] = $v[4];//收件人
                $arr['sMobile'] = $v[5];//收件人手机号码
                $arr['sProvince'] = $v[6];//省
                $arr['sCity'] = $v[7];//市
                $arr['sArea'] = $v[8];//区
                $arr['sAddress'] = $v[9];//详细地址
                $arr['sContent'] = $v[10];//备注

                $arr['dNewDate'] = $date;//批次号
                $arr['sRemark'] = '';//错误说明
                $arr['ProvinceID'] = '';
                $arr['CityID'] = '';
                $arr['AreaID'] = '';
                $arr['buyerID'] = $BuyerID;
                $arr['status'] = 1;
                $arr['cloudProductName'] = '';
                $arr['sAddressFinal'] = '';
                $arr['bGroupProdcut'] = 0;
                $arr['lGroupNum'] = 0;
                $arr['ShipTemplateID'] = 0;
                $arr['sStandard'] = '';//规格
                $arr['sTaste'] = '';//口味
                $arr['fPrice'] = 0;//单价
                $arr['lSerial'] = $k + 1;//序号

                $arrData[] = PreOrder::checkImportRecord($arr);
            } else {
                continue;
            }
//			//判断购买人是否存在
//			$user = Wholesaler::findOne(['sMobile' => $arr['sMobile']]);
//			if (!$user) {
//				$arrMessage[] = "买家手机号不存在：" . $arr['sMobile'];
//				continue;
//			}
//
//			if ($user->SupplierID != $supplier->lID) {
//				$arrMessage[] = "买家手机号不属于您的下级：" . $arr['sMobile'];
//			}

        }

        $table = [
            'PrductID',
            'orderName',
            'productName',
            'lNum',
            'sName',
            'sMobile',
            'sProvince',
            'sCity',
            'sArea',
            'sAddress',
            'sContent',
            'dNewDate',
            'sRemark',
            'ProvinceID',
            'CityID',
            'AreaID',
            'buyerID',
            'status',
            'cloudProductName',
            'sAddressFinal',
            'bGroupProdcut',
            'lGroupNum',
            'ShipTemplateID',
            'sStandard',
            'sTaste',
            'fPrice',
            'lSerial'
        ];
        //批量记录数据，记录在import表
        \Yii::$app->db->createCommand()->batchInsert('import', $table, $arrData)->execute();
        //判断数据是否满足购买条件
        //同一个订单编号 收件人信息必须一致
        $arrImport = Import::find()->where(['and', ['dNewDate' => $date], ['status' => 1]])->orderBy('orderName')->all();
        $key = '';
        $orderName = '';
        $arrErrorOrderName = [];
        foreach ($arrImport as  $import) {
            if ($orderName == '') {
                $orderName = $import->orderName;
                //订单编号+收件人+收人收件号码+省+市+区+详细地址
                $key = $import->orderName . $import->sName . $import->sMobile . $import->ProvinceID . $import->CityID . $import->AreaID . $import->sAddressFinal;
            } else {
                $newKey = $import->orderName . $import->sName . $import->sMobile . $import->ProvinceID . $import->CityID . $import->AreaID . $import->sAddressFinal;
                if ($orderName == $import->orderName) {
                    if ($newKey != $key) {
                        $import->status = 0;
                        $import->sRemark = $import->sRemark . '同一订单编号收货人信息不一致';
                        $import->save();
                        $arrErrorOrderName[] = $orderName;
                    }
                } else {
                    $orderName = $import->orderName;
                }
            }
        }
        foreach ($arrImport as $import) {
            if ($import->status == 1) {
                foreach ($arrErrorOrderName as $orderName) {
                    if ($orderName == $import->orderName) {
                        $import->status = 0;
                        $import->sRemark = $import->sRemark . '同一订单编号收货人信息不一致';
                        $import->save();
                    }
                }
            }
        }
        return $this->redirect(\Yii::$app->homeUrl . "/shop/import/home");;
    }


    /**
     * 发货
     */
    public function ship($data)
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

            $order = \Yii::$app->order->getByNo($data['Sname']);
            $detail = OrderDetail::findByID($data['DetailID']);

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