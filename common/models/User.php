<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use common\query\UserQuery;

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