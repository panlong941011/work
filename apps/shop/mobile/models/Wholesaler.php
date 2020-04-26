<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2018-08-06
 * Time: 下午 7:28
 */

namespace myerm\shop\mobile\models;
use myerm\shop\common\models\ShopModel;

/**
 * 渠道人员类
 * Class Wholesaler
 * @package myerm\shop\mobile\models
 * @author hechengcheng
 * @time 2019/5/9 17:40
 */
class Wholesaler extends ShopModel
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    /**
     * 升级为渠道人员
     * @param $param
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @author hechengcheng
     * @time 2019/5/9 17:49
     */
    public static function wholesalerUp($param)
    {

        $wholesaler = \Yii::$app->wholesaler->findByID($param['BuyerID']);
        if ($wholesaler) {
            return ['status' => false, 'message' => '请不要重复申请'];
        }
        //创建渠道人员账户
        $wholesaler = new static();
        $wholesaler->lID = $param['WholesalerID'];
        $wholesaler->sName = $param['sName'];
        $wholesaler->sMobile = $param['sMobile'];
        $wholesaler->BuyerID = $param['BuyerID'];
        $wholesaler->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $wholesaler->bActive = 0;
        $wholesaler->save();
        return ['status' => true, 'message' => '申请成功，等待审核'];
    }
}