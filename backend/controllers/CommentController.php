<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use common\models\PostComment;
use backend\form\PostCommentSearch;

class CommentController extends Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   'delete' => ['POST'],
                   'audit'  => ['POST'],
                ],
            ],
        ]);
    }


    public function actionIndex()
    {
    	$filterModel = new PostCommentSearch();
    	$dataProvider = $filterModel->search(Yii::$app->request->get());
    	return $this->render('index', [
           'filterModel' => $filterModel,
           'dataProvider' => $dataProvider,
    	]);
    }


    public function actionAudit($id)
    {
        $comment = $this->findComment($id);
        $comment->switchStatus();
        $comment->save();
        return $this->redirect(['index']);
    }

    public function actionView($id)
    {
        $comment = $this->findComment($id);
        return $this->asJson($comment);
    }

    public function actionDelete($id)
    {
        $comment = $this->findComment($id);
        $comment->delete();
        return $this->redirect(['index']);
    }

    public function findComment($id)
    {
    	return $this->findModel($id, PostComment::className());
    }

}