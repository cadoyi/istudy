<?php

namespace frontend\models;

use Yii;

class Mailer
{

    public static function register($customer, $secret, $params = [])
    {
        $view = [
            'text' => 'register-text.php',
            'html' => 'register-html.php',
        ];
        $params = array_merge($params, [
            'user' => $customer,
            'secret' => $secret,
        ]);
        $to = $customer->email;
        return Yii::$app->mailer
           -> compose($view, $params)
           -> setTo($to)
           -> setSubject(Yii::t('app', 'Verify your email address for register'));
    }

    public static function resetPassword($customer, $params = [])
    {
        $view = [
            'text' => 'passwordResetToken-text',
            'html' => 'passwordResetToken-html', 
        ];
        $params = array_merge($params, [
            'customer' => $customer,
        ]);
        $to = $customer->email;
        return Yii::$app->mailer
          -> compose($view, $params)
          -> setTo($to)
          -> setSubject(Yii::t('app', 'Verify your email address for password reset'));
    }
}