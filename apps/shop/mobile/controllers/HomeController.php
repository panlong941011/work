<?php

namespace myerm\shop\mobile\controllers;

use myerm\common\components\Func;
use myerm\shop\common\models\AgentShopProduct;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\Member;
use myerm\shop\common\models\RecommendProduct;
use myerm\shop\common\models\RecommendProductDetail;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\SellerShop;
use myerm\shop\mobile\models\Cart;
use myerm\shop\mobile\models\Group;
use myerm\shop\mobile\models\Groupaddress;
use myerm\shop\mobile\models\Order;
use myerm\shop\mobile\models\Product;
use myerm\shop\mobile\models\Redbag;
use myerm\shop\mobile\models\Redbagproduct;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * 首页
 */
class HomeController extends Controller
{

    /**
     * 准备商品数据
     * User: panlong
     * Time: 2019/9/25 0025 下午 16:18
     */
    public function actionPrepare($product)
    {
        $vip = Seller::findOne(\Yii::$app->frontsession->MemberID);
        if ($vip) {
            $price = '供货价：¥' . number_format($product->fSalePrice, 2);//促销价
            $market_price = '促销价：¥' . number_format($product->fPrice, 2);//市场价
        } else {
            $price = '促销价：¥' . number_format($product->fSalePrice, 2);//促销价
            $market_price = '';
        }

        $link = Url::toRoute([
            \Yii::$app->request->shopUrl . "/product/detail",
            'id' => $product->lID
        ], true);

        $title = $product->sName;
        //规格
        if ($product->sStandard) {
            $title = $title . ' ' . $product->sStandard;
        }
        //口味
        if ($product->sTaste) {
            $title = $title . ' ' . $product->sTaste;
        }
        if (\Yii::$app->frontsession->MemberID == 3736) {
            $market_price = '';
        }
        $item = [
            'lID' => $product->lID,
            'title' => $title,
            'price' => $price,//促销价
            'market_price' => $market_price,//促销价 . $product->fMarketPrice : '',//市场价
            'image' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
            'saleout' => $product->bSaleOut ? true : false,
            'seckill' => [],
            'icon' => $product->icon,
            'link' => $link,
            'sGroupKeyword' => $product->sGroupKeyword
        ];
        return $item;
    }

    /*
     */
    public function actionHome()
    {
        $data = [];
        //轮播图
        $data['arrScrollImage'] = json_decode(MallConfig::getValueByKey("sScrollImageConfig"), true);
        //获取第一个商品列表配置
        $data['sItemJson'] = $this->actionItem(0, 1);
        $this->getView()->title = "来瓜分";
        $data['shoptitle'] = "来瓜分";
        $MemberID = \Yii::$app->frontsession->MemberID;
        $seller = SellerShop::findOne(['lID' => $MemberID]);
        $data['seller'] = $seller;
        if ($seller) {
            $data['shoptitle'] = $seller->sName;
        }
        return $this->render('home', $data);
    }

    public function actionIndexgroup()
    {
        $data = [];
        //轮播图
        $data['arrScrollImage'] = json_decode(MallConfig::getValueByKey("sScrollImageConfig"), true);
        //获取第一个商品列表配置
        $data['sItemJson'] = $this->actionItem(0, 1);
        return $this->render('group', $data);
    }

    public function actionIndexbulk()
    {
        echo '数据维护中';
        exit;
        $data = [];
        //轮播图
        $data['arrScrollImage'] = json_decode(MallConfig::getValueByKey("sScrollImageConfig"), true);
        //获取第一个商品列表配置
        $data['sItemJson'] = null;// $this->actionItem(0, 2);
        return $this->render('bulk', $data);
    }

    public function actionItem($index, $type = 1)
    {
        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['isMore'] = false;
        $data['data'] = [];
        $arrProduct = \Yii::$app->product->getWholesaleProduct($index, $type);
        if ($arrProduct) {
            $data['data']['commodity'] = [];
            foreach ($arrProduct as $product) {
                $fSupplierPrice = '';// '供货价：¥' . number_format($product->fSupplierPrice, 2); //供货价
                $price = '促销价：¥' . number_format($product->fPrice, 2);//促销价
                $market_price = '市场价：¥' . number_format($product->fMarketPrice, 2);//市场价
                $link = Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detail",
                    'id' => $product->lID
                ], true);

                $title = $product->sName;
                //规格
//                if ($product->sStandard) {
//                    $title = $title . ' ' . $product->sStandard;
//                }
//                //口味
//                if ($product->sTaste) {
//                    $title = $title . ' ' . $product->sTaste;
//                }
                $data['data']['commodity'][] = [
                    'title' => $title,
                    'sRecomm' => $product->sRecomm,
                    'fSupplierPrice' => $fSupplierPrice, //供货价
                    'price' => $price,//促销价
                    'market_price' => $market_price,//促销价 . $product->fMarketPrice : '',//市场价
                    'image' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                    'saleout' => $product->bSaleOut ? true : false,
                    'seckill' => [],
                    'icon' => $product->icon,
                    'link' => $link,
                    'lStock' => '库存：' . $product->lStock,
                    'lID' => $product->lID,
                    'sGroupKeyword' => $product->sGroupKeyword
                ];
            }
            $count = count($arrProduct);
            if ($count && $count % 10 == 0) {
                $data['isMore'] = true;
            }

        } else {
            $data['isMore'] = false;
        }

        return json_encode($data);
    }

    public function actionInvite()
    {
        $MemberID = \Yii::$app->frontsession->MemberID;

        $seller = SellerShop::findOne(['lID' => $MemberID]);
        if ($seller) {
            $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true));
        }
        $this->getView()->title = "来瓜分团长";
        return $this->render('invite');
    }

    //满减券
    public function actionRedbag()
    {
        $arrRed = Redbag::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID, 'bUserd' => 0])->all();
        $data = [];
        $data['arrRed'] = $arrRed;
        $this->getView()->title = "爱橙券";
        return $this->render('redbag', $data);
    }

    public function actionChangeredstate()
    {
        $member = \Yii::$app->frontsession->member;
        $member->bReceiveRedbag = 1;
        $member->save();
    }

    public function actionIndex_0203()
    {
        $data = [];
        $date = \Yii::$app->formatter->asDatetime(time());
        //获取第一个商品列表配置
        $data['arrProduct'] = $this->getGroupProduct();
        $recommendProduct = RecommendProduct::findOne(9);
        $timerDate = $recommendProduct->dStrart;
        $timerMsg = '距开始';
        if ($data['arrProduct']['status']) {
            $timerDate = $recommendProduct->dEnd;
            $timerMsg = '距截单';
        }
        $data['timerDate'] = $timerDate;
        $data['timerMsg'] = $timerMsg;
        $MemberID = \Yii::$app->frontsession->MemberID;

        $seller = SellerShop::findOne(['lID' => $MemberID]);
        if ($seller) {
            $data['shoptitle'] = $seller->sName;
        } else {
            $seller = \Yii::$app->frontsession->urlSeller;
            if ($seller) {
                //团长分享链接默认开团
                $groupaddress = Groupaddress::findOne(['MemberID' => $seller->lID]);
                if ($groupaddress) {
                    if (($data['arrProduct']['status'])) {
                        //已到开团时间
                        $prdoctids = '';
                        foreach ($data['arrProduct']['data']['commodity'] as $de) {
                            $prdoctids .= $de['lID'] . ',';
                        }
                        $group = new Group();
                        $group->ProductID = rtrim($prdoctids, ',');
                        $group->MemberID = $seller->lID;
                        $group->save();
                        $groupID = $group->lID;
                        $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/product/membergroup"], true) . "?id=$groupID");
                    }
                } else {
                    //无开团地址，邀请升级
                    $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home/invite"], true));
                }
            } else {
                //无上级 邀请开团
                $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home/invite"], true));
            }
        }


        //轮播图
        $data['arrScrollImage'] = json_decode(MallConfig::getValueByKey("sScrollImageConfig"), true);
        $this->getView()->title = "来瓜分";

        //团长地址是否显示
        $bAddressShow = 0;
        $groupAddress = Groupaddress::findOne(['MemberID' => $MemberID]);
        if ($groupAddress) {
            $bAddressShow = 1;
        }
        $data['bAddressShow'] = $bAddressShow;

        $arrOrder = Order::find()->orderBy('dNewDate desc')->limit(60)->all();
        $buyers = [];
        foreach ($arrOrder as $key => $order) {
            $member = $order->member;
            $buyers[$key]['logo'] = $member->sAvatarPath;
            $buyers[$key]['sName'] = $member->sName;
        }
        $data['buyers'] = json_encode($buyers);

        //商品券
        $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date]])->asArray()->all();
        $bRedProduct = 0;
        if (!$redProduct) {
            $bRedProduct = Redbagproduct::addRedbagproduct();;
        }
        $data['bRedProduct'] = $bRedProduct;

        return $this->render('groupindex', $data);
    }

    public function getGroupProduct()
    {
        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['isMore'] = false;
        $data['data'] = [];
        $data['data']['commodity'] = [];
        $data['data']['sellout'] = [];
        $arrRecommed = RecommendProductDetail::find()->where(['RecommendID' => 9])->with('product')->orderBy('lSort desc')->all();
        if ($arrRecommed) {
            foreach ($arrRecommed as $recommend) {
                $product = $recommend->product;
                $fSupplierPrice = '';// '供货价：¥' . number_format($product->fSupplierPrice, 2); //供货价
                $price = number_format($product->fPrice, 1);//促销价
                $market_price = number_format($product->fMarketPrice, 1);//市场价
                $link = Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detailgroup",
                    'id' => $product->lID
                ], true);

                $title = $product->sName;
                $arrPoduct = [
                    'title' => $title,
                    'sRecomm' => $product->sRecomm,
                    'fSupplierPrice' => $fSupplierPrice, //供货价
                    'price' => $price,//促销价
                    'market_price' => $market_price,//促销价 . $product->fMarketPrice : '',//市场价
                    'image' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                    'imageright' => \Yii::$app->request->imgUrl . '/' . json_decode($product->sPic)[1],
                    'saleout' => $product->bSaleOut ? true : false,
                    'seckill' => [],
                    'icon' => $product->icon,
                    'link' => $link,
                    'lStock' => '库存：' . $product->lStock,
                    'lID' => $product->lID,
                    'sGroupKeyword' => $product->sGroupKeyword
                ];
                if ($product->lStock > 0) {
                    $data['data']['commodity'][] = $arrPoduct;
                } else {
                    $data['data']['sellout'][] = $arrPoduct;
                }
            }
        } else {
            $data['isMore'] = false;
        }
        if (count($data['data']['commodity']) || count($data['data']['sellout'])) {
            $data['status'] = 1;
        }
        return $data;
    }

    //商品券
    public function actionRedbagproduct()
    {
        $bnew = 0;
        $date = \Yii::$app->formatter->asDatetime(time());
        $arrRed = Redbagproduct::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])
            ->andWhere(['<', 'dEnd', $date])
            ->all();
        if (!$arrRed) {
            $bnew = RecommendProduct::addRedbagproduct();
            $arrRed = Redbagproduct::find()->where(['MemberID' => \Yii::$app->frontsession->MemberID])->andWhere(['<', 'dEnd', $date])->all();
        }
        $data = [];
        $data['arrRed'] = $arrRed;
        $data['bnew'] = $bnew;
        $this->getView()->title = "商品券";
        return $this->render('redbagproduct', $data);
    }

    //团购首页
    public function actionIndex()
    {
        $data = [];
        //轮播图
        $data['arrScrollImage'] = json_decode(MallConfig::getValueByKey("sScrollImageConfig"), true);

        //获取第一个商品列表配置
        $data['arrProduct'] = $this->getGroupProduct();

        //如果又开团数据，开团
        $bRedProduct = 0;
        $date = \Yii::$app->formatter->asDatetime(time());
        $recommendProduct = RecommendProduct::findOne(9);
        $timerDate = $recommendProduct->dStrart;
        $timerMsg = '距开始';
        if ($date > $recommendProduct->dStrart && $date < $recommendProduct->dEnd) {
            $timerDate = $recommendProduct->dEnd;
            $timerMsg = '距截单';
        }
        $data['timerDate'] = $timerDate;
        $data['timerMsg'] = $timerMsg;
        $data['buyers'] = '';
        $MemberID = \Yii::$app->frontsession->MemberID;
        if (empty($MemberID)) {
//            $this->redirect(Url::toRoute([
//                \Yii::$app->request->shopUrl . '/member/loginpost',
//                'sReturnUrl' => \Yii::$app->request->rootUrl . \Yii::$app->request->url
//            ], true));
//            return;
        }
        $seller = SellerShop::findOne(['lID' => $MemberID]);
        if (!$seller) {
            $seller = \Yii::$app->frontsession->urlSeller;
            if ($seller) {
                //团长分享链接默认开团
                $groupaddress = Groupaddress::findOne(['MemberID' => $seller->lID]);
                if (!$groupaddress) {
                    //无开团地址，邀请升级
                    $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home/select"], true));
                }
            } else {
                //无上级 邀请开团
                $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home/select"], true));
            }
        }
        $data['shoptitle'] = $seller->sName;
        $this->getView()->title = $seller->sName;
        if ($data['arrProduct']['status']) {
            //团长地址是否显示
            $bAddressShow = 0;
            $groupAddress = Groupaddress::findOne(['MemberID' => $MemberID]);
            if ($groupAddress) {
                $bAddressShow = 1;
            }
            $data['bAddressShow'] = $bAddressShow;

            $arrOrder = Order::find()->orderBy('dNewDate desc')->limit(60)->all();
            $buyers = [];
            foreach ($arrOrder as $key => $order) {
                $member = $order->member;
                $buyers[$key]['logo'] = $member->sAvatarPath;
                $buyers[$key]['sName'] = $member->sName;
            }
            $data['buyers'] = json_encode($buyers);

            //商品券
            $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date]])->asArray()->all();

            if (!$redProduct) {
                $bRedProduct = Redbagproduct::addRedbagproduct();;
            }

        }
        $data['bRedProduct'] = $bRedProduct;
        $cartNo=Cart::find()->where(['MemberID'=>$MemberID])->sum('lQuantity');
        $data['cartNo']=$cartNo?$cartNo:0;
        return $this->render('gindex', $data);
    }

    //选团长
    public function actionSelect()
    {
        $data = [];
        $data['arrGroupaddress'] = Groupaddress::find()->where("lID not in(1847,
1850,
1851,
1856,
1863,
1880)")->all();
        return $this->render('select', $data);
    }
    //团长申请
    //选团长
    public function actionGroupapply()
    {
        $data = [];
        $this->getView()->title='团长招募';
        return $this->render('groupapply', $data);
    }

    public function actionBanner()
    {
        $this->getView()->title='团长招募';
        return $this->render('banner');
    }
}