<?php
use backend\grid\GridView;
use backend\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = Yii::t('app', 'Manage admin user');
?>
<?php $this->beginContent('@backend/views/wraps/grid.php') ?>
<?= GridView::widget([
    'id' => 'admin_list',
    'filterModel'  => $searchModel,
    'dataProvider' => $dataProvider,                                                                                                      
    'columns' => [
        [
            'attribute' => 'id',
            'options' => ['style' => 'width:80px;'],
        ],
        [
            'attribute' => 'username',
            'options' => ['style' => 'width:120px;'],
        ],
        [
            'attribute' => 'nickname',
            'options' => [
                'style' => ['width' => '120px'],
            ],
        ],
        'email',
        [
            'attribute' => 'is_active',
            'format' => 'boolean',
            'options' => [
                'style' => ['width' => '80px'],
            ],
        ], 
        [
            'attribute' => 'created_at',
            'format' => 'datetime',
            'options' => [
                'style' => [
                    'width' => '150px',
                ],
            ],
        ],
        [
            'attribute' => 'updated_at',
            'format' => 'datetime',
            'options' => [
                'style' => [
                    'width' => '150px',
                ],
            ],
        ],
        [
           'class' => ActionColumn::className(),
            'visibleButtons' => [
                'delete' => function($model, $key, $index) {
                    return $model->canDelete();
                },
            ],
        ],
    ],
]) ?>
<?php $this->endContent(); ?>