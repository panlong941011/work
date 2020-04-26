<?php

namespace myerm\shop\backend\controllers;

use myerm\shop\backend\models\Order;
use myerm\shop\common\models\ProductStockChange;
use myerm\backend\common\controllers\ObjectController;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\OrderDetail;
use myerm\shop\common\models\Refund;
use myerm\shop\mobile\models\RefundLog;
use myerm\shop\common\models\DealFlow;

/**
 * 订单退款记录
 */
class RefundController extends BackendController
{
    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];


        $list = [];

        $list['ID'] = 'all';
        $list['sName'] = '全部';
        $list['sKey'] = 'Main.Shop.Refund.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Refund.List.All&sTabID=all&sExtra=all';
        $data['arrList'][] = $list;

        $list['ID'] = 'wait';
        $list['sName'] = '供应商待确认';
        $list['sKey'] = 'Main.Shop.Refund.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Refund.List.All&sTabID=wait&sExtra=wait';
        $data['arrList'][] = $list;

        $list['ID'] = 'apply';
        $list['sName'] = '供应商拒绝';
        $list['sKey'] = 'Main.Shop.Refund.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Refund.List.All&sTabID=apply&sExtra=apply';
        $data['arrList'][] = $list;

        $list['ID'] = 'appeal';
        $list['sName'] = '申诉中';
        $list['sKey'] = 'Main.Shop.Refund.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Refund.List.All&sTabID=appeal&sExtra=appeal';
        $data['arrList'][] = $list;

        $list['ID'] = 'success';
        $list['sName'] = '退款成功';
        $list['sKey'] = 'Main.Shop.Refund.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Refund.List.All&sTabID=success&sExtra=success';
        $data['arrList'][] = $list;

        $list['ID'] = 'closed';
        $list['sName'] = '退款关闭';
        $list['sKey'] = 'Main.Shop.Refund.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.Refund.List.All&sTabID=closed&sExtra=closed';
        $data['arrList'][] = $list;


        return $this->renderPartial('@app/common/views/hometabs', $data);
    }

    public function listDataConfig($sysList, $arrConfig)
    {

//wait=供应商待确认
        //        apply=供应商拒绝
        //appeal='申诉中'
//success=退款成功
//closed=退款关闭
        if (\Yii::$app->backendsession->SysRoleID == 1) {
            //超级管理员
        } elseif ($this->BuyerID > 0) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'MemberID',
                'sOper' => 'equal',
                'sValue' => $this->BuyerID,
                'sSQL' => "MemberID='" . $this->BuyerID . "'"
            ];
        } elseif ($this->supplier) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'SupplierID',
                'sOper' => 'equal',
                'sValue' => $this->supplier->lID,
                'sSQL' => "SupplierID='" . $this->supplier->lID . "'"
            ];
        }

        if ($_POST['sExtra'] == 'all') {

        } elseif ($_POST['sExtra'] == 'wait') {
            //供应商待审核
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'wait'];
        } elseif ($_POST['sExtra'] == 'apply') {
            //供应商拒绝，待提交
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'apply'];
        } elseif ($_POST['sExtra'] == 'appeal') {
            //申诉中
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'appeal'];
        } elseif ($_POST['sExtra'] == 'success') {
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'success'];
        } elseif ($_POST['sExtra'] == 'closed') {
            $arrConfig['arrFlt'][] = ['sField' => 'StatusID', 'sOper' => 'equal', 'sValue' => 'closed'];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }

    public function formatListData($arrData)
    {
        $arrOrderID = [];
        foreach ($arrData as $data) {
            $arrOrderID[] = $data['OrderID']['ID'];
        }

        $arrOrder = Order::find()->select(['lID', 'StatusID'])
            ->where(['lID' => $arrOrderID])
            ->asArray()
            ->indexBy('lID')
            ->all();

        \Yii::trace($arrOrder);
        foreach ($arrData as $key => $data) {

            $arrData[$key]['sShip'] = $arrOrder[$data['OrderID']['ID']]['StatusID'] == 'delivered' ? "已发货" : "未发货";
        }

        return $arrData;
    }

    /* 旧的退款运费计算方法 留做备用 */
    public function actionViewold($ID)
    {

        $data = [];

        $data['data'] = Refund::findByID($ID);
        $data['arrLog'] = RefundLog::find()->where(['RefundID' => $ID])->orderBy('dNewDate DESC')->all();
        $data['BuyerName'] = Buyer::getsName($data['data']->BuyerID);


        /* 根据退款数量比计算退款建议值 开始 */
        $OrderDetailID = $data['data']->OrderDetailID;
        $RefundItem = $data['data']->lRefundItem;//退款数量
        $ItemTotal = $data['data']->lItemTotal;//商品数量
        if ($RefundItem > $ItemTotal) {
            $RefundItem = $ItemTotal;
        }

        $OrderDetail = OrderDetail::findByID($OrderDetailID);
        $fBuyerPaidTotal = $OrderDetail->fBuyerPaidTotal;//渠道商付款小计
        $fSupplierIncomeTotal = $OrderDetail->fSupplierIncomeTotal;//供应商结款金额小计
        $ship = $OrderDetail->fShip;//运费
        $OrderID = $OrderDetail->OrderID;
        $another = \Yii::$app->orderdetail->isLastProduct($OrderDetail->lID, $OrderDetail->OrderID, $OrderDetail->ShipTemplateID);
        $adviseBuyer = $fBuyerPaidTotal * ($RefundItem / $ItemTotal);
        $adviseSupplier = $fSupplierIncomeTotal * ($RefundItem / $ItemTotal);
        if ($another == 1 && $RefundItem == $ItemTotal) {
            $adviseBuyer += $ship;
            $adviseSupplier += $ship;
        }
        $data['adviseBuyer'] = $adviseBuyer;
        $data['adviseSupplier'] = $adviseSupplier;
        $data['ship'] = $ship;

        /* 根据退款数量比计算退款建议值 结束 */

        /* 判断订单状态 */
        $Order = \Yii::$app->order->findbyid($OrderID);
        //如果订单渠道款不足，则不能退款
        if ($Order->StatusID == 'unpaid') {
            $data['unpaid'] = true;
        }

        /* 判断角色操作状态（售后、财务） */
        if (!empty($OrderDetail->dShipDate)) {
            if ($data['data']->lAftersaleUserID) {
                $data['check'] = 'finance';
            } else {
                $data['check'] = 'aftersale';
            }
        } else {
            $data['check'] = 'finance';
        }

        if ($this->checkHasOperaPower('financecheck')) {
            if ($data['check'] == 'aftersale' && !$this->checkHasOperaPower('aftersalecheck')) {
                $data['desc'] = '请等待售后审核';
            }
            $data['financecheck'] = true;
        }
        if ($this->checkHasOperaPower('aftersalecheck')) {
            if ($data['check'] == 'financecheck' && $data['data']->StatusID == 'wait' && !$this->checkHasOperaPower('financecheck')) {
                if (!empty($OrderDetail->dShipDate)) {
                    $data['desc'] = '当前订单未发货请等待财务审核';
                } else {
                    $data['desc'] = '售后已审核，请等待财务审核';
                }
            }
            $data['aftersalecheck'] = true;
        }
        return $this->render("view", $data);
    }

    public function actionView($ID)
    {
        $data = [];
        $refund = Refund::findByID($ID);
        $data['data'] = $refund;
        $data['arrLog'] = RefundLog::find()->where(['RefundID' => $ID])->orderBy('dNewDate DESC')->all();
        $data['BuyerName'] = Buyer::getsName($data['data']->BuyerID);
        $data['check'] = false;
        /* 判断角色操作状态（售后、财务） */
        if ($refund->StatusID == 'wait' && $refund->SupplierID == $this->supplier->lID) {
            $data['check'] = 'finance';//供应商审核
        } elseif ($refund->StatusID == 'apply' && $refund->MemberID == $this->BuyerID) {
            $data['check'] = 'appeal';//渠道商再次提交申请
        } elseif ($refund->StatusID == 'appeal' && \Yii::$app->backendsession->SysRoleID == 1) {
            $data['check'] = 'aftersale';//管理员仲裁
        }
        return $this->render("view", $data);
    }

    /* 旧的财务同意退款  留做备用*/
    public function actionAgreeapplyold($ID)
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->refund->agreeApply($_GET['ID']);
            $refund = Refund::findByID($ID);

//            return $this->asJson(['status' => false, 'msg'=>$refund->fBuyerRefund . ','.$refund->fSupplierRefund, 'message' => '同意退款申请保存成功']);

            return $this->asJson(['status' => true, 'message' => '同意退款申请保存成功']);
        } else {
            $data = [];
            $Refund = Refund::findByID($ID);

            $data['adviseBuyer'] = $_GET['adviseBuyer'];
            $data['adviseSupplier'] = $_GET['adviseSupplier'];

            $OrderDetail = \Yii::$app->orderdetail->findbyid($Refund->OrderDetailID);
            if (empty($OrderDetail->sShipNo)) {
                $Refund->fBuyerRefund = $_GET['adviseBuyer'];
                $Refund->fSupplierRefund = $_GET['adviseSupplier'];
                $Refund->save();
            }
            $data['refund'] = $Refund;
            return $this->renderPartial("agreeapply", $data);
        }
    }

    public function actionAgreeapply($ID)
    {
        $Refund = Refund::findByID($ID);
        if (\Yii::$app->request->isPost) {

//            return $this->asJson(['status' => false, 'msg'=>$refund->fBuyerRefund . ','.$refund->fSupplierRefund, 'message' => '同意退款申请保存成功']);
            return $this->asJson(['status' => true, 'message' => '同意退款申请保存成功']);
        } else {
            $data = [];
            $Refund = Refund::findByID($ID);
            $data['refund'] = $Refund;
            return $this->renderPartial("agreeapply", $data);
        }
    }

    /* 旧的售后同意 */
    public function actionAftersaleagreeold($ID)
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->refund->aftersaleagree($_REQUEST['ID'], $_REQUEST['BuyerRefund'], $_REQUEST['SupplierRefund']);
//            return $this->asJson(['status' => false,'msg'=>$_REQUEST['ID'] . ',' . $_REQUEST['BuyerRefund'] . ',' . $_REQUEST['SupplierRefund'], 'message' => '同意退款申请保存成功']);
            return $this->asJson(['status' => true, 'message' => '同意退款申请保存成功']);
        } else {
            $data = [];
            $data['refund'] = Refund::findByID($ID);
            $data['apply'] = $_GET['apply'];
            $data['adviseSupplier'] = $_GET['adviseSupplier'];
            $data['adviseBuyer'] = $_GET['adviseBuyer'];
            return $this->renderPartial("aftersaleagree", $data);
        }
    }

    /* 售后同意 */
    public function actionAftersaleagree($ID)
    {
        if (\Yii::$app->request->isPost) {

            \Yii::$app->refund->aftersaleagree([
                'ID' => $ID,
                'BuyerRefund' => $_REQUEST['BuyerRefund'],
                'BuyerRefundProduct' => $_REQUEST['BuyerRefundProduct'],
                'SupplierRefund' => $_REQUEST['SupplierRefund'],
                'SupplierRefundProduct' => $_REQUEST['SupplierRefundProduct'],
            ]);
//            return $this->asJson(['status' => false,'msg'=>$_REQUEST['ID'] . ',' . $_REQUEST['BuyerRefund'] . ',' . $_REQUEST['SupplierRefund'], 'message' => '同意退款申请保存成功']);
            return $this->asJson(['status' => true, 'message' => '同意退款申请保存成功']);
        } else {
            $refund = Refund::findByID($ID);

            /* 根据商品价格占订单中同类运费模板商品总价比例退还运费 */
            $orderDetail = OrderDetail::findByID($refund->OrderDetailID);
            $fTotalPrice = \Yii::$app->orderdetail->countProductPrice($orderDetail->OrderID, $orderDetail->ShipTemplateID);
            $fTotalShip = $orderDetail->fShip * ($orderDetail->fBuyerPaidTotal / $fTotalPrice);

            $data = [];
            $data['refund'] = $refund;
            $data['apply'] = $_GET['apply'];
            $data['adviseSupplierProduct'] = $_GET['adviseSupplierProduct'];
            $data['adviseBuyerProduct'] = $_GET['adviseBuyerProduct'];
            $data['fBuyerPaidTotal'] = $refund->orderDetail->fBuyerPaidTotal;
            $data['fSupplierIncomeTotal'] = $refund->orderDetail->fSupplierIncomeTotal;
            $data['fTotalShip'] = $fTotalShip;
            return $this->renderPartial("aftersaleagree", $data);
        }
    }

    public function actionAgreereceive($ID)
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->refund->agreeReceive($_GET['ID']);
            return $this->asJson(['status' => true, 'message' => '确认收货成功']);
        } else {
            $data = [];
            $data['refund'] = Refund::findByID($ID);
            return $this->renderPartial("agreereceive", $data);
        }
    }

    public function actionDenyreceive($ID)
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->refund->denyReceive([
                'id' => $ID,
                'sDenyReceiveExplain' => $_POST['sDenyReceiveExplain']
            ]);
            return $this->asJson(['status' => true, 'message' => '拒绝确认收货成功']);
        } else {
            $data = [];
            $data['refund'] = Refund::findByID($ID);
            return $this->renderPartial("denyreceive", $data);
        }
    }

    public function actionDenyapply($ID)
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->refund->denyApply([
                'id' => $_GET['ID'],
                'sReason' => $_POST['sDenyApplyReason'],
                'sExplain' => $_POST['sDenyApplyExplain'],
            ]);

            return $this->asJson(['status' => true, 'message' => '拒绝退款申请成功']);
        } else {
            $data = [];
            $data['refund'] = Refund::findByID($ID);
            $data['adviseBuyer'] = $_GET['adviseBuyer'];
            $data['adviseSupplier'] = $_GET['adviseSupplier'];
            return $this->renderPartial("denyapply", $data);
        }
    }

    /* 售后拒绝 */
    public function actionAftersaledeny($ID)
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->refund->aftersaledeny([
                'id' => $_GET['ID'],
                'sReason' => $_POST['sDenyApplyReason'],
                'sExplain' => $_POST['sDenyApplyExplain'],
            ]);

            return $this->asJson(['status' => true, 'message' => '拒绝退款申请成功']);
        } else {
            $data = [];
            $data['refund'] = Refund::findByID($ID);
            return $this->renderPartial("aftersaledeny", $data);
        }
    }

    public function actionTest()
    {
        \myerm\shop\backend\models\Refund::test();
    }
}