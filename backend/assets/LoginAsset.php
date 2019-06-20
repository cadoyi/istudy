<?php

namespace backend\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{

    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $js = [

    ];

    public $css = [
       'css/login.css',
    ];

    /**
     * @var  array  设置依赖关系
     * @see  assetManager::$bundles 
     */
    public $depends = [
        'common',         
    ];
}