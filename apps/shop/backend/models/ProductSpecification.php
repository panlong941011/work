<?php

namespace myerm\shop\backend\models;


/**
 * 商品规格
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年9月30日 16:22:33
 * @version v1.0
 */
class ProductSpecification extends \myerm\shop\common\models\ProductSpecification
{
    public static function saveData($data)
    {
        $spec = new static();
        $spec->setAttributes($data, false);
        $spec->save();

        return true;
    }

    public static function getData($ProductID)
    {
        $arrSpec = static::findAll(['ProductID' => $ProductID]);

        $arrJson = [];
        foreach ($arrSpec as $spec) {

            $arrS = [];
            $arr = explode(";", $spec->sValue);
            foreach ($arr as $v) {
                $arrS[] = ['cName' => $v];
            }

            while (1) {

                if (count($arrS) == 10) {
                    break;
                }

                $arrS[] = ['cName' => ''];
            }

            $arrJson[] = ['pName' => $spec->sName, 'spec' => $arrS];
        }

        $arrImageJson = explode(";", $arrSpec[0]->sPic);

        foreach ($arrImageJson as $i => $v) {
            if ($v) {
                $arrImageJson[$i] = \Yii::$app->params['sUploadUrl'] . "/" . $v;
            }
        }

        return [$arrJson, $arrImageJson];
    }
}