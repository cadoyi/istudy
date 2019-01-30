<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
use core\helpers\Form;

?>
<?php
/**
 * 
 */
?>
<?php $this->beginContent('@backend/views/wraps/grid.php') ?>
<?= GridView::widget([
    'filterModel' => $filterModel,
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        'description',
        'is_default' => [
            'attribute' => 'is_default',
            'filter' => Form::booleanList(),
            'value' => function($model, $key, $index, $column) {
            	$values = $column->filter;
            	return $values[$model->is_default];
            }
        ],
        'created_at:datetime',
        'updated_at:datetime',
        [
            'class' => ActionColumn::className(),
            'header' => Yii::t('all', 'Action'),
        ],
    ],
]); 
?>
<?php $this->endContent() ?>