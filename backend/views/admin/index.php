<?php
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '管理员列表';
?>
<?php $this->beginContent('@backend/views/widgets/grid-wrapper.php') ?>
<a class="btn btn-default" href="<?= Url::to(['create']); ?>">添加管理员</a>
<?= GridView::widget([
    'filterModel'  => $searchModel,
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => ['table', 'table-bordered', 'table-hover', 'table-stripped', 'table-responsive', 'table-gray'],
    ],                                                                                                           
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
           'header' => Yii::t('admin', 'Action'),
        ],
    ],
]) ?>
<?php $this->endContent(); ?>