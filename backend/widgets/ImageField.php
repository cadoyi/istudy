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
    
    public $url;
    //public $url;

    public $imageContainerOptions = ['class' => 'pull-left', 'style' => ['padding-right'=> '15px']];

    public $inputContainerOptions = ['class' => 'pull-left', 'style' => ['padding'=> '6px 0']];

    public $inputGroupOptions = ['class' => 'input-group'];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $form = $this->field->form;
        if(!isset($form->options['encrypt'])) {
            $form->options['enctype'] = 'multipart/form-data';
        }
    }

    public function run()
    {
        $input = Html::tag('div', $this->renderInput(), $this->inputContainerOptions);
        if($this->url) {
            $image = Html::tag('div', $this->renderImage(), $this->imageContainerOptions);
            $input = $image . $input;
        }
        
        return Html::tag('div', $input, ['class' => 'input-group'], $this->inputGroupOptions);
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