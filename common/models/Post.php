<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
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
                'exists', 
                'targetClass' => Category::className(), 
                'targetAttribute' => 'id',
                'when' => function($model, $attribute) {
                    return $model->isAttributeChanged($attribute);
                },
            ],
            [['status'], 'default', 'value' => 1],
            [['status'], 'integer', 'max' => 255],
            [['url_path'], 'string', 'length' => [2,255]],
            [['url_path'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['only_category'], 'default', 'value' => 0],
            [['only_category'], 'boolean'],
            [['last_content'], 'required'],
            [['last_content'], 'integer'],
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
            'last_content'  => Yii::t('all', 'Last content'),
            'created_at'    => Yii::t('all', 'Created time'),
            'updated_at'    => Yii::t('all', 'Updated time'),
            'created_by'    => Yii::t('all', 'Author'),
            'updated_by'    => Yii::t('all', 'Revisor'),
        ];
    }



    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(PostQuery::className(), [ get_called_class() ]);
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
     * 获取文章的内容实例.
     *
     * @see hasMany()
     * @return array
     */
    public function getContents()
    {
        return $this->hasMany(PostContent::className(), ['post_id' => 'id'])
           -> orderBy(['id' => SORT_DESC]) 
           -> inverseOf('post');
    }

    

    /**
     * 获取当前最新的文章
     * 
     * @return common\models\PostContent
     */
    public function getContent()
    {
        return $this->hasOne(PostContent::className(), ['post_id' => 'id'])
           -> andWhere(['id' => $this->last_content])
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