<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 3:59
 */

namespace myerm\shop\mobile\models;


/**
 * 收获地址
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明
 * @since 2017年10月21日 10:33:43
 * @version v2.0
 */
class MemberAddress extends \myerm\shop\common\models\Area
{
    /**
     * 新建收货地址
     * @param $data
     */
    public function newAddress($data)
    {
        $address = new static();
        $address->sName = $data['sName'];
        $address->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $address->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $address->MemberID = \Yii::$app->frontsession->MemberID;

        $province = \Yii::$app->area->getProvinceByName($data['sProvince']);
        $address->ProvinceID = $province->ID;

        $city = \Yii::$app->area->getCityByName($data['sProvince'], $data['sCity']);
        $address->CityID = $city->ID;

        $area = \Yii::$app->area->getAreaByName($data['sProvince'], $data['sCity'], $data['sArea']);
        $address->AreaID = $area->ID;

        $address->sAddress = $data['sAddress'];
        $address->sMobile = $data['sMobile'];

        if (static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID, 'bDefault' => 1])->count() == 0) {
            $address->bDefault = 1;
        }

        $address->save();

        return $address->lID;
    }

    /**
     * 编辑收货地址
     * @param $data
     */
    public function editAddress($data)
    {
        $address = self::findOne(['lID' => $data['lID'], 'MemberID' => \Yii::$app->frontsession->MemberID]);
        if (!$address) {
            return false;
        }

        $address->sName = $data['sName'];
        $address->dEditDate = \Yii::$app->formatter->asDatetime(time());

        $province = \Yii::$app->area->getProvinceByName($data['sProvince']);
        $address->ProvinceID = $province->ID;

        $city = \Yii::$app->area->getCityByName($data['sProvince'], $data['sCity']);
        $address->CityID = $city->ID;

        $area = \Yii::$app->area->getAreaByName($data['sProvince'], $data['sCity'], $data['sArea']);
        $address->AreaID = $area->ID;

        $address->sAddress = $data['sAddress'];
        $address->sMobile = $data['sMobile'];

        if (static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])->count() == 0) {
            $address->bDefault = 1;
        }

        $address->save();

        return true;
    }

    public function setDef($id)
    {
        static::updateAll(['bDefault' => 0], ['MemberID' => \Yii::$app->frontsession->MemberID]);
        return static::updateAll(['bDefault' => 1], ['lID' => $id]);
    }

    public function getDefAddress()
    {
        return static::findOne(['MemberID' => \Yii::$app->frontsession->MemberID, 'bDefault' => 1]);
    }

    public function del($id)
    {
        $address = self::findOne(['lID' => $id, 'MemberID' => \Yii::$app->frontsession->MemberID]);
        if ($address->bDefault) {
            $address->delete();

            $arrAddress = $this->findAllAddress();
            foreach ($arrAddress as $address) {
                $address->bDefault = 1;
                $address->save();
            }
        } else {
            $address->delete();
        }

        return true;
    }

    public function findAllAddress()
    {
        return static::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])->orderBy("dEditDate DESC")->all();
    }

    public function getProvince()
    {
        return $this->hasOne(\myerm\shop\common\models\Area::className(), ['ID' => 'ProvinceID']);
    }

    public function getCity()
    {
        return $this->hasOne(\myerm\shop\common\models\Area::className(), ['ID' => 'CityID']);
    }

    public function getArea()
    {
        return $this->hasOne(\myerm\shop\common\models\Area::className(), ['ID' => 'AreaID']);
    }

}