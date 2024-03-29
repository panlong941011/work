<?php
namespace myerm\shop\backend\controllers;
use myerm\shop\common\models\DealFlow;
use yii\helpers\ArrayHelper;


/**
 * 交易流水
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 何城城  <lumingchen@qq.com>
 * @since 2018年05月04日 18:44:17
 * @version v1.0
 */
class DealflowController extends BackendController
{
    public function listDataConfig($sysList, $arrConfig)
    {
        if ($this->supplier) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'SupplierID',
                'sOper' => 'equal',
                'sValue' => $this->supplier->lID,
                'sSQL' => "SupplierID='" . $this->supplier->lID . "'"
            ];
        } elseif ($this->BuyerID) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => $this->BuyerID,
                'sSQL' => "BuyerID='" . $this->BuyerID . "'"
            ];
        }
        else{
            //@TODO 待完善
            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => $this->BuyerID,
                'sSQL' => "BuyerID=-1"
            ];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }

    public function getViewButton()
    {
        $data = [];
        return $this->renderPartial('viewbutton', $data);
    }
}