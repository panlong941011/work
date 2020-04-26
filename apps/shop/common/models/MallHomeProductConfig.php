<?php

namespace myerm\shop\common\models;


/**
 * 首页商品设置
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年10月4日 18:33:11
 * @version v1.0
 */
class MallHomeProductConfig extends ShopModel
{
    public static function updateCache()
    {
        return \Yii::$app->cache->delete("MallHomeProductConfig");
    }

    public function getProductDetail()
    {
        $arrProductID = [];
        $arrDetail = json_decode($this->sProductDetail, true);
        foreach ($arrDetail as $detail) {
            $arrProductID[] = $detail['ProductID'];
        }

        $arrProduct = \Yii::$app->product::findByIDs($arrProductID);
        foreach ($arrDetail as $i => $detail) {
            foreach ($arrProduct as $k => $product) {
                if ($product->lID == $detail['ProductID']) {
                    $arrDetail[$i]['product'] = $product;
                    unset($arrProduct[$k]);
                }
            }
        }

        foreach ($arrDetail as $i => $detail) {
            if (!$detail['product']) {
                unset($arrDetail[$i]);
            }
        }

        return $arrDetail;
    }
}