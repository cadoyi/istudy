<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
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

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return array_merge($behaviors, [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['is_primary'], 'default', 'value' => 1],
            [['is_primary'], 'boolean'],
            [['can_login'], 'default', 'value' => function($model, $attribute) {
                return $this->is_primary;
            }],
            [['can_login'], 'boolean'],
            [['is_public'], 'default', 'value' => 0],
            [['is_public'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'email'       => Yii::t('all', 'Email address'),
            'is_primary'  => Yii::t('all', 'Is priamry email'),
            'is_public'   => Yii::t('all', 'May public'),
            'can_login'   => Yii::t('all', 'Can use for login'),
            'created_at'  => Yii::t('all', 'Created time'),
            'customer_id' => Yii::t('all', 'Cusomter'), 
        ];
    }

    public function fields()
    {
        return [
           'id',
           'email',
           'is_primary',
           'is_public',
           'can_login',
           'created_at' => function($model, $field) {
                return Yii::$app->formatter->asDatetime($model->created_at);
            }
        ];
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


    public function setCustomer($customer)
    {
        $this->customer_id = ($customer instanceof Customer) ? $customer->id : $customer;
    }

    public function setPrimaryCustomer($customer)
    {
        $this->setCustomer($customer);
        $this->is_primary = true;
        $this->can_login = true;
    }

}