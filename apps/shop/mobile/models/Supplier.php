<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 6:07
 */

namespace myerm\shop\mobile\models;
use myerm\shop\common\models\Member;


/**
 * 供应商类
 */
class Supplier extends \myerm\shop\common\models\Supplier
{
	public function getSupplierByMobile($sMobile)
	{
		return static::findOne(['sMobile' => $sMobile]);
	}
	
	public function getArrDetailProduct()
	{
		return \Yii::$app->product->getSupplierLastProduct($this->lID);
	}

	public function getMember(){
        return $this->hasOne(Member::className(), ['lID' => 'MemberID']);
    }

}