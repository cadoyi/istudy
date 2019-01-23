<?php

namespace backend\models;

use Yii;
use common\models\CustomerEmail;

class Customer extends \common\models\Customer
{

	public $confirm_password;

    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $passwordError = Yii::t('admin', 'Invaild password format');
        $rules = parent::rules();
        return array_merge($rules, [
            [['email'], 'required', 'on' => [ static::SCENARIO_CREATE ]],
            [['email'], 'email', 'on' => [ static::SCENARIO_CREATE ]],
            [['email'], 'unique', 'targetClass' => CustomerEmail::className(), 'on' => [static::SCENARIO_CREATE] ],
            [['password', 'confirm_password'], 'required', 'on' => [ static::SCENARIO_CREATE ]],
            [['password'], 'string', 
              'length' => [5, 32],
              'tooShort' => $passwordError,
              'tooLong'  => $passwordError,
              'message'  => $passwordError,
            ],
            [['confirm_password'], 'compare', 
             'compareAttribute' => 'password',
             'when' => function() {
                  return !empty($this->password);
              },
              'whenClient' => 'function(attribute, value) {
                  return $("#customer-password").val().trim() != "";
              }',
            ],
        ]);
    }

    public function formName()
    {
        return 'customer';
    }


}