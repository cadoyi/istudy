<?php

namespace frontend\controllers;

use Yii;
use common\models\Post;


class PostController extends Controller
{

    public function actionView($id)
    {
        $post = $this->findPost($id);
        return $this->render('view', [
            'post' => $post,
        ]);
    }

    public function findPost($id)
    {
        $post = Post::findOne(['id' => $id, 'is_active' => 1]);
        if(!$post) {
            return $this->notFound();
        }
        return $post;
    }
}