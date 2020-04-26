<?php

namespace console\controllers;

use yii\console\Controller;

class ConsoleController extends Controller
{
    public function beforeAction($action)
    {
        $this->simulateLogin();
        return parent::beforeAction($action);
    }
    /**
     * 模拟登录，系统会随机取一个会员的数据进行模拟登录
     */
    public function simulateLogin()
    {
        $member = \Yii::$app->db->createCommand("SELECT * FROM Member ORDER BY RAND() LIMIT 1")->queryOne();

        \Yii::$app->frontsession->ID = \Yii::$app->frontsession->buildNounceString(12);
        \Yii::$app->frontsession->dLastActivity = \Yii::$app->formatter->asDatetime(time());
        \Yii::$app->frontsession->sUserIP = "127.0.0.1";
        \Yii::$app->frontsession->sOpenID = $member['sOpenID'];
        \Yii::$app->frontsession->MemberID = $member['lID'];
        \Yii::$app->frontsession->MemberID = $member['lID'];
        \Yii::$app->frontsession->save();
    }
}