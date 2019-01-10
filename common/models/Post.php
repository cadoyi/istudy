<?php

namespace common\models;

use Yii;
use common\query\PostQuery;

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