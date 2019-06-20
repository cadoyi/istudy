<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use core\helpers\Form;
use common\models\CustomerProfile;

$this->title = '我的资料';
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
    <?= $form->field($profile, 'username') ?>
    <?= $form->field($profile, 'city') ?>
    <?= $form->field($profile, 'url')?>
    <?= $form->field($profile, 'bio')->textarea()?>
    <?= $form->field($profile, 'sex')->radioList(CustomerProfile::sexHashOptions())?>
    <?= $form->field($profile, 'note')->textarea()?>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => ['btn btn-primary']])?>
</div>
<?php ActiveForm::end() ?>