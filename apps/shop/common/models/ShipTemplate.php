<?php

namespace myerm\shop\common\models;

use myerm\backend\common\libs\SystemTime;

/**
 * 运费模板
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年9月21日 16:41:01
 * @version v1.0
 */
class ShipTemplate extends ShopModel
{
	/**
	 * 配置数据源
	 * @return \yii\db\Connection
	 */
	public static function getDb()
	{
		return \Yii::$app->get('ds_cloud');
	}
    /**
     * 获取关联的不发货地区
     * @return \yii\db\ActiveQuery
     */
    public function getNodelivery()
    {
        return $this->hasOne(ShipTemplateNoDelivery::className(), ['ShipTemplateID'=>'lID']);
    }


    /**
     * 新建保存
     * @author 陈鹭明
     */
    public static function newSave()
    {
        $data = [];
	    $arrDeliveryJson = json_decode(htmlspecialchars_decode($_POST['deliveryJson']), true);
	    //bug优化 会出现重复地区的问题，是因为gid重复了 oyyz 2018-5-7 17:44:57  从根本上解决需要从前端JS入手@todo
	    $i = 1;
	    foreach ($arrDeliveryJson['express']['special'] as $key => $special ) {
		    $special['gid'] = 'n'.$i;
		    $arrDeliveryJson['express']['special'][$key]  = $special;
		    $i ++;
	    }
	
	
	    //是否指定条件包邮
        $freeType = json_decode(htmlspecialchars_decode($_POST['freeType']), true);
        $setFree = "0";
        if ($freeType['list']) {
            $setFree = "1";
        }

        //获取运送方式
        $arrTplType = [];
        foreach ($arrDeliveryJson as $key => $value) {
            if ($value['enabled']) {
                if ($key == 'express') {
                    $TplType = 'EXPRESS';
                } elseif ($key == 'ems') {
                    $TplType = 'EMS';
                } elseif ($key == 'post') {
                    $TplType = 'POSTAGE';
                } else {
                    continue;
                }
                $arrTplType[] = $TplType;
            }
	        foreach ($value['special'] as $k => $s) {
		        if (!trim($s['start']) || !trim($s['postage']) || !trim($s['plus']) || !trim($s['postageplus']) || !$s['areas']) {
			        unset($arrDeliveryJson[$key]['special'][$k]);
		        }
	        }
        }
	    $sJsonDeliveryJson = json_encode($arrDeliveryJson,true);
        $tplTypes = ";" . implode(";", $arrTplType) . ";";

        /*是否包邮 暂时不需要 oyyz 2016-05-18 19:51
         switch ($_POST['bBearFreight'])
         {
         case 0:
         $buyerBearFre = "buyerBearFre";
         case 2:
         $buyerBearFre = "sellerBearFre";
         default:
         $buyerBearFre = "buyerBearFre";
         }*/

        //获取计价方式
        switch ($_POST['valuation']) {
            case 0:
                $valuation = "Number";
                break;
            case 1:
                $valuation = "Weight";
                break;
            case 2:
                $valuation = "Volume";
                break;
            default:
                $valuation = "Number";
                break;
        }
        //运费模板主表
        if ($_POST['ID']) {
            $ShipTemplate = ShipTemplate::findOne(['lID' => $_POST['ID']]);
            if (!$ShipTemplate) {
                throw new UserException("你查看的对象数据不存在。");
            }
        } else {
            $ShipTemplate = new ShipTemplate();
            $ShipTemplate->dNewDate = SystemTime::getCurLongDate();
            $ShipTemplate->OwnerID = \Yii::$app->backendsession->SysUserID;
            $ShipTemplate->NewUserID = \Yii::$app->backendsession->SysUserID;
        }

        $ShipTemplate->sName = $_POST['sName'];
        $ShipTemplate->CountryID = $_POST['country']; //发货地——国家
        $ShipTemplate->ProvinceID = $_POST['province']; //发货地——省
        $ShipTemplate->CityID = $_POST['city']; //发货地——市
        $ShipTemplate->AreaID = $_POST['area']; //发货地——地区
        $ShipTemplate->sValuation = $valuation; //计价方式
        $ShipTemplate->sShipMethod = $tplTypes; //运送方式
        $ShipTemplate->bSetFree = $setFree; //是否指定条件包邮
        $ShipTemplate->bBearFreight = "buyerBearFre"; //是否包邮 默认自定义
        $ShipTemplate->EditUserID = \Yii::$app->backendsession->SysUserID;
        $ShipTemplate->dEditDate = SystemTime::getCurLongDate();
        $ShipTemplate->sFreeTypeJson = $_POST['freeType'];
        $ShipTemplate->sConsignDateJson = $_POST['consignDate'];
        $ShipTemplate->sDeliveryJson = $sJsonDeliveryJson;
        $ShipTemplate->sDeliveryAddressJson = $_POST['deliveryAddress'];
        $sCountry = Area::findOne($_POST['country'])->sName;
        $sProvince = Area::findOne($_POST['province'])->sName;
        $sCity = Area::findOne($_POST['city'])->sName;
        $sArea = Area::findOne($_POST['area'])->sName;
        $ShipTemplate->sProductFrom = implode(" ", [$sCountry, $sProvince, $sCity, $sArea]);
        $ShipTemplate->save();
	
	    ShipTemplateDetail::deleteAll(['ShipTemplateID' => $ShipTemplate->lID]);
	    
        //运费模板明细
        foreach ($arrDeliveryJson as $key => $value) {
            if ($value['enabled']) {
                $ShipTemplateDetail = new ShipTemplateDetail();
                $ShipTemplateDetail->ShipTemplateID = $ShipTemplate->lID;
                if ($value['delivery'] == 'express') {
                    $value['delivery'] = 'EXPRESS';
                } elseif ($value['delivery'] == 'ems') {
                    $value['delivery'] = 'EMS';
                } elseif ($value['delivery'] == 'post') {
                    $value['delivery'] = 'POSTAGE';
                }
                $ShipTemplateDetail->sShipMethod = $value['delivery']; //运送方式
                $ShipTemplateDetail->sType = 'default'; //类型
                $ShipTemplateDetail->lStart = number_format($value['start'], 2); //首件/首重/首体积
                $ShipTemplateDetail->fPostage = number_format($value['postage'], 2); //首费
                $ShipTemplateDetail->lPlus = number_format($value['plus'], 2); //续件/续重/续体积
                $ShipTemplateDetail->fPostageplus = number_format($value['postageplus'], 2); //续费
                $ShipTemplateDetail->save();
                if ($value['special']) {
                    foreach ($value['special'] as $sKey => $sValue) {
                        if ($sValue['delivery'] == 'express') {
                            $sValue['delivery'] = 'EXPRESS';
                        } elseif ($sValue['delivery'] == 'ems') {
                            $sValue['delivery'] = 'EMS';
                        } elseif ($sValue['delivery'] == 'post') {
                            $sValue['delivery'] = 'POSTAGE';
                        }
                        $sAreaID = "," . implode(",", $sValue['areas']) . ",";
                        $ShipTemplateDetailSpecial = new ShipTemplateDetail();
	                    $ShipTemplateDetailSpecial->ShipTemplateID = $ShipTemplate->lID;
	                    $ShipTemplateDetailSpecial->sAreaID = $sAreaID;//指定区域
	                    $ShipTemplateDetailSpecial->sShipMethod = $sValue['delivery']; //运送方式
	                    $ShipTemplateDetailSpecial->sType = 'designatedArea'; //类型
	                    $ShipTemplateDetailSpecial->lStart = number_format($sValue['start'], 2); //首件/首重/首体积
	                    $ShipTemplateDetailSpecial->fPostage = number_format($sValue['postage'], 2); //首费
	                    $ShipTemplateDetailSpecial->lPlus = number_format($sValue['plus'], 2); //续件/续重/续体积
	                    $ShipTemplateDetailSpecial->fPostageplus = number_format($sValue['postageplus'], 2); //续费
	                    $ShipTemplateDetailSpecial->save();
                    }
                }
            }
        }

        if ($setFree == '1') {
	        ShipTemplateFree::deleteAll(['ShipTemplateID' => $ShipTemplate->lID]);
	        $jsonFreeType = json_decode(htmlspecialchars_decode($_POST['freeType']), true);
            //运费置顶包邮明细
            foreach ($jsonFreeType['list'] as $fKey => $fValue) {
                //运送方式
                switch ($fValue['transType']) {
                    case '-4':
                        $transType = 'EXPRESS';
                        break;
                    case '-7':
                        $transType = 'EMS';
                        break;
                    case '-1':
                        $transType = 'POSTAGE';
                        break;
                    default:
                        $transType = '';
                }
                $arrFreeAreaID = [];
                foreach ($fValue['areas'] as $AreaID) {
                    if (substr($AreaID, 2) > 0) {
                        $arrFreeAreaID[] = $AreaID;
                    } else {
                        $province = Area::findOne($AreaID);
                        if ($province['sType'] == 'Province') {
                            $arrfreeCity = Area::getSubAreas($AreaID);
                            foreach ($arrfreeCity as $arrfreeCityV) {
                                $arrFreeAreaID[] = $arrfreeCityV['ID'];
                            }
                        }
                    }
                }
                $FreeAreaID = "," . implode(",", $arrFreeAreaID) . ",";
                $ShipTemplateFree = new ShipTemplateFree();
                $ShipTemplateFree->ShipTemplateID = $ShipTemplate->lID; //运费模板lID
                $ShipTemplateFree->sFreeAreaID = $FreeAreaID; //指定区域
                $ShipTemplateFree->sFreeShipMethod = $transType; //运送方式
                $ShipTemplateFree->sFreeExpressType = ''; //快递类型
                $ShipTemplateFree->lFreeType = $fValue['designated']; //包邮条件类型
                $ShipTemplateFree->fFreeNumber = $fValue['preferentialStandard']; //包邮数量
                $ShipTemplateFree->fFreeMoney = $fValue['preferentialMoney']; //包邮金额
                $ShipTemplateFree->save();
            }
        }
        if ($_POST['noDeliveryJson']) {
            ShipTemplateNoDelivery::deleteAll(['ShipTemplateID' => $ShipTemplate->lID]);
            foreach ($_POST['noDeliveryJson'] as $noDelivery) {
                $arrAreaSelect = explode(",", $noDelivery);

                $arrAreaID = [];
                foreach ($arrAreaSelect as $AreaID) {
                    if (substr($AreaID, 2) > 0) {
                        $arrAreaID[] = $AreaID;
                    } else {
                        $province = Area::findOne($AreaID);
                        if ($province['sType'] == 'Province') {
                            $arrCity = Area::getSubAreas($AreaID);
                            foreach ($arrCity as $city) {
                                $arrAreaID[] = $city['ID'];
                            }
                        }
                    }
                }

                $ShipTemplateNoDelivery = new ShipTemplateNoDelivery();
                $ShipTemplateNoDelivery->ShipTemplateID = $ShipTemplate->lID;
                $ShipTemplateNoDelivery->sAreaID = implode(",", $arrAreaID);
                $ShipTemplateNoDelivery->save();
            }
        }
        $data['ID'] = $ShipTemplate->lID;
	    return $data;
    }

    /**
     * 新建保存
     * @author 陈鹭明
     */
    public static function editSave()
    {
        return static::newSave();
    }


    public static function getShipMethodName($key = '')
    {
        switch ($key) {
            case 'EXPRESS':
                $ShipMethodName = '快递';
                break;
            case 'EMS':
                $ShipMethodName = 'EMS';
                break;
            case 'POSTAGE':
                $ShipMethodName = '平邮';
                break;
            default:
                $ShipMethodName = '快递';
                break;
        }
        return $ShipMethodName;
    }
}