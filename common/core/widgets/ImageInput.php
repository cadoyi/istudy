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

    public $containerOptions = ['class' => 'input-group'];

	public $imageOptions = ['class' => 'img-responsive'];

	public $linkOptions = ['class' => 'upload-link'];

	public $inputType = 'file';


    public function init()
    {
    	parent::init();
    	if(!isset($this->deleteAttribute)) {
    		throw new InvalidConfigException('{deleteAttribute} must be set.');
    	}
        $form = $this->field->form;
        if(!isset($form->options['enctype'])) {
            $form->options['enctype'] = 'multipart/form-data';
        }
    }


    /**
     * {@inheritdoc}
     */
	public function run()
	{
		$image = $this->renderImage();
		$input = $this->renderInput();
		$delete = $this->renderDelete();
		return Html::tag('div', $image . $input . $delete, $this->containerOptions);
	}




    /**
     * 渲染图片
     * @return string 
     */
	public function renderImage()
	{
		if($this->url) {
			$image = Html::img($this->url, $this->imageOptions);
			$a = Html::a($image, $this->url, $this->linkOptions);
			return $a;
		}
		return '';
	}


    /**
     * 渲染 input 框
     * @return string
     */
	public function renderInput()
	{
        return $this->renderInputHtml($this->inputType);
	}


    /**
     * 只有图片已经存在,并且不是必须的,就可以渲染 delete 框
     * 
     * @return string delete input
     */
	public function renderDelete()
	{
        if($this->url && !$this->model->isAttributeRequired($this->attribute)) {
        	return Html::activeCheckbox($this->model, $this->deleteAttribute);
        }
        return '';
	}
}