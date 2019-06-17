<?php

namespace common\models;

use Yii;
use common\models\queries\PostTagQuery;
use core\db\ActiveRecord;

/**
 * post tag 表, 这个表为复合主键
 *
 * @property integer $tag_id 
 * @property integer $post_id 
 * 
 */
class PostTag extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_tag}}';
    }


    /**
     * @inheritdoc
     * 
     * @return ActiveQuery
     */
    public static function find()
    {
        return Yii::createObject(PostTagQuery::class, [ get_called_class() ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'tag_id'], 'required'],
            [['post_id', 'tag_id'], 'integer'],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tag_id'  => Yii::t('all', 'Tag'),
            'post_id' => Yii::t('all', 'Post'),
        ];
    }


    /**
     * 获取 post 关联
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }


    /**
     * 获取 tag 关联
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }
    
}
