<?php

namespace backend\controllers;

use Yii;
use common\models\Post;
use common\models\PostContent;

class PostController extends Controller
{

    public function actionIndex()
    {

    }

    public function actionCreate()
    {

    }

    public function actionUpdate($id)
    {

    }

    public function actionView($id)
    {

    }

    public function actionDelete($id)
    {

    }

    public function findPost($id)
    {
        return $this->findModel($id, Post::className());
    }
}