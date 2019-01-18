<?php

namespace common\models;

use Yii;

class ActiveRecord extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';


    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [ get_called_class() ]);
    }


    /**
     * get primary key.
     */
    public function getId()
    {
        return $this->getPrimaryKey(false);
    }


    /**
     * set primary key
     * @param  integer $id  ID 的值.
     */
    public function setId($id)
    {
        $keys = $this->primaryKey();
        $pk = $keys[0];
        $this->$pk = $id;
    }


    /**
     * 允许 form 输出的字段
     * @return array
     */
    public function formFields()
    {
        return $this->attributes();
    }

}