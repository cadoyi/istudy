<?php

namespace backend\form;

use Yii;
use yii\base\Model;
use common\models\User;

class LoginForm extends Model
{
    public $username;

    public $password;
 
    public $code;

    private $_user = false;


    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules()
    {
        $usernameError = Yii::t('admin', 'This user does not exists');
        $passwordError = Yii::t('admin', 'Password incorrect');
        return [
           [['username', 'password', 'code'], 'required'],
           [['username', 'password', 'code'], 'trim'],
           [['code'], 'captcha'],
           [['username'], 'string', 
               'length' => [ 5, 32 ],
               'tooShort' => $usernameError,
               'tooLong'  => $usernameError,
               'message'  => $usernameError,
           ],
           [['password'], 
               'string', 
               'length'   => [ 5, 32 ], 
               'tooShort' => $passwordError,
               'tooLong'  => $passwordError,
               'message'  => $passwordError,
           ],
           [['password'], 'validatePassword'],
        ];
    }


    /**
     * {@inheritdoc}
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('admin', 'Username'),
            'password' => Yii::t('admin', 'Password'),
            'code'     => Yii::t('admin', 'Captcha code'),
        ];
    }


    /**
     * 获取 User 实例.
     * 
     * @return common\models\User
     */
    public function getUser()
    {
        if($this->username && (false === $this->_user)) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }


    /**
     * 验证密码的回调.
     * 
     * @param  [type] $attribute [description]
     * @param  [type] $params    [description]
     * @param  [type] $validator [description]
     * @return [type]            [description]
     */
    public function validatePassword($attribute, $params, $validator)
    {
        if($this->user instanceof User) {
            if(!$this->user->validatePassword($this->$attribute)) {
                $message = Yii::t('admin', 'Username or password incorrect');
                $this->addError('username', $message . '!');
            }
        }
    }


    public function login()
    {
        if($this->validate()) {
            return Yii::$app->user->login($this->user);
        }
        return false;
    }
} 