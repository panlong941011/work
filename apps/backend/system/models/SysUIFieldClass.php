<?php

namespace myerm\backend\system\models;

/**
 * 系统对象模型-界面配置的信息块模型
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-5 10:15
 * @version v2.0
 */
class SysUIFieldClass extends \myerm\backend\system\models\Model
{
    public function getFields()
    {
        return $this->hasMany(SysUIFieldClassField::className(), ['SysUIFieldClassID'=>'ID'])->with('field')->orderBy('lGroup,lPos');
    }
}
