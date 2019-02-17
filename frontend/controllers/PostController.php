<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Post;
use common\models\PostComment;


class PostController extends Controller
{

    public function actionView($id)
    {
        $post = $this->findPost($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $post->getComments()->orderBy('created_at')->with('customer'),
        ]);

        return $this->render('view', [
            'post' => $post,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function findPost($id)
    {
        $post = Post::find()->where(['id' => $id, 'is_active' => 1])
           -> with('tags')
           -> one();
        if(!$post) {
            return $this->notFound();
        }
        return $post;
    }

    public function actionAddComment($id)
    {
        $post = Post::find()->selectWithoutContent()->where(['id' => $id, 'is_active' => 1])->one();
        if(!$post) {
            return $this->notFound();
        }
        if(Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', '用户未登录');
            return $this->redirect(['view', 'id' => $post->id]);
        }        
        $model = new PostComment([
            'scenario' => PostComment::SCENARIO_CREATE,
            'customer_id' => Yii::$app->user->getId(),
            'post_id' => $post->id,
            'status'  => PostComment::STATUS_PENDING,
        ]);
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            Yii::$app->session->setFlash('success', '评论成功,需要等待后台审核才会出现在页面上');
        } else {
            Yii::$app->session->setFlash('error', '评论失败');
        }
        
        return $this->redirect(['view', 'id' => $post->id]);
    }
}