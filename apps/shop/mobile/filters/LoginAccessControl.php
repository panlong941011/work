<?php

namespace myerm\shop\mobile\filters;

use yii\base\ActionFilter;


/**
 * 是否登陆控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年12月18日 15:03:21
 * @version v1.3
 */
class LoginAccessControl extends ActionFilter
{
    public function beforeAction($action)
    {
        if (!\Yii::$app->frontsession->bLogin) {
            header("Location:".\Yii::$app->request->mallHomeUrl);
            exit;
        }

        return parent::beforeAction($action);
    }
}