<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\query\PostContentQuery;

/**
 * post content
 *
 * @property integer $id 
 * @property integer $post_id
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property integer $created_at
 * 
 */
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
    public function formName()
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
            'updated_at'  => Yii::t('all', 'Updated time'),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(PostContentQuery::className(), [ get_called_class() ]);
    }

    public function fields()
    {
        return [
           'id',
           'meta_keywords' => 'keywords',
           'meta_description' => 'description',
           'content'
        ];
    }


    /**
     * 获取文章实例
     * 
     * @see hasMany
     * @return common\query\PostQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id'])->inverseOf('content');
    }

}