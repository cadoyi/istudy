<?php

namespace common\models;

use Yii;
use common\query\CustomerNotificationQuery;

class CustomerNotification extends ActiveRecord
{

    const MESSAGE_LEVEL_INFO = 1;
    const MESSAGE_LEVEL_WARNING = 2;
    const MESSAGE_LEVEL_ERROR = 4;
    

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer_notification}}';
    }


    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(CustomerNotificationQuery::className(), [ get_called_class() ]);
    }


    /**
     * 获取用户
     * @return common\models\Customer 实例
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

}