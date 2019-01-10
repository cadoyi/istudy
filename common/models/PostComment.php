<?php

namespace common\models;

use Yii;

class PostComment extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%post_comment}}';
    }


    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public function getParent()
    {
        if($this->parent_id) {
            return $this->hasOne(static::className(), ['id' => 'parent_id']);
        }
        return null;
    }


}