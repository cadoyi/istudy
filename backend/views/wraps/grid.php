<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
if(isset($this->params['grid']['reset'])) {
    $reset = $this->params['grid']['reset'];
    $resetTitle = Yii::t('admin', $reset['title']);
    $resetRoute = $reset['route'];
} else {
    $resetTitle = Yii::t('admin', 'Reset filter');
    $resetRoute = [ Yii::$app->controller->route ];
}
if(isset($this->params['grid']['create'])) {
    $create = $this->params['grid']['create'];
    $createTitle = $create['title'];
    $createRoute = $create['route'];
} else {
    $createTitle = Yii::t('admin', 'Create new');
    $createRoute = ['create'];
} 
?>
<div class="row">
	<div class="col-xs-12 text-right">
		<?= Html::a(Html::encode($resetTitle), $resetRoute, ['class' => 'btn btn-default btn-sm']); ?>
		<?= Html::a(Html::encode($createTitle), $createRoute, ['class' => 'btn btn-success btn-sm']) ?>
	</div>
	<div class="col-xs-12">
	    <?= $content; ?>
	</div>
    <div id="modal">
        <?php Modal::begin([
            'id' => 'modalid',
        ]) ?>
        <?php Modal::end(); ?>
    </div>
</div>
