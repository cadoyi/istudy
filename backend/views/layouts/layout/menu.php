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
            'label' => Yii::t('all', 'Menu'),
            'url' => ['menu/index'],
            'icon' => 'tasks',
            'visible' => Yii::$app->user->can('menu/view'),
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
            'visible' => Yii::$app->user->can('permission/view'),
            
        ],
        [
            'label' => Yii::t('all', 'Customer'),
            'icon'  => 'user',
            'items' => [
                [
                    'label' => Yii::t('all', 'Customer group'),
                    'url' => ['customer-group/index'],
                    'visible' => Yii::$app->user->can('customer_group/view'),
                ],
                [
                    'label' => Yii::t('all', 'Customer'),
                    'url' => ['customer/index'],
                    'visible' => Yii::$app->user->can('customer/view'),
                    
                ],
            ],
            
        ],
        [
            'label' => Yii::t('app', 'Content'),
            'icon'  => 'newspaper-o',
            'items' => [
                [
                    'label' => Yii::t('all', 'Category'),
                    'url' => ['category/index'],
                    'visible' => Yii::$app->user->can('category/view'),
                ],
                [
                    'label' => Yii::t('all', 'Post'),
                    'url'  => ['post/index'],
                    'visible' => Yii::$app->user->can('post/view'),
                    
                ],
                [
                    'label' => Yii::t('all', 'Tag'),
                    'url'   => ['tag/index'],
                    'visible' => Yii::$app->user->can('tag/view'),
                    
                ],
                [
                    'label' => Yii::t('all', 'Comment'),
                    'url'   => ['comment/index'],
                    'visible' => Yii::$app->user->can('comment/view'),
                ],
            ],
        ],

        [
            'label' => Yii::t('all', 'Enroll'),
            'icon' => 'usb',
            'url'  => ['enroll/index'],
            'visible' => Yii::$app->user->can('enroll/view'),
        ],
    ],

]) ?>


