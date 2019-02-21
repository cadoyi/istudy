<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use core\validators\PasswordValidator;
use common\models\Customer;

class CustomerPasswordForm extends Model
{
    public $password;

    public $password_confirm;

    public $current_password;


    public function rules()
    {
        $error = Yii::t('app', 'Password incorrect');
        return [
           [['password', 'password_confirm', 'current_password'], 'required'],
           [['password', 'password_confirm'], 'string', 'length' => [5,32]],
           [['current_password'], 'string', 'length' => [5,32], 
               'message' => $error,
               'tooLong' => $error,
               'tooShort' => $error,
           ],
           [['password'], PasswordValidator::className()],
           [['password_confirm'], 'compare', 'compareAttribute' => 'password'],
           [['current_password'], function($attribute, $params, $validator) use ($error){
                $customer = Yii::$app->user->identity;
                if(!$customer->validatePassword($this->$attribute)) {
                    $this->addError($attribute, $error);
                }
           }],
        ];
    }

    public function attributeLabels()
    {
        return [
           'current_password' => Yii::t('app', 'Origin password'),
           'password' => Yii::t('app', 'New password'),
           'password_confirm' => Yii::t('app', 'Confirm password'),
        ];
    }


    public function updatePassword()
    {            
        $customer = Yii::$app->user->identity;
        $customer->scenario = Customer::SCENARIO_UPDATE;
        if($this->validate()) {
            $customer->password = $this->password;
            $customer->password_confirm = $this->password_confirm;
            if(!$customer->validate()) {
                $this->addErrors($customer->getErrors());
                return false;
            }
            $customer->save(false);
            return true;
        }
        return false;
    }


}