<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $post common\models\Post
 * 
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
                <a class="label label-danger" 
                   href="<?= Url::to(['tag/index', 'id' => $tag->id])?>">
                   <?= Html::encode($tag->title) ?>    
                </a>
            <?php endforeach; ?>
        </div>
        <div class="col-xs-12 article-content">
            <?= $post->content; ?>
        </div>
    </article>
</div>

