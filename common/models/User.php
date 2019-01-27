<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use core\validators\PasswordValidator;
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
     * @var string 密码
     */
    public $password;

    /**
     * @var string 确认密码
     */
    public $password_confirm;

    /**
     * @var string 当前管理员密码
     */
    public $current_password;

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
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     * @return [type] [description]
     */
    public function rules()
    {
        return [
            [['username', 'email', 'nickname'], 'trim'],
            [['username'], 'string', 'length' => [5,32]],
            [['email'], 'email'],
            [['nickname'], 'string'],
            [['password'], 'string', 'length' => [5,32]],
            [['password'], PasswordValidator::className() ],
            [['password_confirm'], 
              'compare', 
              'compareAttribute' => 'password',
              'when' => function($model, $attribute) {
                   return !empty($this->password);
              },
              'whenClient' => "function(attribute, value) {
                    return \$('#user-password').val().trim() != '';
                }",
            ],
            [['current_password'], function($attribute, $params, $validator) {
                $identity = Yii::$app->user->identity;
                if(!$identity->validatePassword($this->current_password)) {
                    $this->addError($attribute, 'Invalid password');
                }
            }],
            [['is_active'], 'default', 'value' => 1],
            [['is_active'], 'boolean'],
            
            [['username'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['email'], 'unique', 'when' => function($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['username', 'email'], 'required'],
            [
                ['password', 'password_confirm'], 
                'required', 
                'on' => [
                    static::SCENARIO_CREATE,
                ],
            ],
            [['current_password'], 'required', 'on' => [
               static::SCENARIO_CREATE,
               static::SCENARIO_UPDATE,
            ]],
            [
                ['nickname'], 
                'default', 
                'value' => function($model, $attribute) {
                    return $this->username;
                },
                'on' => [
                    static::SCENARIO_CREATE,
                ],
            ],
            [['nickname'], 'required', 'on' => [static::SCENARIO_UPDATE]],
        ];
    }



    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'username'   => Yii::t('all', 'Username'),
            'password'   => Yii::t('all', 'Password'),
            'password_confirm' => Yii::t('all', 'Confirm password'),
            'current_password' => Yii::t('all', 'Current user\'s password'),
            'email'      => Yii::t('all', 'Email address'),
            'nickname'   => Yii::t('all', 'Nickname'),
            'is_active'  => Yii::t('all', 'Whether enabled'),
            'is_deleted' => Yii::t('all', 'Whether deleted'),
            'created_at' => Yii::t('all', 'Created time'),
            'updated_at' => Yii::t('all', 'Updated time'),
        ];
    }


    public function beforeSave($insert)
    {
        if($this->password) {
            $this->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
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
     * 
     * 
     * @return boolean
     */
    public function canDelete()
    {
        return $this->id != static::SUPER_ADMIN;
    }


    public function fields()
    {
        return [
           'id',
           'username',
           'nickname',
           'email',
           'is_active',
           'created_at' => function() {
                return Yii::$app->formatter->asDatetime($this->created_at);
           },
           'updated_at' => function() {
                return Yii::$app->formatter->asDatetime($this->updated_at);
           }
        ];
    }

    public function cleanPassword()
    {
        $this->password = null;
        $this->password_confirm = null;
        $this->current_password = null;
    }


}