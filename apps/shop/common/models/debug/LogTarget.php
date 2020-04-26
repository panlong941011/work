<?php

namespace myerm\shop\common\models\debug;


class LogTarget extends \yii\debug\LogTarget
{

    public function export()
    {
        parent::export();

        //保存到数据库
        $arrData = $this->collectSummary();
        $arrData['path'] = $this->module->dataPath . "/" . $arrData['tag'] . ".data";

        $profiling = $this->module->panels['profiling']->save();

        $arrSaveData = [
            'ID' => $arrData['tag'],
            'sName' => $arrData['url'],
            'MemberID' => \Yii::$app->frontsession->MemberID,
            'SessionID' => \Yii::$app->frontsession->ID,
            'dNewDate' => \Yii::$app->formatter->asDatetime($arrData['time']),
            'bAjax' => $arrData['ajax'],
            'sMethod' => $arrData['method'],
            'sIP' => $arrData['ip'],
            'sStatusCode' => $arrData['statusCode'],
            'lSQLCount' => $arrData['sqlCount'],
            'lMailCount' => $arrData['mailCount'],
            'sSavePath' => $this->module->dataPath . "/" . $arrData['tag'] . ".data",
            'lTimeUse' => floor($profiling['time'] * 1000)
        ];

        $shopDebug = new ShopDebug();
        $shopDebug->saveDebugData($arrSaveData);
    }


}
