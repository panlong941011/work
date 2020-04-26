<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\Controller;
use myerm\backend\system\models\SysUser;
use Yii;

/**
 * 登陆、退出控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2015-12-10 10:21
 * @version v2.0
 */
class LoginController extends Controller
{
    /**
     * 登陆页
     */
    public function actionHome()
    {
        if (Yii::$app->request->isPost) { // 登陆提交
            $sysUser = SysUser::checkLogin($_POST['sLoginName'], $_POST['sPassword']);
            if ($sysUser === false) {

                $data = [];
                $data['sErrMsg'] = "用户名和密码不匹配";
                $data['sLoginName'] = $_POST['sLoginName'];

                return $this->renderPartial('home', $data);
            } elseif (!$sysUser->bActive) {

                $data = [];
                $data['sErrMsg'] = "用户已被禁用";
                $data['sLoginName'] = $_POST['sLoginName'];

                return $this->renderPartial('home', $data);

            } else {

                if($sysUser->SysRoleID==3&&!$sysUser->bPromise){
                    $data = [];
                    $data['SysUserID'] = $sysUser->lID;
                    $data['status'] = 'check';
                    return $this->renderPartial('home', $data);
                }
                elseif ($sysUser->SysRoleID==11&&!$sysUser->bPromise){
                    //试用协议
                    $data = [];
                    $data['SysUserID'] = $sysUser->lID;
                    $data['status'] = 'checkqd';
                    return $this->renderPartial('home', $data);
                }
                else{
                    Yii::$app->backendsession->login($sysUser);
                    return $this->redirect(Yii::$app->getHomeUrl() . "/system/frame/home");
                }

            }
        } else {
            // 如果已经登陆了，跳转到首页
            if (Yii::$app->backendsession->blogin) {
                return $this->redirect(Yii::$app->getHomeUrl() . "/system/frame/home");
            }

            return $this->renderPartial('home');
        }
    }
    /**
     * 协议验证
     */
    public function actionCheck(){
        if (Yii::$app->request->isPost) {
            $syxy=$_POST['syxy'];
            if($syxy){
                if($syxy!=1){
                    return  $this->asJson(['status'=>1001,'请选择已阅读有链科技有限公司供应链服务试用协议']);
                }
            }
            else{
                $xy=$_POST['xy'];
                $gz=$_POST['gz'];
                if($xy!=1||$gz!=1){
                    return  $this->asJson(['status'=>1001,'请选择有链供货商管理规则和有链供货商入驻服务协议']);
                }
            }

            $sysUser = SysUser::findOne($_POST['SysUserID']);
            $sysUser->bPromise=1;
            $sysUser->save();
            Yii::$app->backendsession->login($sysUser);
            return  $this->asJson(['status'=>1000,'登录成功']);
        }
    }
    /**
     * 退出
     */
    public function actionLogout()
    {
        Yii::$app->backendsession->logout();
        return $this->redirect(Yii::$app->getHomeUrl() . "/system/login/home");
    }


    /**
     * 过期，退出
     */
    public function actionExpire()
    {
        if (Yii::$app->backendsession->blogin) {
            return $this->redirect(Yii::$app->getHomeUrl() . "/system/frame/home");
        } else {
            return $this->renderPartial('expire');
        }
    }
}