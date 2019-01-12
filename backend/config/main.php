<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'edu-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
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
            'timeout' => 1440,
            'cookieParams' => [
                'lifetime' => 1440, 
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
                    ],
                    'css' => [
                        'css/styles.css',
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
