<?php

namespace backend\form;

use Yii;
use common\models\User;

class UserForm extends User
{
    /**
     * @var string 当前管理员的密码
     */
    public $current_password;

    /**
     * @var string 设置的密码
     */
    public $password;

    /**
     * @var  string 确认密码
     */
    public $confirm_password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $passwordError = Yii::t('admin', 'Password incorrect');
        $rules = parent::rules();
        return array_merge([
            [['current_password'], 'required'],
            [['password', 'confirm_password'], 'required', 'on' => [static::SCENARIO_CREATE]],
            [['current_password'], 'string', 
                'length' => [5,32],
                'tooShort' => $passwordError,
                'tooLong'  => $passwordError,
                'message'  => $passwordError,
            ],
            [['current_password'], 'validateCurrentPassword'],
            [['password'], 'string', 'length' => [5,32]],
            [['confirm_password'], 'compare', 
                'compareAttribute' => 'password', 
                'skipOnEmpty' => false,
                'when' => function() {
                    return !empty($this->password);
                },
                'whenClient' => "function(attribute, value) {
                    return $('#userform-password').val().trim() != '';
                }",
           ],
        ], $rules);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'current_password' => Yii::t('admin', "Current user's password"),
            'password'         => Yii::t('admin', 'Password'),
            'confirm_password' => Yii::t('admin', 'Confirm password'),
        ]);;
    }


    /**
     * inline 验证器,验证当前用户的密码
     * 
     * @param  string $attribute  属性名称
     * @param  array  $params     定义验证器规则时附加的参数
     * @param  yii\validators\InlineValidator $validator  
     */
    public function validateCurrentPassword($attribute, $params = [], $validator)
    {
        $identity = Yii::$app->user->identity;
        if(!$identity->validatePassword($this->$attribute)) {
            $this->addError($attribute, Yii::t('admin', 'Password incorrect'));
        }
    }

    public function cleanPassword()
    {
        $this->password = null;
        $this->confirm_password = null;
        $this->current_password = null;
    }
}