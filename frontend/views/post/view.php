<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $post common\models\Post
 * @var  $comment frontend\models\CommentForm
 */

?>
<?php 
$this->title = $post->title;
$this->registerMetaKeywords($post->meta_keywords);
$this->registerMetaDescription($post->meta_description);
$tags = $post->tags;
?>
<div class="row">
    <article>
        <div class="col-xs-12 article-title" style="margin-bottom: 20px;">
            <h1><small><?= Html::encode($this->title) ?></small></h1>
            <?php foreach($post->tags as $tag): ?>
                <a class="<?= $tag->htmlClass ?>" 
                   href="<?= Url::to(['tag/index', 'id' => $tag->id])?>">
                   <?= Html::encode($tag->title) ?>    
                </a>
            <?php endforeach; ?>
        </div>
        <div class="col-xs-12 article-content">
            <?= $post->content; ?>
        </div>
    </article>
    <div class="col-xs-12 comment-form-div" style="margin-bottom: 20px;padding-top: 20px;">
        <?php $form = ActiveForm::begin([
            'id' => 'comment_form',
            'action' => Url::to(['post/add-comment', 
                'id' => $post->id,
            ]),
            'method' => 'post',
        ]) ?>
            <?= $form->field($comment, 'content')->textarea() ?>
            <?= $form->field($comment, 'code')->widget(Captcha::className(), [
                'captchaAction' => 'post/captcha-comment',
            ])?>
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end() ?>
    </div>
    <div class="col-xs-12 comment-list" style="margin-bottom: 20px;background-color: #fafafa;padding: 1rem;">
        <?php foreach($dataProvider->getModels() as $comment): ?>
            <div class="panel" style="border-left: 13px solid #f1f1f1;border-top: 1px solid #ddd;">
                <div class="panel-heading">
                    <a class="athim" title="@他" href="#"><?= Html::encode($comment->customer->nickname) ?></a>
                    说:    
                </div>
                <div class="panel-body">
                    <div class="col-xs-12">
                        <?= $comment->comment ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

