<?php

namespace myerm\shop\common\models;


/**
 * 经销商统计分析
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年12月14日 14:38:45
 * @version v1.0
 */
class SellerStats extends ShopModel
{
    public function teamList($config)
    {
        $sKeyword = $config['sKeyword'];
        $lPage = intval($config['lPage']) > 1 ? intval($config['lPage']) : 1;

        $search = static::find();

        if ($config['sKeyword']) {
            $config['sType'] = '全部';
            $config['sSellType'] = '全部';
        }

        if ($config['sType'] == '全部') {
            $search->andWhere(['OR', ['lID' => \Yii::$app->frontsession->MemberID], ['UpID' => \Yii::$app->frontsession->MemberID], ['UpUpID' => \Yii::$app->frontsession->MemberID]]);
        } else {
            $search->andWhere(['UpID' => \Yii::$app->frontsession->MemberID]);
        }

        if ($config['sSellType'] && $config['sSellType'] != "全部") {
            $search->andWhere(['sSellerType' => $config['sSellType']]);
        }

        if ($config['sKeyword']) {
            $search->andWhere("sName LIKE '%" . addslashes($config['sKeyword']) . "%' OR sMobile LIKE '%" . addslashes($config['sKeyword']) . "%'");
        }

        $lCount = $search->count();

        $search->offset(($lPage - 1) * 10)->limit(10);
        $search->orderBy($config['sOrderBy'] . " " . $config['sOrderByDir']);

        return [$lCount, $search->all()];
    }

    /**
     * 计算环比
     */
    public function getSConsecutive()
    {
        if (!$this->fSaleBeforeLastMonth && !$this->fSaleLastMonth) {
            return '--';
        } elseif (!$this->fSaleLastMonth) {
            return "100";
        } elseif ($this->fSaleLastMonth == $this->fSaleLastMonth) {
            return '--';
        } else {
            return ceil(100 * ($this->fSaleLastMonth - $this->fSaleBeforeLastMonth) / $this->fSaleBeforeLastMonth);
        }
    }

    /**
     * 获取头像
     */
    public function getAvatar()
    {
        if ($this->sAvatar) {
            return $this->sAvatar;
        } else {
            return "/images/order/person.png";
        }
    }
}