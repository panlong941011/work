<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 3:59
 */

namespace myerm\shop\mobile\models;


/**
 * 地区类
 */
class Area extends \myerm\shop\common\models\Area
{
    /**
     * 通过名称获取某个城市
     * @param string $Name 城市名称
     * @return array|null|\yii\db\ActiveRecord
     * @author oyyz <oyyz@3elephant.com>
     * @modify 陈鹭明
     * @time 2017-10-8 11:20:30
     */
    public function getCityByName($sProvince, $sCity)
    {
        $ProvinceID = static::find()->select(['ID'])->where(['sType' => 'Province', 'sName' => $sProvince])->scalar();
        return static::find()->where("UpID='$ProvinceID' AND sName LIKE '{$sCity}%'")->one();
    }

    /**
     * 通过省份名称获取数据
     * @param $sProvince
     * @author 陈鹭明
     * @time 2017年10月21日 11:07:47
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getProvinceByName($sProvince)
    {
        return static::find()->where(['sType' => 'Province', 'sName' => $sProvince])->one();
    }

    /**
     * 获取地区数据
     * @param $sProvince
     * @param $sCity
     * @param $sArea
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getAreaByName($sProvince, $sCity, $sArea)
    {
        $ProvinceID = static::find()->select(['ID'])->where(['sType' => 'Province', 'sName' => $sProvince])->scalar();
        $CityID = static::find()->where(['UpID' => $ProvinceID, 'sName' => $sCity])->one();

        return static::find()->where(['UpID' => $CityID, 'sName' => $sArea])->one();
    }


    /**
     * 查询ip地理信息（新浪接口）
     * @param string $IP
     * @return array
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-8 14:23:18
     */
    public function getIpLocation($IP = '')
    {
        $result = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=$IP");
        $result = json_decode($result, true);

        if ($result) {
            $data = [
                'sIP' => $IP,
                'sCountry' => $result['data']['country'],
                'sProvince' => static::findOne($result['data']['region_id'])->sName,
                'sCity' => static::findOne($result['data']['city_id'])->sName,
            ];
        }
        return $data;
    }


}