<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\queries\CategoryQuery;
use core\behaviors\UploadedBehavior;
use core\helpers\App;
use core\db\ActiveRecord;

/**
 * Category table
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string  $path
 * @property integer $level
 * @property string  $url_path
 * @property string  $title
 * @property string  $description
 * @property boolean $is_active 
 * @property integer $position 
 * @property string  $image
 * @property string  $meta_title
 * @property string  $meta_description
 * @property string  $meta_keywords 
 * @property string  $content 
 * @property integer $created_at 
 * @property integer $updated_at
 * @property integer $created_by 
 * @property integer $updated_by
 * 
 */
class Category extends ActiveRecord
{

    const LEVEL_START = 1;

    const CACHE_TAG_ALL = 'Categories';

    public $imageFile;

    public $imageDelete;

    /**
     * @var static 验证用的父分类实例.
     */
    private $_tempParent;


    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => TimestampBehavior::className(),
            'blameable' => BlameableBehavior::className(),
            'url_path'  => [
                'class' => SluggableBehavior::className(),
                'attribute'           => 'title',
                'slugAttribute'       => 'url_path',
                'immutable'           => true,   // title 改变也不用重新生成
                'ensureUnique'        => true,   // 确保唯一
                'uniqueValidator'     => [
                    'targetAttribute' => 'url_path',
                    'targetClass'     => static::className(),
                ],
            ],
            /*
            'image' => [
                'class' => UploadBehavior::className(),
                'attribute' => 'categoryImage',
                'targetAttribute' => 'image',
                'path' => 'category',
                'absolutePath' => '@media/category',
            ], */
            'image' => [
                'class' => UploadedBehavior::className(),
                'attribute' => 'image',
                'path'      => '@media/category',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     * @return string 表名
     */
    public static function tableName()
    {
        return '{{%category}}';
    }


    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'category';
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
            [['parent_id'], 'default', 'value' => null],
            [['parent_id'], 'integer'],
            
            [['parent_id'], function($attribute, $params, $validator) {
                $parent = Category::find()
                   -> select(['id', 'parent_id', 'path'])
                   -> where(['id' => $this->parent_id])
                   -> one();

                if(!$parent instanceof Category) {
                    $this->addError($attribute, Yii::t('all', 'Parent category no exists'));
                    return;
                }
                $pattern = '#(^|/)'.$this->id.'/#';
                if(preg_match($pattern, $parent->path)) {
                    $this->addError($attribute, Yii::t('all', 'This parent category is a child of current category'));
                }
                $this->_tempParent = $parent;
            }, 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['level'], 'default', 'value' => function($model, $attribute) {
                if($this->isAttributeChanged('parent_id')) {
                    if($this->parent_id) {
                       $parent = $this->_tempParent;
                       return $parent->level + 1; 
                    }
                }
                return static::LEVEL_START;
            }],
            [['path'], 'default', 'value' => 0],
            [['url_path'], 'string', 'length' => [2, 255]],
            [['url_path'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['is_active'],'default', 'value' => 1],
            [['is_acitve'], 'boolean'],
            [['position'], 'default', 'value' => 0],
            [['position'], 'integer'],
            [['meta_title'], 'string', 'length' => [0,255]],
            [['meta_keywords'], 'string', 'length' => [0,255]],
            [['meta_description'], 'string', 'length' => [0,255]],
            [['meta_title'], 'default', 'value' => function() {
                return $this->title;
            }],
            [['content'], 'string'],
            [['imageFile'], 
                'image', 
                'extensions' => ['gif', 'jpg', 'png', 'jpeg'],
            ],
            [['imageDelete'], 'default', 'value' => 0],
            [['imageDelete'], 'boolean'],
        ];
    }




    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
           'id'         => 'ID',
           'parent_id'  => Yii::t('all', 'Parent category'),
           'path'       => Yii::t('all', 'Path'),
           'level'      => Yii::t('all', 'Level'),
           'url_path'   => Yii::t('all', 'Url path'),
           'title'      => Yii::t('all', 'Category name'),
           'description' => Yii::t('all', 'Description'),
           'is_active'   => Yii::t('all', 'Status'),
           'position'    => Yii::t('all', 'Position'),
           'image'       => Yii::t('all', 'Category image'),
           'imageFile' => Yii::t('all', 'Category image'),
           'imageDelete' => Yii::t('all', 'Delete image'),
           'meta_title'   => Yii::t('all', 'Meta title'),
           'meta_keywords'    => Yii::t('all', 'Meta keywords'),
           'meta_description' => Yii::t('all', 'Meta description'),
           'content'          => Yii::t('all', 'Category page'),
           'created_at' => Yii::t('all', 'Created time'),
           'updated_at' => Yii::t('all', 'Updated time'),
           'created_by' => Yii::t('all', 'Creator'),
           'updated_at' => Yii::t('all', 'Revisor'),
           'is_menu' => Yii::t('all', 'This is a menu'),
           'is_block' => Yii::t('all', 'This is a block'),
        ];
    }


    /**
     * 修改分类的 path 和 level
     * 
     * @param  null|static $parent 父分类, 如果不设置,则自己获取
     */
    public function changePathAndLevel($parent = null)
    {
        if(empty($this->parent_id)) {
            $this->path = $this->id;
            $this->level = static::LEVEL_START;
        } else {
            if($parent === null) {
                $parent = static::find()
                  -> select(['id', 'path', 'parent_id', 'level'])
                  -> where(['id' => $this->parent_id])
                  -> one();
            }
            $this->path = $parent->path . '/' . $this->id;
            $this->level = $parent->level + 1;
        }
    }


    public function getImageUrl($absolute = true)
    {
        if($this->image) {
            return App::getMediaUrl('category/' . $this->image, $absolute);
        }
        return null;
    }


    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(CategoryQuery::className(), [ get_called_class() ]);
    }


    /**
     * 检查是否有父分类存在
     */
    public function hasParent()
    {
        return $this->parent_id !== null;
    }

    /**
     * 查询 text 字段会使用磁盘临时文件,无法完全使用内存
     * 因此这里去掉 text 字段
     * 
     * @return array category 对象组成的数组.
     */
    public static function all()
    {
        $all = static::find()
            -> selectWithoutContent()
            -> tagCache(static::CACHE_TAG_ALL, 3600)
            -> all();
        return $all;
    }

    public static function menus()
    {
        $all = static::find()
            ->selectWithoutContent()
            ->where([
               'is_active' => 1,
               'is_menu' => 1,
            ])
            ->tagCache(static::CACHE_TAG_ALL, 3600)
            ->orderBy('id')
            ->indexBy('id')
            ->all();
    }


    public static function hashOptions($exclude = null)
    {
        $all = static::all();
        if($exclude === null) { 
            return ArrayHelper::map($all, 'id', 'title');
        }
        $options = [];
        foreach($all as $category) {
           $path = $category->path;
           if(strpos($path, $exclude['path']) !== 0) {
              $options[$category->id] = $category->title;
           }
        }
        return $options;
    }



    public function invalidateCache()
    {
        static::invalidateTag([
            static::CACHE_TAG_ALL,
        ]);
    }

    public function parentOptions()
    {
        return static::hashOptions($this->isNewRecord ? null : $this);
    }



    public function canDelete()
    {
        return !static::find()
            -> filterClosestChilds($this)
            -> exists();
    }


    /**
     * 获取父分类实例
     * 
     * @return common\models\Category
     */
    public function getParent()
    {
        return $this->hasOne(static::className(), ['id' => 'parent_id']);
    }

    public function getParentMenu()
    {
        return $this->getParent()->selectWithoutContent();
    }


    /**
     * 获取子分类实例
     * 
     * @return common\models\Category
     */
    public function getChilds()
    {
        return $this->hasMany(static::className(), ['parent_id' => 'id'])->inverseOf('parent');
    }


    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id'])
           ->inverseOf('category');
    }

}