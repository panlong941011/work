<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 6:07
 */

namespace myerm\shop\mobile\models;

use myerm\shop\common\models\ShopModel;


/**
 * 供应商类
 */
class AlliaceFun extends ShopModel
{
    //联盟双方互换商品
    public function setProduct($AMemberID, $SMemberID)
    {
        $date = date('Y-m-d H:i:s');
        $arrData = [];
        $arr = ['MemberID' => $AMemberID, 'ProductID' => -1, 'dNewDate' => $date, 'dEditDate' => $date, 'StatusID' => 0, 'CatID' => 1];
        if($AMemberID) {
            $arrSPorduct = Product::find()->select('lID')->where(['SupplierID' => $this->SupplierID, 'bSale' => 1, 'bCheck' => 1])->all();
            foreach ($arrSPorduct as $product) {
                $arr['ProductID'] = $product->lID;
                $arrData[] = $arr;
            }
        }
        if($SMemberID) {
            $arr['MemberID'] = $SMemberID;
            $arrAPorduct = Product::find()->select('lID')->where(['SupplierID' => $this->AlliaceFunID, 'bSale' => 1, 'bCheck' => 1])->all();
            foreach ($arrAPorduct as $prdouct) {
                $arr['ProductID'] = $product->lID;
                $arrData[] = $arr;
            }
        }
        if($arrData) {
            $table = [
                'MemberID',
                'ProductID',
                'dNewDate',
                'dEditDate',
                'StatusID',
                'CatID'
            ];
            \Yii::$app->db->createCommand()->batchInsert('agentshopproduct', $table, $arrData)->execute();
        }
    }
}