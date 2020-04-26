<?php

namespace myerm\shop\common\models;


/**
 * 供应商类
 */
class Supplier extends ShopModel
{
	/**
	 * 配置数据源
	 * @return null|object|\yii\db\Connection
	 * @throws \yii\base\InvalidConfigException
	 * @author hechengcheng
	 * @time 2019/5/9 17:05
	 */
	public static function getDb()
	{
		return \Yii::$app->get('ds_cloud');
	}
	
	public static $registerTree = [];
	
	/**
	 * 通过MemberID来查找供应商
	 */
	public static function findByMemberID($MemberID)
	{
		return static::find()->where(['MemberID' => $MemberID])->one();
	}
	
	/**
	 * 取大农云供应商id和来三斤供应商id对应关系
	 */
	public static function getDnyLsjRelation()
	{
		$actionName = \Yii::$app->controller->action->id;
		if (isset(self::$registerTree[$actionName])) {
			return self::$registerTree[$actionName];
		}
		$supplierList = self::find()->asArray()->all();
		$relation = [];
		if (!empty($supplierList)) {
			foreach ($supplierList as $supplier) {
				$lsjID = $supplier['LSJID'];
				$lID = $supplier['lID'];
				$relation[$lID] = $lsjID;
			}
		}
		self::$registerTree[$actionName] = $relation;
		return $relation;
	}

    /**
     *  统计供应账户钱包
     */
    public function computeAccountMoney($waitMoney)
    {
        $SupplierID = $this->lID;
        $this->fUnsettlement = $waitMoney;

        $withdrawnMoney =Withdraw::find()
            ->where(['SupplierID' => $SupplierID, 'CheckID' => 1])
            ->sum('fMoney');

        $this->fWithdrawed = $withdrawnMoney;
        $this->fSumIncome =DealFlow::find()
            ->where(['and', ['=', "SupplierID", $SupplierID], ["=", "TypeID", DealFlow::$TypeID['income']]])
            ->sum('fMoney');
        $this->save();

        return true;
    }

    public function getArrProduct()
    {
        return $this->hasMany(Product::className(), ['SupplierID' => 'lID']);
    }
}