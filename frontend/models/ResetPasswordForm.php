<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\Customer;
use core\validators\PasswordValidator;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{

    public $password;

    public $password_confirm;

    /**
     * @var \common\models\User
     */
    private $_customer;


    public function rules()
    {
        return [
           [['password', 'password_confirm'], 'required'],
           [['password'], 'string', 'length' => [5,32]],
           [['password'], PasswordValidator::className()],
           [['password_confirm'], 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
            'password_confirm' => Yii::t('app', 'Confirm password'),
        ];
    }

    public function setCustomer($customer)
    {
        $this->_customer = $customer;
    }

    public function getCustomer()
    {
        return $this->_customer;
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $this->customer->scenario = Customer::SCENARIO_UPDATE;
        $this->customer->setPassword($this->password);
        $this->customer->removePasswordResetToken();
        return $this->customer->save(false);
    }
}
