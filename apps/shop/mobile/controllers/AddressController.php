<?php

namespace myerm\shop\mobile\controllers;


/**
 * 购物流程中的地址管理
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明
 * @time 2017年10月21日 10:04:50
 * @version v1.0
 */
class AddressController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionList()
    {
        $data = [];

        $data['arrAddress'] = \Yii::$app->memberaddress->findAllAddress();
        $data['sFrom'] = $_GET['from'];
        
        $this->getView()->title = "地址选择";

        return $this->render("list", $data);
    }

    public function actionSetdef($id)
    {
        \Yii::$app->memberaddress->setDef($id);

        $data = [];
        $data['status'] = true;
        $data['message'] = "";

        return $this->asJson($data);
    }

    public function actionDel()
    {
        \Yii::$app->memberaddress->del($_POST['id']);

        $data = [];
        $data['status'] = true;
        $data['message'] = "";

        return $this->asJson($data);
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isPost) {

            $postData = [
                'lID' => $_POST['addressid'],
                'sName' => $_POST['name'],
                'sMobile' => $_POST['mobile'],
                'sProvince' => explode(",", $_POST['area'])[0],
                'sCity' => explode(",", $_POST['area'])[1],
                'sArea' => explode(",", $_POST['area'])[2],
                'sAddress' => $_POST['address']
            ];

            $data = [];
            if (\Yii::$app->memberaddress->editAddress($postData) === false) {
                $data['status'] = false;
                $data['message'] = "编辑的收货地址不存在";
            } else {
                $data['status'] = true;
                $data['message'] = "";
            }

        } else {
            $data = [];

            $address = \Yii::$app->memberaddress->findByID($_GET['addressid']);
            if (!$address) {
                $data['status'] = false;
                $data['message'] = "编辑的收货地址不存在";
            } elseif ($address->MemberID != \Yii::$app->frontsession->MemberID) {
                $data['status'] = false;
                $data['message'] = "非法操作";
            } else {
                $data['status'] = true;
                $data['name'] = $address->sName;
                $data['mobile'] = $address->sMobile;
                $data['area'] = $address->province->sName . "," . $address->city->sName . "," . $address->area->sName;
                $data['address'] = $address->sAddress;
            }
        }


        return $this->asJson($data);
    }

    public function actionNew()
    {
        $data = [
            'sName' => $_POST['name'],
            'sMobile' => $_POST['mobile'],
            'sProvince' => explode(",", $_POST['area'])[0],
            'sCity' => explode(",", $_POST['area'])[1],
            'sArea' => explode(",", $_POST['area'])[2],
            'sAddress' => $_POST['address']
        ];

        $AddressID = \Yii::$app->memberaddress->newAddress($data);

        $data = [];
        $data['status'] = true;
        $data['addressid'] = $AddressID;
        $data['message'] = "";

        return $this->asJson($data);
    }

}