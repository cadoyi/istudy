<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $filterModel backend\form\MenuSearch
 * @var  $dataProvider yii\data\ActiveDataProvider
 */
?>
<?php $this->beginContent('@backend/views/wraps/grid.php') ?>
<?= GridView::widget([
    'filterModel' => $filterModel,
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'title',
        'description',
        'created_at:datetime',
        'updated_at:datetime',
        [
            'class' => ActionColumn::className(),
            'header' => Yii::t('all','Action'),
        ],
    ],
])?>
<?php $this->endContent() ?>
