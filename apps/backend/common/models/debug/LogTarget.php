<?php

namespace myerm\backend\common\models\debug;


class LogTarget extends \yii\debug\LogTarget
{

    public function export()
    {
        if (stristr(\Yii::$app->request->url, 'system/sysdebug') || preg_match("/\/(js|jslang)$/", \Yii::$app->request->url)) {
            return "";
        }

        parent::export();

        //保存到数据库
        $arrData = $this->collectSummary();
        $arrData['path'] = $this->module->dataPath . "/" . $arrData['tag'] . ".data";

        $profiling = $this->module->panels['profiling']->save();

        $arrSaveData = [
            'ID' => $arrData['tag'],
            'sName' => $arrData['url'],
            'SysUserID' => \Yii::$app->backendsession->SysUserID,
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

        $debug = new SysDebug();
        $debug->saveDebugData($arrSaveData);
    }


}
