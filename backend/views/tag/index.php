<?php
use yii\helpers\Html;
use backend\grid\GridView;
use backend\grid\ActionColumn;
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
            'header' => Yii::t('all', 'Action'),
        ],
    ],
])?>
<?php $this->endContent() ?>