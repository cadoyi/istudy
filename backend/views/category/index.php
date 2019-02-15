<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
use core\helpers\Form;
use common\models\Category;
$this->title = Yii::t('app', 'Manage category');
?>
<?php
/**
 * @var yii\web\View $this
 * @var array $categories  - array of common\models\Category
 */
?>
<?php $this->beginContent('@backend/views/wraps/grid.php') ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'columns' => [
        'title',
        'description',
        'url_path',
        'parent_id' => [
       	    'attribute' => 'parent_id',
            'filter' => Category::hashOptions(),
            'value' => function($model, $key, $index, $column) {
                $filter = $column->filter;
                $parent_id = $model->parent_id;
                if(array_key_exists($parent_id, $filter)) {
                    return $filter[$parent_id];
                }
                return null;
            }
        ],
        'is_active' => [
             'attribute' => 'is_active',
             'filter' => Form::statusList(),
             'value' => function($model, $key, $index, $column) {
                 return $column->filter[$model->is_active];
             }
        ],
        'position',
        'created_at:datetime',
        [
            'class' => ActionColumn::className(),
            'header' => Yii::t('all', 'Action'),
        ],
    ],
])?>
<?php $this->endContent() ?>

