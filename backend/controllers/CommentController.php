<?php

namespace backend\controllers;

use Yii;
use common\models\PostComment;
use backend\form\PostCommentSearch;

class CommentController extends Controller
{


    public function actionIndex()
    {
    	$filterModel = new PostCommentSearch();
    	$dataProvider = $filterModel->search(Yii::$app->request->get());
    	return $this->render('index', [
           'filterModel' => $filterModel,
           'dataProvider' => $dataProvider,
    	]);
    }


    public function actionUpdate($id)
    {

    }

    public function actionView($id)
    {
        $comment = $this->findComment($id);
        return $this->asJson($comment);
    }

    public function actionDelete($id)
    {

    }

    public function findComment($id)
    {
    	return $this->findModel($id, PostComment::className());
    }

}