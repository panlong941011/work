<?php

namespace myerm\shop\mobile\controllers;

use myerm\common\components\Func;
use myerm\shop\common\models\AgentShopProduct;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\Member;
use myerm\shop\common\models\MemberProduct;
use myerm\shop\common\models\RecommendProduct;
use myerm\shop\common\models\RecommendProductDetail;
use myerm\shop\common\models\Seller;
use myerm\shop\mobile\models\AlliaceCat;
use myerm\shop\mobile\models\Product;
use myerm\shop\mobile\models\ProductCat;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * 代理店铺商品管理控制器
 * ============================================================================
 */
class AgentshopController extends Controller
{
    /**
     * 选品库
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-7 14:48:01
     */
    public function actionCategory1()
    {
        $data = [];
        $member = \Yii::$app->frontsession->member;

        $seller = Seller::findOne(['MemberID' => $member->lID]);
        if (!$seller || $seller->TypeID != 3) {
            return $this->redirect(\Yii::$app->request->mallHomeUrl);
        }


        //分类数组
        $data['arrCat'] = RecommendProduct::find()->all();

        $this->getView()->title = "商品分类";

        return $this->render('category', $data);
    }

    public function actionCategory()
    {
        $data = [];
        //分类数组
        $data['arrCat'] = RecommendProduct::find()->all();

        $this->getView()->title = "商品分类";
        $bTop = 0;
        $seller = Seller::findOne(\Yii::$app->frontsession->MemberID);
        if ($seller && $seller->TypeID == 3) {
            $bTop = 1;
        }

        $data['bTop'] = $bTop;
        return $this->render('picking', $data);
    }

    /**
     * 根据分类ID获取商品数据
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:15
     */
    public function actionCatproduct()
    {
        $data = [];
        $CatID = 2;
        $bRecommend = false;
        $member = \Yii::$app->frontsession->member;

        $selected = AgentShopProduct::find()
            ->where(['and', ['MemberID' => $member->lID], ['StatusID' => AgentShopProduct::SALE]])
            ->asArray()
            ->all();
        $arrSelected = ArrayHelper::getColumn($selected, 'ProductID');

        $bSelf = false;//是否自有商品


        $arrProduct = RecommendProductDetail::find()->where(['RecommendID' => $CatID])->orderBy('lSort desc')->all();

        $bTop = 0;
        $seller = Seller::findOne(\Yii::$app->frontsession->MemberID);
        if ($seller && $seller->TypeID == 3) {
            $bTop = 1;
        }


        foreach ($arrProduct as $item) {
            //自有商品特殊处理
            if ($bSelf) {
                $product = $item;
            } else {
                $product = $item->product;
            }

            $StatusID = 0;
            if ($bRecommend) {
                $StatusID = $item->bRecommend;
            }
            $fPrice = number_format($product->fPrice, 2);//促销价
            if ($bTop) {
                $fProfit = number_format($product->fPrice - $product->fSupplierPrice, 2); //供货价
            } else {
                $fProfit = '会员可见';
            }

            $bSelect = false;
            if (in_array($product->lID, $arrSelected)) {
                $bSelect = true;
            }

            $data['data'][] = [
                'lID' => $product->lID,
                'sName' => $product->sName,
                'fProfit' => $fProfit,
                'fPrice' => $fPrice,
                'sMasterPic' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                'bSelect' => $bSelect,
                'RecommendStatus' => $StatusID,
                'sRecomm' => $product->sRecomm
            ];
        }

        $data['bRecommend'] = $bRecommend;

        return $this->asJson($data);
    }

    /**
     * 取消店长推荐
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:16
     */
    public function actionCanclerecommend()
    {
        $data = [];

        if (!$_POST['ID']) {
            $data['status'] = false;
            $data['msg'] = '商品ID丢失';
            return $this->asJson($data);
        }

        $log = AgentShopProduct::find()
            ->where(['and',
                ['MemberID' => \Yii::$app->frontsession->member->lID],
                ['bRecommend' => AgentShopProduct::RECOMMEND],
                ['ProductID' => $_POST['ID']],
            ])
            ->one();

        if (!$log) {
            $data['status'] = false;
            $data['msg'] = '未找到记录';
            return $this->asJson($data);
        }

        $log->bRecommend = AgentShopProduct::CANCLE;
        $log->save();

        $data['status'] = true;
        $data['msg'] = '已移除店长推荐';
        return $this->asJson($data);
    }

    /**
     * 加入店长推荐
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:16
     */
    public function actionAddrecommend()
    {
        $ID = $_POST['ID'];
        $data = [];
        $MemberID = \Yii::$app->frontsession->member->lID;

        if (!$ID) {
            $data['status'] = false;
            $data['msg'] = '商品ID丢失';
            return $this->asJson($data);
        }

        //判断商品是否已进货
        $bRecommend = AgentShopProduct::bRecommend([
            'MemberID' => $MemberID,
            'ID' => $ID,
        ]);

        if ($bRecommend) {
            $data['status'] = false;
            $data['msg'] = '已加入推荐';
            return $this->asJson($data);
        }

        //验证商品状态是否允许上架
        $product = Product::findOne(['lID' => $ID]);
        if (!$product->bCheck) {
            $data['status'] = false;
            $data['msg'] = '商品已下架';
            return $this->asJson($data);
        } elseif (!$product->lStock) {
            $data['status'] = false;
            $data['msg'] = '商品已售罄';
            return $this->asJson($data);
        }

        //生成商品选择记录
        AgentShopProduct::saveData([
            'MemberID' => $MemberID,
            'ProductID' => $ID,
            'bRecommend' => AgentShopProduct::RECOMMEND
        ]);

        $data['status'] = true;
        $data['msg'] = '操作成功';
        return $this->asJson($data);
    }

    /**
     * 选择商品进货
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:25
     */
    public function actionSelect()
    {
        $prodcutID = $_POST['prodcutID'];
        $catID = $_POST['catID'];
        $data = [];
        $MemberID = \Yii::$app->frontsession->member->lID;

        if (!$prodcutID) {
            $data['status'] = false;
            $data['msg'] = '商品ID丢失';
            return $this->asJson($data);
        }

        //判断商品是否已进货
        $bSelect = AgentShopProduct::bSelect([
            'MemberID' => $MemberID,
            'ID' => $prodcutID,
        ]);

        if ($bSelect) {
            $data['status'] = false;
            $data['msg'] = '商品已进货';
            return $this->asJson($data);
        }

        //验证商品状态是否允许上架
        $product = Product::findOne(['lID' => $prodcutID]);
        if (!$product->bCheck || !$product->bSale) {
            $data['status'] = false;
            $data['msg'] = '商品已下架';
            return $this->asJson($data);
        } elseif (!$product->lStock) {
            $data['status'] = false;
            $data['msg'] = '商品已售罄';
            return $this->asJson($data);
        }

        //生成商品选择记录
        AgentShopProduct::saveData([
            'MemberID' => $MemberID,
            'ProductID' => $prodcutID,
            'CatID' => $catID,
            'bRecommend' => AgentShopProduct::UNRECOMMEND
        ]);

        $data['status'] = true;
        $data['msg'] = '进货成功';
        return $this->asJson($data);
    }

    /**
     * 取消商品进货
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:25
     */
    public function actionCancelselect()
    {
        $ID = $_POST['ID'];
        $data = [];
        $MemberID = \Yii::$app->frontsession->member->lID;

        if (!$ID) {
            $data['status'] = false;
            $data['msg'] = '商品ID丢失';
            return $this->asJson($data);
        }

        //判断商品是否已进货
        $log = AgentShopProduct::find()
            ->where([
                'MemberID' => $MemberID,
                'ProductID' => $ID,
            ])
            ->one();
        if ($log) {
            $log->StatusID = 0;
            $log->save();
        }

        $data['status'] = true;
        $data['msg'] = '下架成功';
        return $this->asJson($data);
    }

    /**
     * 进货单
     * User: panlong
     * Time: 2019/9/23 0023 下午 15:27
     */
    public function actionProductlist()
    {
        $data = [];
        $member = \Yii::$app->frontsession->member;

        $this->getView()->title = "进货单";
        $SupplierID = -1000;
        if ($member && $member->SupplierID > 0) {
            $SupplierID = $member->SupplierID;
        }

        //计算自有商品数量
        $allSelf = $arrProduct = Product::find()
            ->where(['SupplierID' => $SupplierID])
            ->all();


        $selected = AgentShopProduct::find()
            ->where(['MemberID' => $member->lID])
            ->all();
        $arrSelected = ArrayHelper::index($selected, 'ProductID');

        $lSelfOnSale = 0;
        $lSelfUnSale = 0;

        foreach ($allSelf as $item) {
            if ($arrSelected[$item->lID] && $arrSelected[$item->lID]['StatusID'] == AgentShopProduct::SALE) {
                $lSelfOnSale++;
            } else {
                $lSelfUnSale++;
            }
        }

        //计算代理商品数量
        $lAgentTotal = AgentShopProduct::find()
            ->alias('a')
            ->leftJoin('ylcloud.product p', 'p.lID=a.ProductID')
            ->where(['and',
                ['a.MemberID' => $member->lID],
                ['<>', 'p.SupplierID', $member->SupplierID]
            ])
            ->count();
        $data['lSelfOnSale'] = $lSelfOnSale;
        $data['lSelfUnSale'] = $lSelfUnSale;
        $data['lSelfTotal'] = count($allSelf);
        $data['lAgentTotal'] = $lAgentTotal;
        $data['arrCat'] = AlliaceCat::find()->where(['MemberID' => $member->lID])->limit(5)->all();
        return $this->render('productlist', $data);
    }

    /**
     * 进货单AJAX请求数据接口
     * User: panlong
     * Time: 2019/9/23 0023 上午 11:15
     */
    public function actionList()
    {
        $data = [];
        $bSelf = $_POST['bSelf'];
        $bSale = $_POST['bSale'];
        $sortType = $_POST['sorttype'] ? $_POST['sorttype'] : 'dNewDate';
        $sort = $_POST['sort'] ? $_POST['sort'] : 'asc';
        $member = \Yii::$app->frontsession->member;

        //是店长推荐特殊处理，取用户默认数据
        if ($bSelf) {
            $SupplierID = -1000;
            if ($member && $member->SupplierID > 0) {
                $SupplierID = $member->SupplierID;
            }
            $arrProduct = Product::find()
                ->where(['SupplierID' => $SupplierID])
                ->orderBy($sortType . ' ' . $sort)
                ->all();

            $selected = AgentShopProduct::find()
                ->where(['MemberID' => $member->lID])
                ->asArray()
                ->all();
            $arrSelected = ArrayHelper::index($selected, 'ProductID');
        } else {
            $arrProduct = AgentShopProduct::find()
                ->alias('a')
                ->leftJoin('ylcloud.product p', 'p.lID=a.ProductID')
                ->where(['and',
                    ['a.MemberID' => $member->lID],
                    ['<>', 'p.SupplierID', $member->SupplierID]
                ])
                ->orderBy($sortType . ' ' . $sort)
                ->all();
        }

        foreach ($arrProduct as $item) {
            //自有商品特殊处理
            if ($bSelf) {
                $product = $item;
                if ($bSale == AgentShopProduct::SALE) {
                    if (!$arrSelected[$product->lID] || $arrSelected[$product->lID]['StatusID'] == AgentShopProduct::UNSALE) {
                        continue;
                    }
                } else {
                    if ($arrSelected[$product->lID] && $arrSelected[$product->lID]['StatusID'] == AgentShopProduct::SALE) {
                        continue;
                    }
                }
            } else {
                $product = $item->product;
                if ($item->StatusID != $bSale) {
                    continue;
                }
            }

            $fPrice = number_format($product->fPrice, 2);//促销价
            $fProfit = number_format($product->fPrice - $product->fSupplierPrice, 2); //供货价

            $data['data'][] = [
                'lID' => $product->lID,
                'sName' => $product->sName,
                'fProfit' => $fProfit,
                'fPrice' => $fPrice,
                'sMasterPic' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                'dNewDate' => $product->dNewDate,
                'lStock' => $product->lStock
            ];
        }

        $data['lTotalNum'] = count($arrProduct);

        if ($bSale) {
            $data['lOnSale'] = count($data['data']);
            $data['lUnSale'] = $data['lTotalNum'] - $data['lOnSale'];
        } else {
            $data['lUnSale'] = count($data['data']);
            $data['lOnSale'] = $data['lTotalNum'] - $data['lUnSale'];
        }

        return $this->asJson($data);
    }

    /*
     * 商品价格设定
     */
    private $ProductID;

    public function actionSetvip()
    {
        if (\Yii::$app->request->isPost) {
            $productID = $_POST['ProductID'];
            $vipList = trim($_POST['vipList'], ',');
            $priceList = trim($_POST['priceList'], ',');
            if (empty($vipList)) {
                return $this->asJson(['status' => false, 'message' => '修改失败']);
            }
            $arrVipID = explode(',', $vipList);
            $arrPrice = explode(',', $priceList);
            $arr = [];
            $table = [
                'MemberID',
                'ProductID',
                'fPrice'
            ];
            foreach ($arrVipID as $key => $vipID) {
                $arr[$key]['MemberID'] = $vipID;
                $arr[$key]['ProductID'] = $productID;
                $arr[$key]['fPrice'] = $arrPrice[$key];
            }
            //插入之前先清空
            MemberProduct::deleteAll(['MemberID' => $arrVipID, 'ProductID' => $productID]);
            \Yii::$app->db->createCommand()->batchInsert('memberproduct', $table, $arr)->execute();
            return $this->asJson(['status' => true, 'message' => '修改成功']);
        } else {
            $seller = Seller::findOne(['MemberID' => \Yii::$app->frontsession->member->lID]);
            if (!$seller || $seller->TypeID != Seller::TOP) {
                return $this->redirect(\Yii::$app->request->mallHomeUrl);
            }
            $data = [];
            $this->getView()->title = "VIP价格设定";
            $product = Product::findOne($_GET['ProductID']);
            $data['product'] = $product;
            $data['vipPrice'] = ($product->fPrice - $product->fSupplierPrice) * 0.2 + $product->fSupplierPrice;
            $this->ProductID = $product->lID;
            $data['arrVip'] = Member::find()
                ->where(['FromMemberID' => \Yii::$app->frontsession->member->lID])
                ->andWhere('lID in (SELECT MemberID from seller where TypeID<>3)')
                ->with(
                    [
                        'viprice' => function ($query) {
                            $query->andWhere(['ProductID' => $this->ProductID]);
                        },
                    ]
                )
                ->all();
            return $this->render("setvip", $data);
        }
    }
}