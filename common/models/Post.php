<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\query\PostQuery;

/**
 * Post table
 *
 * @property integer $id 
 * @property string  $title 文章标题
 * @property integer $category_id 
 * @property string  $description 
 * @property integer $status
 * @property string  $url_path
 * @property boolean $only_category 
 * @property integer $last_content 
 * @property integer $created_at 
 * @property integer $updated_at 
 * @property integer $created_by 
 * @property integer $updated_by 
 * 
 */
class Post extends ActiveRecord
{
    const STATUS_PRIVATE = 0;
    const STATUS_PUBLIC  = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => TimestampBehavior::className(),
            'blameable' => BlameableBehavior::className(),
            'url_path' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'url_path',
                'immutable'        => true,   // title 改变也不用重新生成
                'ensureUnique'     => true,   // 确保唯一
                'uniqueValidator'  => [
                    'targetAttribute' => 'url_path',
                    'targetClass'     => static::className(),
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'on' => [
               static::SCENARIO_DEFAULT,
               static::SCENARIO_CREATE,
               static::SCENARIO_UPDATE,
            ], 'length' => [1, 255]],
            [['title'], 'required'],
            [['description'], 'required'],
            [['description'], 'string', 'length' => [1,255]],
            [['category_id'], 'required'],
            [['category_id'], 'integer'],
            [['category_id'], 
                'exist', 
                'targetClass' => Category::className(), 
                'targetAttribute' => 'id',
                'when' => function($model, $attribute) {
                    return $model->isAttributeChanged($attribute);
                },
            ],
            [['status'], 'default', 'value' => 1],
            [['status'], 'integer', 'max' => 255],
            [['url_path'], 'default', 'value' => null],
            [['url_path'], 'string', 'length' => [2,255]],
            [['url_path'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['only_category'], 'default', 'value' => 0],
            [['only_category'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'title'         => Yii::t('all', 'Post title'),
            'description'   => Yii::t('all', 'Post description'),
            'category_id'   => Yii::t('all', 'Category'),
            'status'        => Yii::t('all', 'Status'),
            'url_path'      => Yii::t('all', 'Url path'),
            'only_category' => Yii::t('all', 'Only use for category'),
            'created_at'    => Yii::t('all', 'Created time'),
            'updated_at'    => Yii::t('all', 'Updated time'),
            'created_by'    => Yii::t('all', 'Author'),
            'updated_by'    => Yii::t('all', 'Revisor'),
        ];
    }
    public function loadByCategory($category)
    {
        $this->category_id   = $category->id;
        $this->only_category = 1;
        $this->title         = $category->title;
        $this->url_path      = $category->url_path;
        $this->status        = static::STATUS_PUBLIC;
    }


    public static function statusOptions()
    {
        return [
            static::STATUS_PUBLIC => Yii::t('all', 'Public'),
            static::STATUS_PRIVATE => Yii::t('all', 'Private'),
        ];
    }





    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(PostQuery::className(), [ get_called_class() ]);
    }

    public function canDelete()
    {
        return !$this->only_category;
    }


    /**
     * 获取当前文章对应的分类实例
     * 
     * @return common\models\Category
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }


    /**
     * 获取收藏夹中的所有收藏
     * 
     * @return common\models\CustomerFavorite 
     * @see  hasMany()
     */
    public function getFavorites()
    {
        return $this->hasMany(CustomerFavorite::className(), ['post_id' => 'id'])->inverseOf('post');
    }



    /**
     * 获取收藏这篇文章的用户列表
     * 
     * @see  hasMany()
     * @return [type] [description]
     */
    public function getFavoritedCustomers()
    {
        return $this->hasMany(Customer::className(), ['id' => 'customer_id'])
          ->via('favorites');
    }


    /**
     * 获取当前最新的文章
     * 
     * @return common\models\PostContent
     */
    public function getContent()
    {
        return $this->hasOne(PostContent::className(), ['post_id' => 'id'])
           -> inverseOf('post');
    }


    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['post_id' => 'id'])->inverseOf('post');
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
           -> via('postTags');
    }


}