<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('admin','Admin login');

$this->registerMetaTag([
   'name' => 'meta_keywords',
   'content' => 'admin login',
], 'keywords');

$this->registerMetaTag([
    'name' => 'meta_description',
    'content' => 'admin login',
], 'description');

?>
<div class="row login-form-wrapper">
    <?php $form = ActiveForm::begin([
        'id' => 'loginform',
        'options' => [
            'class' => 'form login-form',
        ],
    ]) ?>
        <div class="form-group form-header">
            <h2><?= Html::encode($this->title); ?></h2>
        </div>
        <?= $form->field($model, 'username', [
            'options' => [
               'class' => 'form-group has-feedback',
            ],
        ])->textInput([
            'autofocus' => true,
        ]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?php if($model->isAttributeActive('code')): ?>
            <?= $form->field($model, 'code')->widget(Captcha::className(),[
                'template' => '<div class="row">
                   <div class="col-xs-12">
                       <div class="col-xs-6" style="padding:0;">{input}</div> 
                       <div class="col-xs-6">{image}</div>
                   </div>
                </div>',
            ]) ?>
        <?php endif; ?>

        <div class="form-group submit-form-group">
            <?= Html::submitButton(Yii::t('admin','Login'), [
                'class' => 'btn btn-primary btn-block', 
            ]) ?>
        </div>        
    <?php ActiveForm::end(); ?>
</div>

