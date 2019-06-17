<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use core\helpers\Form;
use common\models\Customer;
use common\models\CustomerGroup;
use backend\widgets\FormContainer;
use core\widgets\ImageInput;

$groups = CustomerGroup::hashOptions();
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $customer common\models\Customer
 * @var  $profile common\models\Profile
 * @var array $emails  多个邮件地址 
 * 
 */
$this->getBlock('breadcrumbs')->add(Yii::t('app', 'Manage customer'), ['index']);
?>
<?php
   $formid = 'edit_form';
   FormContainer::begin([
      'tabs' => [
        [
            'title'  => '基本信息',
            'target' => 'customer_base_info',
        ],
        [
            'title'  => '资料信息',
            'target' => 'customer_profile_info',
        ],
      ],
      'form' => $formid,
   ]);
   $form = ActiveForm::begin([
       'id' => $formid,
   ]);
?>
   <div id="customer_base_info" class="tab-target">
       <?= $form->field($customer, 'email') ?>
       <?= $form->field($customer, 'password')->passwordInput() ?>
       <?= $form->field($customer, 'password_confirm')->passwordInput() ?>
       <?= $form->field($customer, 'group_id')
           ->dropDownList($groups, ['prompt' => '']);
       ?>      
       <?= $form->field($customer, 'nickname') ?>
       <?= $form->field($customer, 'phone') ?>
       <?= $form->field($customer, 'is_active')->dropDownList(Form::statusList()) ?>

   </div>
   <div id="customer_profile_info" class="tab-target">
        <?= $form->field($profile, 'username') ?>
        <?= $form->field($profile, 'bio') ?>
        <?= $form->field($profile, 'url') ?>
        <?= $form->field($profile, 'wechat') ?>
        <?= $form->field($profile, 'qq') ?>
        <?= $form->field($profile, 'sex')->dropDownList(Form::booleanList(['male', 'female']), ['prompt' => '']) ?>
        <?= $form->field($profile, 'dob') ?>
        <?= $form->field($profile, 'avatorFile')
           ->widget(ImageInput::className(), [
               'url' => $profile->getAvatorUrl(true),
               'deleteAttribute' => 'avatorDelete',
           ]);
        ?>
        <?= $form->field($profile, 'city') ?>
        <?= $form->field($profile, 'note')->textarea() ?>
   </div>

<?php ActiveForm::end() ?>
<?php FormContainer::end() ?>