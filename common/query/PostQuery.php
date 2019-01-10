<?php

namespace common\query;

use Yii;
use yii\base\InvalidParamException;
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

    /**
     * 获取只为分类而设计的文章.
     * 
     * @return $this
     */
    public function filterOnlyCategory()
    {
        return $this->_filterBoolean('only_category', true);
    }


    /**
     * 过滤文章的状态
     * 
     * @param string $status  - 文章的状态
     * @return $this
     */
    public function filterStatus($status)
    {
        switch($status) {
            case Post::STATUS_PRIVATE:
            case Post::STATUS_PUBLIC:
                break;
            default:
                throw new InvalidParamException('Given unknown param :' . $status);
        }
        return $this->andWhere(['status' => $status]);
    }


    /**
     * 过滤私有的文章
     * 
     * @return $this
     */
    public function filterPrivate()
    {
        return $this->filterStatus(Post::STATUS_PRIVATE);
    }


    /**
     * 过滤发布的文章
     * 
     * @return $this
     */
    public function filterPublic()
    {
        return $this->filterStatus(Post::STATUS_PUBLIC);
    }


}