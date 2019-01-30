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
            'label' => Yii::t('all','Dashboard'),
            'url' => ['site/index'],
            'icon' => 'dashboard',
        ],
        [
            'label' => Yii::t('all', 'Permission'),
            'icon' => 'users',	                
            'items' => [
                [
                    'label' => Yii::t('all', 'User'),
                	'url' => ['admin/index'],
                ],
                [
                    'label' => Yii::t('all', 'Role'),
                    'url'   => ['role/index'],
                ],
            ],
        ],
        [
            'label' => Yii::t('all', 'Customer'),
            'icon'  => 'user',
            'items' => [
                [
                    'label' => Yii::t('all', 'Customer group'),
                    'url' => ['customer-group/index'],
                ],
                [
                    'label' => Yii::t('all', 'Customer'),
                    'url' => ['customer/index'],
                ],
            ],
            
        ],
        [
            'label' => Yii::t('all', 'Category'),
            'icon' => 'book',
            'url' => ['category/index'],
        ],
        [
            'label' => Yii::t('all', 'Post'),
            'icon' => 'newspaper-o',
            'url'  => ['post/index'],
        ],
        [
            'label' => Yii::t('all', 'Tag'),
            'icon'  => 'tag',
            'url'   => ['tag/index'],
        ],
        [
            'label' => Yii::t('all', 'Comment'),
            'icon'  => 'comment',
            'url'   => ['comment/index'],
        ]
    ],

]) ?>


