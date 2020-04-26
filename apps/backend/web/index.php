<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);

//启用压缩
ini_set("zlib.output_compression", "On");

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

//require(__DIR__ . '/../../common/libs/sqlsafe.php'); 有些商品编辑界面会因为它报错，Mars，2018年6月4日 14:29:03
require(__DIR__ . '/../../../vendor/autoload.php');
require(__DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

$config['components']['ds_db'] = $config['components']['db'];

Yii::setAlias('@app', __DIR__ . '/../');
Yii::setAlias('@myerm', __DIR__ . '/../../');

$app = new yii\web\Application($config);

Yii::$app->on('beforeRequest', function ($event) {
    //判断目标控制器是否存在，如果不存在要导向基类控制器
    $arrPart = explode("/", strtolower(Yii::$app->request->pathInfo));

    $arrRule = [];

    $mapping = Yii::$app->ds_db->createCommand("SELECT sOldRoute, sNewRoute 
                                                FROM SysObjectMapping 
                                                WHERE sOldRoute='" . $arrPart[0] . "/" . $arrPart[1] . "' AND bActive='1'")->queryOne();
    if ($mapping) {
        $arrPart = explode("/", strtolower($mapping['sNewRoute']));
        if (!is_file(Yii::getAlias('@myerm') . "/$arrPart[0]/backend/controllers/" . ucfirst($arrPart[1]) . "Controller.php")) {
            if (is_file(Yii::getAlias('@myerm') . "/" . dirname($sNewRoute) . '/' . ucfirst(dirname($sNewRoute)) . "Controller.php")) {
                $arrRule['/' . $sNewRoute . '/' . $arrPart[2]] = dirname($sNewRoute) . '/' . dirname($sNewRoute) . '/' . $arrPart[2];
            } else {
                $arrRule['/' . $sNewRoute . '/' . $arrPart[2]] = '/common/controllers/object/' . $arrPart[2];
            }
        } else {
            $arrRule['/' . Yii::$app->request->pathInfo] =  '/' . $mapping['sNewRoute'];
        }
    } else {
        if ($arrPart[0] == 'system') {
            if (!is_file(Yii::getAlias('@app') . "/" . $arrPart[0] . "/controllers/" . ucfirst($arrPart[1]) . "Controller.php")) {
                $arrRule['/' . $arrPart[0] . '/' . $arrPart[1] . '/' . $arrPart[2]] = '/common/object/' . $arrPart[2];
            }
        } elseif (!is_file(Yii::getAlias('@myerm') . "/" . $arrPart[0] . "/backend/controllers/" . ucfirst($arrPart[1]) . "Controller.php")) {
            if (is_file(Yii::getAlias('@myerm') . "/" . $arrPart[0] . "/backend/controllers/" . ucfirst($arrPart[0]) . "Controller.php")) {
                $arrRule['/' . $arrPart[0] . '/' . $arrPart[1] . '/' . $arrPart[2]] = '/' . $arrPart[0] . '/' . $arrPart[0] . '/' . $arrPart[2];
            } else {
                $arrRule['/' . $arrPart[0] . '/' . $arrPart[1] . '/' . $arrPart[2]] = '/common/object/' . $arrPart[2];
            }
        }
    }

    Yii::$app->getUrlManager()->addRules($arrRule);
});

$PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if (preg_match("/([a-z0-9]{1,}\.php)/i", $PHP_SELF, $m)) {
    $pathArray = explode($m[1], $PHP_SELF);
}
Yii::$app->setHomeUrl(htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . preg_replace("/\/$/", "", $pathArray[0])));


$app->run();
