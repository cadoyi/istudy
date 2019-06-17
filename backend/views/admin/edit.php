<?php
use yii\helpers\Html;
use yii\helpers\Url;
use core\helpers\Form;
use backend\widgets\FormContainer;
use core\widgets\ImageInput;
use yii\bootstrap\ActiveForm;
?>
<?php
/**
 * @var  $this yii\web\View
 * @var  $user common\models\User
 * @var  $profile common\models\UserProfile
 * @var  $role yii\base\DynamicModel;
 * 
 */
$this->title = $user->isNewRecord ? Yii::t('app', 'Create admin user') : $user->nickname;
$this->getBlock('breadcrumbs')->add(Yii::t('app', 'Manage admin user'), ['index']);

if($user->id == 1) {
    $options = ['disabled' => 'disabled'];
} else {
    $options = [];
}

?>
<?php 
    $formid = 'edit_form';
    $container = FormContainer::begin([
       'tabs' => [
           [
               'title'    => '用户信息',
               'target'   => 'user_base_info',
           ],
           [
               'title'    => '资料信息',
               'target'   => 'user_profile_info',
           ],
           [
               'title'   => '角色信息',
               'target'  => 'user_role_info',
           ],    
       ],
       'form' => $formid,
    ]); 
    $form = ActiveForm::begin(['id' => $formid]);
?>
<div id="user_base_info" class="tab-target">
    <?= $form->field($user, 'username') ?>
    <?= $form->field($user, 'nickname') ?>
    <?= $form->field($user, 'email') ?>
    <?= $form->field($user, 'is_active')->dropDownList(Form::statusList()) ?>
    <?= $form->field($user, 'password')->passwordInput() ?>
    <?= $form->field($user, 'password_confirm')->passwordInput() ?>
    <?= $form->field($user, 'current_password')->passwordInput() ?>
</div>
<div id="user_profile_info" class="tab-target">
    <?= $form->field($profile, 'phone') ?>
    <?= $form->field($profile, 'email') ?>
    <?= $form->field($profile, 'wechat')?>
    <?= $form->field($profile, 'qq')?>
    <?= $form->field($profile, 'sex')->dropDownList(Form::sexList(), ['prompt' => ''])?>
    <?= $form->field($profile, 'avatorFile')->widget(ImageInput::className(), [
        'url' => $profile->getAvatorUrl(true),
        'deleteAttribute' => 'avatorDelete',
    ]) ?>
    <?= $form->field($profile, 'note')->textarea() ?>
</div>
<div id="user_role_info" class="tab-target">
  
    <?php foreach($role as $name => $value): ?>
         <div class="form-group">
            <?php if($name == 'admin'): ?>
               <?= $form->field($role, $name)->checkbox($options) ?>
            <?php else: ?>
               <?= $form->field($role, $name)->checkbox() ?>
            <?php endif; ?>
         </div>
    <?php endforeach; ?>
</div>
<?php 
    ActiveForm::end();
    FormContainer::end();
 ?>
