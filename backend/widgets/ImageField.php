<?php

namespace backend\widgets;

use Yii;
use yii\bootstrap\InputWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class ImageField extends InputWidget
{
    public $model;

    public $attribute;

    public $view;

    public $field;

    public $imageOptions = ['style' => ['width' => '36px', 'height' => '36px']];
    
    public $url = 'http://cdn.jjglobal.com/media/wysiwyg/app/home2/21-c-59-m.png';

    public $imageContainerOptions = ['class' => 'pull-left', 'style' => ['padding'=> '0 15px']];

    public $inputContainerOptions = ['class' => 'pull-left', 'style' => ['padding'=> '0 15px']];

    public $inputGroupOptions = ['class' => 'input-group'];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $form = $this->field->form;
        if(!isset($form->options['encrypt'])) {
            $form->options['encrypt'] = 'multipart/form-data';
        }
    }

    public function run()
    {
        $image = Html::tag('div', $this->renderImage(), $this->imageContainerOptions);
        $input = Html::tag('div', $this->renderInput(), $this->inputContainerOptions);
        return Html::tag('div', $image . $input, ['class' => 'input-group'], $this->inputGroupOptions);
    }

    public function renderInput()
    {
        return Html::activeFileInput($this->model, $this->attribute, $this->options);
    }

    public function renderImage()
    {
        return Html::img(Url::to($this->url), $this->imageOptions);
    }

}