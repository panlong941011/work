<?php

namespace myerm\backend\system\models;

/**
 * 系统对象模型-工作台方案模型
 * ============================================================================
 * 版权所有 2010-2016 厦门研途教育科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2016-1-12 16:24
 * @version v2.0
 */
class SysSolutionNavTab extends \myerm\backend\system\models\Model
{
    public function getItems()
    {
        return $this->hasMany(SysSolutionNavItem::className(), ['NavTabID' => 'ID'])->with('navitem')->orderBy('lPos');
    }
}
