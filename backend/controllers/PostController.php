<?php

namespace backend\controllers;

use Yii;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\models\Post;
use common\models\Tag;
use backend\form\PostSearch;

class PostController extends Controller
{



    public function actionIndex()
    {
        $filterModel = new PostSearch();
        $dataProvider = $filterModel->search(Yii::$app->request->get());
        return $this->render('index', [
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $this->_title('Create post');
        $post = new Post([
            'scenario' => Post::SCENARIO_CREATE,
        ]);
        if($_post = $this->post) {
            if($post->load($_post) && $post->validate()) {
                Post::getDb()->transaction(function() use ($post) {
                    $post->save(false);
                    $post->saveTags($this->getPostedTags());
                });
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit', [
            'post' => $post,
        ]);
    }

    public function getPostedTags()
    {
        $tags = [];
        $post = Yii::$app->request->post('tags', '{}');
        $postTags = Json::decode($post);
        if(!empty($postTags)) {
            $tags = Tag::find()->where(['id' => $postTags])->all();
        }
        return $tags;
    }

    public function actionUpdate($id)
    {
        $this->_title('Update post');
        $post = $this->findPost($id);
        $post->scenario = Post::SCENARIO_UPDATE;
        
        if($_post = $this->post) {
            if($post->load($_post) && $post->validate()) {
                Post::getDb()->transaction(function() use ($post){
                    $post->save(false);
                    $post->saveTags($this->getPostedTags());
                });
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit', [
           'post' => $post,
        ]);

    }

    public function actionView($id)
    {
        $post = $this->findPost($id);
        return $this->asJson($post);
    }

    public function actionDelete($id)
    {
        $post = $this->findPost($id);
        $post->delete();
        return $this->redirect(['index']);
    }

    public function findPost($id)
    {
        return $this->findModel($id, Post::className());
    }
}