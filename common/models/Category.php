<?php

namespace common\models;

use Yii;
use common\query\CategoryQuery;

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