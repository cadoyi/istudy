<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use common\query\CustomerQuery;
use core\helpers\Form;
use core\validators\PhoneValidator;
use core\validators\PasswordValidator;

/**
 * 客户表
 * 
 * @property integer $id
 * @property string $nickname
 * @property string $phone
 * @property string $password_hash 
 * @property boolean $is_active 
 * @property integer $created_at 
 * @property integer $updated_at 
 * 
 */
class Customer extends ActiveRecord implements IdentityInterface
{

    protected $_accessToken;

    public $password;

    public $password_confirm;


    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules()
    {
        $passwordError = Yii::t('all', 'Invaild password format');
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique', 'when' => function($model, $attribute) {
                 $model->isAttributeChanged($attribute);
            }], 
            [['nickname'], 'required', 'except' => [
                 static::SCENARIO_CREATE,
            ]],
            [['nickname'], 'string', 'length' => [0,32]],
            [['nickname'], 'default', 'value' => function() {
                return substr($this->email,0, strpos($this->email, '@'));
            }, 'on' => [
                static::SCENARIO_CREATE,
            ]],
            [['phone'], PhoneValidator::className()],
            [['phone'], 'default', 'value' => null],
            [['group_id'], 'required', 'except' => [
                 static::SCENARIO_CREATE,
            ]],
            [['group_id'], 'exist', 'targetClass' => CustomerGroup::className(), 'targetAttribute' => 'id'],
            [['group_id'], 'default', 'value' => function() {
                return CustomerGroup::findDefault()->id;
            }],
            [['password'], 'string', 'length' => [5, 32]],
            [['password'], PasswordValidator::className()], 
            [['password_confirm'], 
               'compare', 
               'compareAttribute' => 'password',
               'when' => function($model, $attribute) {
                    return !empty($model->password);
               },
               'whenClient' => 'function(attribute, value) {
                   return $("#'.Html::getInputId($this, 'password').'").val().trim() != "";
               }',
            ],
            [['is_active'], 'default', 'value' => 1],
            [['is_active'], 'boolean'],
            [['password', 'password_confirm'], 'required', 'on' => [
                static::SCENARIO_CREATE,
            ]],
        ];
    }

    public function scenarios()
    {
        $default = [
           'email', 
           'nickname',
           'phone',
           'password',
           'password_confirm',
           'is_active',
           'group_id',
        ];
        return [
            static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_CREATE => $default,
            static::SCENARIO_UPDATE => $default,
        ];
    }

    public function formName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
           'id'               => 'ID',
           'nickname'         => Yii::t('all', 'Nickname'),
           'phone'            => Yii::t('all', 'Mobile phone number'),
           'is_active'        => Yii::t('all', 'Enabled'),
           'created_at'       => Yii::t('all', 'Created time'),
           'updated_at'       => Yii::t('all', 'Updated time'),
           'password'         => Yii::t('all', 'Password'),
           'password_confirm' => Yii::t('all', 'Confirm password'),
           'email'            => Yii::t('all', 'Email address'),
           'group_id'         => Yii::t('all', 'Customer group'),
        ];
    }


    public function beforeSave($insert)
    {
        if($this->password) {
            $this->setPassword($this->password);
        }
        if($insert) {
            $this->generateAuthKey();
        }
        return parent::beforeSave($insert);
    }

    public function cleanPassword()
    {
        $this->password = null;
        $this->password_confirm = null;
    }





    public function fields()
    {
        return [
            'id',
            'email',
            'nickname',
            'phone',
            'is_active',
            'created_at' => function($model, $attribute) {
                return Yii::$app->formatter->asDatetime($model->$attribute);
            },
            'updated_at' => function() {
                return Yii::$app->formatter->asDatetime($this->updated_at);
            },
            'profile',
            'group_id',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(CustomerQuery::className(), [ get_called_class() ]);
    }


    public static function findByEmail($email)
    {
        return static::findOne([
            'email'     => $email,
            'is_active' => 1,
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id'     => $id,
            'is_active' => 1,
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if($id = Yii::$app->cache->get('token:' . $token)) {
            if($user = static::findIdentity($id)) {
                $user->accessToken = $token;
                return $user;
            }
        }
        return null;
    }



    /**
     * 检查 access token 是否可用,主要是检查是否过期
     * 
     * @param  mixed  $token   access token
     * @return boolean   
     */
    public static function isAccessTokenValid($token)
    {
        return $token && Yii::$app->cache->get($token);
    }



    /**
     * 生成一个 access token 字符串,并存储到缓存中.
     * 为了让前端可以读取过期时间,在后面加上一个时间戳.
     * 
     */
    public function generateAccessToken()
    {
        $accessToken = Yii::$app->security->generateRandomString(32) . Yii::$app->security->generateRandomString(21). '_' . time();
        Yii::$app->cache->set('token:' . $accessToken, $this->id, 3600);
        $this->accessToken = $accessToken;
    }



    /**
     * 设置 _accessToken 的 getter
     * 
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->_accessToken;
    }



    /**
     * 设置 _accessToken 属性的 setter
     * @param string $token  - access token 字符串.
     */
    public function setAccessToken($token)
    {
        $this->_accessToken = $token;
    }



    /**
     * 生成一个 auth_key
     * @return [type] [description]
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(32);
    }



    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return md5($this->auth_key) . '_' . time();
    }


    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        $auth_key = substr($authKey, 0, 32);
        return md5($this->auth_key) == $auth_key;
    }


    /**
     * 生成 hash 之后的密码
     *
     * @param  string $password  未加密之前的密码
     * @return string - 被 hash 之后的密码串
     */
    public static function generatePasswordHash($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }



    /**
     * 验证密码是否正确
     *
     * @param  string $password - 待验证的明文密码
     * @return  boolean 
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }



    /**
     * 设置明文密码来生成加密后的密码.
     *
     * @param  string $password - 明文密码
     */
    public function setPassword($password)
    {
        $this->password_hash = static::generatePasswordHash($password);
    }


    /**
     * 生成密码重置的 token, 不需要唯一,会配合 ID 一起使用.
     * 
     * @return [type] [description]
     */
    public function generatePasswordResetToken()
    {
        return $this->getPasswordResetToken();
    }


    /**
     * 验证重置密码的 token 是否可用.
     * 
     * @param  mixed $token  - 需要验证的 token
     * @return boolean
     */
    public function validatePasswordResetToken($token)
    {
        return Yii::$app->cache->get([$this->id, 'resetPassword']) === $token;
    }


    /**
     * 获取 reset token
     */
    public function getPasswordResetToken()
    {
        return Yii::$app->cache->getOrSet([$this->id, 'resetPassword'], function($cache){
            return Yii::$app->security->generateRandomString();
        }, 3600);
    }

    public function removePasswordResetToken()
    {
        Yii::$app->cache->delete([$this->id, 'resetPassword']);
    }





    /**
     * 获取用户的资料等详细信息.
     * 
     * @return common\models\CustomerProfile 实例.
     */
    public function getProfile()
    {
        return $this->hasOne(CustomerProfile::className(), ['customer_id' => 'id'])->inverseOf('customer');
    }


    /**
     * 获取用户的通知信息
     * 
     * @return common\models\CustomerNotification 实例.
     */
    public function getNotifications()
    {
        return $this->hasMany(CustomerNotification::className(), ['customer_id' => 'id'])->inverseOf('customer');
    }


    /**
     * 获取用户收藏实例
     * 
     * @return common\models
     */
    public function getFavorites()
    {
        return $this->hasMany(CustomerFavorite::className(), ['customer_id' => 'id'])->inverseOf('customer');
    }


    /**
     * 获取用户收藏的文章列表.
     */
    public function getFavoritePosts()
    {
        return $this -> hasMany(Post::className(), ['id' => 'post_id'])
                     -> via('favorites');
    }


}

