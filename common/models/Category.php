<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use common\query\CategoryQuery;

/**
 * Category table
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string  $path
 * @property integer $level
 * @property string  $title
 * @property string  $url_path
 *
 */
class Category extends ActiveRecord
{

    const LEVEL_START = 1;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => TimestampBehavior::className(),
            'url_path'  => [
                'class' => SluggableBehavior::className(),
                'attribute'        => 'title',
                'slugAttribute'    => 'url_path',
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
            [['title'], 'string', 'on' => [
                static::SCENARIO_DEFAULT,
                static::SCENARIO_CREATE,
                static::SCENARIO_UPDATE,
            ]],
            [['title'], 'required'],
            [['parent_id'], 'default', 'value' => null],
            [['parent_id'], 'integer'],
            [['parent_id'], function($attribute, $params, $validator) {
                $category = Category::findOne($this->parent_id);
                if(!$category instanceof Category) {
                    $this->addError($attribute, Yii::t('all', 'Parent category no exists'));
                    return;
                }
                $pattern = '#(^|/)'.$this->id.'/#';
                if(preg_match($pattern, $category->path)) {
                    $this->addError($attribute, Yii::t('all', 'This parent category is a child of current category'));
                }
            }, 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['url_path'], 'string', 'length' => [2, 255]],
            [['url_path'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['path'], 'default', 'value' => 0],
            [['level'], 'default', 'value' => static::LEVEL_START],
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
           'created_at' => Yii::t('all', 'Created time'),
           'updated_at' => Yii::t('all', 'Updated time'),
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
                $parent = static::findOne($this->parent_id);
            }
            $this->path = $parent->path . '/' . $this->id;
            $this->level = $parent->level + 1;
        }
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
        return (bool) $this->parent_id;
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


    /**
     * 获取子分类实例
     * 
     * @return common\models\Category
     */
    public function getChilds()
    {
        return $this->hasMany(static::className(), ['parent_id' => 'id'])->inverseOf('parent');
    }


    public static function hashOptions($exclude = null)
    {
        $all = static::find()
           ->select(['id','title', 'path'])
           ->asArray()
           ->all();
        if($exclude === null) { 
            return ArrayHelper::map($all, 'id', 'title');
        }
        $options = [];
        foreach($all as $category) {
           $path = $category['path'];
           if(strpos($path, $exclude['path']) !== 0) {
              $options[$category['id']] = $category['title'];
           }
        }
        return $options;
    }

    public function parentOptions()
    {
        return static::hashOptions($this->isNewRecord ? null : $this);
    }

    public function canDelete()
    {
        return !$this->getChilds()->exists();
    }


}