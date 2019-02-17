<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\PostComment;
?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $post common\models\Post
 * 
 */

$comment = new PostComment(['scenario' => PostComment::SCENARIO_CREATE]);
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
    <div class="col-xs-12 comment-form-div">
        <?php $form = ActiveForm::begin([
            'id' => 'comment_form',
            'action' => Url::to(['post/add-comment', 
                'id' => $post->id,
            ]),
            'method' => 'post',
        ]) ?>
            <?= $form->field($comment, 'comment')->textarea() ?>
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end() ?>
    </div>
    <div class="col-xs-12 comment-list">
        <?php foreach($dataProvider->getModels() as $comment): ?>
            <div class="col-xs-12">
                <div class=""><?= Html::encode($comment->customer->nickname) ?></div>
                <div class=""><?= $comment->comment ?></div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

