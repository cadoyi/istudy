<?php

namespace backend\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\base\Widget;
use yii\bootstrap\BootstrapPluginAsset;

class Permission extends Widget
{

    public $options = ['class' => 'permission-tree'];

    public $role;

    public $ulOptions = ['class' => 'permission-ul'];

    public $liOptions = ['class' => 'permission-li'];


    public function init()
    {
        parent::init();
        if($this->role === null) {
            throw new InvalidConfigException('The {role} must be set.');
        }
        if(!isset($this->options['id'])) {
            $this->options['id'] = $this->id;
        }
    }


    public function run()
    {
        $this->registerClientScript();
        $all = $this->prepare();
        $ul = $this->renderItems($all);
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        return Html::tag($tag, $ul, $options);
    }

    public function registerClientScript()
    {
        BootstrapPluginAsset::register($this->view);
        $id = $this->options['id'];
        $this->view->registerJs("\$('#{$id}').click({
            selector : 'a',
            handler : function() {
               var ul = \$(this).siblings('ul');
               var checked = ul.find(':checked').length;
               var all = ul.find('input[type=\"checkbox\"]').length;
               var value = !(all == checked);
               ul.find('input[type=\"checkbox\"]').each(function() {
                   \$(this).prop('checked', value);
               });
               return false;
            }
        });");
    }

    public function renderItems($all, $prefix = '')
    {
        $html = Html::beginTag('ul', $this->ulOptions);
        foreach($all as $name => $item) {
            $key = trim($prefix . '/' . $name, '/');
            $html .= Html::beginTag('li', $this->liOptions);
            if(is_array($item)) {
                $html .= Html::a($this->getLabel($key), '#');
                $html .= $this->renderItems($item, $key);
            } else {
                if($name === 0 || $name === '0') {
                    $name = 'all';
                    $key = trim($prefix . '/' . $name, '/');
                    $value = $prefix;
                    $html .= $this->renderItem($item, $key, $value);
                } else {
                    $html .= $this->renderItem($item, $key);  
                }
                
            }
            $html .= Html::endTag('li');
        }
        $html .= Html::endTag('ul');
        return $html;
    }

    public function renderItem($value, $name, $checkboxValue = null)
    {
        if(is_null($checkboxValue)) {
            $checkboxValue = $name;
        }
        return Html::checkbox('permission[' . $checkboxValue . ']' , $value, ['label' => $this->getLabel($name)]);
    }

    public function prepare()
    {
        $data = [];
        foreach($this->role->allPermissions as $name => $permission) {
            ArrayHelper::setValue($data, $this->getKey($name), false);
        }
        foreach($this->role->permissions as $name => $permission) {
            $old = ArrayHelper::getValue($data, $this->getKey($name));
            if(is_array($old)) {
                $old[0] = true;
                ArrayHelper::setValue($data, $this->getKey($name), $old);
            } else {
                ArrayHelper::setValue($data, $this->getKey($name), true);
            }
            
        }
        return $data;
    }

    public function getKey($name)
    {
        return str_replace('/', '.', $name);
    }

    public function getLabel($name)
    {
        $pos = strrpos($name, '/');
        if($pos !== false && $pos >= 0) {
            $name = substr($name, $pos +1);
        }
        $name = str_replace('_', ' ', $name);
        return Yii::t('app', ucfirst(strtolower($name)));
    }





}
