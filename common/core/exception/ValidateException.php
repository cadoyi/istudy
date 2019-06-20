<?php

namespace core\exception;

use Yii;
use yii\base\Exception;

class ValidateException extends Exception
{

	public function getName()
	{
		return 'validateException';
	}
}