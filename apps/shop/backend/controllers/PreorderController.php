<?php

namespace myerm\shop\backend\controllers;


use myerm\shop\backend\models\Orderfront;
use myerm\shop\common\models\PreOrder;
use yii\helpers\ArrayHelper;

/**
 * 待确认订单管理
 */
class PreorderController extends BackendController
{
    /**
     * 待确认订单列表处理
     * @param \myerm\backend\common\controllers\unknown $arrData
     * @return \myerm\backend\common\controllers\unknown
     * @author hechengcheng
     * @time 2019/5/13 15:20
     */
    public function formatListData($arrData)
    {
        $arrOrderID = ArrayHelper::getColumn($arrData, 'lID');

        $arrPreOrder = PreOrder::find()
            ->where(['lID' => $arrOrderID])
            ->all();

        $arrDetailData = [];
        foreach ($arrPreOrder as $preOrder) {
            $arrProductStockChange = $preOrder->productStockChange;
            foreach ($arrProductStockChange as $productstock) {
                $fTotalPrice = $preOrder->fTotal - $preOrder->fShip;
                $lQuantity = $productstock->lChange;
                $arrDetailData[$preOrder->lID]['sProductName'][] = "<a href='javascript:;'onclick=\"parent.addTab($(this).text(), '/shop/product/view?ID=" . $productstock->ProductID . "')\">" . $productstock->product->sName . "</a>";
                $arrDetailData[$preOrder->lID]['fPrice'][] = number_format($fTotalPrice / $lQuantity, 2);
                $arrDetailData[$preOrder->lID]['lQuantity'][] = $lQuantity;
                $arrDetailData[$preOrder->lID]['fTotalPrice'][] = number_format($fTotalPrice, 2);
            }
        }

        //数据输出到列表
        foreach ($arrData as $key => $data) {
            $arrData[$key]['sProductName'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sProductName']);
            $arrData[$key]['fPrice'] = $this->listDataTemplate($arrDetailData[$data['lID']]['fPrice']);
            $arrData[$key]['lQuantity'] = $this->listDataTemplate($arrDetailData[$data['lID']]['lQuantity']);
            $arrData[$key]['fTotalPrice'] = $this->listDataTemplate($arrDetailData[$data['lID']]['fTotalPrice']);
        }

        return $arrData;
    }

    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];

        $list = [];

        $list['ID'] = '1';
        $list['sName'] = '待确认';
        $list['sKey'] = 'Main.Shop.PreOrder.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.PreOrder.List.All&sTabID=bcheck&sExtra=bcheck';
        $data['arrList'][] = $list;

        $list['ID'] = '2';
        $list['sName'] = '已确认';
        $list['sKey'] = 'Main.Shop.PreOrder.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.PreOrder.List.All&sTabID=success&sExtra=success';
        $data['arrList'][] = $list;

        $list['ID'] = '3';
        $list['sName'] = '已取消';
        $list['sKey'] = 'Main.Shop.PreOrder.List.All';
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=Main.Shop.PreOrder.List.All&sTabID=cancle&sExtra=cancle';
        $data['arrList'][] = $list;


        return $this->renderPartial('@app/common/views/hometabs', $data);
    }

    public function listDataTemplate($arrData)
    {
        $sHtml = [];
        $count = count($arrData);

        if ($count == 1) {
            return $arrData[0];
        }

        foreach ($arrData as $key => $data) {
            $data = $data ? $data : '&nbsp;';
            $sHtml[] = \yii\helpers\Html::tag('div', $data, [
                'style' => [
                    'padding' => '5px 0',
                    'border-bottom' => $key < ($count - 1) ? '1px solid #E7ECF1' : '0'
                ]
            ]);
        }
        return implode('', $sHtml);
    }

    public function getListButton()
    {
        $data = [];
        return $this->renderPartial("listbutton", $data);
    }

    /**
     * 确认订单
     * @author hechengcheng
     * @time 2019/5/13 15:26
     */
    public function actionConfirmorder()
    {
        $arrData = $this->listBatch();
        foreach ($arrData as $data) {
            $preOrder = \Yii::$app->preorder::findByID($data['lID']);
            $res = $preOrder->confirmOrder();
            if (!$res['bSuccess']) {
                return $this->asJson($res);
            } else {
                //判断是否是前台提交订单
                if ($preOrder->WholesalerID) {
                    $fontOrder = Orderfront::find()
                        ->where(['and', ['sName' => $preOrder->sOrderNo], ['MemberID' => $preOrder->WholesalerID]])->one();
                    $fontOrder->StatusID = 'paid';
                    $fontOrder->fPaid = $fontOrder->fPaid;
                    $fontOrder->dPayDate = date('Y-m-d H:i:s');
                    $fontOrder->save();
                }
            }
        }
        return $this->asJson(['bSuccess' => true, 'sMsg' => '确认成功']);
    }

    public function getViewButton()
    {
        $data = [];
        return $this->renderPartial('viewbutton', $data);
    }

    public function listDataConfig($sysList, $arrConfig)
    {
        if (\Yii::$app->backendsession->SysRoleID == 1) {
            //超级管理员
        } else {
            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => $this->BuyerID,
                'sSQL' => "BuyerID='" . $this->BuyerID . "'"
            ];
        }

        if ($_POST['sExtra'] == 'all') {

        } elseif ($_POST['sExtra'] == 'cancle') {
            $arrConfig['arrFlt'][] = ['sField' => 'bClosed', 'sOper' => 'equal', 'sValue' => '-1'];
        } elseif ($_POST['sExtra'] == 'success') {
            $arrConfig['arrFlt'][] = ['sField' => 'bClosed', 'sOper' => 'equal', 'sValue' => '1'];
        } elseif ($_POST['sExtra'] == 'bcheck') {
            $arrConfig['arrFlt'][] = ['sField' => 'bClosed', 'sOper' => 'equal', 'sValue' => '0'];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }

    /**
     * 取消订单
     * @author hechengcheng
     * @time 2019/5/13 15:26
     */
    public function actionCancleorder()
    {
        $arrData = $this->listBatch();
        foreach ($arrData as $data) {
            $preOrder = \Yii::$app->preorder::findByID($data['lID']);
            if($preOrder->bClosed==0) {
                $preOrder->bClosed = -1;
                $preOrder->dCloseDate = date('Y-m-d H:i:s');
                $preOrder->save();
                //判断是否是前台提交订单
                if ($preOrder->WholesalerID) {
                    $fontOrder = Orderfront::find()
                        ->where(['and', ['sName' => $preOrder->sOrderNo], ['MemberID' => $preOrder->WholesalerID]])->one();
                    $fontOrder->StatusID = 'closed';
                    $fontOrder->save();
                }
            }else{
                return $this->asJson(['bSuccess' => false, 'sMsg' => '已提交订单无法取消，请在订单列表申请退款']);
            }

        }
        return $this->asJson(['bSuccess' => true, 'sMsg' => '取消成功']);
    }
}