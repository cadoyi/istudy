<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
use common\models\Category;
use common\models\Post;

$categories = Category::hashOptions();
?>
<?php
/**
 * @var $this yii\web\View
 * @var $filterModel common\form\PostSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */
?>
<?php $this->beginContent('@backend/views/wraps/grid.php') ?>
<?= GridView::widget([
    'filterModel' => $filterModel,
    'dataProvider' => $dataProvider,
    'columns' => [
	    'id',
	    'title',
	    'url_path',
	    'description',
	    'category_id' => [
            'attribute' => 'category_id',
            'filter' => $categories,
            'value' => function($model, $key, $index, $column) use ($categories) {
            	return $categories[$model->category_id];
            }
	    ],
	    'status' => [
            'attribute' => 'status',
            'filter' => Post::statusOptions(),
            'value' => function($model, $key, $index, $column) {
                 $options = $column->filter;
                 return $options[$model->status];
            }
	    ],
	    [
	        'class' => ActionColumn::className(),
	        'header' => Yii::t('all', 'Action'),
	    ],
    ],
]) ?>
<?php $this->endContent() ?>