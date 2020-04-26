<?php

namespace myerm\shop\common\models;


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
 * @time 2017年12月19日 15:32:52
 * @version v1.0
 */
class HomeSecKill extends ShopModel
{
    /**
     * 添加推荐
     */
    public function addProduct($arrSecKillProduct)
    {
        foreach ($arrSecKillProduct as $lID) {
            static::deleteAll(['SecKillProductID' => $lID]);

            $secKillProduct = \Yii::$app->seckillproduct->findByID($lID);

            $home = new static();
            $home->ProductID = $secKillProduct->ProductID;
            $home->SecKillProductID = $secKillProduct->lID;
            $home->SecKillID = $secKillProduct->SecKillID;
            $home->save();
        }
    }

    /**
     * 保存排序
     * @param $lID
     * @param $lPos
     */
    public function savePos($lID, $lPos)
    {
        static::updateAll(['lPos' => $lPos], ['lID' => $lID]);
    }

    /**
     * 关联商品
     */
    public function getProduct()
    {
        return $this->hasOne(\Yii::$app->product::className(), ['lID' => 'ProductID']);
    }

    public function all()
    {
        return static::find()->indexBy('SecKillProductID')->all();
    }

    /**
     * 关联秒杀活动
     */
    public function getSecKill()
    {
        return $this->hasOne(SecKill::className(), ['lID' => 'SecKillID']);
    }

    /**
     * 获取数据
     */
    public function getArrData()
    {
        //今天的秒杀
        $dTodayStart = \Yii::$app->formatter->asDate(time()) . " 00:00:00";//今天开始
        $dTodayEnd = \Yii::$app->formatter->asDate(time()) . " 23:59:59";//今天结束

        $dTomorrowStart = \Yii::$app->formatter->asDate(time() + 86400) . " 00:00:00";//明天开始
        $dTomorrowEnd = \Yii::$app->formatter->asDate(time() + 86400) . " 23:59:59";//明天结束

        $arrToday = [];
        $arrTomorrow = [];
        $arrData = static::find()->with('secKill')->with('product')->all();
        foreach ($arrData as $k => $data) {
            if (strtotime($data->secKill->dStartDate) <= strtotime($dTodayStart)
                && strtotime($data->secKill->dEndDate) >= strtotime($dTodayStart)
            ) {
                $arrToday[] = $data;
            } elseif (strtotime($data->secKill->dStartDate) >= strtotime($dTodayStart)
                && strtotime($data->secKill->dEndDate) <= strtotime($dTodayEnd)
            ) {
                $arrToday[] = $data;
            } elseif (strtotime($data->secKill->dStartDate) <= strtotime($dTodayEnd)
                && strtotime($data->secKill->dEndDate) >= strtotime($dTodayEnd)
            ) {
                $arrToday[] = $data;
            } elseif (strtotime($data->secKill->dStartDate) <= strtotime($dTodayStart)
                && strtotime($data->secKill->dEndDate) >= strtotime($dTodayEnd)
            ) {
                $arrToday[] = $data;
            }


            if (strtotime($data->secKill->dStartDate) >= strtotime($dTomorrowStart)
                && strtotime($data->secKill->dStartDate) <= strtotime($dTomorrowEnd)
            ) {
                $arrTomorrow[] = $data;
            }
        }

        //去掉已经结束的
        foreach ($arrToday as $k => $d) {
            if (strtotime($d->secKill->dEndDate) + 300 < time()) {
                unset($arrToday[$k]);
            }

            if ($d->product->bOffSale) {
                unset($arrToday[$k]);
            }

            if ($d->product->bDel) {
                unset($arrToday[$k]);
            }
        }

        foreach ($arrTomorrow as $k => $d) {
            if ($d->product->bOffSale) {
                unset($arrTomorrow[$k]);
            }

            if ($d->product->bDel) {
                unset($arrToday[$k]);
            }
        }

        return [$arrToday, $arrTomorrow];
    }
}