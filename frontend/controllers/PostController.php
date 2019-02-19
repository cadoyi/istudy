<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\captcha\CaptchaAction;
use common\models\Post;
use common\models\PostComment;
use frontend\models\CommentForm;


class PostController extends Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => AccessControl::className(),
                'only' => ['add-comment'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    if(Yii::$app->user->isGuest) { 
                        if(Yii::$app->request->isGet) {
                            $route = [ $this->route ];
                            $params = $this->actionParams;
                            $url = array_merge($route, $params);
                            Yii::$app->user->setReturnUrl($url);
                        }
                        return Yii::$app->user->loginRequired();
                    }
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            ],
        ]);
    }

    public function actions()
    {
        return [
            'captcha-comment' => [
                'class' => CaptchaAction::className(),
                'minLength' => 4,
                'maxLength' => 4,
            ],
        ];
    }

    public function actionView($id)
    {
        $post = $this->findPost($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $post->getComments()->orderBy(['created_at' => SORT_DESC])->with('customer'),
        ]);

        $comment = new CommentForm(['post' => $post]);

        return $this->render('view', [
            'post' => $post,
            'dataProvider' => $dataProvider,
            'comment' => $comment,
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

    public function findPostWithoutContent($id)
    {
        $post = Post::find()
            -> selectWithoutContent()
            -> where(['id' => $id, 'is_active' => 1])
            -> one();
        return $post ? : $this->notFound();
    }

    public function actionAddComment($id)
    {
        $post = $this->findPostWithoutContent($id);
        $model = new CommentForm([
            'post' => $post,
        ]);

        if($model->load(Yii::$app->request->post()) && $model->saveComment()) {
            Yii::$app->session->setFlash('success', '评论成功,需要等待后台审核才会出现在页面上');
        }
        return $this->redirect(['view', 'id' => $post->id]);
    }
}