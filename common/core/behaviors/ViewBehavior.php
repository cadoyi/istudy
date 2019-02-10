<?php

namespace core\behaviors;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\base\Behavior;
use core\helpers\App;

class ViewBehavior extends Behavior 
{

	public function getMediaUrl($suffix = '')
	{
        return App::getMediaUrl($suffix);
	}

	public function getImageUrl($image = '')
	{
        return App::getImageUrl($image);
	}

	public function getBodyClass()
	{
		$route = Yii::$app->controller->route;
		return str_replace('/', '-', $route);
	}

    public function getParam($key, $default = '')
    {
    	return ArrayHelper::getValue($this->owner->params, $key, $default);
    }

    public function setParam($key, $value)
    {
    	return ArrayHelper::setValue($this->owner->params, $key, $value);
    }

    public function getAppParam($key, $default = '')
    {
        return App::getParam($key, $default);
    }

    public function setAppParam($key, $value)
    {
    	return App::setParam($key, $value);
    }

}