<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AjaxFilter;
use common\models\User;
use backend\form\AdminSearch;


class AdminController extends Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   'delete' => ['POST'],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::className(),
                'only' => ['view'],
            ],
        ]);
    }

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
        $model = new User(['scenario' => User::SCENARIO_CREATE]);
        $request = Yii::$app->request;
        if($request->isPost) {
            if($model->load($request->post()) && $model->validate()) {
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
        return $this->renderView($model);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id, User::className());
        $model->scenario = User::SCENARIO_UPDATE;
        $request = Yii::$app->request;
        if($request->isPost && $model->load($request->post())) {
            if($model->validate()) {
                $model->save(false);
                return $this->redirect(['index']);
            }
        }
        $model->cleanPassword();
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