<?php

namespace common\models\queries;

use Yii;
use yii\base\InvalidParamException;
use core\db\ActiveQuery;
use common\models\Category;
use common\models\Post;

/**
 * 专门为 post 而建立的 ActiveQuery
 *
 *
 * 
 */
class PostQuery extends ActiveQuery
{
    
    /**
     * 获取某个分类的文章.
     * 
     * @param  integer|Category $category - 分类 id 或者分类
     * @return $this
     */
    public function filterCategory($category)
    {
        if($category instanceof Category) {
            $category = $category->id;
        } 
        return $this->andWhere(['category_id' => $category]);
    }


    public function selectWithoutContent()
    {
        $class = $this->modelClass;
        $model = $class::instance();
        $attributes = $model->attributes();
        $fields = array_diff($attributes, ['content']);
        return $this->select($fields);
    }




}