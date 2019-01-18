<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\form\AdminSearch;
use backend\form\UserForm;


class AdminController extends Controller
{

    public function actionIndex()
    {
        $search = new AdminSearch();
        $get = Yii::$app->request->get();
        $provider = $search->search($get);
        return $this->render('index', [
           'searchModel' => $search,
           'dataProvider' => $provider,
        ]);
    }

    public function actionCreate()
    {
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_CREATE;
        $request = Yii::$app->request;
        if($request->isPost) {
            if($model->load($request->post()) && $model->validate()) {
                return $this->asJson($model->toArray());
            }
        }
        return $this->render('edit', compact('model'));
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