<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use core\validators\PhoneValidator;

/**
 * contact 表 / 联系我们的表单内容
 *
 * @property integer $id 
 * @property string  $name 
 * @property string  $phone 
 * @property string  $email 
 * @property string  $subject 
 * @property string  $message
 * @property integer $status
 * @property integer $created_at 
 * @property integer $updated_at
 *
 */
class Contact extends ActiveRecord
{

    const STATUS_PENDING = 0;
    const STATUS_PROCESS = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }


    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'contact';
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => TimestampBehavior::className(),
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email', 'subject', 'message'], 'required'],
            [['name'], 'string', 'length' => [1,5]],
            [['phone'], PhoneValidator::className()],
            [['email'], 'email'],
            [['subject'], 'string', 'length' => [1,32] ],
            [['message'], 'string', 'length' => [8,255]],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => static::STATUS_PENDING ],
            [['status'], 'in', 'range' => [static::STATUS_PENDING, static::STATUS_PROCESS]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $default = [ 'name', 'phone', 'email', 'subject', 'message', 'status'];
        return [
            static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_CREATE  => $default,
            static::SCENARIO_UPDATE  => $default,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('all', 'Your name'),
            'phone' => Yii::t('all', 'Mobile number'),
            'email' => Yii::t('all', 'Email address'),
            'subject' => Yii::t('all', 'Subject'),
            'message' => Yii::t('all', 'Message'),
            'status'  => Yii::t('all', 'Status'),
            'created_at' => Yii::t('all', 'Send time'),
            'updated_at' => Yii::t('all', 'Process time'),
        ];
    }








}