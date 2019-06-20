<?php

namespace backend\models;

use Yii;
use yii\base\Exception;

class BrowseException extends Exception
{

    /**
     * @inheritdoc
     * 
     * @return string
     */
    public function getName()
    {
        return 'BrowseException';
    }
} 