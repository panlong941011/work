<?php

namespace myerm\shop\common\models;


/**
 * 渠道人员管理类
 * Class Wholesaler
 * @package myerm\shop\common\models
 * @author hechengcheng
 * @time 2019/5/9 18:36
 */
class Wholesaler extends ShopModel
{

	/**
	 * 关联供应商
	 * @return \yii\db\ActiveQuery
	 * @author hechengcheng
	 * @time 2019/5/13 9:17
	 */
	public function getSupplier()
	{
		return $this->hasOne(Supplier::className(), ['lID' => 'SupplierID']);
	}
	
	/**
	 * 启用渠道人员
	 * @param $ID
	 * @author hechengcheng
	 * @time 2019/5/13 9:22
	 */
	public function enable($ID)
	{
		$wholesaler = self::findByID($ID);
		$wholesaler->bActive = 1;
		$wholesaler->save();
		
		$Member = MemberWholesaler::findOne($ID);
		$Member->bActive = 1;
		$Member->save();
	}
	
	/**
	 * 禁用渠道人员
	 * @param $ID
	 * @author hechengcheng
	 * @time 2019/5/13 9:22
	 */
	public function disable($ID)
	{
		$wholesaler = self::findByID($ID);
		$wholesaler->bActive = 0;
		$wholesaler->save();
		
		$Member = MemberWholesaler::findByID($ID);
		$Member->bActive = 0;
		$Member->save();
	}
	/**
	 * 升级为渠道人员
	 * @param $param
	 * @return array
	 * @throws \yii\base\InvalidConfigException
	 * @author hechengcheng
	 * @time 2019/5/9 17:49
	 */
	public function wholesalerUp($param)
	{
		$wholesaler = \Yii::$app->wholesaler->findByID($param['WholesalerID']);
		
		if($wholesaler){
			return ['status' => false, 'message' => '请不要重复申请'];
		}
		
		if($wholesaler->bActive){
			return ['status' => false, 'message' => '您已经是渠道人员'];
		}
		
		//创建渠道人员账户
		$wholesaler = new static();
		$wholesaler->lID = $param['WholesalerID'];
		$wholesaler->sName = $param['sName'];
		$wholesaler->sMobile = $param['sMobile'];
		$wholesaler->SupplierID = $param['SupplierID'];
		$wholesaler->dNewDate = \Yii::$app->formatter->asDatetime(time());
		$wholesaler->bActive = 0;
		$wholesaler->save();
		
		//保存渠道平台人员信息
		$member = Member::findOne(['lID' => $param['WholesalerID']]);
		$member->SupplierID = $param['SupplierID'];
		$member->PurchaseID = Supplier::findOne(['lID' => $param['SupplierID']])->BuyerID;
		$member->save();
		
		return ['status' => true, 'message' => '申请成功，等待审核'];
	}
}