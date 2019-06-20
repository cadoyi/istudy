<?php

namespace common\models\queries;

use Yii;
use core\db\ActiveQuery;
use common\models\Category;

class CategoryQuery extends ActiveQuery
{


    /**
     * 获取顶级分类.也就是没有父分类的分类.
     * 
     * @return $this
     */
    public function filterTop()
    {
        return $this->andWhere(['parent_id' => null]);
    }


    /**
     * 获取某个分类的后代分类, 左 LIKE 可以使用索引.
     * 
     * @param  object/string $path - 父分类或者父分类的 path 路径.
     * @return $this
     */
    public function filterChilds(Category $category)
    {
        $pattern = $category->path . '/%';
        return $this->andWhere(['like', 'path', $pattern, false]);
    }


    /**
     * 获取最靠近的子分类
     * 
     * @param  Category $category - 分类实例
     * @return $this
     */
    public function filterClosestChilds(Category $category)
    {
        return $this->andWhere(['parent_id' => $category->id]);
    }


    /**
     * 获取父分类.
     * 
     * @return [type] [description]
     */
    public function filterParent(Category $category)
    {
        $parent_id = $category->parent_id;
        return $this->andWhere(['id' => $parent_id]);
    } 


    /**
     * 获取所有的上级分类.
     * 
     * @param  Category $category - 分类实例
     * @return $this
     */
    public function filterParents(Category $category)
    {
        $in = explode('/', $category->path);
        array_pop($in);
        return $this->andWhere(['in', 'id', $in]);
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