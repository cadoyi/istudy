<?php

namespace backend\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\Column;

class ActionColumn extends \yii\grid\ActionColumn
{

    public $template = '<ul class="nav">
               <li class="dropdown">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                       <span class="glyphicon glyphicon-option-horizontal"></span>
                   </a>
                   <ul class="dropdown-menu">
                       <li class="action-list">{view}</li>
                       <li class="action-list">{update}</li>
                       <li class="action-list">{delete}</li>
                   </ul>
               </li>
            </ul>';

    
    public $header;

    /**
     * {@inheritdoc}
     * @return [type] [description]
     */
    public function init()
    {
        parent::init();
        if(is_null($this->header)) {
            $this->header = Yii::t('admin', 'Action');
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
                        $title = Yii::t('admin', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('admin', 'Update');
                        break;
                    case 'delete':
                        $title = Yii::t('admin', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $className = 'action-' . strtolower($name);
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                    'class' => $className,
                ], $additionalOptions, $this->buttonOptions);

                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                $content = $icon . ' ' . $title;
                return Html::a($content, $url, $options);
            };
        }
    }
}