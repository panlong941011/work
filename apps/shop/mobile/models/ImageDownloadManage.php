<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 3:59
 */

namespace myerm\shop\mobile\models;


use myerm\shop\common\models\ShopModel;

/**
 * 地区类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author Mars
 * @since 2019年3月18日18:53:46
 * @version v2.0
 */
class ImageDownloadManage extends ShopModel
{

    /**
     * 保存图片下载信息
     * @param $sFileName
     */
    public function attach($sFileName, $sSourceUrl)
    {
        $sFileName = basename($sFileName);//做一次格式化
        $imgManage = $this->get($sFileName);
        if ($imgManage) {
            return $imgManage;
        }

        $imgManage = new static();
        $imgManage->sName = $sFileName;
        $imgManage->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $imgManage->bDownload = 0;
        $imgManage->sSourceUrl = $sSourceUrl;
        $imgManage->sFileName = $sFileName;
        $imgManage->sFileNamePath = "userfile/upload/" . date("Y/m/d", time()) . "/" . $sFileName;
        $imgManage->save();

        return $imgManage;
    }

    /**
     * 通过文件名，查找文件
     * @param $sFileName
     */
    public function get($sFileName)
    {
        return static::find()->where(['sName' => $sFileName])->one();
    }

}