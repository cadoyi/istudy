<?php

namespace backend\form;

use Yii;
use yii\base\Model;
use common\models\User;
use common\components\Attempt;

class LoginForm extends Model
{

    const SCENARIO_CAPTCHA = 'captcha';


    public $username;

    public $password;
 
    public $code;

    protected $_user = false;

    protected $_attempt;


    public function init()
    {
        parent::init();

        $this->_attempt = new Attempt([
            'key'        => 'login_captcha',
            'retryCount' => 3,     //3次之后出验证码
        ]);
        
        // 尝试执行回调.
        $this->_attempt->execute( function($counter) {
             $this->scenario = static::SCENARIO_CAPTCHA;
        });
    }



    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules()
    {
        $usernameError = Yii::t('admin', 'This user does not exists');
        $passwordError = Yii::t('admin', 'Password incorrect');
        return [
           [['username', 'password'], 'required'],
           [['username', 'password'], 'trim'],
           [['code'], 'required', 'on' => [ static::SCENARIO_CAPTCHA ]],
           [['code'], 'captcha', 'on' => [static::SCENARIO_CAPTCHA ]],

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


    /**
     * 登录.
     * 
     * @return boolean
     */
    public function login()
    {
        if($this->validate()) {
            $this->_attempt->reset();
            return Yii::$app->user->login($this->user);
        }
        $this->_attempt->attempt();
        return false;
    }
} 