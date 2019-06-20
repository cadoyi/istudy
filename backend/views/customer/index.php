<?php
use yii\helpers\Html;
use backend\grid\GridView;
use backend\grid\ActionColumn;
$this->title = Yii::t('app', 'Manage customer');
?>
<?php $this->beginContent('@backend/views/wraps/grid.php'); ?>
  <?= GridView::widget([
  	 'filterModel' => $searchModel,
  	 'dataProvider' => $dataProvider,
     'columns' => [
        'id',
        'email',
        'nickname',
        'phone',
        'is_active:boolean',
        'created_at:datetime',
        [
            'class' => ActionColumn::className(),
        ],
     ],
  ])?>
<?php $this->endContent() ?>