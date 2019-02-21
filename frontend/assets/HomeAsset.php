<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{

    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $js = [
       'plugins/slick/slick.min.js',
    ];

    public $css = [
       'plugins/slick/slick.css',
       'plugins/slick/slick-theme.css',
    ];

    public $depends = [
        'frontend\assets\AppAsset',
    ];
}