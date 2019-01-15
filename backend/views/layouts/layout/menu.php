<?php

use yii\widgets\Menu;
use yii\helpers\Html;
?>

<?= Menu::widget([
    'options' => [
        'id' => 'menus',
        'class' => ['list-unstyled', 'menus'],
    ],
    'submenuTemplate' => "\n<ul class=\"list-unstyled\">\n{items}\n</ul>\n",
    'firstItemCssClass' => 'first',
    'lastItemCssClass'  => 'last',
    'encodeLabels' => true,
    'activateParents' => true,
    'items' => [
        [
            'label' => Yii::t('admin','Dashboard'),
            'url' => ['dashboard/index'],
            'template' => '<a title="{label}" href="{url}"><span class="glyphicon glyphicon-home"></span> {label}</a>',
        ],
        [
            'label' => Yii::t('admin', 'Role'),
            'template' => '<span class="glyphicon glyphicon-user"></span> {label}',	                
            'items' => [
                [
                    'label' => 'create admin',
                    'url' =>  ['/user/create'],
                ],
                [
                    'label' => 'index',
                	'url' => ['/site/index'],
                ],
            ],
        ],
        [
            'label' => Yii::t('admin', 'Customer'),
            'template' => '<a title="{label}" href="{url}"><span class="glyphicon glyphicon-home"></span> {label}</a>',
            'url' => ['customer/index'],
        ],
        [
            'label' => Yii::t('admin', 'Category'),
            'template' => '<a title="{label}" href="{url}"><span class="glyphicon glyphicon-home"></span> {label}</a>',
            'url' => ['category/index'],
        ],
        [
            'label' => Yii::t('admin', 'Post'),
            'template' => '<a title="{label}" href="{url}"><span class="glyphicon glyphicon-home"></span> {label}</a>',
            'url' => ['post/index'],
        ],




    ],


]) ?>


