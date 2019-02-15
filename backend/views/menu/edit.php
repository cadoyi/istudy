<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Menu;
use yii\widgets\Menu as MenuWidget;
use yii\bootstrap\Modal;
$request = Yii::$app->request;

$update = $menu->scenario == Menu::SCENARIO_UPDATE;
$this->registerJsVar('menu', [
  'id' => $menu->id,
  'item_save_url' => Url::to(['menu/save-items', 'menu_id' => $menu->id]),
]);

$this->registerJsFile('@web/js/menuitem.js', [
    'depends' => ['common'],
]);

$menuitems = $menu->orderedItems;

?>
<div id="menu_edit" class="menu-edit">
	<div class="col-xs-12">
		<a class="btn btn-primary btn-sm pull-right" 
		   title="返回" 
		   href="<?= Url::to(['index'])?>"
		>返回</a>	

		<a class="btn btn-primary btn-sm pull-right" 
		   title="保存" 
		   href="#"
		   data-form="edit_form"
		>保存</a>
    </div>

	<?php $form = ActiveForm::begin(['id' => 'edit_form']) ?>
        <?= $form->field($menu, 'title') ?>
        <?= $form->field($menu, 'description') ?>
	<?php ActiveForm::end() ?>
	<div id="menu_items">  
	    <?php if($update): ?>
      <div id="menu_item_list">
          <div class="panel panel-default">
              <div id="menu_control" class="panel-heading">
                  <a class="btn btn-success btn-sm plus" href="#">
                        <span class="glyphicon glyphicon-plus"></span>
                        添加
                   </a>

                   <a class="btn btn-success btn-sm modify" 
                      href="#"
                    >
                        <span class="glyphicon glyphicon-edit"></span>
                        修改
                   </a> 
                   <a class="btn btn-danger btn-sm minus" href="#">
                        <span class="glyphicon glyphicon-minus"></span>
                        删除
                   </a>
                   <a class="btn btn-primary btn-sm pull-right save" 
                      href="#"
                    >
                        <span class="glyphicon glyphicon-send"></span>
                        保存
                   </a>
              </div>
              <div class="panel-body">
                  <?php if(empty($menuitems)): ?>
                     <ul id="menutree"></ul>
                  <?php else: ?>
                    <?= MenuWidget::widget([
                        'options' => ['id' => 'menutree'],
                        'items' => $menuitems,
                        'linkTemplate' => '<a class="menu btn" title="{url}" data-title="{label}"
                                              href="{url}">{label}</a>',
                    ])?>
                <?php endif; ?>
              </div>
           </div>
       </div>
       <?php Modal::begin([
           'id' => 'form_modal',
           'header' => '修改',
           'footer' => Html::a('保存', '#', [
                'class' => ['btn', 'btn-success'],
                'id' => 'save_menu_item',
            ]),
       ]) ?>
           <div class="form-group">
              <label class="label-control" for="menu_item_title">标签</label>
              <input id="menu_item_title" class="form-control" name="title">
              <div class="help-block"></div>
           </div>
           <div class="form-group">
               <label class="label-control" for="menu_item_link">链接</label>
               <input id="menu_item_link" class="form-control" name="link">
               <div class="help-block"></div>
           </div>
       <?php Modal::end() ?>
	    <?php endif; ?>
    </div>
</div>