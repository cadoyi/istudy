<?php

namespace common\models;

use Yii;


class CustomerProfile extends ActiveRecord
{
   

   /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return '{{%customer_profile}}'; 
    }  


    /**
     * 获取 customer 实例
     * 
     * @return common\models\Customer 实例.
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id'])->inverseOf('profile');
    }
}