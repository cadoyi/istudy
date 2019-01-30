<?php

namespace backend\controllers;

use Yii;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use common\models\Post;
use common\models\PostContent;
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
        $content = new PostContent([
            'scenario' => postContent::SCENARIO_CREATE,
        ]);
        $request = Yii::$app->request;
        if($request->isPost) {
            $inputs = $request->post();
            
            if($post->load($inputs) && $content->load($inputs)) {
                if($post->validate() && $content->validate()) {
                    Post::getDb()->transaction(function() use ($post, $content) {
                        $post->save(false);
                        $content->post_id = $post->id;
                        $content->save(false);
                    });
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('edit', [
            'post' => $post,
            'postContent' => $content,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->_title('Edit post');
        $post = $this->findPost($id);
        $content = $post->content;
        $post->scenario = Post::SCENARIO_UPDATE;
        $content->scenario = PostContent::SCENARIO_UPDATE;

        $request = Yii::$app->request;
        if($request->isPost) {
            $inputs = $request->post();
            if($post->load($inputs) && $content->load($inputs)) {
                if($post->validate() && $content->validate()) {
                    $db = Post::getDb();
                    $db->transaction(function() use ($post, $content) {
                        $post->save(false);
                        $content->save(false);
                    });
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('edit', [
           'post' => $post,
           'postContent' => $content,
        ]);

    }

    public function actionView($id)
    {
        $post = $this->findPost($id);
        return $this->asJson($post->toArray([],['content']));
    }

    public function actionDelete($id)
    {
        $post = $this->findPost($id);
        if($post->canDelete()) {
            $post->delete();
        }
        return $this->redirect(['index']);
    }

    public function findPost($id)
    {
        return $this->findModel($id, 
            Post::className(), 
            function($query) {
                $query->with('content');
            }
        );
    }
}