<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\FormContainer;
use backend\widgets\Permission;

?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $role backend\models\AuthItem;
 * @var  $showPermission  boolean
 */
?>
<?php 
   $container = FormContainer::begin([
       'form' => 'edit_form',
       'tabs' => [
            [
                'title' => '基本信息',
                'target' => 'role_base_info',
            ],
            [
                'title' => '权限信息',
                'target' => 'role_permission_info',
                'visible' => $showPermission,
            ],
       ],
   ]);
   $form = ActiveForm::begin([
      'id' => 'edit_form',
   ]);
?>
<div id="role_base_info" class="tab-target">
    <?= $form->field($role, 'name') ?>
    <?= $form->field($role, 'description')->textarea() ?>
</div>
<?php if($showPermission): ?>
    <div id="role_permission_info" class="tab-target">
        <?= Permission::widget([
            'role' => $role,
        ])?>
    </div>
<?php endif; ?>

<?php 
ActiveForm::end();
FormContainer::end();
?>
