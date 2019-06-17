<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\widgets\FormContainer;
use yii\widgets\ActiveForm;
?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $tag common\models\Tag
 */
$this->getBlock('breadcrumbs')->add(Yii::t('app', 'Manage tag'), ['index']);
?>
<?php 
   $formid = 'edit_form';

   $container = FormContainer::begin([
      'tabs' => [
          [
              'title'  => '基本信息',
              'target' => 'tag_base_info',
          ],
      ], 
     'form' => $formid,
   ]);
   $form = ActiveForm::begin([
       'id' => $formid,
   ]);
?>
<div id="tag_base_info">
	<?= $form->field($tag, 'title') ?>
	<?= $form->field($tag, 'description')->textarea() ?>
</div>
<?php 
   ActiveForm::end();
   FormContainer::end();
?>