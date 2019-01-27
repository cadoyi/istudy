<?php

namespace common\models;

use Yii;
use common\query\CategoryQuery;

/**
 * Category table
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $path
 * @property integer $level
 * @property string $title
 * @property string $url_path
 *
 */
class Category extends ActiveRecord
{

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


    public function rules()
    {
        return [
            [['title'], 'string', 'on' => [
                static::SCENARIO_DEFAULT,
                static::SCENARIO_CREATE,
                static::SCENARIO_UPDATE,
            ]],
            [['parent_id'], 'default', 'value' => null],
            [['parent_id'], 'integer'],
            [['parent_id'], function($attribute, $params, $validator) {
                $category = Category::findOne($this->$attribute);
                if(!$category instanceof Category) {
                    $this->addError($attribute, Yii::t('all', 'Invalid parent category id'));
                    return;
                }
                $pattern = '#(^|/)'.$this->id.'/#';
                if(preg_match($pattern, $category->path)) {
                    $this->addError($attribute, Yii::t('all', 'This parent_id is a child of current category'));
                }
            }],
            [['url_path'], 'string', 'length' => [2, 255]],
            [['url_path'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            

        ];
    }

    public function attributeLabels()
    {

    }

    public function beforeSave($insert)
    {

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


}