<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $filterModel backend\form\RoleSearch
 * @var  $dataProvider yii\data\ActiveDataProvider
 * 
 */
$this->title = Yii::t('app', 'Manage role');
?>
<?php $this->beginContent('@backend/views/wraps/grid.php'); ?>
<?= GridView::widget([
    'id' => 'grid',
    'filterModel' => $filterModel,
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
        'description',
        'created_at:datetime',
        'updated_at:datetime',
        [
            'class' => ActionColumn::className(),
            'header' => Yii::t('app', 'Action'),
            'visibleButtons' => [
                'delete' => function($model, $key, $index) {
                    return $model->name != 'admin';
                },
                'update' => function($model, $key, $index) {
                    return $model->name != 'admin';
                }
            ],
        ],
    ],
])?>
<?php $this->endContent() ?>