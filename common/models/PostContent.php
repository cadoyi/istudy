<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\query\PostContentQuery;

class PostContent extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_content}}';
    }



    /**
     * {@inheritdoc}
     */
    public function tableName()
    {
        return 'content';
    }



    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           [['content'], 'string', 'on' => [
               static::SCENARIO_DEFAULT,
               static::SCENARIO_CREATE,
               static::SCENARIO_UPDATE,
           ]],
           [['content'], 'required'],
           [['keywords'], 'string'],
           [['description'], 'string'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'keywords'    => Yii::t('all', 'Meta keywords'),
            'description' => Yii::t('all', 'Meta description'),
            'content'     => Yii::t('all', 'Post content'),
            'post_id'     => Yii::t('all', 'Post'),
            'created_at'  => Yii::t('all', 'Created time'),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(PostContentQuery::className(), [ get_called_class() ]);
    }


    /**
     * 获取文章实例
     * 
     * @see hasMany
     * @return common\query\PostQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }


    /**
     * 此内容是否是文章的主要内容
     * 
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->post->last_content == $this->id;
    }

}