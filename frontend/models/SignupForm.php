<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Customer;
use common\models\CustomerProfile;
use core\validators\PasswordValidator;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $email;
    public $password;
    public $password_confirm;
    public $code;

    public function rules()
    {
        return [
           [['email', 'password', 'password_confirm', 'code'], 'required'],
           [['code'], 'captcha', 'captchaAction' => 'site/captcha-register'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email address'),
            'password' => Yii::t('app', 'Password'),
            'password_confirm' => Yii::t('app', 'Confirm password'),
            'code' => Yii::t('app', 'Captcha code'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $customer = new Customer([
            'scenario' => Customer::SCENARIO_CREATE,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirm' => $this->password_confirm,
            'is_active' => 0,
        ]);

        if ($customer->validate()) {
            Customer::getDb()->transaction(function() use ($customer){
                $customer->save(false);
                $profile = new CustomerProfile([
                        'scenario' => CustomerProfile::SCENARIO_CREATE,
                        'customer_id' => $customer->id,
                ]);
                $profile->save();
                static::sendRegisterVerifyEmail($customer);
            });
            return $customer;
            
        }
        $this->addErrors($customer->getErrors());
        return null;
    }

    public static function sendRegisterVerifyEmail($customer)
    {
        $secret = Yii::$app->cache
           ->getOrSet([
                $customer->email, 
                $customer->id
            ], function($cache) {
                return md5(time());
            }, 3600);
        Mailer::register($customer, $secret)->send();
    }
}
