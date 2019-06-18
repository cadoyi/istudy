<?php

namespace common\assets;

use Yii;
use yii\web\AssetBundle;
use yii\helpers\Url;

/**
 *
 *
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class CKEditorAsset extends AssetBundle
{

    /**
     * @var string  发布原路径.
     */
    public $sourcePath = '@common/assets/ckeditor';


    /**
     * @var array 发布选项.
     */
    public $publishOptions = [
        'except' => [
            'samples/*',
            'LICENSE.md',
            'README.md',
            'CHANGES.md',
        ],
    ];


    /**
     * @var array  注册的 js
     */
    public $js = [
        'ckeditor.js',
    ];


    /**
     * @var array 依赖的资源列表.
     */
    public $depends = [
        'common',
    ];




    /**
     * 注册 window.CKEDITOR_BASEPATH 变量
     * {@inheritdoc}
     */
    public static function register($view)
    {
        $bundle = parent::register($view);

        $baseUrl = rtrim(Url::to($bundle->baseUrl, true), '/') . '/';

        $view->registerJsVar('CKEDITOR_BASEPATH', $baseUrl);

        return $bundle;
    }



}