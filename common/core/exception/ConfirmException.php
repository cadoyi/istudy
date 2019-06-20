<?php

namespace core\exception;

use Yii;
use yii\base\Exception;

class ConfirmException extends Exception
{
    public function getName()
    {
        return 'ConfirmException';
    }
}