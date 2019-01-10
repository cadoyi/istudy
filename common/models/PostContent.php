<?php

namespace common\models;

use Yii;
use common\query\PostContentQuery;

class PostContent extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%post_content}}';
    }

    public static function find()
    {
        return Yii::createObject(PostContentQuery::className(), [ get_called_class() ]);
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function isPrimary()
    {
        return $this->post->last_content == $this->id;
    }

}