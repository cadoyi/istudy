<?php

namespace frontend\controllers;

use Yii;
use core\web\Controller as WebController;
use yii\web\NotFoundHttpException;

class Controller extends WebController
{
	
    public function notFound()
    {
        throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
    }
}