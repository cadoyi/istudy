<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\PostComment;

class CommentForm extends Model
{

    public $content;

    public $code;

    public $post;

    public function init()
    {
        parent::init();
        if($errors = Yii::$app->session->getFlash('comment.errors')) {
            $this->addErrors($errors);
        }
    }

    public function rules()
    {
        return [
            [['content', 'code'], 'required'],
            [['content'], 'trim'],
            [['content'], 'string', 'length' => [5,255]],
            [['code'], 'captcha', 'captchaAction' => 'post/captcha-comment'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => Yii::t('app', 'Comment'),
            'code' => Yii::t('app', 'Captcha code'),
        ];
    }

    public function saveComment()
    {
        if($this->validate()) {
            $comment = new PostComment([
                'scenario' => PostComment::SCENARIO_CREATE,
                'customer_id' => Yii::$app->user->getId(),
                'post_id' => $this->post->id,
                'status'  => PostComment::STATUS_PENDING,
                'comment' => $this->content,
            ]);
            if($comment->save()) {
                return true;
            }
            if($comment->hasErrors()) {
                $this->addErrors($comment->getErrors());
            }
        }
        $this->saveErrors();
        return false;
    }

    public function saveErrors()
    {
        if($this->hasErrors()) {
            Yii::$app->session->setFlash('comment.errors', $this->getErrors());
        }
    }
}