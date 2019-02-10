<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = 'Login';
?>
<div class="row">
    <div class="col-xs-12 site-login">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" style="max-width:320px;">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'code')->widget(Captcha::className()) ?>

                    <?= $form->field($model, 'remember')->checkbox() ?>

                    <div style="color:#999;margin:1em 0">
                        If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
