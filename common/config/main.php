<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'charset'        => 'UTF-8',
    'language'       => 'zh-CN',
    'sourceLanguage' => 'en-US',
    'timeZone'       => 'Asia/Shanghai',
    'bootstrap' => ['initable'],
    'components' => [
        'initable' => [
            'class' => 'common\components\Initable',
            'handlers' => [
                'fs' => function($fs) {
                    $plugin = $fs->getBehavior('urlPlugin');
                    if($plugin) {
                        $plugin->attachPlugin();
                    }
                },
                'wysiwyg' => function($fs) {
                    $plugin = $fs->getBehavior('urlPlugin');
                    if($plugin) {
                        $plugin->attachPlugin();
                    }
                }
            ],
        ],
        'cache' => [
            'class'           => 'yii\caching\FileCache',
            'cachePath'       => '@common/runtime/cache',
            'defaultDuration' => 300,
        ],
        'fs' => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => '@media',
            'as urlPlugin' => [
                'class'   => 'common\behaviors\FlysystemPlugin',
                'baseUrl' => '@mediaUrl', 
            ],
        ],
        'wysiwyg' => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => '@media/wysiwyg',
            'as urlPlugin' => [
                'class'   => 'common\behaviors\FlysystemPlugin',
                'baseUrl' => '@mediaUrl/wysiwyg', 
            ],
        ],
        'formatter' => [
            'timeZone'        => 'Asia/Shanghai',
            'defaultTimeZone' => 'Asia/Shanghai',
            'dateFormat'      => 'php:Y-m-d',
            'timeFormat'      => 'php:H:i:s',
            'datetimeFormat'  => 'php:Y-m-d H:i:s',
            'nullDisplay'     => '',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => YII_DEBUG ? null : 'cache',
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'app' => 'all.php',
                    ],
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'all'     => 'all.php',
                        'admin'   => 'all.php',    // @deprecated
                        'front'   => 'all.php',    // @deprecated
                        'web'     => 'all.php',    // @deprecated
                    ],
                ],
            ],
        ],
    ],
];
