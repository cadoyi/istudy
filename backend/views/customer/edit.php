<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\helpers\Form;
use common\models\Customer;
use backend\widgets\FormContainer;
use backend\widgets\ImageField;
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $profile backend\models\Customer
 * @var  $profile common\models\Profile
 */
?>
<?php
   FormContainer::begin([
      'tabs' => [
        [
            'title'  => '基本信息',
            'target' => 'customer_base_info',
        ],
        [
            'title'  => '密码信息',
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
       <?php //if($customer->scenario == Customer::SCENARIO_CREATE): ?>
           <?= $form->field($email, 'email') ?>
       <?php //endif; ?>
       <?= $form->field($customer, 'nickname') ?>
       <?= $form->field($customer, 'phone') ?>
       <?= $form->field($customer, 'is_active')->dropDownList(Form::statusList()) ?>
   </div>
   <div id="customer_password_info" class="tab-target">
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