<?php
use yii\helpers\Html;
$route = [ Yii::$app->controller->route ];
?>
<div class="row">
	<div class="col-xs-12 text-right">
		<?= Html::a(Yii::t('admin', 'Reset filter'), $route, ['class' => 'btn btn-primary']); ?>

		<?= Html::a(Yii::t('admin', 'Create new'), ['create'], ['class' => 'btn btn-primary']) ?>

	</div>
	<div class="col-xs-12">
	    <?= $content; ?>
	</div>
</div>