<?php

namespace myerm\common\libs;

/**
 * 公共函数库-文件助手类
 */
class File extends \yii\helpers\FileHelper
{
    /**
     * 获取后缀名
     * @param $sFileName
     */
    public static function getExtension($sFileName)
    {
        return substr($sFileName, strrpos($sFileName, ".") + 1);
    }

    /**
     * 从base64提取文件信息
     */
    public static function parseImageFromBase64($sBase64Code)
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $sBase64Code, $result)) {
            return [$result[2], base64_decode(str_replace($result[1], '', $sBase64Code))];
        }

        return null;
    }

    /**
     * 把文件保存到系统指定的文件夹
     * @param $sFileName
     * @param $sFileContent
     * @return string
     */
    public static function putContentToUploadDir($sFileName, $sFileContent)
    {
        file_put_contents(static::getUploadDir() . "/" . $sFileName,
            $sFileContent);

        return "userfile/upload/" . date("Y-m-d") . "/" . $sFileName;
    }


    public static function getUploadDir()
    {
        File::createDirectory(\Yii::$app->params['sUploadDir'] . "/userfile");
        File::createDirectory(\Yii::$app->params['sUploadDir'] . "/userfile/upload");
        File::createDirectory(\Yii::$app->params['sUploadDir'] . "/userfile/upload/" . date("Y-m-d"));

        return \Yii::$app->params['sUploadDir'] . "/userfile/upload/" . date("Y-m-d");
    }
    //后台路径
    public static function backToUploadDir($sFileName, $sFileContent)
    {
        file_put_contents(static::getBackloadDir() . "/" . $sFileName,
            $sFileContent);

        return "userfile/upload/" . date("Y-m-d") . "/" . $sFileName;
    }


    public static function getBackloadDir()
    {
        File::createDirectory("/home/www/yl/apps/backend/web/userfile");
        File::createDirectory("/home/www/yl/apps/backend/web/userfile/upload");
        File::createDirectory("/home/www/yl/apps/backend/web/userfile/upload/" . date("Y-m-d"));

        return "/home/www/yl/apps/backend/web/userfile/upload/" . date("Y-m-d");
    }


}
