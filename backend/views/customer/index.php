<?php
use backend\grid\GridView;
use backend\grid\ActionColumn;
?>
<?php $this->beginContent('@backend/views/wraps/grid.php'); ?>
  <?= GridView::widget([
  	 'filterModel' => $searchModel,
  	 'dataProvider' => $dataProvider,
     'columns' => [
        'id',
        'nickname',
        'phone',
        'is_active:boolean',
        'created_at:datetime',
        'updated_at:datetime',
        [
            'class' => ActionColumn::className(),

        ],
     ],
  ])?>
<?php $this->endContent() ?>