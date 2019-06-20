<?php

namespace frontend\controllers;

use Yii;
use yii\web\Cookie;
use yii\captcha\CaptchaAction;
use frontend\models\Enroll;

class EnrollController extends Controller 
{

    public function actions()
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::className(),
                'minLength' => 4,
                'maxLength' => 4,
            ], 
        ];
    }

    public function actionJoin()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        /*
        $is_enrolled = $request->cookies['is_enrolled'];
        if($is_enrolled) {
            Yii::$app->session->setFlash('success', '您已经报名,不需要重新报名!');
            return $this->goHome();
        }
        */
        $enroll = new Enroll(['scenario' => Enroll::SCENARIO_CREATE]);

        if($enroll->load($request->post()) && $enroll->validate()) {
            $enroll->save(false);
            Yii::$app->session->setFlash('success', '报名成功!');
            /*
            $response->cookies->add(new Cookie([
                'name' => 'is_enrolled',
                'value' => 1,
                'expire' => time() + 2592000,
                'httpOnly' => true,
            ]));
            */
            return $this->goHome();
        }
        return $this->render('join', ['enroll' => $enroll]);
    }
}