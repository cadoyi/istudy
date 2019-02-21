<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use core\widgets\ImageInput;

$this->registerCssFile('@web/css/login.css', ['depends' => ['common']]);
?>
<?php
/**
 * @var  $this yii\web\View
 * 
 */
?>
<?php $form = ActiveForm::begin() ?>
<div class="col-xs-12">
	<div class="form-group">
		<label for="customer_email"><?= Html::encode('账号') ?></label>
		<span class="form-control" id="customer_email"><?= Html::encode($customer->email) ?></span>
	</div>
    <?= $form->field($customer, 'nickname') ?>
	<?= $form->field($profile, 'avatorFile')->widget(ImageInput::className(), [
        'url' => $profile->getAvatorUrl(),
        'deleteAttribute' => 'avatorDelete',
	])?>
	<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary'])?>
</div>
<?php ActiveForm::end() ?>