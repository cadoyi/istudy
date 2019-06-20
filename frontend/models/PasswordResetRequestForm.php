<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Customer;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{

    public $email;

    public $code;

    private $_customer;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            [['email', 'code'], 'required'],
            [['code'], 'captcha', 'captchaAction' => 'site/captcha-reset-password'],
            ['email', 'email'],
            [['email'], 'exist', 
                'targetClass' => Customer::className(),
                'filter' => [
                   'is_active' => 1,
                ],
                'message' => Yii::t('app', 'Your email unregisted or inactived.'),
           ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email address'),
            'code' => Yii::t('app', 'Captcha code'),
        ];
    }

    public function getCustomer()
    {
        if($this->_customer === null) {
            $this->_customer = Customer::findByEmail($this->email);
        }
        return $this->_customer;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public static function sendEmail($customer)
    {
        Mailer::resetPassword($customer)->send();
    }
}
