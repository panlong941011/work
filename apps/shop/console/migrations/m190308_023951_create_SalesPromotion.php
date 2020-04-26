<?php

use yii\db\Migration;

/**
 * Class m190308_023951_create_SalesPromotion
 */
class m190308_023951_create_SalesPromotion extends Migration
{
	/**
	 * 创建促销活动表
	 * @return bool|void
	 * @author hechengcheng
	 * @time 2019/3/12 9:14
	 */
    public function up()
    {
	    $this->createTable('SalesPromotion', [
		    'lID' => $this->primaryKey(),
		    'sName' => $this->string()->comment('活动名称'),
		    'OwnerID' => $this->integer()->comment('拥有者'),
		    'NewUserID' => $this->integer()->comment('新建人'),
		    'EditUserID' => $this->integer()->comment('编辑人'),
		    'dNewDate' => $this->dateTime()->comment('新建时间'),
		    'dEditDate' => $this->dateTime()->comment('编辑时间'),
		    'TypeID' => $this->string(32)->comment('促销方式 (限时抢购:promotion_time;限量抢购:promotion_quantity;秒杀:promotion_seckill)'),
		    'PositionID' => $this->string(500)->comment('推广渠道'),
		    'dStart' => $this->dateTime()->comment('开始时间'),
		    'dEnd' => $this->dateTime()->comment('结束时间'),
		    'bActive' => $this->integer()->comment('是否开启'),
		    'PurchaseID' => $this->string(32)->comment('限购类型 (账户:purchase_account)'),
		    'lPurchase' => $this->integer()->comment('限购数量'),
		    'sAd' => $this->string(500)->comment('广告语'),
		    'sAdUrl' => $this->string(500)->comment('广告链接'),
		    'sProduct' => $this->string(500)->comment('商品名称'),
		    'lSort' => $this->integer()->comment('排序'),
		    'bSendMsg' => $this->integer()->comment('是否已推送消息'),
		    'LSJID' => $this->integer()->comment('来三斤ID'),
	    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
	    $this->dropTable('SalesPromotion');
    }
    
}
