<?php

use yii\db\Migration;

/**
 * Class m180821_005330_upload_sDesc_to_SysObject
 */
class m180821_005330_update_sDesc_to_SysObject extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->execute("UPDATE SysObject SET sDesc='在线打印电子面单，需要打印控件，<a 
href=\'http://myerm.laisanjin.cn/upload1/clodop/CLodop_Setup_for_Win32NT.rar\'>点击下载安装</a>' WHERE sObjectName='Shop/Order'");
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {

    }
}
