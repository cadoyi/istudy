<?php

namespace common\models;

use Yii;
use common\query\CustomerEmailQuery;

/**
 *
 * @property integer $id
 * @property integer $customer_id 
 * @property string $email
 * @property boolean $is_primary 
 * @property boolean $is_public
 * @property boolean $can_login 
 * @property integer $created_at 
 *
 */
class CustomerEmail extends ActiveRecord
{

    /**
     * {@inheritdoc}
     * @return string 表名
     */
    public static function tableName()
    {
        return '{{%customer_email}}';
    }

    
    /**
     * {@inheritdoc}
     * @return common\models\CustomerEmailQuery
     */
    public static function find()
    {
        return Yii::createObject(CustomerEmailQuery::className(),[ get_called_class() ]);
    }


    /**
     * 获取 customer 实例.
     * @return common\models\Customer
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }



    /**
     * 此邮件地址是否可以用于登录
     * @return boolean
     */
    public function canLogin()
    {
        return $this->is_primary || $this->can_login;
    }


    /**
     * 是否可以删除此邮件地址
     * @return boolean
     */
    public function canDelete()
    {
        return !$this->is_primary;
    }

}