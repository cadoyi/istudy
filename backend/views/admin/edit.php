<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'confirm_password')->passwordInput() ?>
    <?= $form->field($model, 'nickname') ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'is_active')->dropDownList(['1' => 'enabled', '0' => 'disabled']) ?>
    <?= $form->field($model,'current_password')->passwordInput() ?>
    <?= Html::submitButton('submit', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>