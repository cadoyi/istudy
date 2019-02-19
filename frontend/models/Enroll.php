<?php

namespace frontend\models;

use Yii;

class Enroll extends \common\models\Enroll
{

    public $code;

    public function rules()
    {
        return array_merge([
           [['code'], 'required'],
           [['code'], 'captcha', 'captchaAction' => 'enroll/captcha'],
        ], parent::rules());
    }

    public function attributeLabels()
    {
        return array_merge([
            'code' => Yii::t('app', 'Captcha code'),
        ], parent::attributeLabels());
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        foreach($scenarios as $scenario => $_scens) {
            $scenarios[$scenario] = $_scens + ['code'];
        }
        return $scenarios;
    }

}