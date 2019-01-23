<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AjaxFilter;
use backend\form\CustomerSearch;
use backend\models\Customer;
use common\models\CustomerProfile;

class CustomerController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
               'delete' => ['post'],
            ],
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::className(),
            'only' => ['view'],
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $search = new CustomerSearch();
        $provider = $search->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel'  => $search,
            'dataProvider' => $provider,
        ]);
    }


    public function actionCreate()
    {
        $customer = new Customer([
            'scenario' => Customer::SCENARIO_CREATE,
        ]);
        $profile = new CustomerProfile();

        return $this->render('edit', ['model' => $customer, 'profile' => $profile]);
    }


    public function actionView($id)
    {

    }

    public function actionUpdate($id)
    {

    }

    public function actionDelete($id)
    {

    }

}