<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<?php 
/**
 * @var $this yii\web\View
 * @var $category common\models\Category;
 */
?>
<?php $form = ActiveForm::begin([
   'id' => 'category_new',
   'method' => 'post',
   'action' => ['category/save', 'id' => $category->id],
]) ?>
   <?= $form->field($category, 'title') ?>
   <?= $form->field($category, 'url_path') ?>
<?php ActiveForm::end(); ?>