<?php

namespace common\models;

use Yii;

class Tag extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tag}}';
    }

    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['tag_id' => 'id'])->inverseOf('tag');
    }

    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])
                    ->via('postTags');
    }


}