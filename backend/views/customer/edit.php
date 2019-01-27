<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use core\helpers\Form;
use common\models\Customer;
use backend\widgets\FormContainer;
use backend\widgets\ImageField;
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $customer common\models\Customer
 * @var  $profile common\models\Profile
 * @var  $email common\models\CustomerEmail
 * @var array $emails  多个邮件地址 
 * 
 */
?>
<?php
   FormContainer::begin([
      'tabs' => [
        [
            'title'  => '邮件信息',
            'target' => 'customer_base_info',
        ],
        [
            'title'  => '基本信息',
            'target' => 'customer_password_info',
        ],
        [
            'title'  => '其他资料',
            'target' => 'customer_profile_info',
        ],
      ],
      'form' => 'edit_form',
   ]);
   $form = ActiveForm::begin([
       'id' => 'edit_form',
   ]);
?>
   <div id="customer_base_info" class="tab-target">
       <?php if(isset($email)): ?>
           <?= $form->field($email, 'email') ?>
       <?php else: ?>
           <?php foreach($emails as $index => $email): ?>
               <?= $form->field($email, "[{$index}]email") ?>
           <?php endforeach; ?>
       <?php endif; ?>
   </div>
   <div id="customer_password_info" class="tab-target">
       <?= $form->field($customer, 'nickname') ?>
       <?= $form->field($customer, 'phone') ?>
       <?= $form->field($customer, 'is_active')->dropDownList(Form::statusList()) ?>
       <?= $form->field($customer, 'password')->passwordInput() ?>
       <?= $form->field($customer, 'password_confirm')->passwordInput() ?>
   </div>
   <div id="customer_profile_info" class="tab-target">
        <?= $form->field($profile, 'username') ?>
        <?= $form->field($profile, 'bio') ?>
        <?= $form->field($profile, 'url') ?>
        <?= $form->field($profile, 'wechat') ?>
        <?= $form->field($profile, 'qq') ?>
        <?= $form->field($profile, 'sex')->dropDownList(Form::booleanList(['male', 'female']), ['prompt' => '']) ?>
        <?= $form->field($profile, 'dob') ?>
        <?= $form->field($profile, 'avatorImage')
           ->widget(ImageField::className(), [
               'url' => $profile->getAvatorUrl(true),
           ]);
        ?>
        <?= $form->field($profile, 'city') ?>
        <?= $form->field($profile, 'note')->textarea() ?>
   </div>

<?php ActiveForm::end() ?>
<?php FormContainer::end() ?>