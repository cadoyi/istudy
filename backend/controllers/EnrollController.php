<?php

namespace backend\controllers;

use Yii;
use common\models\Enroll;
use backend\form\EnrollSearch;


class EnrollController extends Controller
{

    public function actionIndex()
    {
        $filterModel = new EnrollSearch();
        $dataProvider = $filterModel->search(Yii::$app->request->get());
        return $this->render('index', [
           'filterModel' => $filterModel,
           'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $this->_title('Create enroll');
        $enroll = new Enroll(['scenario' => Enroll::SCENARIO_CREATE]);
        if($enroll->load(Yii::$app->request->post()) && $enroll->validate()) {
            $enroll->save(false);
            return $this->redirect(['index']);
        }
        return $this->render('edit', [
            'enroll' => $enroll,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->_title('Update enroll');
        $enroll = $this->findEnroll($id);
        $enroll->scenario = Enroll::SCENARIO_UPDATE;
        if($enroll->load(Yii::$app->request->post()) && $enroll->validate()) {
            $enroll->save(false);
            return $this->redirect(['index']);
        }
        return $this->render('edit', [
            'enroll' => $enroll,
        ]);        
    }

    public function actionView($id)
    {
        return $this->asJson($this->findEnroll($id));
    }

    public function actionDelete($id)
    {
        $this->findEnroll($id)->delete();
        return $this->redirect(['index']);
    }

    public function findEnroll($id)
    {
        return $this->findModel($id, Enroll::className());
    }

}