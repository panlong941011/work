<?php

namespace myerm\backend\objectbuilder\controllers;

use Yii;
use myerm\backend\system\models\SysObject;

/**
 * 对象管理器的控制器基类，主要用于判断客户端连接是否有权限
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-2 14:25
 * @version v2.0
 */
class Controller extends \myerm\backend\common\controllers\Controller
{
	/*
	 * 状态，1表示操作成功
	 */
	public $lStatus = 1;
	
	/**
	 * 错误信息
	 * @var String
	 */
	public $sErrMsg = "";
	
	/**
	 * 关闭csrf验证
	 * @var boolean
	 */
	public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        $arrPart = explode("/", Yii::$app->request->pathInfo);

        // 设置模块名称
        $this->sModule = strtolower($arrPart[0]);
        Yii::trace("设置模块名称：" . $this->sModule);

        // 设置对象的名称
        $this->sSysObjectID = strtolower($arrPart[1]);
        Yii::trace("设置对象的名称：" . $this->sSysObjectID);

        // 设置对象的完整路径
        $this->sObjectName = $this->sModule . '/' . $this->sSysObjectID;

        //设置语言
        \Yii::$app->language = Yii::$app->backendsession->sysuser->LanguageID ? Yii::$app->backendsession->sysuser->LanguageID : 'cn-ZH';

        return true;
    }


	public function output($arrData)
	{
		$arrData['status'] = $this->lStatus;
		$arrData['message'] = $this->sErrMsg;

		echo json_encode($arrData);
		exit;
	}
}
