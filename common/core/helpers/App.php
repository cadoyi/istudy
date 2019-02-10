<?php

namespace core\helpers;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class App
{

    /**
     * 获取 app 参数
     * @param  string|callback $key   可以用点分开的key
     * @param  mixed $default         默认值
     * @see  ArrayHelper::getValue()
     * @return mixed
     */
	public static function getParam($key, $default = null)
	{
		return ArrayHelper::getValue(Yii::$app->params, $key, $default);
	}


    /**
     * 设置 app 参数
     * @param  string $key     键
     * @param  mixed  $value   值
     * @see  ArrayHelper::setValue()
     */
	public static function setParam($key, $value)
	{
        return ArrayHelper::setValue(Yii::$app->params, $key, $value);
	}


    /**
     * 获取 media url
     * @param  string $suffix  URL 后缀
     * @return string url
     */
    public static function getMediaUrl($suffix = '')
    {
    	return Url::to('@web/media/') . ltrim($suffix, '/');
    }


    /**
     * 获取 media path
     * 
     * @param  string $suffix path路径后缀
     * @return string  路径
     */
    public static function getMediaPath($suffix = '')
    {
    	return Yii::getAlias('@media') . DIRECTORY_SEPARATOR . ltrim($suffix, '/\\');
    }


    /**
     * 获取 web 图片路径
     * 
     * @return [type] [description]
     */
    public static function getImageUrl($suffix = '')
    {
        return Url::to('@web/images/') . ltrim($suffix, '/');
    }

    

}