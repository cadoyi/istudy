<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\FormContainer;
?>
<?php $container = FormContainer::begin([
    'tabs' => [
       [
           'title'  => '基本信息',
           'target' => 'category_base_info', 
       ],
       [
           'title'  => '父分类',
           'target' => 'category_parent_info',
       ],
    ],
]) ?>
<?php $form = ActiveForm::begin() ?>
   <div id="category_base_info" class="tab-target">
       <?= $form->field($category, 'title') ?>
       <?= $form->field($category, 'url_path')?>
   </div>
   <div id="category_parent_info" class="tab-target">
       <?= $form->field($category, 'parent_id')->dropDownList([], ['prompt' => '']) ?>
   </div>
<?php ActiveForm::end() ?>
<?php FormContainer::end() ?>