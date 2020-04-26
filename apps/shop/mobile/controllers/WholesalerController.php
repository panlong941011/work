<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2018-08-08
 * Time: 上午 10:54
 */

namespace myerm\shop\mobile\controllers;

use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\VerifyCode;
use myerm\shop\mobile\models\Wholesaler;

class WholesalerController extends Controller
{
    /**
     * 成为渠道人员
     * @author hechengcheng
     * @time 2019/5/9 14:45
     */
    public function actionApplydesc()
    {
        $data['member'] = \Yii::$app->frontsession->member;
        $this->getView()->title = "成为渠道人员";
        return $this->render("applydesc", $data);
    }

    /**
     * 发送验证码
     * @param $mobile
     * @return \yii\web\Response
     */
    public function actionSendjoincode($mobile)
    {
        if (\Yii::$app->request->isPost) {
            return $this->asJson(VerifyCode::send($mobile));
        }
    }

    /**
     * 申请成为渠道人员
     * @author hechengcheng
     * @time 2019/5/9 16:41
     */
    public function actionApply()
    {
        if ($_POST['code'] != VerifyCode::getCodeByMobile($_POST['phone'])) {
            return $this->asJson(['status' => false, 'message' => '验证码不正确']);
        }

        $member = \Yii::$app->frontsession->member;
        if ($member->bActive) {
            return $this->asJson(['status' => false, 'message' => '您已经是渠道人员']);
        }

        $buyer = Buyer::find()->where(['sMobile' => $_POST['sSupplierPhone']])->one();
        if (!$buyer) {
            return $this->asJson(['status' => false, 'message' => '该手机号不是渠道商']);
        }

        $param = [
            'WholesalerID' => $member->lID,
            'sName' => $_POST['name'],
            'sMobile' => $_POST['phone'],
            'BuyerID' => $buyer->lID
        ];
        $res = Wholesaler::wholesalerUp($param);
        if ($res['status']) {
            $member->SupplierID = $buyer->lID;
            $member->PurchaseID = $buyer->lID;
            $member->save();
        }
        return $this->asJson($res);
    }

    public function actionChooserole()
    {
        return $this->render("chooserole");
    }
}