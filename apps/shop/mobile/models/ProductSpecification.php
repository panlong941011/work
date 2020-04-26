<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/8 0008
 * Time: 下午 3:41
 */

namespace myerm\shop\mobile\models;

/**
 * 商品规格
 * Class ProductSpecification
 * @package myerm\shop\mobile\models
 * @author oyyz <oyyz@3elephant.com>
 * @time 2017-10-8 15:42:21
 */
class ProductSpecification extends \myerm\shop\common\models\ProductSpecification
{
    /**
     * 获取商品规格组合数据
     * @param string $ProductID 商品ID
     * @param string $sMasterPic 商品主图
     * @return array
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-8 17:26:51
     */
    public function getArrSpec($ProductID, $sMasterPic = '')
    {
        $ProductSpec = ProductSpecification::find()
            ->where(['ProductID' => $ProductID])
            ->asArray()
            ->orderBy('lPos')
            ->all();
        if (!$ProductID || !$ProductSpec) {
            return [];
        }
        $o = 100;
        $arrSpec = []; //循环渲染数据
        $arrParamsID = [];//每个属性的ID
        foreach ($ProductSpec as $key => $value) {
            $arrValue = explode(';', $value['sValue']);
            $arrImage = explode(';', $value['sPic']);
            $arrID = [];
            foreach ($arrValue as $k => $v) {
                $id = $o + $k;
                $arrSpec[$value['sName']][$v] = ['id' => $id, 'value' => $v];
                if ($key == 0) {
                    $arrSpec[$value['sName']][$v]['image'] = $arrImage[$k] ? $arrImage[$k] : $sMasterPic;
                }
                $arrID[] = $id;
            }
            $arrParamsID[] = $arrID;
            $o += 100;
        }

        $sJsonID = json_encode($arrParamsID);
        $sJsonGroup = $this->getJsonGroup($ProductID, $arrSpec);
        $result = [
            'arrSpec' => $arrSpec,
            'arrSpecID' => $sJsonID,
            'sJsonGroup' => $sJsonGroup
        ];
        return $result;
    }

    /**
     * 处理规格的组合
     * @param string $ProductID
     * @param array $arrSpec
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-8 17:26:10
     */
    public function getJsonGroup($ProductID = '', $arrSpec = [])
    {
        //处理组合数据
        $SKU = ProductSKU::find()
            ->where(['ProductID' => $ProductID])
            ->asArray()
            ->all();
        foreach ($SKU as $item) {
            if (!$item['lStock']) {
                //continue;
            }
            $arrSkuValue = explode(';', $item['sValue']);
            $arrGroupKey = [];
            $image = '';
            foreach ($arrSkuValue as $s) {
                $s = explode(':', $s);
                $sku = $arrSpec[$s[0]][$s[1]]['id'];
                if ($arrSpec[$s[0]][$s[1]]['image']) {
                    $image = $arrSpec[$s[0]][$s[1]]['image'];
                }
                $arrGroupKey[] = $sku;
            }
            $sGroupKey = implode(';', $arrGroupKey);
            $arrGroup[$sGroupKey] = [
                'price' => $item['fPrice'],
                'count' => $item['lStock'],
                'title' => $item['sValue'],
                'image' => \Yii::$app->request->imgUrl . "/" . $image
            ];
        }
        $sJsonGroup = json_encode($arrGroup);
        return $sJsonGroup;
    }
}