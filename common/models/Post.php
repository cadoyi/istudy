<?php

namespace common\models;

use Yii;
use common\query\PostQuery;

class Post extends ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%post}}';
    }

    public static function find()
    {
        return Yii::createObject(PostQuery::className(), [ get_called_class() ]);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getFavorites()
    {
        return $this->hasMany(CustomerFavorite::className(), ['post_id' => 'id'])->inverseOf('post');
    }

    public function getFavoritedCustomers()
    {
        return $this->hasMany(Customer::className(), ['id' => 'customer_id'])
          ->via('favorites');
    }


}