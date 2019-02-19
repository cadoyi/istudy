<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\FormContainer;
use core\helpers\Form;
use common\models\Enroll;
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $enroll common\models\Enroll
 */
?>
<?php 
$formid = 'edit_form';
$container = FormContainer::begin([
   'form' => $formid,
   'tabs' => [
        [
           'title' => '基本信息',
           'target' => 'enroll_base_info',
        ],
   ],
]);
$form = ActiveForm::begin([
    'id' => $formid,
]);
?>
<div id="enroll_base_info" class="tab-target">
    <?= $form->field($enroll, 'name') ?>
    <?= $form->field($enroll, 'email') ?>
    <?= $form->field($enroll, 'phone') ?>
    <?= $form->field($enroll, 'dob') ?>
    <?= $form->field($enroll, 'sex')->dropDownList(Form::booleanList(['male', 'female']), ['prompt' => '']) ?>
    <?= $form->field($enroll, 'status')->dropDownList(Enroll::statusList(), ['prompt' => ''])?>
    <?= $form->field($enroll, 'note')->textarea() ?>
</div>

<?php 
ActiveForm::end();
FormContainer::end();
?>