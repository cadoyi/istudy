<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\grid\GridView;
use backend\grid\ActionColumn;
use core\helpers\Form;
use common\models\PostComment;
$this->title = Yii::t('app', 'Manage comment');

?>
<?php $this->beginContent('@backend/views/wraps/grid.php'); ?>
<?= GridView::widget([
    'filterModel' => $filterModel,
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'post_title' => [
            'attribute' => 'post_title',
            'value' => function($model, $key, $index, $column) {
            	return Html::encode($model->post->title);
            }
        ],
        'post_url' => [
            'attribute' => 'post_url',
            'value' => function($model, $key, $index, $column) {
            	return Html::encode($model->post->url_path);
            }
        ],
        'customer_email' => [
            'attribute' => 'customer_email',
            'value' => function($model, $key, $index, $column) {
            	return Html::encode($model->customer->email);
            }
        ],
        'customer_nickname' => [
            'attribute' => 'customer_nickname',
            'value' => function($model, $key, $index, $column) {
            	return Html::encode($model->customer->nickname);
            }
        ],
        'status' => [
            'attribute' => 'status',
            'filter' => PostComment::statusList(),
            'value' => function($model, $key, $index, $column) {
                 return $column->filter[$model->status];
            }
        ],
        'comment',
        [
            'class' => ActionColumn::className(),
            'header' => Yii::t('all', 'Action'),
            'template' => '<ul class="nav">
               <li class="dropdown">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                       <span class="glyphicon glyphicon-option-horizontal"></span>
                   </a>
                   <ul class="dropdown-menu">
                       <li class="action-list">{view}</li>
                       <li class="action-list">{audit}</li>
                       <li class="action-list">{delete}</li>
                   </ul>
               </li>
            </ul>',
            'buttons' => [
                'audit' => function($url, $model, $key) {
                    if($model->status == PostComment::STATUS_PENDING) {
                        $title = Yii::t('app','Audit');
                        $confirm = Yii::t('app', 'Do you sure audit it?');
                    } else {
                        $title = Yii::t('app', 'Unaudit');
                        $confirm = Yii::t('app', 'Do you sure cancle audit it?');
                    }
                    
                    $options = [
                        'title' => $title,
                        'aria-label' => $title,
                        'data-pjax' => '0',
                        'class' => 'action-audit',
                        'data-confirm' => $confirm,
                        'data-method' => 'post',
                    ];
                    $icon = '<span class="glyphicon glyphicon-random"></span> ';

                    return Html::a($icon . $title, $url, $options);
                }
            ],
        ],
    ],
]) ?>
<?php $this->endContent() ?>