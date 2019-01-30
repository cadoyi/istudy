<?php

namespace core\helpers;

use Yii;

class Form
{

	const BOOLEAN_TRUE  = 1;
    const BOOLEAN_FALSE = 0;

	public static function t($message)
	{
		$message = ucfirst($message);
		return Yii::t('all', $message);
	}

    public static function booleanList(array $active = [])
    {
    	list($yes, $no) = empty($active) ? ['yes', 'no'] : $active;

        return [
            static::BOOLEAN_TRUE   => static::t($yes),
            static::BOOLEAN_FALSE  => static::t($no),
        ];
    }

    public static function statusList()
    {
    	return static::booleanList(['enabled', 'disabled']);
    }

    public static function sexList()
    {
        return static::booleanList(['male', 'female']);
    }

}