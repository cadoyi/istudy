<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = $category->meta_title;
$this->registerMetaKeywords($category->meta_keywords);
$this->registerMetaDescription($category->meta_description);
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $category common\models\Category
 * @var  $dataProvider yii\data\ActiveDataProvider
 */

$posts = $dataProvider->getModels();
?>
<div class="row">
    <div class="col-xs-12">
        <h1><?= Html::encode($category->title) ?></h1>
        <div class="col-xs-12" style="padding-bottom: 15px;">
            <?= Html::encode($category->description) ?>
        </div>
    </div>
    <?php foreach($posts as $post): ?>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 style="margin:0;">                            
                        <a title="" href="<?= Url::to(['post/view', 'id' => $post->id])?>">
                            <?= Html::encode($post->title); ?>    
                        </a>
                        <small>
                            <?= Yii::$app->formatter->asDatetime($post->created_at) ?>
                        </small>
                    </h3>
                </div>
                <div class="panel-body">
                   
                   <?= Html::encode($post->description)?>
                   <a title="点击阅读完整文章" href="<?= Url::to(['post/view', 'id' => $post->id])?>">
                       <span class="fa fa-hand-o-left"></span> 阅读更多
                   </a>
                </div>
                <div class="panel-footer">
                    <?php foreach($post->tags as $tag): ?>
                        <a class="<?= $tag->htmlClass ?>" href="<?= Url::to(['tag/index', 'id' => $tag->id])?>">
                            <?= Html::encode($tag->title); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="col-xs-12">
        <?= LinkPager::widget(['pagination' => $dataProvider->pagination]); ?>
    </div>
</div>
