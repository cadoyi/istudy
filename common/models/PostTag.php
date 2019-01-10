<?php

namespace common\models;

use Yii;

class PostTag extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%post_tag}}';
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }
    
}
