<?php

use backend\widgets\Menu;
use yii\helpers\Html;
?>

<?= Menu::widget([
    'options' => [
        'id' => 'menus',
        'class' => ['list-unstyled', 'menus'],
    ],
    'items' => [
        [
            'label' => Yii::t('admin','Dashboard'),
            'url' => ['site/index'],
            'boot-icon' => 'dashboard',
        ],
        [
            'label' => Yii::t('admin', 'Role'),
            'boot-icon' => 'user',	                
            'items' => [
                [
                    'label' => 'create admin',
                    'url' =>  ['/admin/create'],
                ],
                [
                    'label' => 'index',
                	'url' => ['/admin/index'],
                ],
            ],
        ],
        [
            'label' => Yii::t('admin', 'Customer'),
            'icon'  => 'user',
            'url' => ['customer/index'],
        ],
        [
            'label' => Yii::t('admin', 'Category'),
            'icon' => 'home',
            'url' => ['category/index'],
        ],
        [
            'label' => Yii::t('admin', 'Post'),
            'icon' => 'home',
            'url' => ['post/index'],
        ],

    ],

]) ?>


