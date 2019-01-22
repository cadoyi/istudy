<?php

namespace backend\controllers;

use Yii;
use backend\form\CustomerSearch;

class CustomerController extends Controller
{

    public function actionIndex()
    {
        $search = new CustomerSearch();
        $provider = $search->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel'  => $search,
            'dataProvider' => $provider,
        ]);
    }



}