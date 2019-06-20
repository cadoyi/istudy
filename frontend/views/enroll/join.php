<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use core\helpers\Form;
use frontend\models\Enroll;
use common\widgets\DatePicker;
$this->title = '报名表';
$this->registerMetaKeywords();
$this->registerMetaDescription();
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $enroll frontend\models\Enroll
 * 
 */
?>
<?php $form = ActiveForm::begin(['id' => 'enroll']) ?>
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-warning">
            <strong>通知</strong>: 我们的工程师正在开发在线支付功能,所以报名只能在这里先填写下信息,1-2个工作日内我们会给您去电话,所以请认真填写您的手机号码和邮件地址.
            <p>如果您有任何疑问,也可以在备注信息中注明,或者直接扫描网站底部的微信二维码,和薇姐面对面聊.</p>
        </div>
    </div>
    <div class="col-xs-12" style="margin-bottom:20px;">
        <div class="col-xs-12 col-sm-6">
        <?= $form->field($enroll, 'name') ?>
        <?= $form->field($enroll, 'email') ?>
        <?= $form->field($enroll, 'phone')?>
        <?= $form->field($enroll, 'dob')->widget(DatePicker::class, [

        ])?>
        
        <?= $form->field($enroll, 'sex')->radioList(Enroll::sexHashOptions())?>
        <?= $form->field($enroll, 'note')->textarea()?>
        <?= Html::submitButton(Yii::t('app', 'Enroll'), ['class' => 'btn btn-primary'])?>
       </div>
  </div>
</div>
<?php ActiveForm::end() ?>