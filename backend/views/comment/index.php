<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
use core\helpers\Form;
$this->title = Yii::t('app', 'Manage comment');

?>
<?php $this->beginContent('@backend/views/wraps/grid.php'); ?>
<?= GridView::widget([
    'filterModel' => $filterModel,
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'post_title' => [
            'attribute' => 'post_title',
            'value' => function($model, $key, $index, $column) {
            	return Html::encode($model->post->title);
            }
        ],
        'post_url' => [
            'attribute' => 'post_url',
            'value' => function($model, $key, $index, $column) {
            	return Html::encode($model->post->url_path);
            }
        ],
        'customer_email' => [
            'attribute' => 'customer_email',
            'value' => function($model, $key, $index, $column) {
            	return Html::encode($model->customer->email);
            }
        ],
        'customer_nickname' => [
            'attribute' => 'customer_nickname',
            'value' => function($model, $key, $index, $column) {
            	return Html::encode($model->customer->nickname);
            }
        ],
        'status' => [
            'attribute' => 'status',
            'filter' => Form::booleanList(['Audited', 'Unaudited']),
            'value' => function($model, $key, $index, $column) {
                 return $column->filter[$model->status];
            }
        ],
        'comment',
        [
            'class' => ActionColumn::className(),
            'header' => Yii::t('all', 'Action'),
        ],
    ],
]) ?>
<?php $this->endContent() ?>