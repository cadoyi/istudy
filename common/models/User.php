<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use common\query\UserQuery;

/**
 * admin_user 模型
 *
 * @property integer $id
 * @property string $username 
 * @property string $nickname 
 * @property string $email 
 * @property string $password_hash 
 * @property boolean $is_active 
 * @property boolean $is_deleted 
 * @property integer $created_at 
 * @property integer $updated_at 
 * 
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const SUPER_ADMIN = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = TimestampBehavior::className();
        return $behaviors;
    }


    public function rules()
    {
        return [
           [['username', 'email'], 'required'],
           [['nickname'], 'required', 'on' => [ static::SCENARIO_UPDATE ]],
           [['username', 'email', 'nickname'], 'trim'],
           [['username'], 'string', 'length' => [5,32]],
           [['email'], 'email'],
           [['is_active'], 'default', 'value' => 1],
           [['is_active'], 'boolean'],
           [['is_deleted'], 'default', 'value' => 0],
           [['is_deleted'], 'boolean'],
           [['nickname'], 'default', 'value' => function($model, $attribute) {
                return $model->username;
           }, 'on' => [ static::SCENARIO_CREATE ]],
           [['nickname'], 'string', 'length' => [5, 32]],
           [['username'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
           }],
           [['email'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
           }],
        ];
    }



    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'username'   => Yii::t('admin', 'Username'),
            'password'   => Yii::t('admin', 'Password'),
            'email'      => Yii::t('admin', 'Email address'),
            'nickname'   => Yii::t('admin', 'Nickname'),
            'is_active'  => Yii::t('admin', 'Whether enabled'),
            'is_deleted' => Yii::t('admin', 'Whether deleted'),
            'created_at' => Yii::t('admin', 'Created time'),
            'updated_at' => Yii::t('admin', 'Updated time'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(UserQuery::className(), [ get_called_class() ]);
    }


    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username,
            'is_active' => static::STATUS_ENABLED,
        ]);
    }


    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'is_active' => static::STATUS_ENABLED,
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function getAuthKey()
    {

    }

    public function validateAuthKey($authKey)
    {

    }


    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }



    public function generatePasswordHash($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);        
    }


    /**
     * 可以删除 60 秒内新建立的管理员,但是不能删除超级管理员.
     * 
     * @return boolean
     */
    public function canDelete()
    {
        return ($this->updated_at - $this->created_at) < 60 && $this->id != static::SUPER_ADMIN;
    }



}