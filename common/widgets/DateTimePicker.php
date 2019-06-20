<?php

namespace common\widgets;

use Yii;

/**
 * 覆盖用于设置一些自定义选项.
 * 省得每个页面都要写相同的内容.
 * 
 */
class DateTimePicker extends \kartik\datetime\DateTimePicker
{

    public $bsVersion = 3;

    public $pluginOptions = [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd hh:ii:ss',
    ];

    public $layout = '{picker} {input} {remove}';
}