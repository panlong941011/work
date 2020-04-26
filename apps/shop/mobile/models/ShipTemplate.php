<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 3:54
 */

namespace myerm\shop\mobile\models;

/**
 * 运费模板类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 欧阳燕忠  <oyyz@3elephant.com>
 * @since 2017-10-7 16:01:15
 * @version v2.0
 */
class ShipTemplate extends \myerm\shop\common\models\ShipTemplate
{
    /* oyyz 2018-5-8 10:00:07 */
    //运送方式
    const TYPE_EXPRESS = 'EXPRESS';//普通快递
    const TYPE_EMS = 'EMS';//EMS
    const TYPE_POSTAGE = 'POSTAGE';//平邮

    //计价方式
    const VALUATION_NUMBER = 'Number'; //件
    const VALUATION_WEIGHT = 'Weight'; //KG
    const VALUATION_VOLUME = 'Volume'; //m³

    const VALUATION_NUMBER_NAME = '件'; //件
    const VALUATION_WEIGHT_NAME = 'KG'; //KG
    const VALUATION_VOLUME_NAME = 'm³'; //m³

    /**
     * 计算运费
     * @param array $param
     *
     * //参数说明
     * $param['ProvinceName'] 省份 (必需)
     * $param['CityName'] 城市 (必需)
     * $param['ShipTemplateID'] 运费模板ID (必需)
     * $param['ShipMethod'] 运送方式 默认EXPRESS (非必需)
     * $param['Number'] 商品数量 (必需)
     * $param['fTotalMoney'] 商品总金额 (必需)
     *
     * @return array
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-8 11:02:50
     */
    public static function getShipCount($param = [])
    {
        //运送方式
        $ShipMethod = 'EXPRESS';
        //运费模板ID
        $ShipTemplateID = $param['ShipTemplateID'];

        //返回数据初始化
        $ShipMoney = 0.00;
        $result = [];
        $result['status'] = 1;

        if (!$param['CityName'] && !$param['CityID'] || !$param['ShipTemplateID'] || !$param['Number'] || !$param['fTotalMoney']) {
            $result['status'] = -1;
            $result['msg'] = "地址信息缺失";
            return $result;
        }

        //获取运费模板
        $arrShipTemplate = ShipTemplate::find()
            ->where(["lID" => $ShipTemplateID])
            ->asArray()
            ->one();

        if (!$arrShipTemplate) {
            $result['status'] = -1;
            $result['msg'] = "运费模板不存在";
            return $result;
        }


        //获取城市数据
        if ($param['CityID']) {
            $City = \Yii::$app->area::findByID($param['CityID']);
        } else {
            $City = \Yii::$app->area->getCityByName($param['ProvinceName'], $param['CityName']);
        }


        //计量单位
        switch ($arrShipTemplate['sValuation']) {
            case "Number":
                $unitName = "件";
                break;
            case "Weight":
                $unitName = "KG";
                //商品设置为g，需要转换为KG
                $param['Number'] = $param['Weight'] / 1000;
                break;
            case "Volume":
                $unitName = "m³";
                break;
            default :
                $unitName = "件";
                break;
        }

        //判断该地区是否发货
        $NoDelivery = ShipTemplateNoDelivery::find()
            ->select('lID')
            ->where(['ShipTemplateID' => $ShipTemplateID])
            ->andWhere(['like', 'sAreaID', $City['ID']])
            ->asArray()
            ->one();
        if ($NoDelivery) {
            $result['status'] = -1;
            $result['msg'] = "不发货";
            return $result;
        }
        //判断指定地区是否存在
        $ShipTemplateDetailSet = ShipTemplateDetail::find()
            ->where(['ShipTemplateID' => $ShipTemplateID, 'sShipMethod' => $ShipMethod, 'sType' => 'designatedArea'])
            ->andWhere(['like', 'sAreaID', ',' . $City['ID'] . ','])
            ->asArray()
            ->one();
        if ($ShipTemplateDetailSet) {
            //计算指定地区运费
            $ShipTemplateDetailSet['countBuy'] = $param['Number'];
            $ShipMoney = self::getShipMoney($ShipTemplateDetailSet);
        } else {
            //计算默认运费
            $defaultShip = ShipTemplateDetail::find()
                ->where(['ShipTemplateID' => $ShipTemplateID, 'sShipMethod' => $ShipMethod, 'sType' => 'default'])
                ->asArray()
                ->one();

            if ($defaultShip) {
                $defaultShip['countBuy'] = $param['Number'];
                $ShipMoney = self::getShipMoney($defaultShip);
            } else {
                $result['status'] = -1;
                $result['msg'] = "默认运费不存在";
            }
        }

        //指定条件包邮运费
        if ($arrShipTemplate['bSetFree'] == '1') {
            $arrShipTemplateFree = ShipTemplateFree::find()
                ->select('lFreeType,fFreeNumber,fFreeMoney')
                ->where(['ShipTemplateID' => $ShipTemplateID, 'sFreeShipMethod' => $ShipMethod])
                ->andWhere(['like', 'sFreeAreaID', ',' . $City['ID'] . ','])
                ->asArray()
                ->one();

            //判断商品是否满足指定区域
            if ($arrShipTemplateFree) {
                if ($arrShipTemplate['sValuation'] == 'Number') {
                    $arrShipTemplateFree['fFreeNumber'] = intval($arrShipTemplateFree['fFreeNumber']);
                }
                if ($arrShipTemplateFree['lFreeType'] == '0') {
                    if ($param['Number'] >= $arrShipTemplateFree['fFreeNumber']) {
                        $ShipMoney = 0;
                        if ($arrShipTemplate['sValuation'] == 'Number' && intval($arrShipTemplateFree['fFreeNumber']) == '1') {

                        } else {
                            $result['status'] = 0;
                            $result['msg'] = "满" . $arrShipTemplateFree['fFreeNumber'] . $unitName . "包邮";
                        }
                    }
                } elseif ($arrShipTemplateFree['lFreeType'] == '1') {
                    if ($param['fTotalMoney'] >= $arrShipTemplateFree['fFreeMoney']) {
                        $ShipMoney = 0;
                        $result['status'] = 0;
                        $result['msg'] = "满" . $arrShipTemplateFree['fFreeMoney'] . "元包邮";
                    }
                } elseif ($arrShipTemplateFree['lFreeType'] == '2') {
                    if (($param['Number'] >= $arrShipTemplateFree['fFreeNumber']) && ($param['fTotalMoney'] >= $arrShipTemplateFree['fFreeMoney'])) {
                        $ShipMoney = 0;
                        $result['status'] = 0;
                        $result['msg'] = "满" . $arrShipTemplateFree['fFreeNumber'] . $unitName . "," . $arrShipTemplateFree['fFreeMoney'] . "元以上 包邮";
                    }
                }
            }
        }
        if (!$ShipMoney && !$result['msg']) {
            $result['status'] = 0;
            $result['msg'] = '免运费';
        }

        $ShipMoney = number_format($ShipMoney, 2, '.', '');

        $result['fShipMoney'] = $ShipMoney;
        return $result;
    }

    /**
     * 计算某个条件下的运费金额
     * @param array $param
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-8 11:40:29
     */
    public static function getShipMoney($param = [])
    {
        //首件运费
        $ShipMoney = $param['fPostage'];
        //大于首件（进一法计算）
        if ($param['countBuy'] > $param['lStart']) {
            $eachCount = ceil(($param['countBuy'] - $param['lStart']) / $param['lPlus']);
            $ShipMoney += $eachCount * $param['fPostageplus'];
        }
        return $ShipMoney;
    }

    /**
     * 计算运费
     */
    public function computeShip($param)
    {
        return static::getShipCount($param);
    }

    /**
     * 计算商品的总的件数/重量/体积
     * @param array $param
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2018-5-2 09:56:18
     */
    public function carProCount($param = [])
    {
        $ShipTemplate = $param['ShipTemplate'];
        $Product = $param['Product'];

        //计量单位
        $result = 0;
        switch ($ShipTemplate->sValuation) {
            case ShipTemplate::VALUATION_NUMBER:
                $result = $param['lQuantityTotal'];
                break;
            case ShipTemplate::VALUATION_WEIGHT:
                $result = $param['lQuantityTotal'] * $Product->lWeight;
                break;
            case ShipTemplate::VALUATION_VOLUME:
                $result = $param['lQuantityTotal'] * $Product->fVolume;
                break;
            default:
                $result = $param['lQuantityTotal'];
        }
        return $result;

    }
}