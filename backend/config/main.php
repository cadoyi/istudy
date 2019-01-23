<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'edu-backend',
    'name' => "My sister's website",
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'layout'  => 'layout',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'loginUrl' => ['site/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name'    => 'backend',
            'timeout' => 86400,
            'cookieParams' => [
                'lifetime' => 86400, 
                'httpOnly' => true, 
                'secure' => !YII_DEBUG,
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => !YII_DEBUG,
            'bundles' => [
                'common' => [
                    'class' => 'yii\web\AssetBundle',
                    'basePath' => '@webroot',
                    'baseUrl'  => '@web',
                    'js' => [
                        'js/scripts.js',
                        'js/layout.js',

                    ],
                    'css' => [
                        'css/styles.css',
                        'css/layout.css',
                        'css/font-awesome.css',
                    ],
                    'depends' => [
                        'yii\bootstrap\BootstrapPluginAsset',
                        'yii\web\YiiAsset',
                    ],
                ],
            ],
        ],
        
    ],
    'params' => $params,
];
