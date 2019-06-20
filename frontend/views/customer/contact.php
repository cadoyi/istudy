<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = '联系方式';
?>
<?php
/**
 * @var  $this yii\web\View
 * @var $customer common\models\Customer
 * @var  $profile common\models\CustomerProfile
 *
 */
?>
<?php $form = ActiveForm::begin() ?>
<div class="col-xs-12">
    <?= $form->field($customer, 'phone') ?>
    <?= $form->field($profile, 'wechat')?>
    <?= $form->field($profile, 'qq')?>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => ['btn btn-primary']])?>
</div>
<?php ActiveForm::end() ?>