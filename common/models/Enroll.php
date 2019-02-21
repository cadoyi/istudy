<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\query\EnrollQuery;
use core\validators\PhoneValidator;


/**
 * This is the model class for table "enroll".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $dob
 * @property int $sex
 * @property int $status
 * @property string $note
 * @property int $created_at
 * @property int $updated_at
 */
class Enroll extends ActiveRecord
{

    const STATUS_PENDING = 0;
    const STATUS_PROCESSED = 1;

    const SEX_MALE = 0;
    const SEX_FEMALE = 1;

    /**
     * @deprecated
     */
    public static function statusList()
    {
        return static::statusHashOptions();
    }

    public static function statusHashOptions()
    {
        return [
            self::STATUS_PENDING   => Yii::t('app', 'Pending'),
            self::STATUS_PROCESSED => Yii::t('app', 'Processed'),
        ];
    }

    public static function sexHashOptions()
    {
        return [
            static::SEX_MALE => Yii::t('app', 'Male'),
            static::SEX_FEMALE => Yii::t('app', 'Female'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%enroll}}';
    }


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email', 'dob', 'sex'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['phone'], PhoneValidator::className()],
            [['email'], 'email'],
            [['dob'], 'date'],
            [['sex'], 'boolean'],
            [['status'], 'default', 'value' => static::STATUS_PENDING],
            [['status'], 'in', 'range' => [ static::STATUS_PENDING, static::STATUS_PROCESSED]],
            [['note'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        $default = ['name', 'email', 'phone', 'sex', 'dob', 'status', 'note'];
        return [
            static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_CREATE => $default,
            static::SCENARIO_UPDATE => $default,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone number'),
            'email' => Yii::t('app', 'Email address'),
            'dob' => Yii::t('app', 'Date of birth'),
            'sex' => Yii::t('app', 'Sex'),
            'status' => Yii::t('app', 'Status'),
            'note' => Yii::t('app', 'Note'),
            'created_at' => Yii::t('app', 'Created time'),
            'updated_at' => Yii::t('app', 'Updated time'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\query\EnrollQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(EnrollQuery::className(), [ get_called_class() ]);
    }
}
