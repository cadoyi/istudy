<?php

namespace common\widgets;

use Yii;

/**
 * 日期插件只让它选择日期.
 * 
 * 
 */
class DatePicker extends \kartik\datetime\DateTimePicker
{

    public $bsVersion = 3;

    public $pluginOptions = [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd',
        'minView' => 2,
    ];

    public $layout = '{picker} {input} {remove}';
}