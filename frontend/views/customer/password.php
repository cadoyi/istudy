<?php
use yii\helpers\Html;
use yii\heleprs\Url;
use yii\widgets\ActiveForm;

$this->title = '密码信息';
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $model frontend\models\CustomerPasswordForm
 * 
 */
?>
<?php $form = ActiveForm::begin() ?>
<div class="col-xs-12">
    <?= $form->field($model, 'current_password')->passwordInput() ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'password_confirm')->passwordInput() ?>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary'])?>
</div>
<?php ActiveForm::end() ?>