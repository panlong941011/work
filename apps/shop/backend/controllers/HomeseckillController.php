<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\shop\common\models\HomeSecKill;
use myerm\shop\common\models\SecKill;


/**
 * 首页秒杀区设置
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年12月19日 14:56:25
 * @version v1.0
 */
class HomeseckillController extends ObjectController
{
    public function getListButton()
    {
        return $this->renderPartial("listbutton");
    }

    public function actionAddproduct()
    {
        if (\Yii::$app->request->isPost) {
            $arrProduct = \Yii::$app->homeseckill->addProduct($_POST['checked']);
            return $this->asJson(['status' => true, 'message' => '添加成功']);
        } else {
            $arrProduct = \Yii::$app->seckillproduct->findAllActive();

            return $this->renderPartial("addproduct", ['arrProduct' => $arrProduct, 'arrID'=>array_keys(\Yii::$app->homeseckill->all())]);
        }
    }

    public function listDataConfig($sysList, $arrConfig)
    {
        SecKill::updateStatus();

        if ($arrConfig['sListKey'] == 'Main.Shop.HomeSecKill.List.Today') {

            $dTodayStart = \Yii::$app->formatter->asDate(time()) . " 00:00:00";//今天开始
            $dTodayEnd = \Yii::$app->formatter->asDate(time()) . " 23:59:59";//今天结束
            $arrData = HomeSecKill::find()->with('secKill')->all();
            $arrToday = [];
            foreach ($arrData as $k => $data) {
                if (strtotime($data->secKill->dStartDate) <= strtotime($dTodayStart)
                    && strtotime($data->secKill->dEndDate) >= strtotime($dTodayStart)
                ) {
                    $arrToday[] = $data->lID;
                } elseif (strtotime($data->secKill->dStartDate) >= strtotime($dTodayStart)
                    && strtotime($data->secKill->dEndDate) <= strtotime($dTodayEnd)
                ) {
                    $arrToday[] = $data->lID;
                } elseif (strtotime($data->secKill->dStartDate) <= strtotime($dTodayEnd)
                    && strtotime($data->secKill->dEndDate) >= strtotime($dTodayEnd)
                ) {
                    $arrToday[] = $data->lID;
                } elseif (strtotime($data->secKill->dStartDate) <= strtotime($dTodayStart)
                    && strtotime($data->secKill->dEndDate) >= strtotime($dTodayEnd)
                ) {
                    $arrToday[] = $data->lID;
                }
            }

            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sSQL' => "lID IN ('".implode("','", $arrToday)."')"
            ];

        } elseif ($arrConfig['sListKey'] == 'Main.Shop.HomeSecKill.List.Tomorrow') {
            $dTomorrowStart = \Yii::$app->formatter->asDate(time() + 86400) . " 00:00:00";//明天开始
            $dTomorrowEnd = \Yii::$app->formatter->asDate(time() + 86400) . " 23:59:59";//明天结束
            $arrData = HomeSecKill::find()->with('secKill')->all();
            $arrTomorrow = [];
            foreach ($arrData as $k => $data) {
                if (strtotime($data->secKill->dStartDate) >= strtotime($dTomorrowStart)
                    && strtotime($data->secKill->dStartDate) <= strtotime($dTomorrowEnd)
                ) {
                    $arrTomorrow[] = $data->lID;
                }
            }

            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sSQL' => "lID IN ('".implode("','", $arrTomorrow)."')"
            ];
        }

        if ($arrConfig['arrSearchField']['dDate']['sValue']) {
            preg_match_all("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $arrConfig['arrSearchField']['dDate']['sValue'], $m);
            $dStart = $m[0][0] . " 00:00:00";
            $dEnd = $m[0][1]. " 23:59:59";

            $arrData = HomeSecKill::find()->with('secKill')->all();
            $arrID = [];
            foreach ($arrData as $k => $data) {
                if (strtotime($data->secKill->dStartDate) <= strtotime($dStart)
                    && strtotime($data->secKill->dEndDate) >= strtotime($dStart)
                ) {
                    $arrID[] = $data->lID;
                } elseif (strtotime($data->secKill->dStartDate) >= strtotime($dStart)
                    && strtotime($data->secKill->dEndDate) <= strtotime($dEnd)
                ) {
                    $arrID[] = $data->lID;
                } elseif (strtotime($data->secKill->dStartDate) <= strtotime($dEnd)
                    && strtotime($data->secKill->dEndDate) >= strtotime($dEnd)
                ) {
                    $arrID[] = $data->lID;
                } elseif (strtotime($data->secKill->dStartDate) <= strtotime($dStart)
                    && strtotime($data->secKill->dEndDate) >= strtotime($dEnd)
                ) {
                    $arrID[] = $data->lID;
                }
            }

            $arrConfig['arrFlt'][] = [
                'sField' => 'lID',
                'sOper' => 'in',
                'sSQL' => "lID IN ('".implode("','", $arrID)."')"
            ];

            unset($arrConfig['arrSearchField']['dDate']);

            foreach ($arrConfig['arrFlt'] as $k => $flt) {
                if ($flt['sField'] == 'dDate') {
                    unset($arrConfig['arrFlt'][$k]);
                }
            }
        }


        return parent::listDataConfig($sysList, $arrConfig); // TODO: Change the autogenerated stub
    }

    public function formatListData($arrData)
    {
        foreach ($arrData as $key => $data) {
            $arrData[$key]['lPos'] = "<input type='text' style='width: 50px' value='" . $data['lPos'] . "' onblur=\"savePos(" . $data['lID'] . ", this.value)\">";
        }

        return parent::formatListData($arrData); // TODO: Change the autogenerated stub
    }

    public function actionSavepos($lID, $lPos)
    {
        \Yii::$app->homeseckill->savePos($lID, $lPos);
    }
}