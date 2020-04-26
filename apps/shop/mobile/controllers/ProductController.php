<?php

namespace myerm\shop\mobile\controllers;

use myerm\common\components\Func;
use myerm\common\libs\File;
use myerm\shop\common\models\Area;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\Member;
use myerm\shop\common\models\RecommendProduct;
use myerm\shop\common\models\RecommendProductDetail;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\SellerShop;
use myerm\shop\mobile\models\AlliaceCat;
use myerm\shop\mobile\models\Group;
use myerm\shop\mobile\models\Groupaddress;
use myerm\shop\mobile\models\Groupapply;
use myerm\shop\mobile\models\GroupMember;
use myerm\shop\mobile\models\Order;
use myerm\shop\mobile\models\Product;
use myerm\shop\mobile\models\ProductCat;
use myerm\shop\mobile\models\Redbag;
use myerm\shop\mobile\models\Redbagproduct;
use myerm\shop\mobile\models\ShipTemplate;
use myerm\shop\mobile\models\ShipTemplateDetail;
use myerm\shop\mobile\models\ShipTemplateNoDelivery;
use myerm\shop\mobile\models\Supplier;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * 商品控制器
 * ============================================================================
 */
class ProductController extends Controller
{
    /**
     * 商品分类页
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-7 14:48:01
     */
    public function actionCategory()
    {
        return $this->redirect("/home");
        $data = [];
        //判断用户角色
        if (\Yii::$app->frontsession->member->bActive < 1) {
            if (!\Yii::$app->frontsession->member->sMobile) {
                return $this->redirect("/member/reg");
            } else {
                return $this->redirect("/wholesaler/applydesc");
            }
        }
        //分类数组
        $data['arrCat'] = \Yii::$app->productcat::category();

        $this->getView()->title = "商品分类";

        return $this->render('category', $data);
    }

    /**
     * 渠道商品详情
     * @param $name
     * @return string
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2018-8-15 13:46:20
     */
    public function actionWholesaledetail($name)
    {
        return $this->redirect("/home");
        return $this->actionDetail($name);
    }

    /**
     * 商品详情页
     * @param int $id 商品id
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-7 15:10:36
     */
    public function actionDetail($id)
    {
        $product = \Yii::$app->product->findByID($id);
        $data['fShowSalePrice'] = number_format($product->fSalePrice, 2);
        $data['bWholesale'] = 1;

        //商品详情
        if (!$id || !$product || $product->bDel) {
            $this->getView()->title = "商品已删除";
            return $this->render('del');
        }

        /* @var Product $product */
        $data['product'] = $product;


        //商品规格
        $arrSpec = \Yii::$app->productspec->getArrSpec($id, $product->sMasterPic);
        $data['arrSpec'] = $arrSpec;

        $data['lCartProductQty'] = \Yii::$app->cart->lProductQty;


        //去掉库存为0的规格，Mars，2018年6月22日 18:24:29
        $arrJson = json_decode($arrSpec['sJsonGroup'], true);
        foreach ($arrJson as $ID => $json) {
            if (!$json['count']) {
                unset($arrJson[$ID]);
            }
        }
        $data['arrSpec']['sJsonGroup'] = $arrJson ? json_encode($arrJson) : json_encode([]);

        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");

        //商品参数
        $data['arrParamValue'] = array_values(\Yii::$app->productparamvalue->getArrParamValue($id));

//        $TopID = \Yii::$app->seller->topSeller;
//        $shop = \Yii::$app->sellershop->getShop($TopID);
        $this->getView()->title = '来瓜分';
        //轮播图
//        if ($TopID) {
//            $data['logo'] = '/' . $shop->sLogoPath;
//            $data['shop'] = $shop;
//        } else {
        $data['logo'] = \Yii::$app->request->imgUrl . '/' . \myerm\shop\common\models\MallConfig::getValueByKey("sMallLogo");
        //}
        return $this->render('detail', $data);
    }

    /**
     * 计算商品运费接口
     *
     * //get参数
     * ProductID 商品ID (必需)
     * sCity 城市名称 (必需)
     *
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-9 09:53:19
     * @return json
     */
    public function actionCountship()
    {
        //get参数数组
        $arrGet = \Yii::$app->request->get();

        //商品详情
        $product = \Yii::$app->product->findByID($arrGet['ProductID']);
        if (!$arrGet['ProductID'] || !$product) {
            return json_encode(['status' => -1, 'msg' => '数据不存在']);
        }

        if (!$arrGet['sProvince'] && !$arrGet['sCity']) {
            $sLocation = \Yii::$app->area->getIpLocation(\Yii::$app->request->getUserIP());
            $arrGet['sProvince'] = $sLocation['sProvince'];
            $arrGet['sCity'] = $sLocation['sCity'];
        }

        if (!$arrGet['sProvince'] || !$arrGet['sCity']) {
            return json_encode(['status' => -1, 'msg' => '请选择收货地址']);
        }


        //组装参数
        $arrCountShip = [
            'ProvinceName' => $arrGet['sProvince'],
            'CityName' => $arrGet['sCity'],
            'ShipTemplateID' => $product->ShipTemplateID,
            'Number' => $arrGet['lNumber'] ? $arrGet['lNumber'] : 1,
            'fTotalMoney' => $product->fPrice
        ];

        //计算费用
        $result = \Yii::$app->shiptemplate->getShipCount($arrCountShip);
        $result['sProvince'] = $arrGet['sProvince'];
        $result['sCity'] = $arrGet['sCity'];

        return $this->asJson($result);
    }

    /**
     * 搜索结果页、列表页、渠道商列表页
     * @author 陈鹭明
     * @time 2017年10月9日 22:08:40
     */
    public function actionList()
    {
        $data = [];
        $data['arrBrand'] = \Yii::$app->productbrand->showBrands;
        $data['sProductCatName'] = ProductCat::findOne($_GET['catid'])->sName;
        $data['sFirstPageListData'] = $this->actionItem();
        $this->getView()->title = "商品列表";

        return $this->render("wholesalelist", $data);
    }

    /**
     * 在搜索结果页中，Ajax返回的搜索结果数据
     */
    public function actionItem()
    {
        //准备搜索的条件

        //分类ID
        $CatID = $_GET['catid'];
        //关键词
        $sKeyword = urldecode($_GET['keyword']);

        //品牌
        $arrBrand = $_GET['brand'] ? explode(",", urldecode($_GET['brand'])) : [];

        //标签
        $arrTag = $_GET['tag'] ? explode(",", urldecode($_GET['tag'])) : [];

        //排序
        $sSortBy = urldecode($_GET['sortby']);
        //排序方向
        $sAscDesc = urldecode($_GET['ascdesc']);
        //页码
        $lPageNo = intval($_GET['pageno']) ? intval($_GET['pageno']) : 1;


        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['data'] = [];


        $ret = \Yii::$app->product->getByConfig([
            'CatID' => $CatID,
            'sKeyword' => $sKeyword,
            'arrBrand' => $arrBrand,
            'arrTag' => $arrTag,
            'sSortBy' => $sSortBy,
            'sAscDesc' => $sAscDesc,
            'lPageNo' => $lPageNo
        ]);
        $arrProduct = $ret[0];

        if ($lPageNo == 1 && $ret[1] <= 10) {
            $data['isMore'] = false;
        } elseif ($lPageNo == 1 && $ret[1] > 10) {
            $data['isMore'] = true;
        }

        $data['data']['commodity'] = [];
        foreach ($arrProduct as $key => $product) {
            $fSupplierPrice = '供货价：¥' . number_format($product->fSupplierPrice, 2); //供货价
            $price = '促销价：¥' . number_format($product->fPrice, 2);//促销价
            $market_price = '市场价：¥' . number_format($product->fMarketPrice, 2);//市场价
            $link = Url::toRoute([\Yii::$app->request->shopUrl . "/product/detail", 'id' => $product->lID], true);
            if (\Yii::$app->frontsession->member->bActive < 1) {
                $fSupplierPrice = '';
                $price = '上市周期：' . $product->sPeriod;
                $market_price = '核心产地：' . $product->sProductReduce;
                $link = '';
            }
            $title = $product->sName;
            //规格
            if ($product->sStandard) {
                $title = $title . ' ' . $product->sStandard;
            }
            //口味
            if ($product->sTaste) {
                $title = $title . ' ' . $product->sTaste;
            }

            $data['data']['commodity'][$key] = [
                'title' => $title,
                'fSupplierPrice' => $fSupplierPrice,
                'price' => $price,
                'sold' => intval($product->lSale),
                'market_price' => $market_price,
                'image' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                'saleout' => $product->bSaleOut ? true : false,
                'seckill' => null,
                'link' => $link
            ];

        }
        return json_encode($data);
    }

    /**
     * 是否可以购买商品
     * @author 陈鹭明
     * @time 2017年10月19日 05:02:50
     */
    public function actionCanbuy()
    {
        $product = \Yii::$app->product->findByID($_GET['productid']);

        $data = [];

        if (!$product) {
            $data['status'] = false;
            $data['message'] = Product::STATUS_NOTEXISTS;
        } elseif ($product->bDel) {
            $data['status'] = false;
            $data['message'] = Product::STATUS_DEL;
        } elseif ($product->bOffSale) {
            $data['status'] = false;
            $data['message'] = Product::STATUS_OFFSALE;
        } elseif ($product->bSaleOut) {
            $data['status'] = false;
            $data['message'] = Product::STATUS_SALEOUT;
        } elseif ($product->bOnSale) {
            $data['status'] = true;
        }

        if ($data['status']) {
            if ($_GET['province'] && $_GET['city']) {
                $city = \Yii::$app->area->getCityByName($_GET['province'], $_GET['city']);
                if (!$product->nodelivery || $city && !strstr($product->nodelivery->sAreaID, $city->ID)) {
                    $data['status'] = true;
                } elseif ($product->nodelivery && $city && strstr($product->nodelivery->sAreaID, $city->ID)) {
                    $data['status'] = false;
                    $data['message'] = Product::STATUS_AREA_NODELIVERY;
                }
            }
        }

        //判断是否渠道商品 ouyangyz 2018-8-15 13:48:13
        if ($_GET['wholesaleid']) {
            $wholesale = \Yii::$app->wholesale->findByID($_GET['wholesaleid']);
            if ($wholesale->bOffSale) {
                $data['status'] = false;
                $data['message'] = Product::STATUS_OFFSALE;
            }
        }
        return $this->asJson($data);
    }


    public function actionCatproduct()
    {
        $data = [];
        $CatID = \Yii::$app->request->post('CatID');
        $arrProduct = \Yii::$app->product->getProductByCatID($CatID);
        if ($arrProduct) {
            foreach ($arrProduct as $product) {
                $fSupplierPrice = '供货价：¥' . number_format($product->fSupplierPrice, 2); //供货价
                $price = '促销价：¥' . number_format($product->fPrice, 2);//促销价
                $market_price = '市场价：¥' . number_format($product->fMarketPrice, 2);//市场价
                $data[] = [
                    'lID' => $product->lID,
                    'sName' => $product->sName,
                    'fSupplierPrice' => $fSupplierPrice,
                    'price' => $price,
                    'market_price' => $market_price,
                    'sMasterPic' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                ];
            }
        }

        return $this->asJson($data);
    }

    /**
     * 商品分类页
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-7 14:48:01
     */
    public function actionYear()
    {
        return $this->redirect("/home");
        $data = [];
        $data['arrCat'] = [1 => '一月', 2 => '二月', 3 => '三月', 4 => '四月', 5 => '五月', 6 => '六月', 7 => '七月', 8 => '八月', 9 => '九月', 10 => '十月', 11 => '十一月', 12 => '十二月'];
        $m = intval(date('m'));
        $fontCat = [];
        $backCat = [];
        foreach ($data['arrCat'] as $key => $cat) {
            if ($key < $m) {
                $backCat[$key] = $cat;
            } else {
                $fontCat[$key] = $cat;
            }
        }
        foreach ($backCat as $key => $cat) {
            $fontCat[$key] = $cat;
        }
        $data['arrCat'] = $fontCat;
        $data['current'] = $m;
        $this->getView()->title = "年度计划";
        return $this->render('year', $data);
    }

    /**
     * 年度商品
     */
    public function actionYearproduct()
    {
        return $this->redirect("/home");
        $data = [];
        $CatID = \Yii::$app->request->post('CatID');
        $arrProduct = \Yii::$app->product->getProductByMonth($CatID);
        if ($arrProduct) {
            foreach ($arrProduct as $product) {
                $fSupplierPrice = $product->sRecomm; //推荐词
                $price = '上市周期：' . $product->sPeriod; //供货价
                $market_price = '核心产地：' . $product->sProductReduce;//促销价
                $data[] = [
                    'lID' => $product->lID,
                    'sName' => $product->sName,
                    'fSupplierPrice' => $fSupplierPrice,
                    'price' => $price,
                    'market_price' => $market_price,
                    'sMasterPic' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                ];
            }
        }

        return $this->asJson($data);
    }

    /**
     * 推荐商品
     */
    public function actionRecommend()
    {
        return $this->redirect("/home");
        $this->getView()->title = "推荐商品";
        //判断用户角色
        if (\Yii::$app->frontsession->member->bActive < 1) {
            if (!\Yii::$app->frontsession->member->sMobile) {
                return $this->redirect("/member/reg");
            } else {
                return $this->redirect("/wholesaler/applydesc");
            }
        } else {
            $data = [];
            //获取第一个商品列表配置
            $data['sItemJson'] = $this->actionRecommendlist(0);
            return $this->render('recommd', $data);
        }
    }

    public function actionRecommendlist($index, $type = 0)
    {
        return $this->redirect("/home");
        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['isMore'] = false;
        $data['data'] = [];
        if (\Yii::$app->frontsession->member->bActive < 1 && $index > 10) {
            return json_encode($data);
        }
        $arrProduct = \Yii::$app->product->getRecommendProduct($index, $type);
        if ($arrProduct) {
            $data['data']['commodity'] = [];
            foreach ($arrProduct as $product) {
                $fSupplierPrice = '供货价：¥' . number_format($product->fSupplierPrice, 2); //供货价
                $price = '促销价：¥' . number_format($product->fPrice, 2);//促销价
                $market_price = '市场价：¥' . number_format($product->fMarketPrice, 2);//市场价
                $link = Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detail",
                    'id' => $product->lID
                ], true);
                //判断用户角色
                if (\Yii::$app->frontsession->member->bActive < 1) {
                    $fSupplierPrice = '';
                    $price = '上市周期：' . $product->sPeriod;
                    $market_price = '核心产地：' . $product->sProductReduce;
                    $link = '';
                }
                $title = $product->sName;
                //规格
                if ($product->sStandard) {
                    $title = $title . ' ' . $product->sStandard;
                }
                //口味
                if ($product->sTaste) {
                    $title = $title . ' ' . $product->sTaste;
                }
                $data['data']['commodity'][] = [
                    'title' => $title,
                    'fSupplierPrice' => $fSupplierPrice, //供货价
                    'price' => $price,//促销价
                    'market_price' => $market_price,//促销价 . $product->fMarketPrice : '',//市场价
                    'image' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                    'saleout' => $product->bSaleOut ? true : false,
                    'seckill' => [],
                    'icon' => $product->icon,
                    'link' => $link,
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


    public function actionSample()
    {
        return $this->redirect("/home");
        $this->getView()->title = "推荐商品";
        //判断用户角色
        if (\Yii::$app->frontsession->member->bActive < 1) {
            return $this->redirect("/wholesaler/applydesc");
        } else {
            $data = [];
            //获取第一个商品列表配置
            $data['sItemJson'] = $this->actionSampledata(0);
            return $this->render('sample', $data);
        }
    }

    public function actionSampledata($index)
    {
        return $this->redirect("/home");
        $data = [];
        $data['msg'] = "操作成功";
        $data['status'] = 0;
        $data['isMore'] = false;
        $data['data'] = [];
        if (\Yii::$app->frontsession->member->bActive < 1 && $index > 10) {
            return json_encode($data);
        }
        $arrProduct = \Yii::$app->product->getSampleProduct();
        if ($arrProduct) {
            $data['data']['commodity'] = [];
            foreach ($arrProduct as $product) {
                $fSupplierPrice = '供货价：¥' . number_format($product->fSupplierPrice, 2); //供货价
                $price = '促销价：¥' . number_format($product->fPrice, 2);//促销价
                $market_price = '市场价：¥' . number_format($product->fMarketPrice, 2);//市场价
                $link = Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detail",
                    'id' => $product->lID
                ], true);
                //判断用户角色
                if (\Yii::$app->frontsession->member->bActive < 1) {
                    $fSupplierPrice = '';
                    $price = '上市周期：' . $product->sPeriod;
                    $market_price = '核心产地：' . $product->sProductReduce;
                    $link = '';
                }
                $title = $product->sName;
                //规格
                if ($product->sStandard) {
                    $title = $title . ' ' . $product->sStandard;
                }
                //口味
                if ($product->sTaste) {
                    $title = $title . ' ' . $product->sTaste;
                }
                $data['data']['commodity'][] = [
                    'title' => $title,
                    'fSupplierPrice' => $fSupplierPrice, //供货价
                    'price' => $price,//促销价
                    'market_price' => $market_price,//促销价 . $product->fMarketPrice : '',//市场价
                    'image' => \Yii::$app->request->imgUrl . '/' . $product->sMasterPic,
                    'saleout' => $product->bSaleOut ? true : false,
                    'seckill' => [],
                    'icon' => $product->icon,
                    'link' => $link,
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

    /**
     * 商品详情页展示 免费商品
     * @param int $id 商品id
     */
    public function actionSampledetail($id)
    {
        return $this->redirect("/home");
        $product = \Yii::$app->product->findByID($id);
        $member = \Yii::$app->frontsession->member;
        $data['fShowSalePrice'] = $product->getFShowPrice($member->SupplierID);
        $data['bWholesale'] = $member->bActive;

        //商品详情
        if (!$id || !$product || $product->bDel) {
            $this->getView()->title = "商品已删除";
            return $this->render('del');
        }

        /* @var Product $product */
        $data['product'] = $product;


        //商品规格
        $arrSpec = \Yii::$app->productspec->getArrSpec($id, $product->sMasterPic);
        $data['arrSpec'] = $arrSpec;

        $data['lCartProductQty'] = \Yii::$app->cart->lProductQty;

        $this->getView()->title = $product->sName;

        //去掉库存为0的规格，Mars，2018年6月22日 18:24:29
        $arrJson = json_decode($arrSpec['sJsonGroup'], true);
        foreach ($arrJson as $ID => $json) {
            if (!$json['count']) {
                unset($arrJson[$ID]);
            }
        }
        $data['arrSpec']['sJsonGroup'] = $arrJson ? json_encode($arrJson) : json_encode([]);

        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");

        //商品参数
        $data['arrParamValue'] = array_values(\Yii::$app->productparamvalue->getArrParamValue($id));

        return $this->render('sampledetail', $data);
    }

    /**
     * 商品详情页展示 付费商品
     * @param int $id 商品id
     */
    public function actionDefrayproductdetail($id)
    {
        if (!strpos($_SERVER['REQUEST_URI'], 'shop01')) {
            $this->getView()->title = "商品信息错误";
            return $this->render('del');
        }
        $product = \Yii::$app->product->findByID($id);
        $data['fShowSalePrice'] = $product->fPrice;
        $data['bWholesale'] = 1;

        //商品详情
        if (!$id || !$product || $product->bDel) {
            $this->getView()->title = "商品已删除";
            return $this->render('del');
        }

        /* @var Product $product */
        $data['product'] = $product;

        $this->getView()->title = $product->sName;

        $data['arrSpec']['sJsonGroup'] = json_encode([]);

        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");

        //商品参数
        $data['arrParamValue'] = array_values(\Yii::$app->productparamvalue->getArrParamValue($id));

        return $this->render('detail', $data);
    }


    /**
     * 商品详情页
     * @param int $id 商品id
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-7 15:10:36
     */
    public function actionShowdetail($id)
    {
        return $this->redirect("/home");
        $product = \Yii::$app->product->findByID($id);
        $data['fShowSalePrice'] = number_format($product->fPrice, 2);
        $data['bWholesale'] = 1;

        //商品详情
        if (!$id || !$product || $product->bDel) {
            $this->getView()->title = "商品已删除";
            return $this->render('del');
        }

        /* @var Product $product */
        $data['product'] = $product;


        $data['lCartProductQty'] = \Yii::$app->cart->lProductQty;

        $this->getView()->title = $product->sName;


        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");


        return $this->render('showdetail', $data);
    }

    /*
     * 添加商品
     */
    public function actionAdd()
    {
        if (\Yii::$app->request->isPost) {
            //创建运费模板
            $areaList = $_POST['areaList'];
            if (!empty($_POST['ProductID'])) {
                $product = \Yii::$app->product->findByID($_POST['ProductID']);
            } else {
                $product = new Product();
            }
            $product->sName = $_POST['sName'];
            $product->SupplierID = \Yii::$app->frontsession->member->SupplierID;
            $product->fPrice = $_POST['fPrice'];
            $product->fSupplierPrice = $_POST['fSupplierPrice'];
            $product->lStock = $_POST['lStock'];
            $product->ShipTemplateID = $this->saveShipTemplate($areaList, $product->sName, $product->SupplierID, $product->ShipTemplateID);
            $product->sNoDelivery = $areaList;
            //轮播图
            if ($_POST['imglist']) {
                $arrImg = [];
                foreach ($_POST['imglist'] as $key => $img) {
                    if (substr($img, 0, 5) == 'data:') {
                        $arrFileInfo = File::parseImageFromBase64($img);
                        $sFileName = date('YmdHis') . $key . "." . $arrFileInfo[0];
                        $res = File::backToUploadDir($sFileName, $arrFileInfo[1]);
                        $arrImg[] = $res;

                    } else {
                        $res = str_ireplace("http://product.aiyolian.cn/", "", $img);
                        $arrImg[] = $res;
                    }
                    if ($key == 0) {
                        //主图
                        $product->sMasterPic = $res;
                    }
                }
                $product->sPic = json_encode($arrImg);
            }
            //详情图
            if ($_POST['photoList']) {
                $arrImg = [];
                $str = '';
                foreach ($_POST['photoList'] as $key => $img) {
                    if (substr($img, 0, 5) == 'data:') {
                        $arrFileInfo = File::parseImageFromBase64($img);
                        $sFileName = date('YmdHis') . $key . "." . $arrFileInfo[0];
                        $res = File::backToUploadDir($sFileName, $arrFileInfo[1]);
                        $arrImg[] = $res;
                        $str .= "<img src='http://product.aiyolian.cn/$res'/>";
                    } else {
                        $res = str_ireplace("http://product.aiyolian.cn/", "", $img);
                        $arrImg[] = $res;
                        $str .= "<img src='http://product.aiyolian.cn/$res'/>";
                    }
                }
                $product->PathID = json_encode($arrImg);
                $product->sContent = $str;
            }
            $product->save();
        } else {
            $data = [];
            $arrProduct = [
                'sName' => null,
                'fPrice' => null,
                'fSupplierPrice' => null,
                'lStock' => null,
                'PathID' => null,//主图
                'sPic' => null,//轮播图
            ];
            if (!empty($_GET['ProductID'])) {
                $product = $product = \Yii::$app->product->findByID($_GET['ProductID']);
                $arrProduct['sName'] = $product->sName;
                $arrProduct['fPrice'] = $product->fPrice;
                $arrProduct['fSupplierPrice'] = $product->fSupplierPrice;
                $arrProduct['lStock'] = $product->lStock;
                $arrProduct['PathID'] = json_decode($product->PathID);
                $arrProduct['sPic'] = json_decode($product->sPic);

                //不发货地区
            }
            $data['product'] = json_encode($arrProduct);
            $data['sNoDelivery'] = json_encode(explode(",", trim($product->sNoDelivery, ',')));
            return $this->render('add', $data);
        }

    }

    /**
     * 新建保存
     * @author 陈鹭明
     */
    private function saveShipTemplate($areaList, $sName, $supplierID, $lID = 0)
    {
        //后台供应商ID
        $supplier = Supplier::findOne($supplierID);
        $userID = $supplier->SysUserID;
        $date = \Yii::$app->formatter->asDatetime(time());

        //运费模板主表
        if ($lID) {
            $ShipTemplate = ShipTemplate::findOne(['lID' => $lID]);
        } else {
            $ShipTemplate = new ShipTemplate();
            $ShipTemplate->dNewDate = $date;
            $ShipTemplate->OwnerID = $userID;
            $ShipTemplate->NewUserID = $userID;
        }

        $ShipTemplate->sName = $sName;
        $ShipTemplate->CountryID = 1; //发货地——国家
        $ShipTemplate->ProvinceID = 110000; //发货地——省
        $ShipTemplate->CityID = 110100; //发货地——市
        $ShipTemplate->AreaID = 110101; //发货地——地区
        $ShipTemplate->sValuation = 'Number'; //计价方式
        $ShipTemplate->sShipMethod = ';EXPRESS;'; //运送方式
        $ShipTemplate->bSetFree = 0; //是否指定条件包邮
        $ShipTemplate->bBearFreight = "buyerBearFre"; //是否包邮 默认自定义
        $ShipTemplate->EditUserID = $userID;
        $ShipTemplate->dEditDate = $date;
        $ShipTemplate->sFreeTypeJson = '{"list":[]}';
        $ShipTemplate->sConsignDateJson = '';
        $ShipTemplate->sDeliveryJson = '{"express":{"enabled":true,"global":false,"start":"1","postage":"0.00","plus":1,"postageplus":"0.00","wayDay":"20","delivery":"express","inherit":false,"message":[]},"ems":{"enabled":false,"global":false,"start":"1","postage":"","plus":"1","postageplus":"","wayDay":"20","delivery":"ems","inherit":false},"post":{"enabled":false,"global":false,"start":"1","postage":"","plus":"1","postageplus":"","wayDay":"20","delivery":"post","inherit":false}}';
        $ShipTemplate->sDeliveryAddressJson = '{"path":[1,110000,110100,110101]}';
        $ShipTemplate->sProductFrom = '中国 北京 北京市 东城区';
        $ShipTemplate->save();

        //运费模板明细
        ShipTemplateDetail::deleteAll(['ShipTemplateID' => $ShipTemplate->lID]);
        $ShipTemplateDetail = new ShipTemplateDetail();
        $ShipTemplateDetail->ShipTemplateID = $ShipTemplate->lID;
        $ShipTemplateDetail->sShipMethod = 'EXPRESS'; //运送方式
        $ShipTemplateDetail->sType = 'default'; //类型
        $ShipTemplateDetail->lStart = 1; //首件/首重/首体积
        $ShipTemplateDetail->fPostage = 0; //首费
        $ShipTemplateDetail->lPlus = 1; //续件/续重/续体积
        $ShipTemplateDetail->fPostageplus = 0; //续费
        $ShipTemplateDetail->save();

        //不发货地区--(港澳台,海外) 820000,810000,990000,710000
        $areaList = $areaList . '820000,810000,990000,710000';
        $arrArea = Area::find()->where("UpID in($areaList)")->select('ID')->asArray()->all();
        $ShipTemplateNoDelivery = new  ShipTemplateNoDelivery();
        $ShipTemplateNoDelivery->ShipTemplateID = $ShipTemplate->lID;
        $arrArea = array_column($arrArea, 'ID');
        $ShipTemplateNoDelivery->sAreaID = implode(",", $arrArea);
        $ShipTemplateNoDelivery->save();

        return $ShipTemplate->lID;
    }


    /**
     * 组团详情页面
     * @param int $id 商品id
     * @return string
     */
    public function actionGroup($id)
    {
        $group = Group::findOne($id);
        $date = \Yii::$app->formatter->asDatetime(time());
        if ($group->dEnd < $date) {
            //团已结束，回到首页
            $url = \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true);
            $this->redirect($url);
        }
        $product = \Yii::$app->product->findByID($group->ProductID);
        $data['fShowSalePrice'] = number_format($product->fSalePrice, 2);
        $data['bWholesale'] = 1;

        //商品详情
        if (!$id || !$product || $product->bDel) {
            $this->getView()->title = "商品已删除";
            return $this->render('del');
        }

        /* @var Product $product */
        $data['product'] = $product;


        //商品规格
        $arrSpec = \Yii::$app->productspec->getArrSpec($id, $product->sMasterPic);
        $data['arrSpec'] = $arrSpec;

        $data['lCartProductQty'] = \Yii::$app->cart->lProductQty;

        $this->getView()->title = $product->sName;

        //去掉库存为0的规格，Mars，2018年6月22日 18:24:29
        $arrJson = json_decode($arrSpec['sJsonGroup'], true);
        foreach ($arrJson as $ID => $json) {
            if (!$json['count']) {
                unset($arrJson[$ID]);
            }
        }
        $data['arrSpec']['sJsonGroup'] = $arrJson ? json_encode($arrJson) : json_encode([]);

        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");

        //商品参数
        $data['arrParamValue'] = array_values(\Yii::$app->productparamvalue->getArrParamValue($id));


        //轮播图
        $member = \Yii::$app->frontsession->member->findOne($group->MemberID);
        $shop = \Yii::$app->sellershop->getShop($member->lID);
        $this->getView()->title = $shop->sName;
        $data['logo'] = '/' . $shop->sLogoPath;
        $data['shop'] = $shop;
        $data['group'] = $group;
        $data['member'] = $member;

        $groupMember = GroupMember::find()->where(['GroupID' => $group->lID])->asArray()->all();
        $data['groupMember'] = json_encode($groupMember);
        $TotalNum = array_sum(array_column($groupMember, 'lNum'));
        $data['TotalNum'] = $TotalNum;
        return $this->render('group', $data);
    }

    /*
     * 开团
     */
    public function actionSetgroup()
    {
        $ProductID = $_POST['ProductID'];
        $ProductName = $_POST['sName'];
        $MemberID = \Yii::$app->frontsession->MemberID;
        $recommend = RecommendProduct::findOne(9);
        $date = \Yii::$app->formatter->asDatetime(time());
        $group = new Group();
        $group->ProductID = rtrim($ProductID, ',');
        $group->sProductName = $ProductName;
        $group->MemberID = $MemberID;
        $group->dStart = $date;
        $group->dEnd = $recommend->dEnd;
        $group->fMoney = $recommend->fMoney;
        $group->save();

        return $this->asJson(['status' => true, 'message' => '开团成功', 'id' => $group->lID]);
    }

    public function actionMembergroup($id)
    {
        $data = [];
        $group = Group::findOne($id);
        //组装前端要的数据
        $data['arrSupplier'] = [];
        $data['arrInvalid'] = [];
        $productIDs = $group->ProductID;
        $arrID = explode(',', $productIDs);
        $bRedProduct = 0;
        foreach ($arrID as $lID) {
            $product = Product::findOne($lID);
            $recomProduct = RecommendProductDetail::find()->where(['RecommendID' => 9, 'ProductID' => $lID])->one();
            if ($recomProduct->fRedBagMoney > 0) {
                $bRedProduct = 1;
            }
            $supplier = $product->supplier;
            if (!isset($data['arrSupplier'][$supplier->lID])) {
                $data['arrSupplier'][$supplier->lID] = [
                    'shop_id' => $supplier->lID,
                    'shop_name' => $supplier->sName,
                    'shop_link' => '',
                    'is_selected' => true
                ];
            }

            $arrProductInfo = [
                'id' => $product->lID,
                'pic' => $product->masterPic,
                'name' => $product->sName,
                'price' => $product->fPrice,
                'fMarketPrice' => '市场价：￥' . $product->fMarketPrice,
                'total' => $product->fPrice,

                'num' => 1,
                'link' => \yii\helpers\Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detailgroup",
                    'id' => $product->lID,
                    'groupID' => $group->lID,
                ], true),
                'stock' => '库存：' . $product->lStock,

                'is_selected' => true
            ];
            //失效状态判断
            $data['arrSupplier'][$supplier->lID]['products'][] = $arrProductInfo;
        }


        //统计数量、总价
        $data['lQty'] = 0;
        $data['fTotal'] = 0;
        foreach ($data['arrSupplier'] as $SupplierID => $v) {
            if (!$v['products']) {
                unset($data['arrSupplier'][$SupplierID]);
            } else {
                foreach ($v['products'] as $p) {
                    $data['lQty'] += $p['num'];
                    $data['fTotal'] += $p['total'];
                }
            }
        }

        //判断是否有商品券
        $date = \Yii::$app->formatter->asDatetime(time());
        $data['arrRed'] = [];
        $data['redProduct'] = [];
        $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date]])->asArray()->all();
        if ($redProduct) {
            $bRedProduct=0;
            $data['redProduct'] = $redProduct;
        } elseif(!$redProduct){
            $bRedProduct = Redbagproduct::addRedbagproduct();
            $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['>', 'dEnd', $date]])->asArray()->all();
            $data['redProduct'] = $redProduct;
        }elseif(!$redProduct) {
            //通用券
            $arrRed = Redbag::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['bUserd' => 0]])->orderBy('fChange desc')->asArray()->all();
            $data['arrRed'] = $arrRed;
        }
        $data['bRedProduct'] = $bRedProduct;

        //团长信息
        $member = Member::findOne($group->MemberID);
        $shop = SellerShop::findOne($member->lID);
        $this->getView()->title = $shop->sName;
        $data['shop'] = $shop;
        $data['shoptitle'] = $shop->sName;
        $data['group'] = $group;
        $data['groupaddress'] = Groupaddress::findOne(['MemberID' => $group->MemberID]);
        $data['member'] = $member;

        $arrOrder = Order::find()->orderBy('dNewDate desc')->limit(60)->all();
        $buyers = [];
        foreach ($arrOrder as $key => $order) {
            $member = $order->member;
            $buyers[$key]['logo'] = $member->sAvatarPath;
            $buyers[$key]['sName'] = $member->sName;
        }
        $data['buyers'] = json_encode($buyers);

        return $this->render("membergroup", $data);
    }

    public function actionGroupaddress()
    {
        $getdata = [
            'sName' => $_POST['name'],
            'sMobile' => $_POST['mobile'],
            'sProvince' => explode(",", $_POST['area'])[0],
            'sCity' => explode(",", $_POST['area'])[1],
            'sArea' => explode(",", $_POST['area'])[2],
            'sAddress' => $_POST['address']
        ];
        $shop = \Yii::$app->sellershop->getShop(\Yii::$app->frontsession->MemberID);
        $shop->sName = $_POST['name'];
        $shop->save();
        $AddressID = Groupaddress::newAddress($getdata);
        $data = [];
        $data['status'] = true;
        $data['addressid'] = $AddressID;
        $data['message'] = "";


        return $this->asJson($data);
    }

    /**
     * 商品详情页
     * @param int $id 商品id
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-7 15:10:36
     */
    public function actionDetailgroup_0303($id, $groupID)
    {
        $product = \Yii::$app->product->findByID($id);
        $data['fShowSalePrice'] = number_format($product->fSalePrice, 2);
        $data['bWholesale'] = 1;
        //商品详情
        if (!$id || !$product || $product->bDel) {
            $this->getView()->title = "商品已删除";
            return $this->render('del');
        }
        /* @var Product $product */
        $data['product'] = $product;
        $data['groupID'] = $groupID;
        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");
        $this->getView()->title = '来瓜分';
        $data['logo'] = \Yii::$app->request->imgUrl . '/' . \myerm\shop\common\models\MallConfig::getValueByKey("sMallLogo");
        $arrOrder = Order::find()->orderBy('dNewDate desc')->limit(60)->all();
        $buyers = [];
        foreach ($arrOrder as $key => $order) {
            $member = $order->member;
            $buyers[$key]['logo'] = $member->sAvatarPath;
            $buyers[$key]['sName'] = $member->sName;
        }
        $data['buyers'] = json_encode($buyers);
        return $this->render('detailgroup', $data);
    }

    /*
     * 样品购买
     */
    public function actionDetailsingle()
    {
        $product = \Yii::$app->product->findByID(3211);
        //1、非代理回首页
        $memberID = \Yii::$app->frontsession->MemberID;
        if (empty($memberID)) {
            $this->redirect(Url::toRoute([
                \Yii::$app->request->shopUrl . '/member/loginpost',
                'sReturnUrl' => \Yii::$app->request->rootUrl . \Yii::$app->request->url
            ], true));
	    return;
        }
        $seller = Seller::findOne($memberID);
        if (!$seller) {
            $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home/home"], true));
            return;
        }
        //2、已买过回首页
        $arrOrder = Order::find()->alias('o')->select('o.lID,o.StatusID,lQuantity')
            ->leftJoin('orderdetail', 'orderdetail.OrderID=o.lID')
            ->where("o.MemberID=$memberID and o.StatusID<>'closed' and orderdetail.ProductID=3211")
            ->all();
        if ($arrOrder) {
            $totalNum = 0;
            foreach ($arrOrder as $order) {
                if ($order->StatusID == 'unpaid') {
                    Order::updateAll(
                        [
                            'StatusID' => Order::STATUS_CLOSED,
                            'dCloseDate' => \Yii::$app->formatter->asDatetime(time()),
                            'sCloseReson' => '自动关闭'
                        ], ['lID' => $order->lID]);
                } elseif ($order->StatusID == 'paid') {
                    $totalNum += 1;
                }
            }

            if ($totalNum > 0) {
                $this->redirect(Url::toRoute([\Yii::$app->request->shopUrl . "/home/index"], true));
                return;
            }
        }

        //3、关闭待付款的订单
        //4、只能买一件

        $data['fShowSalePrice'] = number_format($product->fSalePrice, 2);
        $data['bWholesale'] = 1;

        /* @var Product $product */
        $data['product'] = $product;
        $data['arrSpec'] = [];
        $data['lCartProductQty'] = \Yii::$app->cart->lProductQty;
        $data['arrSpec']['sJsonGroup'] = json_encode([]);

        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");

        //商品参数
        $data['arrParamValue'] = [];


        $this->getView()->title = '来瓜分';
        //轮播图
//        if ($TopID) {
//            $data['logo'] = '/' . $shop->sLogoPath;
//            $data['shop'] = $shop;
//        } else {
        $data['logo'] = \Yii::$app->request->imgUrl . '/' . \myerm\shop\common\models\MallConfig::getValueByKey("sMallLogo");
        //}

        return $this->render('single', $data);
    }

    /**
     * 商品详情页
     * @param int $id 商品id
     * @return string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-10-7 15:10:36
     */
    public function actionDetailgroup($id)
    {
        $product = \Yii::$app->product->findByID($id);
        $data['fShowSalePrice'] = number_format($product->fSalePrice, 2);
        $data['bWholesale'] = 1;
        //商品详情
        if (!$id || !$product || $product->bDel) {
            $this->getView()->title = "商品已删除";
            return $this->render('del');
        }
        /* @var Product $product */
        $data['product'] = $product;
        //售后说明
        $data['sAfterSaleNote'] = MallConfig::getValueByKey("sAfterSaleNote");
        $this->getView()->title = '来瓜分';
        $data['logo'] = \Yii::$app->request->imgUrl . '/' . \myerm\shop\common\models\MallConfig::getValueByKey("sMallLogo");
        $arrOrder = Order::find()->orderBy('dNewDate desc')->limit(60)->all();
        $buyers = [];
        foreach ($arrOrder as $key => $order) {
            $member = $order->member;
            $buyers[$key]['logo'] = $member->sAvatarPath;
            $buyers[$key]['sName'] = $member->sName;
        }
        $data['buyers'] = json_encode($buyers);
        $date = \Yii::$app->formatter->asDatetime(time());
        $recommendProduct = RecommendProduct::findOne(9);
        $timerDate = $recommendProduct->dStrart;
        $timerMsg = '距开始仅剩';
        if ($date > $recommendProduct->dStrart && $date < $recommendProduct->dEnd) {
            $timerDate = $recommendProduct->dEnd;
            $timerMsg = '距截单仅剩';
        }
        $data['timerDate'] = $timerDate;
        $data['timerMsg'] = $timerMsg;
        return $this->render('dgroup', $data);
    }
    public function actionGroupapply()
    {
        $getdata = [
            'sName' => $_POST['name'],
            'sMobile' => $_POST['mobile'],
            'bGroup' => $_POST['bGroup'],
            'bShop' => $_POST['bShop'],
            'bHas' => $_POST['bHas'],
            'sMsg' => $_POST['sMsg'],
            'sProvince' => explode(",", $_POST['area'])[0],
            'sCity' => explode(",", $_POST['area'])[1],
            'sArea' => explode(",", $_POST['area'])[2],
            'sAddress' => $_POST['address']
        ];
        $AddressID = Groupapply::newAddress($getdata);
        $data = [];
        $data['status'] = true;
        $data['addressid'] = $AddressID;
        $data['message'] = "提交成功！";


        return $this->asJson($data);
    }
}