<?php

namespace myerm\shop\common\models;

use myerm\common\components\Func;
use myerm\shop\mobile\models\AlliaceCat;

/**
 * 顶级代理店铺商品类
 */
class AgentShopProduct extends ShopModel
{
    const RECOMMEND = 1;//推荐
    const CANCLE = -1;//已取消推荐
    const UNRECOMMEND = 0;//未推荐

    const SALE = 1;//上架中
    const UNSALE = 0;//已下架

    /**
     * 关联商品
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['lID' => 'ProductID']);
    }

    /**
     * 判断商品是否已经进货
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:31
     */
    public static function bSelect($arr)
    {
        $log = self::find()
            ->where(['and',
                ['MemberID' => $arr['MemberID']],
                ['ProductID' => $arr['ID']]
            ])
            ->one();

        if ($log && $log->StatusID == self::SALE) {
            return true;
        }
        return false;
    }

    /**
     * 保存记录
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:25
     */
    public static function saveData($arr)
    {
        $log = self::find()
            ->where(['and', ['MemberID' => $arr['MemberID']], ['ProductID' => $arr['ProductID']]])
            ->one();

        if (!$log) {
            $log = new self();
            $log->dNewDate = Func::getDate();
            $log->MemberID = $arr['MemberID'];
            $log->ProductID = $arr['ProductID'];
        }

        $log->bRecommend = $arr['bRecommend'];
        $log->dEditDate = Func::getDate();
        $log->StatusID = self::SALE;
        $log->CatID = $arr['CatID'];
        $log->save();
    }

    /**
     * 判断商品是否已经进货
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:31
     */
    public static function bRecommend($arr)
    {
        $log = self::find()
            ->where(['and',
                ['MemberID' => $arr['MemberID']],
                ['ProductID' => $arr['ID']]
            ])
            ->one();

        if ($log && $log->bRecommend == self::RECOMMEND) {
            return true;
        }
        return false;
    }


    /**
     * 关联商品
     */
    public function getCat()
    {
        return $this->hasOne(AlliaceCat::className(), ['CatID' => 'CatID']);
    }

}