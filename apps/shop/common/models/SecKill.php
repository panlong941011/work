<?php

namespace myerm\shop\common\models;


/**
 * 秒杀
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年11月27日 22:53:47
 * @version v1.0
 */
class SecKill extends ShopModel
{
    /**
     * 已抢光的状态
     */
    const STATUS_SALEOUT = '已抢光';

    const STATUS_OVERLIMIT = '有秒杀商品超出限购数量，请修改数量。';

    public static function updateStatus()
    {
        $arrSecKill = static::find()->where("StatusID IN ('nostart', 'ongoing') OR StatusID IS NULL")->all();
        foreach ($arrSecKill as $secKill) {
            if (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($secKill->dStartDate) < 0) {
                //未开始
                $secKill->StatusID = 'nostart';
            } elseif (\Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($secKill->dStartDate) > 0
                && \Yii::$app->formatter->asTimestamp(\Yii::$app->formatter->asDatetime(time())) - \Yii::$app->formatter->asTimestamp($secKill->dEndDate) < 0
            ) {
                $secKill->StatusID = 'ongoing';
            } else {
                $secKill->StatusID = 'finished';
            }

            $secKill->save();
        }
    }

    public function getSStatus()
    {
        if ($this->StatusID == 'nostart') {
            return "未开始";
        } elseif ($this->StatusID == 'ongoing') {
            return "进行中";
        } elseif ($this->StatusID == 'finished') {
            return "已结束";
        }
    }
}