<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Student register');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@frontend/views/site/_siginup.php') ?>
<div class="site-signup">

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'password_confirm')->passwordInput() ?>

        <?= $form->field($model, 'code')->widget(Captcha::className(), [
             'captchaAction' => 'site/captcha-register',
        ])?>

        <div class="form-group">
            <?= Html::submitButton('&nbsp;' . Yii::t('app','Register') . '&nbsp;', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->endContent() ?>
