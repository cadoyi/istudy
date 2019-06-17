<?php

namespace backend\grid;

use Yii;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn
{

    public $template = '{view} {update} {delete}';


    
    public $header;
    

    /**
     * {@inheritdoc}
     * @return [type] [description]
     */
    public function init()
    {
        parent::init();
        if(is_null($this->header)) {
            $this->header = Yii::t('app', 'Action');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('app', 'View');
                        $class = 'btn-default showPageModal';
                        break;
                    case 'update':
                        $title = Yii::t('app', 'Update');
                        $class = 'btn-primary';
                        break;
                    case 'delete':
                        $title = Yii::t('app', 'Delete');
                        $class = 'btn-danger';
                        break;
                    default:
                        $title = ucfirst($name);
                        $class = 'btn-default';
                }
                $className = 'btn btn-sm ' . $class . ' action-' . strtolower($name);
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                    'class' => $className,
                ], $additionalOptions, $this->buttonOptions);

                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                $content = $icon . ' ';
                return Html::a($content, $url, $options);
            };
        }
    }


}