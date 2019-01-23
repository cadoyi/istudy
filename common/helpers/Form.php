<?php

namespace common\helpers;

use Yii;
use yii\helpers\Html;

class Form
{
    const BOOLEAN_TRUE  = 1;
    const BOOLEAN_FALSE = 0;

	public static function t($message)
	{
		$message = ucfirst($message);
		return Yii::t('web', $message);
	}

    public static function booleanList(array $active = [])
    {
    	$yes = 'Yes';
    	$no = 'No';
        if(!empty($active)) {
           list($yes, $no) = $active;
        }
        return [
            static::BOOLEAN_TRUE   => Yii::t('web', static::t($yes)),
            static::BOOLEAN_FALSE  => Yii::t('web', static::t($no)),
        ];
    }

    public static function statusList()
    {
    	return static::booleanList(['enabled', 'disabled']);
    }

    public static function submitButton($content = 'Submit', $options = [])
    {
        $content = static::t($content);
        return Html::submitButton($content, $options);
    }

}