<?php

namespace myerm\shop\mobile\controllers;

use myerm\common\components\Func;
use myerm\common\libs\File;
use myerm\shop\common\models\AgentShopProduct;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\RecommendProduct;
use myerm\shop\common\models\RecommendProductDetail;
use myerm\shop\common\models\Seller;
use myerm\shop\mobile\models\Material;
use myerm\shop\mobile\models\MaterialDetail;
use myerm\shop\mobile\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * 首页
 */
class MaterialController extends Controller
{
    public function actionIndex()
    {
        $data = [];
        //默认搜索词
        $data['sDefSearchWord'] = MallConfig::getValueByKey("sDefSearchWord");
        //获取第一个商品列表配置
        $data['sItemJson'] = $this->actionItem(1);
        $this->getView()->title = '动态';
        $bTop = 0;
        $seller = Seller::findOne(\Yii::$app->frontsession->MemberID);
        if ($seller && $seller->TypeID == 3) {
            $bTop = 1;
        }
        $data['bTop'] = $bTop;
        return $this->render('index', $data);
    }



    public function actionItem($index)
    {
        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['isMore'] = false;
        $data['data'] = [];
        $TopID = \Yii::$app->seller->topSeller;
        $where = [];
        if ($TopID != \Yii::$app->frontsession->MemberID) {
            $arrProductID = AgentShopProduct::find()->select('ProductID')->where(['MemberID' => $TopID])->asArray()->all();
            $arrProductID = array_column($arrProductID, 'ProductID');
            $where['ProductID'] = $arrProductID;
        }
        if ($_GET['Type']) {
            $where['MemberID'] = \Yii::$app->frontsession->MemberID;
        }
        if ($TopID != \Yii::$app->frontsession->MemberID && !$_GET['Type']) {
            $where['StatusID'] = 3;
        }
        $arrMaterail = Material::find()->where($where)
            ->with('detail')
            ->with('product')
            ->limit(10)
            ->offset(($index - 1) * 10)
            ->orderBy('dNewDate desc')
            ->all();
        if ($arrMaterail) {
            $data['data']['commodity'] = [];
            foreach ($arrMaterail as $materail) {
                $fSupplierPrice = '供货价：¥' . number_format($materail->product->fSalePrice, 2); //供货价
                $price = '促销价：¥' . number_format($materail->product->fPrice, 2);//促销价
                $market_price = '市场价：¥' . number_format($materail->product->fMarketPrice, 2);//市场价
                $link = Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detail",
                    'id' => $materail->product->lID
                ], true);
                //判断用户角色

                $title = $materail->product->sName;
                //规格
                if ($materail->product->sStandard) {
                    $title = $title . ' ' . $materail->product->sStandard;
                }
                //口味
                if ($materail->product->sTaste) {
                    $title = $title . ' ' . $materail->product->sTaste;
                }
                //评论图
                $arrDetail = [];
                if ($materail->detail) {
                    foreach ($materail->detail as $v) {
                        if ($v->sUrl) {
                            $arrDetail[] = 'https://yl.aiyolian.cn/' . $v->sUrl;
                        }
                    }
                }
                $data['data']['commodity'][] = [
                    'title' => $title,
                    'fSupplierPrice' => $fSupplierPrice, //供货价
                    'price' => $price,//促销价
                    'market_price' => $market_price,//促销价 . $product->fMarketPrice : '',//市场价
                    'image' => \Yii::$app->request->imgUrl . '/' . $materail->product->sMasterPic,
                    'saleout' => $materail->product->bSaleOut ? true : false,
                    'seckill' => [],
                    'icon' => '',
                    'link' => $link,
                    'sContent' => $materail->sContent,
                    'dNewDate' => substr($materail->dNewDate, 0, 10),
                    'detail' => $arrDetail ? $arrDetail : -1
                ];
            }
            $count = count($arrMaterail);
            if ($count && $count % 10 == 0) {
                $data['isMore'] = true;
            }

        } else {
            $data['isMore'] = false;
        }

        return json_encode($data);
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isPost) {
            $arrImg = [];
            if ($_POST['imglist']) {
                foreach ($_POST['imglist'] as $key => $img) {
                    if ($key < 9) {
                        if (substr($img, 0, 5) == 'data:') {
                            $arrFileInfo = File::parseImageFromBase64($img);
                            $sFileName = date('YmdHis') . $key . "." . $arrFileInfo[0];
                            $arrImg[] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                        } else {
                            $arrImg[] = str_ireplace(\Yii::$app->request->imgUrl . "/", "", $img);;
                        }
                    }
                }
            }
            $material = new Material();
            $material->MemberID = \Yii::$app->frontsession->MemberID;
            $material->sContent = $_POST['sMsg'];
            $material->save();
            foreach ($arrImg as $img) {
                $materialDetail = new MaterialDetail();
                $materialDetail->MaterialID = $material->lID;
                $materialDetail->sUrl = $img;
                $materialDetail->save();
            }
            return $this->asJson(['status' => true, 'message' => '发布成功,等待管理员审核']);
        } else {
            $data = [];
            return $this->render('edit', $data);
        }
    }
}