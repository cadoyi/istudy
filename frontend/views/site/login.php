<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = Yii::t('app', 'Student login');
?>
<?php $this->beginContent('@frontend/views/site/_siginup.php'); ?>

    <div class="site-login">

        <div>
            <div class="" style="max-width:320px;">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'code')->widget(Captcha::className()) ?>
                    <div class="form-group clearfix">
                        <?= $form->field($model, 'remember', [
                            'options' => [
                                'class' => ['pull-left','form-group'],
                            ],
                        ])->checkbox() ?>

                        <div class="form-group pull-right">
                            <?= Html::a(Yii::t('app', 'Forgot password?'), ['site/request-password-reset'],[
                                    'class' => 'btn btn-sm'
                                ]) ?>
                        </div>
                   </div>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php $this->endContent() ?>
