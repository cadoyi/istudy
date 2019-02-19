<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
use core\helpers\Form;
use common\models\Enroll;

$this->title = Yii::t('app', 'Manage enroll');
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $filterModel backend\form\EnrollSearch
 * @var  $dataProvider yii\data\ActiveDataProvider
 */
?>
<?php $this->beginContent('@backend/views/wraps/grid.php')?>
<?= GridView::widget([
    'id' => 'grid',
    'filterModel' => $filterModel,
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        'email',
        'phone',
        'dob',
        'sex' => [
           'attribute' => 'sex',
           'filter' => Form::booleanList(['male', 'female']),
           'value' => function($model, $key, $index, $column) {
               return $column->filter[$model->sex];
           }
        ],
        'status' => [
           'attribute' => 'status',
           'filter' => Enroll::statusList(),
           'value' => function($model, $key, $index, $column) {
               return $column->filter[$model->status];
           }
        ],
        'created_at:datetime',
        'updated_at:datetime',
        [
            'class' => ActionColumn::className(),
            'header' => Yii::t('app', 'Action'),
        ],
    ],
]) ?>
<?php $this->endContent() ?>