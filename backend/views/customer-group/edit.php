<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\widgets\FormContainer;
use core\helpers\Form;
use yii\widgets\ActiveForm;
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $group common\models\Group
 */
?>
<?php
   $formid = 'edit_form';
   $container = FormContainer::begin([
   	  'tabs' => [
          [
              'title' => '基本信息',
              'target' => 'group_base_info',
          ],
   	  ],
      'form' => $formid,
   ]);
   $form = ActiveForm::begin([
       'id' => $formid,
   ]);
?>
<div id="group_base_info" class="tab-target">
	<?= $form->field($group, 'name')?>
	<?= $form->field($group, 'description')?>
	<?= $form->field($group, 'is_default')->checkbox() ?>
</div>

<?php 
	ActiveForm::end();
	FormContainer::end();
?>