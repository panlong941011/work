<?php

namespace myerm\shop\common\models;


/**
 * 金币明细
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2018年1月24日 10:49:28
 * @version v1.5
 */
class GoldFlow extends ShopModel
{
    /**
     * 明细变化
     * @param $data
     */
    public function change($data)
    {
        $member = \Yii::$app->member->findByID($data['MemberID']);

        $flow = new static();
        $flow->sName = $data['sName'];
        $flow->TypeID = $data['TypeID'];
        $flow->MemberID = $data['MemberID'];
        $flow->NewUserID = $data['NewUserID'];
        $flow->fChange = $data['fChange'];
        $flow->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $flow->fChangeBefore = $member->computeGold();
        $flow->fChangeAfter = $member->computeGold() + $flow->fChange;

        if (isset($data['arrOrderID']) && is_array($data['arrOrderID'])) {
            $flow->sOrderID = ";" . implode(";", $data['arrOrderID']) . ";";
        }

        if (isset($data['OrderDetailID'])) {
            $flow->OrderDetailID = $data['OrderDetailID'];
        }

        $flow->save();

        $member->fGold = $flow->fChangeAfter;
        $member->save();
    }

    public function computeGold($MemberID)
    {
        return static::find()->where(['MemberID' => $MemberID])->sum('fChange');
    }

    public function listData($config)
    {
        $page = $config['page'] ? intval($config['page']) : 1;

        $flowSearch = static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID]);

        if ($config['type']) {
            $flowSearch->andWhere(['TypeID' => $config['type']]);
        }

        return $flowSearch->orderBy("dNewDate DESC")->limit(10)->offset(($page - 1) * 10)
            ->all();
    }
}