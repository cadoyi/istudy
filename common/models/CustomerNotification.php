<?php

namespace common\models;

use Yii;
use common\models\queries\CustomerNotificationQuery;
use core\db\ActiveRecord;


/**
 * This is the model class for table "customer_notification".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $message
 * @property int $level
 * @property int $watched
 * @property int $rewatch
 * @property int $expire_at 过期时间
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Customer $customer
 */
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