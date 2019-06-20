<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\models\queries\PostQuery;
use core\db\ActiveRecord;

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
            [['title'], 'required'],
            [['title'], 'string', 'length' => [1,255]],
            [['description'], 'required'],
            [['description'], 'string', 'length' => [1,255]],
            [['category_id'], 'required'],
            [['category_id'], 'integer'],
            [['category_id'], 
                'exist', 
                'targetClass' => Category::className(), 
                'targetAttribute' => 'id',
                'filter' => function($query) {
                    $query->select('id');
                },
                'when' => function($model, $attribute) {
                    return $model->isAttributeChanged($attribute);
                },
            ],
            [['is_active'], 'default', 'value' => 1],
            [['is_active'], 'boolean'],
            [['url_path'], 'default', 'value' => null],
            [['url_path'], 'string', 'length' => [2,255]],
            [['url_path'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['meta_title'], 'default', 'value' => function() {
                return $this->title;
            } ],
            [
                ['meta_title', 'meta_keywords', 'meta_description'], 
                'string', 
                'length' => [0,255]
            ],
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }



    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'title'         => Yii::t('all', 'Post title'),
            'description'   => Yii::t('all', 'Post description'),
            'category_id'   => Yii::t('all', 'Category'),
            'is_active'     => Yii::t('all', 'Status'),
            'url_path'      => Yii::t('all', 'Url path'),
            'meta_title'    => Yii::t('all', 'Meta title'),
            'meta_keywords' => Yii::t('all', 'Meta keywords'),
            'meta_description' => Yii::t('all', 'Meta description'),
            'content'          => Yii::t('all', 'Post content'),
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

    public function saveTags($newTags)
    {
        if(empty($newTags)) {
            $newTags = [];
        }
        $tags = $this->tags;
        if(empty($tags) && empty($newTags)) {
            return true;
        }
        $newids = ArrayHelper::getColumn($newTags, 'id');
        $oldids = array_keys($tags);

        $insert = array_diff($newids, $oldids);
        $delete = array_diff($oldids, $newids);

        foreach($insert as $tag_id) {
            $postTag = new PostTag([
                'tag_id' => $tag_id,
                'post_id' => $this->id,
            ]);
            $postTag->save();
        }
        $postTags = $this->postTags;
        foreach($delete as $tag_id) {
            $postTag = isset($postTags[$tag_id]) ? $postTags[$tag_id] : null;
            if($postTag) {
                $postTag->delete();
            }
        }
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



    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['post_id' => 'id'])
           -> indexBy('tag_id')
           -> inverseOf('post');
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
           -> indexBy('id')
           -> via('postTags');
    }

    public function getComments()
    {
        return $this->hasMany(PostComment::className(), ['post_id' => 'id'])
          ->andWhere(['status' => PostComment::STATUS_REVIEWED])
        ->inverseOf('post');
    }


}