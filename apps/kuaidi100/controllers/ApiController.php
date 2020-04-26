<?php


namespace myerm\kuaidi100\controllers;

use myerm\common\controllers\MyERMController;
use myerm\kuaidi100\models\ExpressTrace;

/**
 * 快递接口
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明
 * @time 2017年10月27日 23:32:51
 * @version v1.0
 */
class ApiController extends MyERMController
{
    //关闭csrf验证
    public $enableCsrfValidation = false;

    /**
     * 实时查询
     * @param $ExpressCompanyID
     * @param $sNo
     */
    public function actionQuery($ExpressCompanyID, $sNo)
    {
        return (new ExpressTrace())->query($ExpressCompanyID, $sNo);
    }

    /**
     * 订阅快递信息
     */
    public function actionPost($ExpressCompanyID, $sNo)
    {
        $return = [];

        //参数不完整
        if (empty($ExpressCompanyID) || empty($sNo)) {
            $return['status'] = false;
            $return['message'] = "参数不完整";
            return $this->asJson($return);
        }

        $return['status'] = (new ExpressTrace())->poll([
            'sNo' => $sNo,
            'ExpressCompanyID' => $ExpressCompanyID,
        ]);

        return $this->asJson($return);
    }

    /**
     * 快递100回调
     */
    public function actionNotify()
    {
        \Yii::info('快递100回调');
        \Yii::info($_POST, '快递100参数');
        $param = \Yii::$app->request->post('param');
        \Yii::info($param, '快递100参数');

        $param = str_replace('\'', '', $param);
        $param = htmlspecialchars_decode($param);
        $param = json_decode($param, true);

        (new ExpressTrace())->saveNotifyData($param);

        return json_encode([
            'result' => true,
            'returnCode' => '200',
            'message' => '成功'
        ]);
    }

    public function actionNofity()
    {
        return $this->actionNotify();
    }
}