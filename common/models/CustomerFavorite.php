<?php

namespace common\models;

use Yii;
use common\models\queries\CustomerFavoriteQuery;
use core\db\ActiveRecord;


/**
 * This is the model class for table "customer_favorite".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $post_id
 * @property string $reason 收藏原因
 * @property int $is_user_reason 是否是用户自己输入的原因
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Customer $customer
 * @property Post $post
 */
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
     * @return common\models\queries\CustomerFavoriteQuery
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