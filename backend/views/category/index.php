<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
use common\models\Category;
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
        'url_path',
        'parent_id' => [
       	    'attribute' => 'parent_id',

        ],
        [
            'class' => ActionColumn::className(),
            'header' => Yii::t('all', 'Action'),
        ],
    ],
])?>
<?php $this->endContent() ?>

