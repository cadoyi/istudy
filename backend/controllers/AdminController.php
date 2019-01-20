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
                $model->generatePasswordHash($model->password);
                $model->cleanPassword();
                $model->save(false);
                return $this->redirect(['index']);
            }
        }
        $model->cleanPassword();
        return $this->render('edit', compact('model'));
    }

    public function actionView($id)
    {
        $model = $this->findModel($id, User::className());
        return $this->asJson($model);
        //return $this->render('view', compact('model'));
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id, UserForm::className());
        $request = Yii::$app->request;
        if($request->isPost && $model->load($request->post())) {
            if($model->validate()) {
                if($model->password) {
                    $model->generatePasswordHash($model->password);
                }
                $model->cleanPassword();
                $model->save(false);
                Yii::$app->session->addFlash('success', '更新成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id, User::className());
        if($model->canDelete()) {
            $model->delete();
        }
        $this->redirect(['index']);
    }



}