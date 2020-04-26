<?php

namespace myerm\shop\mobile\controllers;

use myerm\common\libs\File;
use myerm\shop\backend\controllers\SellershopController;
use myerm\shop\common\models\Alliance;
use myerm\shop\common\models\Providerapply;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\SellerShop;
use myerm\shop\common\models\Supplierapply;
use myerm\shop\mobile\models\AlliaceCat;
use myerm\shop\mobile\models\AlliaceFun;
use myerm\shop\mobile\models\Product;
use myerm\shop\mobile\models\Supplier;
use yii\helpers\Url;


/**
 * 购物车
 */
class SupplierController extends Controller
{

    /**
     * 供应商详情页
     * @param $id
     */
    public function actionDetail($id)
    {
        $data = [];

        $data['supplier'] = \Yii::$app->supplier->findByID($id);
        $this->getView()->title = $data['supplier']->sName;

        return $this->render("detail", $data);
    }

    /**
     * 全部商品
     */
    public function actionList($id)
    {
        $data = [];
        $this->getView()->title = "全部商品";
        $data['data'] = $this->actionItem();

        return $this->render("list", $data);
    }

    public function actionItem()
    {
        //页码
        $lPageNo = intval($_GET['index']) ? intval($_GET['index']) : 1;

        $ret = \Yii::$app->product->getByConfig([
            'SupplierID' => $_GET['id'],
            'lPageNo' => $lPageNo
        ]);

        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['data'] = [];


        $arrProduct = $ret[0];
        foreach ($arrProduct as $product) {

            $secKillData = [];
            $secKill = $product->secKill;
            if ($secKill) {
                $secKillData = [
                    'start' => $secKill->dStartDate,
                    'end' => $secKill->dEndDate,
                    'stock' => $secKill->lStock,
                    'status' => $secKill->sStatus,
                ];
            }

            $data['data'][] = [
                'pic' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                'name' => $product->sName,
                'price' => number_format($product->fShowSalePrice, 2),
                'sellout' => $product->bSaleOut ? true : false,
                'seckill' => $secKillData,
                'icon' => $product->icon,
                'link' => Url::toRoute([\Yii::$app->request->shopUrl . "/product/detail", 'id' => $product->lID], true),
            ];
        }

        if ($lPageNo == 1 && $ret[1] <= 10) {
            $data['isMore'] = false;
        } elseif ($lPageNo == 1 && $ret[1] > 10) {
            $data['isMore'] = true;
        }

        return json_encode($data);
    }

    /**
     * 供应商入住
     */
    public function actionSupplierapply()
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET');
        if (empty($_GET['sName']) || empty($_GET['sTel'])) {
            return json_encode(['status' => 1001, 'msg' => '请完善输入信息']);
        }
        $supplierapply = new Supplierapply();
        $supplierapply->sName = $_GET['sName'];
        $supplierapply->sCompanyName = $_GET['sCompanyName'];
        $supplierapply->sTel = $_GET['sTel'];
        $supplierapply->sAddress = $_GET['sAddress'];
        $supplierapply->sDes = $_GET['sDes'];
        $supplierapply->sCat = $_GET['sCat'];
        $supplierapply->sMark = $_GET['sMark'];
        $supplierapply->dNewDate = date('Y-m-d H:i:s');
        if (\Yii::$app->frontsession->member) {
            $supplierapply->MemberID = \Yii::$app->frontsession->member->lID;
        }
        $supplierapply->save();
        return json_encode(['status' => 1000, 'msg' => '提交成功！']);
    }

    public function actionSupplier()
    {
        return $this->render("supplier");
    }

    public function actionProvider()
    {
        return $this->render("provider");
    }

    /**
     * 渠道商入住
     */
    public function actionProviderapply()
    {
        if (empty($_GET['sName']) || empty($_GET['sTel'])) {
            return json_encode(['status' => 1001, 'msg' => '请完善输入信息']);
        }
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET');
        $providerapply = new Providerapply();
        $providerapply->sName = $_GET['sName'];
        $providerapply->sCompanyName = $_GET['sCompanyName'];
        $providerapply->sTel = $_GET['sTel'];
        $providerapply->sAddress = $_GET['sAddress'];
        $providerapply->sDes = $_GET['sDes'];
        $providerapply->sCat = $_GET['sCat'];
        $providerapply->sMark = $_GET['sMark'];
        if (\Yii::$app->frontsession->member) {
            $providerapply->MemberID = \Yii::$app->frontsession->member->lID;
        }
        $providerapply->save();
        return json_encode(['status' => 1000, 'msg' => '提交成功！']);
    }

    //供应入驻
    public function actionSupplierreg()
    {
        if (\Yii::$app->request->isPost) {
            if (empty($_POST['sName']) || empty($_POST['sTel'])) {
                return json_encode(['status' => 0, 'msg' => '请完善输入信息']);
            }
            $supplierapply = new Supplierapply();
            $supplierapply->sName = $_POST['sName'];
            $supplierapply->sCompanyName = $_POST['sCompanyName'];
            $supplierapply->sTel = $_POST['sTel'];
            $supplierapply->sAddress = $_POST['sAddress'];
            $supplierapply->sDes = $_POST['sDes'];
            $supplierapply->sCat = $_POST['sCat'];
            $supplierapply->sMark = $_POST['sMark'];
            $supplierapply->bEshop = $_POST['bEshop'];
            $supplierapply->dNewDate = date('Y-m-d H:i:s');
            if (\Yii::$app->frontsession->member) {
                $supplierapply->MemberID = \Yii::$app->frontsession->member->lID;
            }

            $arrImg = [];
            if ($_POST['imglist']) {
                foreach ($_POST['imglist'] as $img) {
                    if (substr($img, 0, 5) == 'data:') {
                        $arrFileInfo = File::parseImageFromBase64($img);
                        $sFileName = date('YmdHis') . "." . $arrFileInfo[0];
                        $arrImg[] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                    } else {
                        $arrImg[] = str_ireplace(\Yii::$app->request->imgUrl . "/", "", $img);;
                    }
                }
            }

            $supplierapply->sLicense = json_encode($arrImg);
            $supplierapply->save();
            return json_encode(['status' => 1, 'msg' => '提交成功！']);

        } else {
            return $this->render("reg");
        }
    }

    //渠道入驻
    public function actionProviderreg()
    {
        if (\Yii::$app->request->isPost) {
            if (empty($_POST['sName']) || empty($_POST['sTel'])) {
                return json_encode(['status' => 0, 'msg' => '请完善输入信息']);
            }

            $providerapply = new Providerapply();
            $providerapply->sName = $_POST['sName'];
            $providerapply->sCompanyName = $_POST['sCompanyName'];
            $providerapply->sTel = $_POST['sTel'];
            $providerapply->sAddress = $_POST['sAddress'];
            $providerapply->sDes = $_POST['sDes'];
            $providerapply->sCat = $_POST['sCat'];
            $providerapply->sMark = $_POST['sMark'];
            if (\Yii::$app->frontsession->member) {
                $providerapply->MemberID = \Yii::$app->frontsession->member->lID;
            }

            $arrImg = [];
            if ($_POST['imglist']) {
                foreach ($_POST['imglist'] as $img) {
                    if (substr($img, 0, 5) == 'data:') {
                        $arrFileInfo = File::parseImageFromBase64($img);
                        $sFileName = date('YmdHis') . "." . $arrFileInfo[0];
                        $arrImg[] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                    } else {
                        $arrImg[] = str_ireplace(\Yii::$app->request->imgUrl . "/", "", $img);;
                    }
                }
            }
            $providerapply->dNewDate = date('Y-m-d H:i:s');
            $providerapply->sLicense = json_encode($arrImg);
            $providerapply->save();
            return json_encode(['status' => 1, 'msg' => '提交成功！']);

        } else {
            return $this->render("providerreg");
        }
    }

    //提交成功
    public function actionSuccess()
    {
        return $this->render("success");
    }

    //联盟商首页
    public function actionAlliance()
    {
        $data = [];
        //轮播图
        $data['arrScrollImage'] = json_decode(SellerShop::findOne(44)->sImg, true);
        //获取第一个商品列表配置
        $bTop = false;
        $vip = Seller::findOne(\Yii::$app->frontsession->MemberID);
        if ($vip && $vip->TypeID == 3) {
            $bTop = true;
        }
        $data['bTop'] = $bTop;
        $data['sItemJson'] = $this->actionItemsupplier(0);
        $this->getView()->title = '联盟商';
        return $this->render('index', $data);
    }

    //联盟商首页数据
    public function actionItemsupplier($index)
    {
        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['isMore'] = false;
        $data['data'] = [];

        $arrSupplier = Supplier::find()
            ->where(['>', 'MemberID', '0'])
            ->with('member')
            ->with(['arrProduct' => function ($query) {
                    $query->where(['bSale' => 1, 'bCheck' => 1]);
                }]
            )
            ->limit(10)
            ->offset(($index) * 10)
            ->orderBy('lFlag desc,fSumOrder desc,lProductNum desc')
            ->all();
        if ($arrSupplier) {
            $data['data']['commodity'] = [];
            foreach ($arrSupplier as $supplier) {
                $link = Url::toRoute([
                    \Yii::$app->request->shopUrl . "/supplier/supplierdetail",
                    'supplierID' => $supplier->lID
                ], true);
                $member = $supplier->member;
                $img = '/images/home/logo.jpg';
                if ($member) {
                    $img = $member->sAvatarPath;
                }
                $arrProductImg = [];
                if ($supplier->arrProduct) {
                    foreach ($supplier->arrProduct as $key => $product) {
                        if ($key > 2) {
                            break;
                        }
                        $arrProductImg[] = 'http://product.aiyolian.cn/' . $product->sMasterPic;
                    }
                }
                $fSumOrder = $supplier->fSumOrder;
                if ($fSumOrder > 10000) {
                    $fSumOrder = number_format($fSumOrder / 10000, 1) . '万';
                }
                $arrFlag = ['/images/home/flag.png', '/images/home/flag.png', '/images/home/flag.png'];
                if ($supplier->lFlag == 4) {
                    $arrFlag = ['/images/home/flag.png', '/images/home/flag.png', '/images/home/flag.png', '/images/home/flag.png'];
                } elseif ($supplier->lFlag == 5) {
                    $arrFlag = ['/images/home/flag.png', '/images/home/flag.png', '/images/home/flag.png', '/images/home/flag.png', '/images/home/flag.png'];
                }
                $checkImg = '/images/home/namechecked.png?11';
                if ($supplier->TypeID == 2) {
                    $checkImg = '/images/home/companychecked.png?3';
                }
                $data['data']['commodity'][] = [
                    'title' => $supplier->sName,
                    //'nickname' => '昵称：' . $member->sName, //昵称
                    'image' => $img,//头像
                    'checkImg' => $checkImg,//认证图片
                    'image' => $img,//星标
                    'lProductNum' => '商品个数：' . $supplier->lProductNum,//产品个数
                    'fSumOrder' => '销售额：' . $fSumOrder,//订单金额
                    'lFanNum' => '粉丝数量：' . $supplier->lFanNum,//粉丝数量
                    'fFanOrder' => '粉丝销售额：' . $supplier->fFanOrder,//粉丝销量额
                    'mainProduct' => '主营产品：' . $supplier->sProduct,//粉丝销量额
                    'link' => $link,
                    'arrProductImg' => $arrProductImg,
                    'arrFlag' => $arrFlag
                ];
            }
            $count = count($arrSupplier);
            if ($count > 0 && $count % 10 == 0) {
                $data['isMore'] = true;
            }

        } else {
            $data['isMore'] = false;
        }

        return json_encode($data);
    }

    //联盟商详情页
    public function actionSupplierdetail($supplierID)
    {
        $data = [];
        $suppler = Supplier::findOne($supplierID);
        $this->getView()->title = $suppler->sName;
        $shop = SellerShop::findOne($suppler->MemberID);

        if (empty($shop)) {
            $img = SellerShop::findOne(44)->sImg;
            $data['arrScrollImage'] = json_decode($img, true);
        } else {
            $data['arrScrollImage'] = json_decode($shop->sImg, true);
        }
        $bTop = 0;
        $seller = Seller::findOne(\Yii::$app->frontsession->MemberID);
        if ($seller && $seller->TypeID == 3) {
            $bTop = 1;
        }

        $data['bTop'] = $bTop;
        $data['suppler'] = $suppler;
        $data['shop'] = $shop;
        $data['arrProduct'] = Product::find()->where(['SupplierID' => $supplierID, 'bSale' => 1, 'bCheck' => 1])->asArray()->all();
        $AlliaceFunID = \Yii::$app->frontsession->member->SupplierID;
        $res = AlliaceFun::find()->where(['or', [
            'SupplierID' => $supplierID, 'AlliaceFunID' => $AlliaceFunID
        ], [
            'AlliaceFunID' => $supplierID, 'SupplierID' => $AlliaceFunID
        ]])->andWhere(['StatusID' => 1])->one();
        $bAlliaceFun = false;
        if ($res) {
            $bAlliaceFun = true;
        }
        $data['bAlliaceFun'] = $bAlliaceFun;
        return $this->render('supplierdetail', $data);
    }

    //联盟好友
    public function actionAlliancefun()
    {
        $data = [];
        $sItemJson = $this->getAlliaceFunData();
        $data['sItemJson'] = $sItemJson;
        return $this->render('alliancefun', $data);
    }

    //联盟好友数据
    public function getAlliaceFunData()
    {
        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['isMore'] = false;
        $data['data'] = [];
        //被邀请
        $supplierID = \Yii::$app->frontsession->member->SupplierID;
        if ($_GET['Type']) {
            $arrAlliaceFun = AlliaceFun::find()->select('SupplierID,AlliaceFunID')
                ->where(['or', ['AlliaceFunID' => $supplierID], ['SupplierID' => $supplierID]])
                ->andWhere(['StatusID' => 1])
                ->all();
            $arrSupplierID = array_column($arrAlliaceFun, 'SupplierID');
            $arrAlliaceFunID = array_column($arrAlliaceFun, 'AlliaceFunID');
            $arrID = array_merge($arrSupplierID, $arrAlliaceFunID);
        } else {
            $arrAlliaceFun = AlliaceFun::find()->select('SupplierID')->where(['AlliaceFunID' => $supplierID, 'StatusID' => 0])->all();
            $arrID = array_column($arrAlliaceFun, 'SupplierID');
        }

        $arrSupplier = Supplier::find()
            ->andWhere(['lID' => $arrID])
            ->with('member')->all();
        if ($arrSupplier) {
            $data['data']['commodity'] = [];
            foreach ($arrSupplier as $supplier) {
                $link = Url::toRoute([\Yii::$app->request->shopUrl . "/supplier/supplierdetail", 'supplierID' => $supplier->lID], true);
                $member = $supplier->member;
                $img = '/images/home/logo.jpg';
                if ($member) {
                    $img = $member->sAvatarPath;
                }
                $data['data']['commodity'][] = [
                    'title' => $supplier->sName,
                    'nickname' => '昵称：' . $member->sName, //昵称
                    'image' => $img,//头像
                    'lProductNum' => '商品个数：' . $supplier->lProductNum,//产品个数
                    'fSumOrder' => '产品销售额：' . $supplier->fSumOrder,//订单金额
                    'lFanNum' => '粉丝数量：' . $supplier->lFanNum,//粉丝数量
                    'fFanOrder' => '粉丝销售额：' . $supplier->fFanOrder,//粉丝销量额
                    'mainProduct' => '主营产品：' . $supplier->sProduct,//粉丝销量额
                    'ID' => $supplier->lID,//粉丝销量额
                    'link' => $link
                ];
            }
        } else {
            return false;
        }
        $data['isMore'] = false;
        return json_encode($data);
    }

    //联盟商 加好友
    public function actionAlliaceadd()
    {
        $supplierID = \Yii::$app->frontsession->member->SupplierID;
        $AlliaceFunID = $_POST['supplierID'];
        $res = AlliaceFun::find()->where(['or', [
            'SupplierID' => $supplierID, 'AlliaceFunID' => $AlliaceFunID
        ], [
            'AlliaceFunID' => $supplierID, 'SupplierID' => $AlliaceFunID
        ]])->one();
        if ($res) {
            if ($res->AlliaceFunID == $supplierID) {
                $res->StatusID = 1;
                $res->save();
                //双方商品互通
                $AMemberID = \Yii::$app->frontsession->MemberID;
                $SMemberID = Supplier::findOne($AlliaceFunID)->MemberID;
                $res->setProduct($AMemberID, $SMemberID);
            }
        } else {
            $alliaceFun = new AlliaceFun();
            $alliaceFun->SupplierID = $supplierID;
            $alliaceFun->AlliaceFunID = $AlliaceFunID;
            $alliaceFun->StatusID = 0;
            $alliaceFun->save();
        }
        return json_encode(['status' => 1, 'msg' => '提交成功！']);
    }

    //同意加好友
    public function actionAlliaceagree()
    {
        $supplierID = \Yii::$app->frontsession->member->SupplierID;
        $AlliaceFunID = $_POST['supplierID'];
        $res = AlliaceFun::find()->where(['AlliaceFunID' => $supplierID, 'SupplierID' => $AlliaceFunID])->one();
        if ($res) {
            $res->StatusID = 1;
            $res->save();
            //双方商品互通
            $AMemberID = \Yii::$app->frontsession->MemberID;
            $SMemberID = Supplier::findOne($AlliaceFunID)->MemberID;
            $res->setProduct($AMemberID, $SMemberID);
        }
        return json_encode(['status' => 1, 'msg' => '提交成功！']);
    }

    //联盟入驻
    public function actionAlliancereg()
    {
        if (\Yii::$app->request->isPost) {
            if (empty($_POST['sName']) || empty($_POST['sTel'])) {
                return json_encode(['status' => 0, 'msg' => '请完善输入信息']);
            }
            $alliance = new Alliance();
            $alliance->sName = $_POST['sName'];
            $alliance->sCompanyName = $_POST['sCompanyName'];
            $alliance->sTel = $_POST['sTel'];
            $alliance->sAddress = $_POST['sAddress'];

            $alliance->dNewDate = date('Y-m-d H:i:s');
            $alliance->MemberID = \Yii::$app->frontsession->MemberID;

            if ($_POST['imglist']) {
                $img = $_POST['imglist'][0];
                if (substr($img, 0, 5) == 'data:') {
                    $arrFileInfo = File::parseImageFromBase64($img);
                    $sFileName = date('YmdHis') . "." . $arrFileInfo[0];
                    $path = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                    $alliance->sLicense = $path;
                }
            }
            $alliance->save();
            return json_encode(['status' => 1, 'msg' => '提交成功！']);
        } else {
            $alliance = Alliance::findOne(['MemberID' => \Yii::$app->frontsession->MemberID]);
            if ($alliance) {
                if ($alliance->StatusID) {
                    $this->redirect(\Yii::$app->request->mallHomeUrl);
                } else {
                    $this->redirect('/supplier/success');
                }
            }
            $this->getView()->title = '加入联盟';
            return $this->render("alliancereg");
        }
    }

    //商品品类
    public function actionSetcat()
    {
        $arrCat = AlliaceCat::findAll(['MemberID' => \Yii::$app->frontsession->MemberID]);
        if (\Yii::$app->request->isPost) {
            $arrCat[0]->sName = $_POST['sName1'];
            $arrCat[1]->sName = $_POST['sName2'];
            $arrCat[2]->sName = $_POST['sName3'];
            $arrCat[3]->sName = $_POST['sName4'];
            $arrCat[4]->sName = $_POST['sName5'];
            $arrCat[0]->save();
            $arrCat[1]->save();
            $arrCat[2]->save();
            $arrCat[3]->save();
            $arrCat[4]->save();
            return json_encode(['status' => 1, 'msg' => '提交成功！']);
        } else {
            $this->getView()->title = '设置品类';
            $data = [];
            $data['arrCat'] = $arrCat;
            return $this->render("cat", $data);
        }
    }
}