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
    'components' => [
        'cache' => [
            'class'           => 'yii\caching\FileCache',
            'cachePath'       => '@common/runtime/cache',
            'defaultDuration' => 300,
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
