<?php

namespace common\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use common\assets\CKEditorAsset;

class CKEditorInput extends InputWidget
{

    /**
     * @var array 插件选项, 用于传递给 CKEDITOR.replace() 的第二个参数.
     */
    public $pluginOptions = [];


    /**
     * 注册 asset
     * 
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        CKEditorAsset::register($this->view);
        $this->getInputId();
    }


    /**
     * {@inheritdoc}
     * 
     * @return string
     */
    public function run()
    {
        $this->registerEditorOptions();
        return $this->textarea();
    }


    public function getInputId()
    {
        $id = $this->options['id'] ?? false;
        if($id === false) {
            if($this->hasModel()) {
                $id = Html::getInputId($this->model, $this->attribute);
            } else {
                $id = $this->getId();
            }
            $this->options['id'] = $id;
        }
        return $id;
    }

    /**
     * 获取 textarea
     * 
     * @return  string 
     */
    public function textarea()
    {
        $options = array_merge($this->field->inputOptions, $this->options);
        if($this->hasModel()) {
             return Html::activeTextarea($this->model, $this->attribute, $options);
        }
        return Html::textarea($this->name, $this->value, $options);
    }


    /**
     * 注册 CKEDITOR 
     * @return [type] [description]
     */
    public function registerEditorOptions()
    {
        $options = $this->pluginOptions;
        if(!isset($options['defaultLanguage'])) {
            $options['defaultLanguage'] = Yii::$app->language;
        }
        $config = Json::encode($options);
        $this->view->registerJs("CKEDITOR.replace('{$this->getInputId()}', {$config});");
    }

}