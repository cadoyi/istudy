<?php

namespace common\models;

use Yii;

class Contact extends ActiveRecord
{


    /**
     * {@inheritdoc}
     * @return [type] [description]
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }


}