<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = $tag->title;

?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $dataProvider yii\data\ActiveDataProvider
 * @var  $tag common\models\Tag
 */
$posts = $dataProvider->getModels();
  
?>
<div class="row">
    <div class="col-xs-12">
        <h1>标签 <span style="font-size:1rem;" class="<?= $tag->htmlClass ?>"><?= Html::encode($tag->title); ?></span></h1>
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
                    <?php foreach($post->tags as $_tag): ?>
                        <?php if($_tag->id == $tag->id): ?>
                            <?php $className = $tag->htmlClass ?>
                        <?php else: ?>
                            <?php $className = 'tag label label-default' ?>
                        <?php endif; ?>
                        <a class="<?= $className ?>" href="<?= Url::to(['tag/index', 'id' => $_tag->id])?>">
                            <?= Html::encode($_tag->title); ?>
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