<?php

namespace core\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;

class ImageInput extends InputWidget
{

    /**
     * @var string  图片的 url 路径
     */
	public $url;

	public $deleteAttribute;

	public $imageOptions = [];

	public $linkOptions = [];

	public $inputType = 'image';


    public function init()
    {
    	parent::init();
    	if(!isset($this->deleteAttribute)) {
    		throw new InvalidConfigException('{deleteAttribute} must be set.');
    	}
    }



	public function run()
	{
		$image = $this->renderImage();
		$input = $this->renderInput();
		$delete = $this->renderDelete();
		return $image . $input . $delete;
	}

	public function renderImage()
	{
		if($this->url) {
			$image = Html::img($this->url, $this->imageOptions);
			$a = Html::a($image, $this->url, $this->linkOptions);
			return $a;
		}
		return '';
	}

	public function renderInput()
	{
        return $this->renderInputHtml($this->inputType);
	}

	public function renderDelete()
	{
        if($this->url) {
        	if($this->hasModel()) {
        		return Html::activeCheckbox($this->model, $this->deleteAttribute);
        	}
        	return Html::checkbox($this->deleteAttribute);
        }
        return '';
	}
}