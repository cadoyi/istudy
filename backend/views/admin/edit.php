<?php
use yii\helpers\Html;
use yii\helpers\Url;
use core\helpers\Form;
use backend\widgets\FormContainer;
use yii\bootstrap\ActiveForm;
?>
<?php
/**
 * 
 */
?>
<?php 

$container = FormContainer::begin([
   'tabs' => [
       [
          'title' => '基本信息',
          'target'   => 'form1',
       ],
       [
          'title'    => '密码',
          'target'   => 'form2',
       ],       
   ],
   'form' => 'edit_form',
]); 
$form = ActiveForm::begin(['id' => 'edit_form']);
?>
<div id="form1" class="tab-target">
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'nickname') ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'is_active')->dropDownList(Form::statusList()) ?>
    <?= $form->field($model, 'current_password')->passwordInput() ?>
</div>
<div id="form2" class="tab-target">
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'password_confirm')->passwordInput() ?>
</div>
<?php 
    ActiveForm::end();
    FormContainer::end();
 ?>
