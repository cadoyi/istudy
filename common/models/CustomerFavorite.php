<?php

namespace common\models;

use Yii;
use common\query\CustomerFavoriteQuery;

class CustomerFavorite extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer_favorite}}';
    }


    /**
     * {@inheritdoc}
     * @return common\query\CustomerFavoriteQuery
     */
    public static function find()
    {
        return Yii::createObject(CustomerFavoriteQuery::className(), [ get_called_class() ]);
    }


    /**
     * 获取关联的 customer 实例
     * 
     * @return common\models\Customer
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }


    /**
     * 获取关联的 post 实例
     * @return common\models\Post
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

}