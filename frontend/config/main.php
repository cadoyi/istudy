<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'edu-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'layout' => 'layout',
    'components' => [
        'request' => [
            'csrfParam' => 'csrf',
        ],
        'response' => [
            'format' => 'html',
        ],
        'user' => [
            'identityClass' => 'common\models\Customer',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_frontend', 'httpOnly' => true],
            'autoRenewCookie' => true,
            'loginUrl' => ['site/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name'         => 'frontend',
            'cookieParams' => [
                'lifetime' => 86400, 
                'httpOnly' => true,
                'secure' => !YII_DEBUG,
            ],
            'timeout'      => 86400,
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
            'errorAction' => 'site/error',   // only for html
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'common' => [
                    'class' => 'yii\web\AssetBundle',
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'js/scripts.js',
                    ],
                    'css' => [
                        'css/styles.css',
                    ],
                    'depends' => [
                        'yii\web\YiiAsset',
                        'yii\bootstrap\BootstrapPluginAsset',
                    ],
                ],
            ],
        ],
        'view' => [
            'as view' => 'core\behaviors\ViewBehavior',
        ],
        
    ],
    'params' => $params,
];
