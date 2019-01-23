<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use common\query\CustomerQuery;
use common\helpers\Form;

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


    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::className(),
        ];
        return $behaviors;
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
        return [
            [['phone'], 'match', 'pattern' => '/^1\d{10}$/'],
            [['nickname'], 'string', 'length' => [5,32]],
            [['is_active'], 'default', 'value' => Form::BOOLEAN_TRUE ],
            [['is_active'], 'in', 'range' => [Form::BOOLEAN_TRUE, Form::BOOLEAN_FALSE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
           'id'               => 'ID',
           'nickname'         => Yii::t('admin', 'Nickname'),
           'phone'            => Yii::t('admin', 'Mobile phone number'),
           'is_active'        => Yii::t('admin', 'Enabled'),
           'created_at'       => Yii::t('admin', 'Created time'),
           'updated_at'       => Yii::t('admin', 'Updated time'),
           'password'         => Yii::t('admin', 'Password'),
           'confirm_password' => Yii::t('admin', 'Confirm password'),
           'email'            => Yii::t('admin', 'Email address'),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(CustomerQuery::className(), [ get_called_class() ]);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id)->filterActive();
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
        $token = Yii::$app->security->generateRandomString(32);
        $this->setPasswordResetToken($token); 
    }


    /**
     * 验证重置密码的 token是否可用.
     * 
     * @param  mixed $token  - 需要验证的 token
     * @return boolean
     */
    public function validatePasswordResetToken($token)
    {
        return $token && $this->getPasswordResetToken() == $token;
    }


    /**
     * 获取 reset token
     */
    public function getPasswordResetToken()
    {
        return Yii::$app->cache->get('password_reset_token:'. $this->id);
    }


    /**
     * 设置重置密码的 token, 或者如果没有,则删除token
     * 
     * @param string $token
     */
    public function setPasswordResetToken($token)
    {
        if($token) {
            Yii::$app->cache->set('password_reset_token:' . $this->id, $token, 3600);
        } else {
            Yii::$app->cache->delete('password_reset_token:' . $this->id);
        }
        
    }
    

    /**
     * 获取所有的邮件地址, 为了 with() 方法.
     * 
     * @see yii\db\ActiveRecord::hasMany()
     */
    public function getEmails()
    {
        return $this->hasMany(CustomerEmail::className(), ['customer_id' => 'id'])->inverseOf('customer');
    }


    /**
     * 获取主要邮件地址实例, 为了 with() 方法.
     * 
     * @see yii\db\ActiveRecord::hasOne()
     */
    public function getPrimaryEmail()
    {
        return $this->hasOne(CustomerEmail::className(), ['customer_id' => 'id'])->inverseOf('customer')
            -> filterPrimary();
    }


    /**
     * 获取可以公开的邮件地址实例
     * 
     * @see yii\db\ActiveRecord::hasMany()
     */
    public function getPublicEmails()
    {
        return $this->hasMany(CustomerEmail::className(), ['customer_id' => 'id'])->inverseOf('customer')
          -> filterPublic();
    }



    /**
     * 获取可以登录的邮件地址实例
     * 
     * @see yii\db\ActiveRecord::hasMany()
     */
    public function getCanLoginedEmails()
    {
        return $this->hasMany(CustomerEmail::className(), ['customer_id' => 'id'])->inverseOf('customer')
          -> filterCanLogin();
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


    public function fields()
    {
        $fields = array_filter(parent::fields(), function($value, $key) {
            if($key == 'password_hash' || $value == 'password_hash') {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);
        
        return $fields;
    }


}

