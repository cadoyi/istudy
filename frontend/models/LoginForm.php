<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use core\validators\PasswordValidator;
use common\models\Customer;
use core\exception\ConfirmException;

class LoginForm extends Model
{

	public $email;

	public $password;

	public $code;

	public $remember;

	private $_user = true;

	public function rules()
	{
		$passwordError =  Yii::t('all', 'Password incorrect');
		return [
           [['email','password', 'code'], 'required'],
           [['code'], 'captcha', 'captchaAction' => 'site/captcha'],
           [['email'], 'email'],
           [['password'], PasswordValidator::className(), 
              'message' => $passwordError, 
           ],
           [['password'], function($attribute, $params, $validator) use ($passwordError) {
               if($this->user instanceof Customer && $this->user->validatePassword($this->$attribute)) {
                   return;
               }
               $this->addError($attribute,$passwordError);
           }],
           [['remember'], 'default', 'value' => 0],
           [['remember'], 'boolean'],
		];
	}

	public function attributeLabels()
	{
		return [
            'email' => Yii::t('all', 'Email address'),
            'password' => Yii::t('all', 'Password'),
            'code' => Yii::t('all', 'Captcha code'),
            'remember' => Yii::t('all', 'Remember me'),
		];
	}

	public function getUser()
	{
		if($this->_user && !$this->_user instanceof Customer) {
			$this->_user = Customer::find()->where(['email' => $this->email])->one();
		}
		return $this->_user;
	}

	public function login()
	{
        if($this->user) {
            if(!$this->user->is_active) {
                throw new ConfirmException('Confirm required');
            }
            return Yii::$app->user->login($this->user, $this->remember);
        }
        return false;
	}
}
